<?php

namespace App\Notify;

use App\Notify\NotifyProcess;
use App\Notify\SmsGateway;
use App\Notify\Notifiable;

class Sms extends NotifyProcess implements Notifiable{

	public $mobile;

	public function __construct(){
		$this->statusField = 'sms_status';
		$this->body = 'sms_body';
		$this->globalTemplate = 'sms_template';
		$this->notifyConfig = 'sms_config';
	}

	public function send(){
        if (!gs('sn')) {
			return false;
		}
		$message = $this->getMessage();
        if ($message) {
			try {
				$gateway = gs('sms_config')->name;
                if($this->mobile){
                    $sendSms = new SmsGateway();
                    $sendSms->to = $this->mobile;
                    $sendSms->from = $this->getSmsFrom();
                    $sendSms->message = strip_tags($message);
                    $sendSms->config = gs('sms_config');
                    $sendSms->$gateway();
                    $this->createLog('sms');
                }
			} catch (\Exception $e) {
				$this->createErrorLog('SMS Error: '.$e->getMessage());
				session()->flash('sms_error','API Error: '.$e->getMessage());
			}
		}
	}

	public function prevConfiguration(){
		if ($this->user) {
			$this->mobile = $this->user->mobileNumber;
			$this->receiverName = $this->user->fullname;
		}
		$this->toAddress = $this->mobile;
	}

    private function getSmsFrom(){
        $this->sentFrom = $this->replaceTemplateShortCode($this->template->sms_sent_from ?? gs('sms_from'));
        return $this->sentFrom;
    }
}
