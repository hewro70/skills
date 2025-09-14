<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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

   
public function getImageUrlAttribute(): string
{
    // جرّب أي حقل عندك للصورة
    $path = $this->attributes['image_path']
          ?? $this->attributes['image_url']
          ?? $this->attributes['avatar']
          ?? null;

    // فولباك افتراضي (اسم المستخدم على ui-avatars)
    $name     = $this->fullName() ?: ($this->name ?? '') ?: ($this->email ?? 'User');
    $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($name);

    // لو في قيمة
    if ($path) {
        // 1) لو URL كامل جاهز
        if (preg_match('#^https?://#i', $path)) {
            $url = $path;
        }
        // 2) لو بيبدأ بـ "/" خلّيه مطلق على نفس الدومين
        elseif (Str::startsWith($path, '/')) {
            $url = URL::to($path);
        }
        // 3) ملف على public disk أو داخل public
        else {
            // شيل "public/" لو موجود
            $normalized = Str::startsWith($path, 'public/') ? substr($path, 7) : $path;

            if (Storage::disk('public')->exists($normalized)) {
                // /storage/... ← نخليه مطلق
                $url = URL::to(Storage::url($normalized));
            } elseif (file_exists(public_path($path))) {
                // موجود جوّا public مباشرة
                $url = URL::to(asset($path));
            } else {
                $url = $fallback;
            }
        }
    } else {
        $url = $fallback;
    }

    // كسر كاش قوي (يتغيّر مع تحديث الملف)
    $v   = optional($this->updated_at)->timestamp ?: time();
    $sep = Str::contains($url, '?') ? '&' : '?';
    return $url . $sep . 'v=' . $v;
}



public function profileCompletionPercentage()
{
    // الحقول المطلوبة (الحد الأدنى)
    $requiredFields = [
        'first_name',
        'last_name',
        'country_id',
    ];

    $totalRequired = count($requiredFields) + 2; // +1 للمهارات +1 للغات
    $filledRequired = 0;

    // تحقق من الحقول الأساسية
    foreach ($requiredFields as $field) {
        if (!empty($this->$field)) {
            $filledRequired++;
        }
    }

    // مهارة وحدة تكفي
    if ($this->skills()->count() > 0) {
        $filledRequired++;
    }

    // لغة وحدة تكفي
    if ($this->languages()->count() > 0) {
        $filledRequired++;
    }

    // النسبة الأساسية (الحد الأدنى من الإكمال)
    $percentage = ($filledRequired / $totalRequired) * 100;

    // الحقول الاختيارية (بونس فقط)
    $optionalFields = [
        'date_of_birth',
        'gender',
        'about_me',
        'phone',
        'image_path',
    ];

    $bonus = 0;
    foreach ($optionalFields as $field) {
        if (!empty($this->$field)) {
            $bonus += 3; // كل حقل اختياري يزيد 3% فقط
        }
    }

    // النتيجة النهائية (ما تتجاوز 100%)
    return min(100, round($percentage + $bonus));
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
