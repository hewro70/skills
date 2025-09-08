<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
        // نضمن وجود المستخدمين
        $this->message->loadMissing('conversation.users');
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /** ابث على قناتين: محادثة + قنوات المستخدمين (باستثناء المرسل) */
    public function broadcastOn(): array
    {
        $channels = [ new PrivateChannel('conversation.' . $this->message->conversation_id) ];

        $senderId = (int) $this->message->user_id;
        foreach ($this->message->conversation->users as $u) {
            if ((int) $u->id !== $senderId) {
                $channels[] = new PrivateChannel('App.Models.User.' . $u->id);
            }
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id'              => $this->message->id,
                'body'            => $this->message->body,
                'created_at'      => optional($this->message->created_at)->toIso8601String(),
                'user_id'         => $this->message->user_id,
                'conversation_id' => $this->message->conversation_id,
            ],
            // بيانات مختصرة لواجهة الجرس
            'title' => __('conversations.new_message_title', [], app()->getLocale() ?? 'ar'), // اختياري
            'body'  => str($this->message->body)->limit(80),
            'url'   => route('conversations.show', $this->message->conversation_id),
            'icon'  => 'fa-message'
        ];
    }
}
