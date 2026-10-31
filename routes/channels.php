<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('live-stream.{streamId}', function ($user, $streamId) {
    if ($user) {
        return [
            'id' => $user->id, 
            'name' => $user->name,
            'avatar' => substr($user->name, 0, 1) // Provide initials for the chat UI
        ];
    }
});
