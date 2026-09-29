<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VideoPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $video;
    public $trx;

    public function __construct($user, $video, $trx)
    {
        $this->user = $user;
        $this->video = $video;
        $this->trx = $trx;
    }

    public function build()
    {
        return $this->subject('Premium Video Purchased - ' . $this->video->title)
                    ->view('emails.video_purchase');
    }
}
