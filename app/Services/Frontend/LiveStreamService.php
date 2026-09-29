<?php

namespace App\Services\Frontend;

use App\Models\LiveStream;
use App\Events\StreamMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LiveStreamService
{
    public function show($slug): array
    {
        $stream = LiveStream::where('slug', $slug)
            ->with('user.channel')
            ->firstOrFail();

        $messages = $stream->chatMessages()
            ->with('user.channel')
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return compact('stream', 'messages');
    }

    public function sendMessage(Request $request, LiveStream $stream): array
    {
        $request->validate(['message' => 'required|string|max:500']);

        $chat = $stream->chatMessages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        try {
            broadcast(new StreamMessageSent($chat));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Stream broadcast failed: ' . $e->getMessage());
        }

        return ['status' => 'Message sent successfully'];
    }

    public function goLive(Request $request): LiveStream
    {
        $user = auth()->user();

        $stream = LiveStream::firstOrCreate(
            ['user_id' => $user->id],
            [
                'title' => $user->name . "'s Live Stream",
                'slug' => Str::slug($user->name . ' live ' . Str::random(5)),
                'stream_key' => Str::upper(Str::random(12)),
            ]
        );

        $stream->update(['is_live' => true]);

        return $stream;
    }
}
