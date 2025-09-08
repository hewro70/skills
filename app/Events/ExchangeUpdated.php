<?php

namespace App\Events;

use App\Models\Exchange;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExchangeUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Exchange $exchange,
        public string   $action,        
        public int      $targetUserId   
    ) {
        $this->exchange->loadMissing(['sender','receiver','senderSkill','receiverSkill']);
    }

    public function broadcastAs(): string
    {
        return 'exchange.updated';
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' . $this->targetUserId);
    }

    public function broadcastWith(): array
    {
        $ex  = $this->exchange;
        $url = route('conversations.show', $ex->conversation_id);

        $title = __('exchanges.title');
        $body  = match ($this->action) {
            'created'             => __('exchanges.notify.created', ['name' => $ex->sender?->fullName() ?? '']),
            'accepted'            => __('exchanges.notify.accepted'),
            'accepted_teach_only' => __('exchanges.notify.accepted_teach_only'),
            'rejected'            => __('exchanges.notify.rejected'),
            'cancelled'           => __('exchanges.notify.cancelled'),
            default               => __('statuses.' . ($ex->status ?? 'pending')),
        };

        return [
            'id'              => $ex->id,
            'action'          => $this->action,
            'status'          => $ex->status,
            'sender_name'     => $ex->sender?->fullName(),
            'receiver_name'   => $ex->receiver?->fullName(),
            'sender_skill'    => $ex->senderSkill?->name,
            'receiver_skill'  => $ex->receiverSkill?->name,
            'conversation_id' => $ex->conversation_id,
            'url'             => $url,
            'title'           => $title,
            'body'            => $body,
            'icon'            => 'fa-people-arrows', 
        ];
    }
}
