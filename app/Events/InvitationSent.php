<?php

namespace App\Events;

use App\Models\Invitation;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitationSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Invitation $invitation) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->invitation->destination_user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'invitation.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id'    => $this->invitation->id,
            'from'  => $this->invitation->sourceUser?->fullName() ?? '',
            'at'    => optional($this->invitation->date_time)->format('Y-m-d H:i'),
            'url'   => route('invitations.index'),
            'title' => __('invitations.title_received'),
            'body'  => __('invitations.sent_at', ['date' => optional($this->invitation->date_time)->format('Y-m-d H:i')]),
            'icon'  => 'fa-envelope-open-text',
        ];
    }
}
