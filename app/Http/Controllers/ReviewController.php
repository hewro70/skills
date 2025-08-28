<?php
// app/Http/Controllers/ReviewController.php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // إنشاء تقييم جديد
    // POST /conversations/{conversation}/reviews
// app/Http/Controllers/ReviewController.php
// app/Http/Controllers/ReviewController.php

public function store(Request $req, Conversation $conversation)
{
    $this->authorize('view', $conversation);

    $req->validate([
        'ratings' => 'required|integer|between:1,5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $senderId   = Auth::id();
    $receiverId = $conversation->users()->where('users.id', '!=', $senderId)->value('users.id');
    abort_if(!$receiverId, 403);

    // لا تقيّم نفسك
    if ((int)$receiverId === (int)$senderId) {
        $msg = 'لا يمكنك تقييم نفسك.';
        return $req->ajax()
            ? response()->json(['success'=>false,'error'=>$msg], 422)
            : back()->withErrors(['self_review'=>$msg])->withInput();
    }

    // نشتغل داخل ترانزاكشن لمنع السباقات
    return \DB::transaction(function() use ($req, $conversation, $senderId, $receiverId) {

        // 1) احسب عدد "التبادلات المقبولة" بينكما (أي اتجاه) — ممكن عبر كل المحادثات
        $acceptedCount = \DB::table('exchanges')
            ->where('status', 'accepted')
            ->where(function($q) use ($senderId, $receiverId) {
                $q->where(function($qq) use ($senderId, $receiverId) {
                    $qq->where('sender_id', $senderId)->where('receiver_id', $receiverId);
                })->orWhere(function($qq) use ($senderId, $receiverId) {
                    $qq->where('sender_id', $receiverId)->where('receiver_id', $senderId);
                });
            })
            ->count();

        if ($acceptedCount === 0) {
            $msg = 'لا يمكنك التقييم إلا بعد إتمام تبادل مقبول بينكما.';
            return $req->ajax()
                ? response()->json(['success'=>false,'error'=>$msg], 422)
                : back()->withErrors(['review_not_allowed'=>$msg])->withInput();
        }

        // 2) اقفل صفوف المراجعات ذات الصلة قبل العدّ (لتفادي السباق)
        \DB::table('reviews')
            ->where('sender_id',  $senderId)
            ->where('receved_id', $receiverId)
            ->lockForUpdate()
            ->get(); // مجرد لمس الصفوف المطلوب قفلها

        // 3) عدّ عدد التقييمات التي أرسلها هذا "المرسل" لنفس "المستلم" حتى الآن
        $reviewsCount = Review::where('sender_id', $senderId)
                              ->where('receved_id', $receiverId)
                              ->count();

        // 4) مسموح تقييم جديد فقط إذا reviewsCount < acceptedCount
        if ($reviewsCount >= $acceptedCount) {
            $msg = 'لقد وصلت للحد الأقصى من التقييمات المتاحة لك بناءً على عدد التبادلات المقبولة.';
            return $req->ajax()
                ? response()->json(['success'=>false,'error'=>$msg], 422)
                : back()->withErrors(['review_limit'=>$msg])->withInput();
        }

        // 5) إنشاء التقييم
        $review = Review::create([
            'sender_id'         => $senderId,
            'receved_id'        => $receiverId,
            'ratings'           => (int) $req->ratings,
            'comment'           => $req->comment,
            'reply'             => null,
            'reply_created_at'  => null,
        ]);

        return $req->ajax()
            ? response()->json(['success'=>true, 'review'=>$review], 201)
            : back()->with('success', 'تم إرسال التقييم بنجاح.');
    });
}



    // تعديل التقييم (المرسل فقط)
    // PATCH /conversations/{conversation}/reviews/{review}
    public function update(Request $req, Conversation $conversation, Review $review)
    {
        $this->authorize('view', $conversation);
        abort_if(!in_array(Auth::id(), [$review->sender_id, $review->receved_id]), 403);
        abort_if($review->sender_id !== Auth::id(), 403);

        $req->validate([
            'ratings' => 'sometimes|required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($req->only('ratings','comment'));

        return $req->ajax()
            ? response()->json(['success'=>true, 'review'=>$review])
            : back()->with('success', 'تم تحديث التقييم.');
    }

    // رد المستلم على التقييم
    // PATCH /conversations/{conversation}/reviews/{review}/reply
    public function reply(Request $req, Conversation $conversation, Review $review)
    {
        $this->authorize('view', $conversation);
        abort_if(!in_array(Auth::id(), [$review->sender_id, $review->receved_id]), 403);
        abort_if($review->receved_id !== Auth::id(), 403);

        $req->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'reply'            => $req->reply,
            'reply_created_at' => now(),
        ]);

        return $req->ajax()
            ? response()->json(['success'=>true, 'review'=>$review])
            : back()->with('success', 'تم إضافة الرد على التقييم.');
    }

    // حذف التقييم (منشئ التقييم فقط)
    // DELETE /conversations/{conversation}/reviews/{review}
    public function destroy(Request $req, Conversation $conversation, Review $review)
    {
        $this->authorize('view', $conversation);
        abort_if(!in_array(Auth::id(), [$review->sender_id, $review->receved_id]), 403);
        abort_if($review->sender_id !== Auth::id(), 403);

        $review->delete();

        return $req->ajax()
            ? response()->json(['success'=>true])
            : back()->with('success', 'تم حذف التقييم.');
    }
}
