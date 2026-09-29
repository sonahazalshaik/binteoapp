<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $credentialsPath = storage_path('app/firebase-credentials.json');

        if (!file_exists($credentialsPath)) {
            $credentialsPath = base_path('matrimony-v1-ed605-firebase-adminsdk-fbsvc-540b9829e8.json');
        }

        if (file_exists($credentialsPath)) {
            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
        }
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        if (!$this->messaging || !$token) {
            return false;
        }

        try {
            $dataPayload = array_merge([
                'title' => $title,
                'body' => $body,
                'click_action' => $data['url'] ?? $data['click_action'] ?? '',
            ], $data);

            $notification = Notification::create($title, $body);

            if (!empty($data['image'])) {
                $notification = $notification->withImageUrl($data['image']);
            }

            $message = CloudMessage::withTarget('token', $token)
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

            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            \Log::error('Firebase Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendToTopic($topic, $title, $body, $data = [])
    {
        if (!$this->messaging) {
            return false;
        }

        try {
            $dataPayload = array_merge([
                'title' => $title,
                'body' => $body,
                'click_action' => $data['url'] ?? $data['click_action'] ?? '',
            ], $data);

            $notification = Notification::create($title, $body);

            if (!empty($data['image'])) {
                $notification = $notification->withImageUrl($data['image']);
            }

            $message = CloudMessage::withTarget('topic', $topic)
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

            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            \Log::error('Firebase Topic Notification Error: ' . $e->getMessage());
            return false;
        }
    }
}
