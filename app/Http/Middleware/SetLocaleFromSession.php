<?php

namespace App\Http\Middleware;

use Closure;

class SetLocaleFromSession
{
    public function handle($request, Closure $next)
    {
        $locale = session('app_locale', config('app.locale', 'ar'));
        if (! in_array($locale, ['ar','en'])) {
            $locale = 'ar';
        }

        app()->setLocale($locale);

        // لو حاب كمان تواريخ/أرقام:
        try { \Carbon\Carbon::setLocale($locale); } catch (\Throwable $e) {}

        return $next($request);
    }
}
