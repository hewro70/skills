<?php

namespace App\Listeners;

use App\Events\InvitationSent;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInvitationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvitationSent $event)
    {
        $invitation = $event->invitation;
        $user = $invitation->destinationUser;

        // Ensure OneSignal ID is available
        if (!$user->onesignal_player_id) return;

        Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.onesignal.rest_api_key'),
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => [$user->onesignal_player_id],
            'headings' => ['en' => 'New Invitation'],
            'contents' => ['en' => 'You received a new invitation!'],
            'url' => route('invitations.index'),
        ]);
    }
}
