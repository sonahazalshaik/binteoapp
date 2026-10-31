<?php

namespace App\Notify;

use App\Lib\CurlRequest;

class SmsGateway{

    public $to;
    public $from;
    public $message;
    public $config;

	public function clickatell()
	{
		$message = urlencode($this->message);
		$apiKey = $this->config->clickatell->api_key;
		file_get_contents("https://platform.clickatell.com/messages/http/send?apiKey=$apiKey&to=$this->to&content=$message");
	}

	public function infobip(){
		$message = urlencode($this->message);
		file_get_contents("https://api.infobip.com/api/v3/sendsms/plain?user=".$this->config->infobip->username."&password=".$this->config->infobip->password."&sender=$this->from&SMSText=$message&GSM=$this->to&type=longSMS");
	}

	public function messageBird(){
		// Requires messagebird/php-rest-api package
		if (!class_exists(\MessageBird\Client::class)) return;
		$MessageBird = new \MessageBird\Client($this->config->message_bird->api_key);
	  	$Message = new \MessageBird\Objects\Message();
	  	$Message->originator = $this->from;
	  	$Message->recipients = array($this->to);
	  	$Message->body = $this->message;
	  	$MessageBird->messages->create($Message);
	}

	public function nexmo(){
		// Requires vonage/client package
		if (!class_exists(\Vonage\Client::class)) return;
		$basic  = new \Vonage\Client\Credentials\Basic($this->config->nexmo->api_key, $this->config->nexmo->api_secret);
		$client = new \Vonage\Client($basic);
		$response = $client->sms()->send(
		    new \Vonage\SMS\Message\SMS($this->to, $this->from, $this->message)
		);
		$response->current();
	}

	public function smsBroadcast(){
		$message = urlencode($this->message);
		file_get_contents("https://api.smsbroadcast.com.au/api-adv.php?username=".$this->config->sms_broadcast->username."&password=".$this->config->sms_broadcast->password."&to=$this->to&from=$this->from&message=$message&ref=112233&maxsplit=5&delay=15");
	}

	public function twilio(){
		// Requires twilio/sdk package
		if (!class_exists(\Twilio\Rest\Client::class)) return;
		$account_sid = $this->config->twilio->account_sid;
		$auth_token = $this->config->twilio->auth_token;
		$twilio_number = $this->config->twilio->from;
		$client = new \Twilio\Rest\Client($account_sid, $auth_token);
		$client->messages->create(
		    '+'.$this->to,
		    array(
		        'from' => $twilio_number,
		        'body' => $this->message
		    )
		);
	}

	public function textMagic(){
		// TextMagic integration placeholder
	}

	public function custom(){
		$credential = $this->config->custom;
		$method = $credential->method;
		$shortCodes = [
			'{{message}}'=>$this->message,
			'{{number}}'=>$this->to,
		];
		$body = array_combine($credential->body->name,$credential->body->value);
		foreach ($body as $key => $value) {
			$bodyData = str_replace($value,isset($shortCodes[$value]) ? $shortCodes[$value] : $value ,$value);
			$body[$key] = $bodyData;
		}
		$header = array_combine($credential->headers->name,$credential->headers->value);
		if ($method == 'get') {
			$credential->url = $credential->url.'?'.http_build_query($body);
			CurlRequest::curlContent($credential->url,$header);
		}else{
			CurlRequest::curlPostContent($credential->url,$body,$header);
		}
	}
}
