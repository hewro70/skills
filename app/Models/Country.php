<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['id','name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    
public function getNameTextAttribute(): string
{
    $locale   = app()->getLocale();
    $fallback = config('app.fallback_locale');

    // لو تستعمل spatie/translatable
    if (method_exists($this, 'getTranslation')) {
        // حاول باللوكال الحالي
        $v = $this->getTranslation('name', $locale, false);

        // لو فاضي، جرّب fallback
        if (!$v && $fallback) {
            $v = $this->getTranslation('name', $fallback, false);
        }

        // لو لسا فاضي، خذ أول ترجمة متاحة
        if (!$v) {
            $all = $this->getTranslations('name');
            if (is_array($all) && !empty($all)) {
                $v = reset($all); // أول قيمة
            }
        }

        // لو ما في ترجمات أبداً، ارجع الحقل الخام (لو نص عادي)
        return (string)($v ?: ($this->attributes['name'] ?? ''));
    }

    // لو ما في translatable: ارجع النص كما هو
    return (string)($this->name ?? '');
}

}
