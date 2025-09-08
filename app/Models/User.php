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
                'is_mentor',         

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
                'is_mentor'         => 'bool',  

    ];
   public function scopeMentors($q) { return $q->where('is_mentor', true); }
    public function scopeNotMentors($q) { return $q->where('is_mentor', false); }

    public function getMentorBadgeAttribute(): ?string
    {
        return $this->is_mentor ? __('badges.mentor') : null;
    }
    protected $appends = ['image_url'];

   
public function country()
{
    return $this->belongsTo(\App\Models\Country::class)->withDefault();
}


 public function getLocationTextAttribute(): string
{
    if (!$this->country_id) {
        return __('talent.location_unknown');
    }

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
    $path = $this->attributes['image_path']
          ?? $this->attributes['image_url']
          ?? $this->attributes['avatar']
          ?? null;

    $name     = $this->fullName() ?: ($this->name ?? '') ?: ($this->email ?? 'User');
    $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($name);

    if ($path) {
        if (preg_match('#^https?://#i', $path)) {
            $url = $path;
        }
        elseif (Str::startsWith($path, '/')) {
            $url = URL::to($path);
        }
        else {
            $normalized = Str::startsWith($path, 'public/') ? substr($path, 7) : $path;

            if (Storage::disk('public')->exists($normalized)) {
                $url = URL::to(Storage::url($normalized));
            } elseif (file_exists(public_path($path))) {
                $url = URL::to(asset($path));
            } else {
                $url = $fallback;
            }
        }
    } else {
        $url = $fallback;
    }

    $v   = optional($this->updated_at)->timestamp ?: time();
    $sep = Str::contains($url, '?') ? '&' : '?';
    return $url . $sep . 'v=' . $v;
}



 public function profileCompletionPercentage()
{
    $totalFields = 0;
    $filledFields = 0;

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

    $totalFields++;
    if ($this->skills()->count() > 0) {
        $filledFields++;
    }

    $totalFields++;
    if ($this->languages()->count() > 0) {
        $filledFields++;
    }

    return $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;
}


      public function hasActiveSubscription(): bool
    {
        if (method_exists($this, 'subscriptions')) {
            if ($this->subscriptions()->active()->exists()) return true;
        }
        if ($this->is_premium) return true;

        

        return false;
    }

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
