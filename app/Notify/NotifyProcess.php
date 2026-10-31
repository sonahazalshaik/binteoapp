<?php

namespace App\Notify;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;

class NotifyProcess{

	public $templateName;
    public $shortCodes;
    public $user;
	protected $statusField;
	protected $globalTemplate;
	protected $body;
	public $template;
	public $message;
	public $createLog;
	public $notifyConfig;
    public $subject;
	public $receiverName;
	public $userColumn;
    protected $toAddress;
    protected $finalMessage;
    protected $sentFrom = null;

	protected function getMessage(){
        $this->prevConfiguration();
		$body = $this->body;
		$user = $this->user;
		$globalTemplate = $this->globalTemplate;
		$template = null;
		try {
			if (\Illuminate\Support\Facades\Schema::hasTable('notification_templates')) {
				$template = NotificationTemplate::where('act', $this->templateName)->where($this->statusField, Status::ENABLE)->first();
			}
		} catch (\Throwable $e) {
			$template = null;
		}
		$this->template = $template;

		if ($user && $template) {
		    $message = $this->replaceShortCode($user->fullname ?? $user->name ?? $user->username ?? '', $user->username ?? '', gs($globalTemplate), $template->$body);
		    if (empty($message)) {
		        $message = $template->$body;
		    }
	    	if ($this->shortCodes) {
	            $message = $this->replaceTemplateShortCode($message);
		    }
	        $this->getSubject();
		} else {
			$this->subject = $this->shortCodes['subject'] ?? keyToTitle($this->templateName ?? 'Notification');
			$bodyContent = $this->shortCodes['message'] ?? '';
			if (!$bodyContent && !empty($this->shortCodes)) {
				$lines = [];
				foreach ($this->shortCodes as $k => $v) {
					if (!in_array($k, ['site_name', 'site_currency', 'currency_symbol', 'subject'])) {
						$lines[] = '<strong>' . keyToTitle($k) . ':</strong> ' . (is_array($v) ? json_encode($v) : $v);
					}
				}
				$bodyContent = implode('<br>', $lines);
			}
			$name = $user->fullname ?? $user->name ?? $user->username ?? $this->receiverName ?? 'User';
			$siteName = gs('site_name') ?? 'Binteo App';
			$message = "<div style=\"font-family:Arial,sans-serif;line-height:1.6;\"><h2>" . e($this->subject) . "</h2><p>Hello " . e($name) . ",</p><p>" . $bodyContent . "</p><br><p>Best regards,<br>" . e($siteName) . "</p></div>";
		}

        $this->finalMessage = $message;
	    return $message;
	}

	protected function replaceShortCode($name,$username,$template,$body){
	    if(is_array($username)){
	        $username = implode(',',$username);
	    }
		$message = str_replace("{{fullname}}", $name, $template);
	    $message = str_replace("{{username}}", $username, $message);
	    $message = str_replace("{{message}}", $body, $message);
	    return $message;
	}

    protected function replaceTemplateShortCode($content){
        foreach ($this->shortCodes ?? [] as $code => $value) {
            $content = str_replace('{{' . $code . '}}', $value, $content);
        }
        return $content;
    }

	protected function getSubject(){
		if ($this->template) {
			$subject = $this->template->subject;
			if ($this->shortCodes) {
			    foreach ($this->shortCodes as $code => $value) {
			        $subject = str_replace('{{' . $code . '}}', $value, $subject);
			    }
		    }
			$this->subject = $subject;
		}
	}

	public function createErrorLog($message){
		$adminNotification = new AdminNotification();
        $adminNotification->user_id = 0;
        $adminNotification->title = $message;
        $adminNotification->click_url = '#';
        $adminNotification->save();
	}

	public function createLog($type){
        $userColumn = $this->userColumn;
		if ($this->user && $this->createLog) {
			$notifyConfig = $this->notifyConfig;
			$config = gs($notifyConfig);
			$notificationLog = new NotificationLog();
            if (@$this->user->id) {
                $notificationLog->$userColumn = $this->user->id;
            }
		    $notificationLog->notification_type = $type;
		    $notificationLog->sender = @$config->name ?? 'firebase';
		    $notificationLog->sent_from = $this->sentFrom;
		    $notificationLog->sent_to = $type == 'push' ? 'Firebase Token' : $this->toAddress;
		    $notificationLog->subject = $this->subject;
		    $notificationLog->image = @$this->pushImage ?? null;
		    $notificationLog->message = $type == 'email' ? $this->finalMessage : strip_tags($this->finalMessage);
		    $notificationLog->save();
		}
	}

}
