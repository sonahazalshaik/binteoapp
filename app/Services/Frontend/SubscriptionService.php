<?php

namespace App\Services\Frontend;

use App\Models\Channel;
use App\Models\Subscription;
use App\Models\UserNotification;
use App\Jobs\SendFirebaseNotificationJob;
use Illuminate\Http\Request;

class SubscriptionService
{
    public function toggle(Channel $channel): array
    {
        $user = auth()->user();

        if ($user->id === $channel->user_id) {
            return ['error' => 'Cannot subscribe to your own channel'];
        }

        $subscription = $channel->subscribers()->where('user_id', $user->id)->first();

        if ($subscription) {
            $channel->subscribers()->detach($user->id);
            $channel->decrement('subscribers_count');
            return [
                'subscribed' => false,
                'subscribers_count' => $channel->subscribers_count,
                'notification_preference' => 'none',
            ];
        }

        $channel->subscribers()->attach($user->id, ['notification_preference' => 'all']);
        $channel->increment('subscribers_count');

        UserNotification::create([
            'user_id' => $channel->user_id,
            'sender_id' => $user->id,
            'title' => "{$user->firstname} subscribed to your channel!",
            'click_url' => $user->getProfileUrl(),
        ]);

        SendFirebaseNotificationJob::dispatch($channel->user_id, [
            'title' => 'New Subscriber!',
            'body' => "{$user->firstname} subscribed to your channel!",
            'url' => $user->getProfileUrl(),
        ]);

        return [
            'subscribed' => true,
            'subscribers_count' => $channel->subscribers_count,
            'notification_preference' => 'all',
        ];
    }

    public function updatePreference(Request $request, Channel $channel): array
    {
        $request->validate([
            'preference' => 'required|in:all,none',
        ]);

        $user = auth()->user();
        $subscription = Subscription::where('user_id', $user->id)
            ->where('channel_id', $channel->id)
            ->first();

        if (!$subscription) {
            return ['error' => 'Subscription not found'];
        }

        $subscription->notification_preference = $request->preference;
        $subscription->save();

        return [
            'success' => true,
            'preference' => $subscription->notification_preference,
        ];
    }
}


