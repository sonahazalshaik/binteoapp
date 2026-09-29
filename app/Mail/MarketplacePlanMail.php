<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketplacePlanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;

    public function __construct($user, $plan)
    {
        $this->user = $user;
        $this->plan = $plan;
    }

    public function build()
    {
        return $this->subject('Marketplace Plan Activated - ' . $this->plan->plan_name)
                    ->view('emails.marketplace_plan');
    }
}
