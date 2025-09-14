<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'reply',
        'source_user_id',
        'destination_user_id',
        'message',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    // ✅ خلي reply_norm يطلع تلقائيًا مع المخرجات
    protected $appends = ['reply_norm'];

    public function sourceUser(){ return $this->belongsTo(User::class, 'source_user_id'); }
    public function destinationUser(){ return $this->belongsTo(User::class, 'destination_user_id'); }

    public function getReplyNormAttribute(): ?string
    {
        $raw = $this->reply;

        if ($raw === null) return null;

        // ننظّف المسافات ونحوّل لحروف صغيرة (يدعم العربي)
        $v = trim(mb_strtolower((string) $raw));

        $map = [
            // accepted
            'accepted' => 'accepted', 'accept' => 'accepted', 'approve' => 'accepted',
            'قبول' => 'accepted', 'موافقة' => 'accepted',
            // rejected
            'rejected' => 'rejected', 'reject' => 'rejected', 'declined' => 'rejected',
            'رفض' => 'rejected', 'مرفوض' => 'rejected',
        ];

        return $map[$v] ?? null; // null = pending
    }

    public function isAccepted(): bool { return $this->reply_norm === 'accepted'; }
    public function isRejected(): bool { return $this->reply_norm === 'rejected'; }
    public function isPending(): bool  { return $this->reply_norm === null; }
}
