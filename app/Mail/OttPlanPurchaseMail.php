<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OttPlanPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $plan;
    public $trx;

    public function __construct($user, $plan, $trx)
    {
        $this->user = $user;
        $this->plan = $plan;
        $this->trx = $trx;
    }

    public function build()
    {
        return $this->subject('OTT Membership Activated - ' . $this->plan->name)
                    ->view('emails.ott_plan_purchase');
    }
}
