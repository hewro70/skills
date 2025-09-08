<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use App\Models\User;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'last_message_at'];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['is_active', 'left_at', 'read_at', 'created_at', 'updated_at']);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // ✅ صحّح الفلترة: استخدم users.id
    public function otherUser(?int $viewerId = null)
    {
        $viewerId = $viewerId ?? auth()->id();
        return $this->users()->where('users.id', '!=', $viewerId);
    }

    public function exchanges()
    {
        return $this->hasMany(\App\Models\Exchange::class);
    }

    /** عدد الرسائل المحتسَبة للتقييم (≥ 7 أحرف) */
    public function eligibleMessagesCount(): int
    {
        return (int) $this->messages()
            ->whereRaw('CHAR_LENGTH(body) >= 7') // PostgreSQL: LENGTH(body)
            ->count();
    }

    /** هل تحقّق شرط فتح التقييم (12 رسالة مجتمعة بطول ≥ 7)؟ */
    public function canTriggerReview(): bool
    {
        return $this->eligibleMessagesCount() >= 12;
    }
}
