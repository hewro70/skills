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

    if (method_exists($this, 'getTranslation')) {
        $v = $this->getTranslation('name', $locale, false);

        if (!$v && $fallback) {
            $v = $this->getTranslation('name', $fallback, false);
        }

        if (!$v) {
            $all = $this->getTranslations('name');
            if (is_array($all) && !empty($all)) {
                $v = reset($all); 
            }
        }

        return (string)($v ?: ($this->attributes['name'] ?? ''));
    }

    return (string)($this->name ?? '');
}

}
