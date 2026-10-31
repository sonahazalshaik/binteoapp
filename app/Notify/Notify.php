<?php

namespace App\Notify;

class Notify
{
	public $templateName;
	public $shortCodes;
	public $sendVia;
	public $user;
	public $createLog;
	public $setting;
	public $userColumn;
	public $pushImage;

	public function __construct($sendVia = null)
	{
		$this->sendVia = $sendVia;
	}

	public function send(){
		$methods = [];
		if($this->sendVia){
			foreach ($this->sendVia as $sendVia) {
				$methods[$sendVia] = $this->notifyMethods($sendVia);
			}
		}else{
			$methods = $this->notifyMethods();
		}
		foreach($methods as $method){
			$notify = new $method;
			$notify->templateName = $this->templateName;
			$notify->shortCodes = $this->shortCodes;
			$notify->user = $this->user;
			$notify->createLog = $this->createLog;
			$notify->userColumn = $this->userColumn;
			$notify->pushImage = $this->pushImage;
			$notify->send();
		}
	}

	protected function notifyMethods($sendVia = null){
		$methods = [
			'email'=>Email::class,
			'sms'=>Sms::class,
            'push'=>Push::class,
		];
		if ($sendVia) {
			return $methods[$sendVia];
		}
		return $methods;
	}
}
