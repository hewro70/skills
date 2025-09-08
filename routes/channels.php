<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', fn($user,$id) => (int)$user->id === (int)$id);



Broadcast::channel('conversation.{conversation}', function ($user, $conversation) {
    return \App\Models\Conversation::where('id', $conversation)
        ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
        ->exists();
});

