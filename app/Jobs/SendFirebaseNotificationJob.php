<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receiverId;
    protected $data;
    protected $receiverType;
    protected $tokens;
    public int $timeout = 120;
    public int $tries = 3;


    public function __construct($receiverId, $data, $receiverType = 'user', array $tokens = null)
    {
        $this->receiverId = $receiverId;
        $this->data = $data;
        $this->receiverType = $receiverType;
        $this->tokens = $tokens;
        $this->onQueue('high');
    }

    public function handle(): void
    {
        if ($this->tokens !== null) {
            $tokens = $this->tokens;
        } else {
            $query = \App\Models\FirebaseToken::query();
            if ($this->receiverType === 'admin') {
                $query->where('admin_id', $this->receiverId);
            } else {
                $query->where('user_id', $this->receiverId);
            }
            $tokens = $query->pluck('token')->toArray();
        }

        if (empty($tokens)) {
            \Log::info("[NOTIFY-PIPELINE][STAGE-3] Firebase Job [{$this->receiverType}:{$this->receiverId}]: No tokens found, skipping.");
            return;
        }

        \Log::info("[NOTIFY-PIPELINE][STAGE-3] Firebase Job [{$this->receiverType}:{$this->receiverId}]: Sending to " . count($tokens) . " tokens.");

        try {
            $credentialsPath = config('services.firebase.credentials');
            if (!file_exists($credentialsPath)) {
                \Log::error("Firebase Credentials missing at: " . $credentialsPath);
                return;
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $messaging = $factory->createMessaging();

            $title = $this->data['title'] ?? '';
            $body = $this->data['body'] ?? '';

            $dataPayload = array_merge([
                'title' => $title,
                'body' => $body,
                'image' => $this->data['image'] ?? '',
                'url' => $this->data['url'] ?? '/',
                'click_action' => $this->data['url'] ?? '/',
                'sender_id' => (string)($this->data['sender_id'] ?? ''),
            ], $this->data['extra_data'] ?? []);

            $notification = Notification::create($title, $body);

            if (!empty($this->data['image'])) {
                $notification = $notification->withImageUrl($this->data['image']);
            }

            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($dataPayload)
                ->withApnsConfig(\Kreait\Firebase\Messaging\ApnsConfig::new()->withPriority('10'))
                ->withWebPushConfig(\Kreait\Firebase\Messaging\WebPushConfig::new()->withHighUrgency())
                ->withAndroidConfig(
                    \Kreait\Firebase\Messaging\AndroidConfig::fromArray([
                        'ttl' => '86400s',
                        'priority' => 'high',
                        'notification' => [
                            'notification_priority' => 'PRIORITY_HIGH',
                            'sound' => 'default',
                            'channel_id' => 'high_importance_channel',
                        ],
                    ])
                );

            $report = $messaging->sendMulticast($message, $tokens);

            \Log::info("[NOTIFY-PIPELINE][STAGE-3] Firebase Job [{$this->receiverType}:{$this->receiverId}]: Success: " . $report->successes()->count() . ", Failures: " . $report->failures()->count());

            if ($report->hasFailures()) {
                foreach ($report->failures()->getItems() as $failure) {
                    $token = $failure->target()->value();
                    $error = $failure->error();
                    $errorMessage = $error ? $error->getMessage() : 'Unknown error';
                    \Log::warning("[NOTIFY-PIPELINE][STAGE-3] Firebase Token Failure: " . $errorMessage . " (Token: " . substr($token, 0, 15) . "...)");
                    \App\Models\FirebaseToken::where('token', $token)->delete();
                }
            }
        } catch (\Throwable $e) {
            \Log::error("[NOTIFY-PIPELINE][STAGE-3][ERROR] Firebase Sending Error: " . $e->getMessage());
        }
    }
}
