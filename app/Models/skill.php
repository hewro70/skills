<?php

// app/Models/Skill.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Skill extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'classification_id'];
    public $translatable = ['name'];

    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
                    ->using(UserSkill::class)
                    ->withPivot('description')
                    ->withTimestamps();
    }

    public function exchanges()
    {
        return $this->hasMany(Exchange::class, 'skill_id');
    }

    /* سكوبات مساعدة */
    public function scopeWhereNameLike($q, string $term, ?string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $q->where("name->$locale", 'like', "%{$term}%");
    }

    public function scopeOrderByTranslatedName($q, ?string $locale = null, string $dir = 'asc')
    {
        $locale = $locale ?? app()->getLocale();
        return $q->orderByRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))) $dir");
    }
public function taughtExchanges()
{
    return $this->hasMany(\App\Models\Exchange::class, 'sender_skill_id');
}

public function learnedExchanges()
{
    return $this->hasMany(\App\Models\Exchange::class, 'receiver_skill_id');
}

}