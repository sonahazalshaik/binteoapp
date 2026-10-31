<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    private $comment;
    private $video;

    public function __construct($comment, $video)
    {
        $this->comment = $comment;
        $this->video = $video;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => "{$this->comment->user->name} commented on your video: {$this->video->title}",
            'url' => route('videos.show', $this->video->id),
        ];
    }
}
