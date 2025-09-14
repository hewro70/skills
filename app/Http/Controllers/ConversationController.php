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
use Illuminate\Support\Facades\Cache;

class ConversationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1) المحادثات النشطة
        $conversations = Auth::user()->conversations()
            ->with([
                'users',
                'messages' => function ($q) { $q->latest()->limit(1); }, // أو علاقة lastMessage
            ])
            ->wherePivot('is_active', true)
            ->orderByDesc('last_message_at')
            ->paginate(10);

        // 2) مرشّحو الدردشة من الدعوات المقبولة (باتجاهين) بدون محادثة قائمة
        // received: الآخر = source_user_id
        $received = DB::table('invitations')
            ->select([
                'invitations.id as invitation_id',
                DB::raw('COALESCE(invitations.updated_at, invitations.date_time, invitations.created_at) as ts'),
                'invitations.source_user_id as other_user_id',
            ])
            ->where('destination_user_id', $userId)
            ->whereRaw("TRIM(invitations.reply) = 'قبول'");

        // sent: الآخر = destination_user_id
        $sent = DB::table('invitations')
            ->select([
                'invitations.id as invitation_id',
                DB::raw('COALESCE(invitations.updated_at, invitations.date_time, invitations.created_at) as ts'),
                'invitations.destination_user_id as other_user_id',
            ])
            ->where('source_user_id', $userId)
            ->whereRaw("TRIM(invitations.reply) = 'قبول'");

        $invRows = DB::query()
            ->fromSub($received->unionAll($sent), 'inv')
            ->whereNotExists(function ($q) use ($userId) {
                $q->from('conversations')
                  ->join('conversation_user as cu1', 'conversations.id', '=', 'cu1.conversation_id')
                  ->join('conversation_user as cu2', 'conversations.id', '=', 'cu2.conversation_id')
                  ->where('cu1.user_id', $userId)
                  ->whereColumn('cu2.user_id', 'inv.other_user_id');
            })
            ->orderByDesc('ts')
            ->get();

        $users = User::whereIn('id', $invRows->pluck('other_user_id')->unique())->get()->keyBy('id');

        $inviteCandidates = $invRows->map(function ($r) use ($users) {
            return (object) [
                'invitation_id' => $r->invitation_id,
                'other_user_id' => $r->other_user_id,
                'ts'            => $r->ts ? \Carbon\Carbon::parse($r->ts) : null,
                'otherUser'     => $users[$r->other_user_id] ?? null,
            ];
        });

        if (request()->boolean('_ajax')) {
            return view('theme.conversations._list', compact('conversations', 'inviteCandidates'));
        }

        return view('theme.conversations.index', compact('conversations', 'inviteCandidates'));
    }

   public function show(Conversation $conversation)
{
    $this->authorize('view', $conversation);

    $beforeId = request('before');

    $messages = $conversation->messages()
        ->with('user')
        ->when($beforeId, fn($q) => $q->where('id', '<', $beforeId))
        ->orderByDesc('id')
        ->limit(100)
        ->get()
        ->reverse()
        ->values();

    $otherUser = $conversation->users()
        ->where('users.id', '!=', Auth::id())
        ->first();

    // ===== علّق جلب الإكستشينج =====
    /*
    $exchanges = $conversation->exchanges()
        ->with(['sender','receiver','senderSkill:id,name','receiverSkill:id,name'])
        ->orderByDesc('created_at')
        ->get();
    */

    if (request()->boolean('_ajax')) {
        $html = '';
        foreach ($messages as $m) {
            $isMe = $m->user_id == auth()->id();
            $html .= '<div class="message d-flex '.($isMe ? 'justify-content-end' : 'justify-content-start').' mb-2" data-id="'.$m->id.'">
                <div class="message-content p-3 rounded shadow '.($isMe ? 'bg-primary text-white' : 'bg-light text-dark').'"
                     style="max-width: 75%; word-wrap: break-word;">
                    <p class="mb-1">'.e($m->body).'</p>
                    <small class="'.($isMe ? 'text-white-50' : 'text-muted').' fst-italic" style="font-size: 0.75rem;">'
                        .$m->created_at->diffForHumans().
                    '</small>
                </div>
            </div>';
        }

        $hasMore = false;
        $nextBefore = null;
        if ($messages->isNotEmpty()) {
            $earliestId = $messages->first()->id;
            $hasMore    = $conversation->messages()->where('id', '<', $earliestId)->exists();
            $nextBefore = $earliestId;
        }

        return response()->json([
            'html'        => $html,
            'hasMore'     => $hasMore,
            'next_before' => $nextBefore,
        ]);
    }

    // ===== مرّر بدون exchanges =====
    return view('theme.conversations.show', compact('conversation', 'messages', 'otherUser'));
}


    public function create()
    {
        $userId = auth()->id();

        // الدعوات التي استلمتها (الطرف الآخر = المرسل)
        $received = auth()->user()->receivedInvitations()
            ->with('sourceUser')
            ->where('reply', 'قبول')
            ->get()
            ->map(function ($inv) {
                $inv->other_user_id = $inv->source_user_id;
                $inv->setRelation('otherUser', $inv->getRelation('sourceUser'));
                return $inv;
            });

        // الدعوات التي أرسلتها (الطرف الآخر = المستلم)
        $sent = auth()->user()->sentInvitations()
            ->with('destinationUser')
            ->where('reply', 'قبول')
            ->get()
            ->map(function ($inv) {
                $inv->other_user_id = $inv->destination_user_id;
                $inv->setRelation('otherUser', $inv->getRelation('destinationUser'));
                return $inv;
            });

        $invitations = $received->merge($sent)
            ->filter(function ($inv) use ($userId) {
                return !DB::table('conversations')
                    ->join('conversation_user as cu1', 'conversations.id', '=', 'cu1.conversation_id')
                    ->join('conversation_user as cu2', 'conversations.id', '=', 'cu2.conversation_id')
                    ->where('cu1.user_id', $userId)
                    ->where('cu2.user_id', $inv->other_user_id)
                    ->exists();
            })
            ->unique('id')
            ->sortByDesc('date_time')
            ->values();

        return view('theme.conversations.create', ['invitations' => $invitations]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|not_in:' . auth()->id(),
            'message' => 'required|string|max:1000',
        ]);

        $targetUserId   = (int) $request->user_id;
        $currentUserId  = (int) auth()->id();
        $messageBody    = $request->message;

        // ✅ اعتبر القبول باتجاهين: إما أنت قبلت دعوته أو هو قبل دعوتك
        $invAcceptedEitherWay = DB::table('invitations')
            ->where(function ($q) use ($currentUserId, $targetUserId) {
                $q->where('source_user_id', $targetUserId)
                  ->where('destination_user_id', $currentUserId);
            })
            ->orWhere(function ($q) use ($currentUserId, $targetUserId) {
                $q->where('source_user_id', $currentUserId)
                  ->where('destination_user_id', $targetUserId);
            })
            ->whereRaw("TRIM(reply) = 'قبول'")
            ->exists();

        if (!$invAcceptedEitherWay) {
            return back()->with('error', 'يجب أن تكون الدعوة مقبولة بينكما قبل بدء المحادثة.');
        }

        DB::beginTransaction();
        try {
            // ابحث إن كانت محادثة قائمة
            $conversation = Conversation::whereHas('users', function ($q) use ($currentUserId) {
                    $q->where('user_id', $currentUserId);
                })
                ->whereHas('users', function ($q) use ($targetUserId) {
                    $q->where('user_id', $targetUserId);
                })
                ->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'title' => 'New Conversation',
                    'last_message_at' => now()
                ]);

                $conversation->users()->attach([
                    $currentUserId => ['is_active' => true, 'read_at' => now()],
                    $targetUserId  => ['is_active' => true, 'read_at' => null],
                ]);
            } else {
                // لو كان أحدهما left سابقاً فعّله
                $conversation->users()->updateExistingPivot($currentUserId, ['is_active' => true]);
                $conversation->users()->updateExistingPivot($targetUserId, ['is_active' => true]);
            }

            $message = $conversation->messages()->create([
                'user_id' => $currentUserId,
                'body'    => $messageBody,
            ]);

            $conversation->update(['last_message_at' => now()]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'conversation_id' => $conversation->id]);
            }

            return redirect()->route('conversations.show', $conversation);
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'حدث خطأ أثناء إنشاء المحادثة: ' . $e->getMessage()
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

    $senderId   = auth()->id();
    $receiver   = $conversation->users()->where('users.id', '!=', $senderId)->first();

    // 👈 هل كانت هناك رسائل قبل هذه الرسالة؟
    $hadAnyBefore = $conversation->messages()->exists();

    // أنشئ الرسالة
    $message = $conversation->messages()->create([
        'user_id' => $senderId,
        'body'    => $request->body
    ]);

    $conversation->update(['last_message_at' => now()]);

    // ========= منطق الإيميل المختصر =========
    if ($receiver && $receiver->email) {
        $isFirst = ! $hadAnyBefore;

        // هل الطرف الآخر (المستلم) أرسل رسالة خلال آخر 5 دقائق؟
        $recentFromReceiver = $conversation->messages()
            ->where('user_id', $receiver->id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();

        // نرسل فقط إذا أول رسالة، أو ما في رسالة من الطرف الآخر آخر 5 دقائق
        if ($isFirst || ! $recentFromReceiver) {
            // تهدئة 5 دقائق لكل (محادثة + مستلم) لتفادي التكرار
            $key = 'mail_unread_window_'.$conversation->id.'_'.$receiver->id;
            if (Cache::add($key, 1, now()->addMinutes(5))) {
                try {
                    \Mail::to($receiver->email)->send(
                        new \App\Mail\NewMessageReminderMail($conversation, $message)
                    );
                } catch (\Throwable $e) {
                    \Log::warning('new-message-mail-failed', [
                        'conversation_id' => $conversation->id,
                        'receiver_id'     => $receiver->id,
                        'error'           => $e->getMessage(),
                    ]);
                }
            }
        }
    }
    // ========================================

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
            'left_at'   => now()
        ]);

        return redirect()->route('conversations.index')->with('success', 'تم مغادرة المحادثة');
    }

    public function storeReview(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);

        $otherUser = $conversation->users()->where('user_id', '!=', auth()->id())->firstOrFail();

        Review::create([
            'sender_id'   => auth()->id(),
            'received_id' => $otherUser->id,
            'rating'      => $request->rating,
            'comment'     => $request->comment
        ]);

        return back()->with('success', 'تم إرسال التقييم بنجاح');
    } 
    } 