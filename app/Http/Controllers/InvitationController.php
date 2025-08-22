<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Models\User;

class InvitationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $invitations = Invitation::with('sourceUser')
            ->where('destination_user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('theme.invitations', compact('invitations'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'destination_user_id' => 'required|exists:users,id',
        ]);

        $user = $request->user();
        $destinationId = $request->destination_user_id;

        if ($user->id == $destinationId) {
            return response()->json(['message' => 'لا يمكنك دعوة نفسك.'], 422);
        }

        $existingInvitation = Invitation::where(function ($query) use ($user, $destinationId) {
            $query->where('source_user_id', $user->id)
                ->where('destination_user_id', $destinationId);
        })->orWhere(function ($query) use ($user, $destinationId) {
            $query->where('source_user_id', $destinationId)
                ->where('destination_user_id', $user->id);
        })->whereNull('reply')
            ->first();

        if ($existingInvitation) {
            $message = $existingInvitation->source_user_id == $user->id
                ? 'لقد أرسلت دعوة بالفعل لهذا المستخدم.'
                : 'هذا المستخدم أرسل لك دعوة بالفعل. يرجى الرد عليها أولاً.';

            return response()->json(['message' => $message], 422);
        }

        try {
            $invitation = Invitation::create([
                'source_user_id' => $user->id,
                'destination_user_id' => $destinationId,
                'date_time' => now(),
            ]);

            event(new \App\Events\InvitationSent($invitation));

            return response()->json(['message' => 'تم إرسال الدعوة بنجاح!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'حدث خطأ أثناء إرسال الدعوة.'], 500);
        }
    }

    public function reply(Request $request, Invitation $invitation)
    {
        $request->validate([
            'reply' => 'required|in:قبول,رفض',
        ]);

        if ($invitation->destination_user_id !== auth()->id()) {
            return response()->json(['message' => 'غير مصرح لك بالرد على هذه الدعوة.'], 403);
        }

        $invitation->update(['reply' => $request->reply]);

        return response()->json(['message' => 'تم تحديث حالة الدعوة.']);
    }


    public function checkEligibility()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => 'unauthenticated',
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        $completion = $user->profileCompletionPercentage();
        if ($completion < 100) {
            return response()->json([
                'status' => 'incomplete',
                'completion_percentage' => $completion,
                'message' => 'يجب إكمال ملفك الشخصي'
            ], 200);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'يمكنك إرسال الدعوات'
        ]);
    }
}
