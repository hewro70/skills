<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invitation $invitation;
    public string $for; // 'sender' | 'receiver'

    public function __construct(Invitation $invitation, string $for = 'sender')
    {
        $this->invitation = $invitation;
        $this->for = $for;
    }

   public function build()
{
    // تأكد من تحميل العلاقات
    $this->invitation->loadMissing(['sourceUser', 'destinationUser']);

    // دالة ترجمة مع فولباك حقيقي
    $tr = function (string $key, string $fallback, array $params = []) {
        $t = __($key, $params);
        return ($t === $key) ? $fallback : $t;
    };

    $brand     = config('app.name', 'Maharat Hub');
    $isAccepted = $this->invitation->isAccepted();

    // أسماء لعرضها بالموضوع لو لزم
    $senderName   = $this->invitation->sourceUser?->fullName()
        ?: ($this->invitation->sourceUser?->email ?? $tr('mail.user', 'User'));
    $receiverName = $this->invitation->destinationUser?->fullName()
        ?: ($this->invitation->destinationUser?->email ?? $tr('mail.user', 'User'));

    // Subject نظيف مع فولباك
    if ($this->for === 'sender') {
        $subject = $isAccepted
            ? $tr('mail.invitation.sender.accepted_subject',  'Your invitation was accepted')
            : $tr('mail.invitation.sender.rejected_subject',  'Your invitation was rejected');
    } else { // receiver
        $subject = $isAccepted
            ? $tr('mail.invitation.receiver.accepted_subject','You accepted the invitation')
            : $tr('mail.invitation.receiver.rejected_subject','You rejected the invitation');
    }

    // (اختياري) ثبّت المُرسِل من إعداداتك
    $mail = $this->subject($subject)
        // ->from(config('mail.from.address'), $brand)
        ->view('emails.invitation_status')
        ->with([
            'for'         => $this->for,        // 'sender' | 'receiver'
            'invitation'  => $this->invitation, // القالب سيستخدم $invitation->reply_norm
        ]);

    return $mail;
}

}
