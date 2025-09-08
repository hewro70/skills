<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invitation;
use App\Models\Exchange;

class NotificationController extends Controller
{
    public function counts(Request $request)
    {
        $user = $request->user();

        // الدعوات غير المُجاب عليها
        $invitations = Invitation::where('destination_user_id', $user->id)
            ->whereNull('reply')
            ->count();

        // طلبات التبادل الواردة قيد الانتظار
        $exchanges = Exchange::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->count();

        /**
         * المحادثات التي "لم ترد عليها":
         * آخر رسالة في المحادثة مرسلة من غيرك.
         */
        $lastMsgPerConv = DB::table('messages as m1')
            ->select('m1.conversation_id', 'm1.user_id', 'm1.id')
            ->whereRaw('m1.id = (select max(m2.id) from messages m2 where m2.conversation_id = m1.conversation_id)');

        $chats = DB::query()
            ->fromSub($lastMsgPerConv, 'lm')
            ->join('conversation_user as cu', 'cu.conversation_id', '=', 'lm.conversation_id')
            ->where('cu.user_id', $user->id)
            ->where('cu.is_active', true)              // اختياري: فعّال فقط
            ->whereColumn('lm.user_id', '!=', 'cu.user_id') // آخر رسالة ليست منّي
            ->count();

        return response()->json([
            'chats'       => $chats,         // ← عدد المحادثات التي لم ترد عليها
            'invitations' => $invitations,
            'exchanges'   => $exchanges,
            'total'       => $chats + $invitations + $exchanges,
        ]);
    }
}
