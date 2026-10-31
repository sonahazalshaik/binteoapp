<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChannelPlanPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $channel;
    public $plan;
    public $subscription;
    public $trx;

    public function __construct($user, $channel, $plan, $subscription, $trx)
    {
        $this->user = $user;
        $this->channel = $channel;
        $this->plan = $plan;
        $this->subscription = $subscription;
        $this->trx = $trx;
    }

    public function build()
    {
        $channelName = $this->channel->name ?? 'Creator';
        $planName = $this->plan->name ?: ($this->plan->duration_days . ' Days');

        return $this->subject("Channel Membership Activated - {$channelName} ({$planName})")
                    ->view('emails.channel_plan_purchase');
    }
}
