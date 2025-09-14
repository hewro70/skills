<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Models\Invitation;
use App\Models\User;
use App\Mail\InvitationStatusMail;
use App\Mail\PremiumUpgradedMail;
use Illuminate\Support\Str;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * يرسل إيميل تلقائياً (بدون Queue) عند:
     * 1) تغيير حالة الدعوة إلى accepted أو rejected → يرسل للطرفين.
     * 2) ترقية المستخدم إلى Premium → يرسل للمستخدم نفسه.
     */
    public function boot(): void
    {
        // ===== 1) دعوات: قبول/رفض (إرسال للطرفين) =====


Invitation::updated(function (Invitation $inv) {
    // نتحرك فقط إذا تغير حقل reply
    if (! $inv->wasChanged('reply')) return;

    // طبّع الحالة (الموديل عندك فيه accessor reply_norm)
    $norm = $inv->reply_norm; // 'accepted' | 'rejected' | null
    if ($norm !== 'accepted') return; // لا نرسل شيء عند الرفض أو الـ pending

    // جهّز العلاقات
    $inv->loadMissing(['sourceUser','destinationUser']);

    // أرسل للمرسِل فقط
    $senderEmail = $inv->sourceUser?->email;
    if ($senderEmail) {
        Mail::to($senderEmail)->send(new InvitationStatusMail($inv, 'sender'));
    }
});

    

        // ===== 2) ترقية Premium =====
        User::updated(function (User $user) {
            if ($user->wasChanged('is_premium') && $user->is_premium) {
                if ($user->email) {
                    Mail::to($user->email)->send(new PremiumUpgradedMail($user));

                    // اختياري: نسخة للإدارة
                    // Mail::to('info@maharathub.com')->send(new PremiumUpgradedMail($user));

                    // أو بعد الرد:
                    // dispatch(function () use ($user) {
                    //     Mail::to($user->email)->send(new PremiumUpgradedMail($user));
                    // })->afterResponse();
                }
            }
        });
    }
}
