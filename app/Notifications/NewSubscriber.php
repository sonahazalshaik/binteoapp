<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSubscriber extends Notification
{
    use Queueable;

    private $subscriber;

    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => "{$this->subscriber->name} subscribed to your channel!",
            'url' => route('home'),
        ];
    }
}
