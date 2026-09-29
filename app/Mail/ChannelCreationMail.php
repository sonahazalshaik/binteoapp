<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChannelCreationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $channelName;

    public function __construct($user, $channelName)
    {
        $this->user = $user;
        $this->channelName = $channelName;
    }

    public function build()
    {
        return $this->subject('Your Channel is Ready!')
                    ->view('emails.channel_creation');
    }
}
