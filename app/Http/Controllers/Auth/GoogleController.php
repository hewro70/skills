<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // لو بتحب تظهر اختيار الحساب دائمًا أضف ->with(['prompt' => 'select_account'])
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // لو واجهت "Invalid state", جرّب فك التعليق عن السطر التالي واستبدل السطر اللي بعده:
            // $google = Socialite::driver('google')->stateless()->user();
            $google = Socialite::driver('google')->user();

            $email = $google->getEmail();
            if (!$email) {
                // نادراً، بس احتياط
                return redirect()->route('login')->with('error', 'Google account has no email.');
            }

            // ابحث عن المستخدم أو أنشئه
            $user = User::where('email', $email)->first();

            if (!$user) {
                // حسّاس لهيكلة جدول users عندك:
                // إن كان عندك first_name/last_name:
                $attrs = [
                    'email'             => $email,
                    'password'          => bcrypt(Str::random(40)),
                    'email_verified_at' => now(),
                ];

                // لو جدولك فيه first_name/last_name:
                if (User::query()->getModel()->isFillable('first_name') || \Schema::hasColumn('users','first_name')) {
                    $attrs['first_name'] = $google->user['given_name']  ?? '';
                }
                if (User::query()->getModel()->isFillable('last_name') || \Schema::hasColumn('users','last_name')) {
                    $attrs['last_name']  = $google->user['family_name'] ?? '';
                }

                // لو جدولك فيه name (بدل first/last):
                if (User::query()->getModel()->isFillable('name') || \Schema::hasColumn('users','name')) {
                    $attrs['name'] = $google->getName() ?: (($attrs['first_name'] ?? '').' '.($attrs['last_name'] ?? ''));
                }

                // (اختياري) حفظ الصورة:
                if (User::query()->getModel()->isFillable('image_path') || \Schema::hasColumn('users','image_path')) {
                    $attrs['image_path'] = $google->getAvatar();
                }

                $user = User::create($attrs);
            }

            Auth::login($user, true);
            return redirect()->route('theme.index'); // أو إلى /dashboard حسب مشروعك

        } catch (Throwable $e) {
            // للتشخيص أثناء التطوير:
            // report($e);
            return redirect()->route('login')->with('error', 'Google authentication failed');
        }
    }
}
