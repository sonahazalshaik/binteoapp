<?php

namespace App\Jobs;

use App\Events\UserNotification;
use App\Models\UserNotification as UserNotificationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifySubscribers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;
    public int $tries = 2;

    public function __construct(
        public int $userId,
        public string $channelName,
        public string $title,
        public string $url,
        public string $notificationTitle,
        public string $type = 'video'
    ) {
        $this->onQueue('high');
    }

    public function handle(): void
    {
        Log::info('[NOTIFY-PIPELINE][STAGE-2] NotifySubscribers job STARTED.', [
            'user_id' => $this->userId,
            'channel' => $this->channelName,
            'type' => $this->type,
            'title' => $this->title,
        ]);

        $user = \App\Models\User::find($this->userId);
        if (!$user || !$user->channel) {
            Log::info('[NOTIFY-PIPELINE][STAGE-2] User or channel not found, aborting.', ['user_id' => $this->userId]);
            return;
        }

        $channelId = $user->channel->id;

        // 1. Fetch all subscriber IDs in a single flat array
        $subscriberIds = \DB::table('subscriptions')
            ->where('channel_id', $channelId)
            ->where('notification_preference', 'all')
            ->pluck('user_id')
            ->toArray();

        if (empty($subscriberIds)) {
            Log::info('[NOTIFY-PIPELINE][STAGE-2] No subscribers found for channel, aborting.', ['channel_id' => $channelId]);
            return;
        }

        // 2. Fetch all corresponding FCM tokens using an efficient join query
        $tokens = \DB::table('subscriptions')
            ->join('firebase_tokens', 'subscriptions.user_id', '=', 'firebase_tokens.user_id')
            ->where('subscriptions.channel_id', $channelId)
            ->where('subscriptions.notification_preference', 'all')
            ->whereNotNull('firebase_tokens.token')
            ->distinct()
            ->pluck('firebase_tokens.token')
            ->toArray();

        Log::info('[NOTIFY-PIPELINE][STAGE-2] Subscriber query completed.', [
            'channel_id' => $channelId,
            'subscriber_count' => count($subscriberIds),
            'fcm_token_count' => count($tokens),
        ]);

        // 3. Process subscribers in chunks of 500 for bulk DB insert & queued broadcasts
        $chunks = array_chunk($subscriberIds, 500);
        foreach ($chunks as $chunk) {
            $notificationData = [];
            $now = now();

            foreach ($chunk as $subId) {
                $notificationData[] = [
                    'user_id' => $subId,
                    'sender_id' => $this->userId,
                    'title' => $this->title,
                    'click_url' => $this->url,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                try {
                    broadcast(new UserNotification(
                        $subId,
                        $this->title,
                        $this->url
                    ));
                } catch (\Exception $e) {
                    Log::error('Subscriber broadcast queueing failed: ' . $e->getMessage());
                }
            }

            try {
                UserNotificationModel::insert($notificationData);
            } catch (\Exception $e) {
                Log::error('Subscriber bulk notification insert failed: ' . $e->getMessage());
            }
        }

        // 4. Batch tokens in groups of 500 and dispatch high-priority FCM job per batch
        if (!empty($tokens)) {
            $tokenChunks = array_chunk($tokens, 500);
            Log::info('[NOTIFY-PIPELINE][STAGE-2] Dispatching Firebase batch jobs.', [
                'total_tokens' => count($tokens),
                'batch_count' => count($tokenChunks),
            ]);
            foreach ($tokenChunks as $batchIndex => $tokenChunk) {
                SendFirebaseNotificationJob::dispatch(
                    0, 
                    [
                        'title' => $this->notificationTitle,
                        'body' => $this->title,
                        'url' => $this->url,
                    ],
                    'user',
                    $tokenChunk
                );
                Log::info('[NOTIFY-PIPELINE][STAGE-2] Firebase batch job dispatched.', [
                    'batch' => $batchIndex + 1,
                    'tokens_in_batch' => count($tokenChunk),
                ]);
            }
        } else {
            Log::info('[NOTIFY-PIPELINE][STAGE-2] No FCM tokens found, skipping Firebase dispatch.');
        }

        Log::info('[NOTIFY-PIPELINE][STAGE-2] NotifySubscribers job COMPLETED.', [
            'user_id' => $this->userId,
            'channel' => $this->channelName,
            'subscribers_notified' => count($subscriberIds),
        ]);
    }
}
