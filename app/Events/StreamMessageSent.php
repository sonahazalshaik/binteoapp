<?php

namespace App\Events;

use App\Models\LiveChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StreamMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(LiveChatMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('live-stream.' . $this->message->live_stream_id),
        ];
    }

    public function broadcastWith(): array
    {
        $user = clone $this->message->user;
        $user->channel = $this->message->user->channel; // Eager load channel

        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user' => [
                'id' => $user->id,
                'name' => $user->channel->name ?? $user->name,
                'avatar' => substr($user->channel->name ?? $user->name, 0, 1),
            ],
            'created_at' => $this->message->created_at->format('H:i'),
        ];
    }
}
