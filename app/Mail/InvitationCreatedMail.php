<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invitation $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

public function build()
{
    // تأكد إن العلاقات جاهزة للقالب
    $this->invitation->loadMissing(['sourceUser', 'destinationUser']);

    // دالة ترجمة بفولباك حقيقي (لو المفتاح مفقود)
    $tr = function (string $key, string $fallback, array $params = []) {
        $t = __($key, $params);
        return ($t === $key) ? $fallback : $t;
    };

    $brand      = config('app.name', 'Maharat Hub');
    $senderName = $this->invitation->sourceUser?->fullName()
        ?: ($this->invitation->sourceUser?->email ?? $tr('mail.user', 'User'));

    // Subject أنظف + فولباك
    $subject = $tr('mail.invitation.created.subject', 'New invitation on '.$brand, ['name' => $senderName]);

    // (اختياري) ثبّت عنوان المُرسِل من إعدادات mail.from (إذا حاب)
    $mail = $this->subject($subject)
        // ->from(config('mail.from.address'), config('app.name', 'Maharat Hub'))
        ->view('emails.invitation_created')
        ->with([
            'invitation' => $this->invitation,
        ]);

    return $mail;
}


}
