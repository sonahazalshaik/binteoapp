<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $reason;

    public function __construct($userName, $reason)
    {
        $this->userName = $userName;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Your Account Has Been Deleted - ' . gs('site_name'))
                    ->view('emails.account_deleted');
    }
}
