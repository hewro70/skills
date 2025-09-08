<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{

public function store(Request $req, Conversation $conversation)
{
    $this->authorize('view', $conversation);

    $req->validate([
        'sender_skill_id'      => 'required|exists:skills,id',
        'receiver_skill_id'    => 'required|exists:skills,id',
        'message_for_receiver' => 'nullable|string|max:1000',
    ]);

    $senderId   = Auth::id();
    $receiverId = $conversation->users()->where('user_id','!=',$senderId)->value('users.id');

    if (!$receiverId) {
        if ($req->ajax()) {
            return response()->json(['success'=>false,'error'=>'لا يمكن تحديد الطرف الآخر.'], 403);
        }
        abort(403);
    }

    $senderOwns = DB::table('user_skills')
        ->where('user_id', $senderId)
        ->where('skill_id', $req->sender_skill_id)
        ->exists();

    $receiverOwns = DB::table('user_skills')
        ->where('user_id', $receiverId)
        ->where('skill_id', $req->receiver_skill_id)
        ->exists();

    if (!$senderOwns) {
        if ($req->ajax()) {
            return response()->json(['success'=>false,'error'=>'المهارة المختارة لا تخصك.'], 422);
        }
        abort(422, 'المهارة المختارة لا تخصك.');
    }

    if (!$receiverOwns) {
        if ($req->ajax()) {
            return response()->json(['success'=>false,'error'=>'المهارة المختارة لا تخص الطرف الآخر.'], 422);
        }
        abort(422, 'المهارة المختارة لا تخص الطرف الآخر.');
    }

$user = Auth::user();

$hasActiveSub = false;
if (method_exists($user, 'subscriptions')) {
    $hasActiveSub = $user->subscriptions()->active()->exists();
} elseif (array_key_exists('is_premium', $user->getAttributes())) {
    $hasActiveSub = (bool) $user->is_premium;
}

if (!$hasActiveSub) {
    $totalRequests = DB::table('exchanges')
        ->where('sender_id', $senderId)
        ->count();

    $MAX = 5;

    if ($totalRequests >= $MAX) {
        $msg = 'لقد بلغت الحدّ الأقصى ('.$MAX.') من طلبات التعلّم المسموح بها بدون اشتراك.';
        if ($req->ajax()) {
            return response()->json(['success'=>false, 'error'=>$msg], 422);
        }
        return back()->withErrors(['skill_limit' => $msg])->withInput();
    }
}


    $ex = Exchange::create([
        'conversation_id'     => $conversation->id,
        'sender_id'           => $senderId,
        'receiver_id'         => $receiverId,
        'sender_skill_id'     => $req->sender_skill_id,
        'receiver_skill_id'   => $req->receiver_skill_id,
        'status'              => 'pending',
        'message_for_receiver'=> $req->message_for_receiver,
    ]);

    return $req->ajax()
        ? response()->json(['success'=>true,'exchange'=>$ex])
        : back()->with('success','تم إرسال طلب التبادل.');
}

public function accept(Request $req, Conversation $conversation, Exchange $exchange)
{
    $this->authorize('view', $conversation);

    if ($exchange->conversation_id !== $conversation->id) {
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>'العنصر غير موجود.'], 404)
            : abort(404);
    }
    if ($exchange->receiver_id !== Auth::id()) {
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>'غير مسموح.'], 403)
            : abort(403);
    }

    $exchange->update(['status'=>'accepted']);
    return $req->ajax() ? ['success'=>true] : back();
}


public function acceptTeachOnly(Request $req, Conversation $conversation, Exchange $exchange)
{
    $this->authorize('view', $conversation);

    if ($exchange->conversation_id !== $conversation->id) {
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>'العنصر غير موجود.'], 404)
            : abort(404);
    }
    if ($exchange->receiver_id !== Auth::id()) {
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>'غير مسموح.'], 403)
            : abort(403);
    }

    $exchange->update([
        'sender_skill_id' => null,
        'status'          => 'accepted',
    ]);

    return $req->ajax() ? ['success'=>true, 'mode'=>'teach_only'] : back();
}
public function reject(Request $req, Conversation $conversation, Exchange $exchange)
{
    $this->authorize('view', $conversation);

    if ($exchange->conversation_id !== $conversation->id) {
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>'العنصر غير موجود.'], 404)
            : abort(404);
    }
    if ($exchange->receiver_id !== Auth::id()) {
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>'غير مسموح.'], 403)
            : abort(403);
    }

    $exchange->update(['status'=>'rejected']);
    return $req->ajax() ? ['success'=>true] : back();
}

public function cancel(Request $req, Conversation $conversation, Exchange $exchange)
{
    $this->authorize('view', $conversation);

    if ($exchange->conversation_id !== $conversation->id) {
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>'العنصر غير موجود.'], 404)
            : abort(404);
    }
    if ($exchange->sender_id !== Auth::id()) {
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>'غير مسموح.'], 403)
            : abort(403);
    }

    $exchange->update(['status'=>'cancelled']);
    return $req->ajax() ? ['success'=>true] : back();
}

  
}
