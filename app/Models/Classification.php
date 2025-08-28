<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Classification extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name']; // ما تحتاج 'id'

    public $translatable = ['name'];

    public function skills()
    {
        return $this->hasMany(Skill::class);
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
        // MySQL
        return $q->orderByRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"'))) $dir");
    }
}