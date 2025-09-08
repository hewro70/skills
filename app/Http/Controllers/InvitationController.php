<?php
namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvitationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // دعوات واردة فقط (حسب السيناريو الجديد ما عندنا صفحات Exchanges)
        $invitations = Invitation::with('sourceUser')
            ->where('destination_user_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'invites_page');

        return view('theme.invitations', [
            'invitations' => $invitations,
        ]);
    }

    // 🔕 تعطيل أي صفحة Exchanges قديمة:
    public function exchanges(Request $request)
    {
        abort(404); // أو رجّع View فارغ مع تنبيه أنها Disabled حسب الخطة الجديدة
    }

    public function unreadCount(Request $request)
    {
        $count = Invitation::where('destination_user_id', $request->user()->id)
            ->whereNull('reply')
            ->count();

        return response()->json(['count' => $count]);
    }

  public function send(Request $request)
{
    $request->validate([
        'destination_user_id' => 'required|exists:users,id',
        'message' => 'nullable|string|max:1000',
    ]);

    /** @var User $user */
    $user = $request->user();
    $destinationId = (int) $request->destination_user_id;

    if ($user->id === $destinationId) {
        return response()->json(['message' => __('errors.invite_self')], 422);
    }

    // ممنوع لو في محادثة قائمة
    $hasConversation = Conversation::whereHas('users', fn($q) => $q->where('users.id', $user->id))
        ->whereHas('users', fn($q) => $q->where('users.id', $destinationId))
        ->exists();
    if ($hasConversation) {
        return response()->json(['message' => __('invitations.errors.already_connected')], 422);
    }

    // موجود Pending باتجاهين؟
    $existsPending = Invitation::whereNull('reply')
        ->where(function ($q) use ($user, $destinationId) {
            $q->where(function ($q2) use ($user, $destinationId) {
                $q2->where('source_user_id', $user->id)
                   ->where('destination_user_id', $destinationId);
            })->orWhere(function ($q2) use ($user, $destinationId) {
                $q2->where('source_user_id', $destinationId)
                   ->where('destination_user_id', $user->id);
            });
        })->exists();
    if ($existsPending) {
        return response()->json(['message' => __('invitations.errors.pending_exists')], 422);
    }

    // بريميوم؟
    $isPremium = method_exists($user, 'hasActiveSubscription')
        ? $user->hasActiveSubscription()
        : (bool) ($user->is_premium ?? false);

    // حد 5/شهر للمجاني
    if (!$isPremium) {
        $sentThisMonth = Invitation::where('source_user_id', $user->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
        if ($sentThisMonth >= 5) {
            return response()->json(['message' => __('invitations.errors.monthly_limit', ['limit' => 5])], 422);
        }
    }

    // رسالة مخصّصة للبريميوم فقط
    $customMessage = $isPremium ? (trim((string)$request->message) ?: null) : null;

    try {
        $invitation = Invitation::create([
            'source_user_id'      => $user->id,
            'destination_user_id' => $destinationId,
            'date_time'           => now(),
            'message'             => $customMessage, // null للـ Free
        ]);

        // نص الإشعار للطرف الآخر:
        $systemMessage = __('invitations.free.system_notice', ['name' => $user->fullName()]);
        $textForReceiver = $customMessage ?: $systemMessage;

        // ابعث Notification (اكتب الكلاس تبعك)
        // Notification::send($invitation->destinationUser, new InvitationCreatedNotification($invitation, $textForReceiver));

        event(new \App\Events\InvitationSent($invitation));

        return response()->json(['message' => __('invitations.sent_success')]);
    } catch (\Throwable $e) {
        return response()->json(['message' => __('invitations.errors.unexpected')], 500);
    }
}


  public function reply(Request $request, Invitation $invitation)
{
    $request->validate([
        'reply' => 'required|in:قبول,رفض', // أو استخدم accepted/declined لو تحب توحّد
    ]);

    if ($invitation->destination_user_id !== auth()->id()) {
        return response()->json(['message' => __('errors.unauthorized')], 403);
    }

    if ($request->reply === 'قبول') {
        $invitation->update(['reply' => 'قبول']);

        // أنشئ/اجلب محادثة بين الطرفين
        $sender   = User::findOrFail($invitation->source_user_id);
        $receiver = User::findOrFail($invitation->destination_user_id);
        $convId   = $this->findOrCreateConversation($sender, $receiver);

        return response()->json(['message' => __('invitations.accepted'), 'conversation_id' => $convId]);
    }

    // رفض => حذف
    $invitation->delete();
    return response()->json(['message' => __('invitations.declined_deleted')]);
}

protected function findOrCreateConversation(User $a, User $b): int
{
    $conv = Conversation::whereHas('users', fn($q) => $q->where('users.id', $a->id))
        ->whereHas('users', fn($q) => $q->where('users.id', $b->id))
        ->first();

    if ($conv) return $conv->id;

    $conv = Conversation::create(['last_message_at' => now()]);
    $conv->users()->attach([$a->id, $b->id], ['is_active' => true]);

    return $conv->id;
}



    public function checkEligibility()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'unauthenticated', 'message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        $completion = $user->profileCompletionPercentage();
        if ($completion < 100) {
            return response()->json([
                'status' => 'incomplete',
                'completion_percentage' => $completion,
                'message' => 'يجب إكمال ملفك الشخصي'
            ]);
        }

        return response()->json(['status' => 'ok', 'message' => 'يمكنك إرسال الدعوات']);
    }
}

