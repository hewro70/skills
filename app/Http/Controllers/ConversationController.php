<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Events\ChatMessageSent;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['users', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->wherePivot('is_active', true)
            ->orderByDesc('last_message_at')
            ->paginate(10);

        if (request()->has('_ajax')) {
            return view('theme.conversations.index', ['conversations' => $conversations]);
        }

        return view('theme.conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $query = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(1000);

        if ($beforeId = request('before')) {
            $query = $conversation->messages()
                ->with('user')
                ->where('id', '<', $beforeId)
                ->orderBy('created_at', 'desc')
                ->limit(1000);
        }

        $messages = $query->get()->reverse();

        $otherUser = $conversation->users()->where('user_id', '!=', Auth::id())->first();

        if (request()->has('_ajax')) {
            $html = '';
            foreach ($messages as $message) {
                $html .= '<div class="message d-flex ' . ($message->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start') . ' mb-2"
                  data-id="' . $message->id . '">
                  <div class="message-content p-3 rounded shadow ' . ($message->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark') . '"
                       style="max-width: 75%; word-wrap: break-word;">
                      <p class="mb-1">' . e($message->body) . '</p>
                      <small class="' . ($message->user_id == auth()->id() ? 'text-white-50' : 'text-muted') . ' fst-italic" style="font-size: 0.75rem;">
                          ' . $message->created_at->diffForHumans() . '
                      </small>
                  </div>
              </div>';
            }

            return response()->json([
                'html' => $html,
                'hasMore' => $conversation->messages()->where('id', '<', $messages->first()->id)->exists()
            ]);
        }

        return view('theme.conversations.show', compact('conversation', 'messages', 'otherUser'));
    }






    public function create()
    {
        $userId = auth()->id();

        $invitations = auth()->user()->receivedInvitations()
            ->with('sourceUser')
            ->where('reply', 'قبول')
            ->get()
            ->filter(function ($invitation) use ($userId) {
                return !DB::table('conversations')
                    ->join('conversation_user as cu1', 'conversations.id', '=', 'cu1.conversation_id')
                    ->join('conversation_user as cu2', 'conversations.id', '=', 'cu2.conversation_id')
                    ->where('cu1.user_id', $userId)
                    ->where('cu2.user_id', $invitation->source_user_id)
                    ->exists();
            });

        return view('theme.conversations.create', ['invitations' => $invitations]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|not_in:' . auth()->id(),
            'message' => 'required|string|max:1000',
        ]);

        $targetUserId = $request->user_id;
        $currentUserId = auth()->id();
        $messageBody = $request->message;

        $acceptedInvitation = auth()->user()->receivedInvitations()
            ->where('source_user_id', $targetUserId)
            ->where('reply', 'قبول')
            ->exists();

        if (!$acceptedInvitation) {
            return back()->with('error', 'يجب قبول الدعوة أولاً قبل بدء المحادثة');
        }

        DB::beginTransaction();

        try {
            $conversation = auth()->user()->conversations()
                ->whereHas('users', fn($q) => $q->where('user_id', $targetUserId))
                ->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'title' => 'New Conversation',
                    'last_message_at' => now()
                ]);

                $conversation->users()->attach([
                    $currentUserId => ['is_active' => true, 'read_at' => now()],
                    $targetUserId => ['is_active' => true, 'read_at' => null],
                ]);
            }

            $message = $conversation->messages()->create([
                'user_id' => $currentUserId,
                'body' => $messageBody,
            ]);

            $conversation->update(['last_message_at' => now()]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('conversations.show', $conversation);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'حدث خطأ أثناء إنشاء المحادثة: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء إنشاء المحادثة: ' . $e->getMessage());
        }
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $request->validate([
            'body' => 'required|string|max:1000'
        ]);

        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => $request->body
        ]);

        $conversation->update(['last_message_at' => now()]);

        broadcast(new ChatMessageSent($message))->toOthers();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', 'تم إرسال الرسالة');
    }

    public function leave(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        auth()->user()->conversations()->updateExistingPivot($conversation->id, [
            'is_active' => false,
            'left_at' => now()
        ]);

        return redirect()->route('conversations.index')
            ->with('success', 'تم مغادرة المحادثة');
    }

    public function storeReview(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);

        $otherUser = $conversation->users()->where('user_id', '!=', auth()->id())->first();

        Review::create([
            'sender_id' => auth()->id(),
            'received_id' => $otherUser->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'تم إرسال التقييم بنجاح');
    }
}
