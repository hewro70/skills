<?php

namespace App\Mail;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewMessageReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Conversation $conversation;
    public Message $chat; // <-- سمِّيه chat لتجنّب اللخبطة

    public function __construct(Conversation $conversation, Message $message)
    {
        $this->conversation = $conversation->loadMissing('users');
        $this->chat         = $message->loadMissing('user');
    }

    public function build()
    {
        $senderName = $this->chat->user?->fullName()
            ?: $this->chat->user?->email
            ?: 'User';

        $subject = __('mail.chat.new_message_subject', ['name' => $senderName]);
        if ($subject === 'mail.chat.new_message_subject') {
            $subject = "New message from {$senderName}";
        }

        return $this->subject($subject)
            ->view('emails.chat_new_message')
            ->with([
                'conversation' => $this->conversation,
                'chat'         => $this->chat, // <-- لا تمرّر message
            ]);
    }
}
