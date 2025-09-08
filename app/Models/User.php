<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'country_id',
        'role',
        'about_me',
        'image_path',
        'is_premium'        => 'bool',
                'is_mentor',         // 👈 جديد

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
                'is_mentor'         => 'bool',   // 👈

    ];
   public function scopeMentors($q) { return $q->where('is_mentor', true); }
    public function scopeNotMentors($q) { return $q->where('is_mentor', false); }

    // (اختياري) وسم جاهز للنص
    public function getMentorBadgeAttribute(): ?string
    {
        return $this->is_mentor ? __('badges.mentor') : null;
    }
    protected $appends = ['image_url'];

   
public function country()
{
    return $this->belongsTo(\App\Models\Country::class)->withDefault();
}

/**
 * نص موقع المستخدم (الدولة) بلغة الواجهة
 */public function getLocationTextAttribute(): string
{
    // لو مافي country_id أصلاً
    if (!$this->country_id) {
        return __('talent.location_unknown');
    }

    // استخدم name_text الجديد مع fallback
    $c = $this->getRelationValue('country') ?? \App\Models\Country::find($this->country_id);
    return $c?->name_text ?: __('talent.location_unknown');
}
public function skills()
{
    return $this->belongsToMany(Skill::class, 'user_skills')
        ->using(UserSkill::class)
        ->withPivot(['level', 'description'])
        ->withTimestamps();
}


    public function languages()
    {
        return $this->belongsToMany(Language::class, 'user_languages')
            ->withPivot('level');
    }

    public function fullName()
    {
        return trim("{$this->first_name} {$this->last_name}") ?: '';
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return $this->gender === 'female'
            ? 'https://cdn-icons-png.flaticon.com/512/4140/4140047.png'
            : 'https://cdn-icons-png.flaticon.com/512/4140/4140048.png';
    }

 public function profileCompletionPercentage()
{
    $totalFields = 0;
    $filledFields = 0;

    // حقول أساسية (أسهل)
    $basicFields = [
        'first_name',
        'last_name',
        'phone',
        'country_id',
    ];

    foreach ($basicFields as $field) {
        $totalFields++;
        if (!empty($this->$field)) {
            $filledFields++;
        }
    }

    // حقول اختيارية (تزيد النسبة بس مش شرط)
    $optionalFields = [
        'date_of_birth',
        'gender',
        'about_me',
        'image_path',
    ];
    foreach ($optionalFields as $field) {
        $totalFields++;
        if (!empty($this->$field)) {
            $filledFields++;
        }
    }

    // Skills (مجرد وجود Skill واحدة يكفي)
    $totalFields++;
    if ($this->skills()->count() > 0) {
        $filledFields++;
    }

    // Languages (مجرد لغة وحدة تكفي)
    $totalFields++;
    if ($this->languages()->count() > 0) {
        $filledFields++;
    }

    return $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;
}

    /**
     * Invitations sent by this user.
     */
      public function hasActiveSubscription(): bool
    {
        // إن كنت مركّب Cashier:
        if (method_exists($this, 'subscriptions')) {
            if ($this->subscriptions()->active()->exists()) return true;
        }
        // فلاغ بسيط:
        if ($this->is_premium) return true;

        // (اختياري) تاريخ انتهاء:
        // if ($this->premium_until && now()->lt($this->premium_until)) return true;

        return false;
    }

    // (اختياري) Scopes سريعة
    public function scopePremium($q)   { $q->where('is_premium', true); }
    public function scopeFree($q)      { $q->where('is_premium', false); }

    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'source_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedInvitations()
    {
        return $this->hasMany(Invitation::class, 'destination_user_id');
    }

    public function sentReviews()
    {
        return $this->hasMany(Review::class, 'sender_id');
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'receved_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)
            ->withPivot(['is_active', 'left_at', 'read_at', 'created_at', 'updated_at']);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function hasConversationWith($userId)
    {
        return $this->conversations()
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->exists();
    }
}
