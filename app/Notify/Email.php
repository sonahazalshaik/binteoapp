<?php

namespace App\Notify;
use App\Notify\NotifyProcess;
use App\Notify\Notifiable;

class Email extends NotifyProcess implements Notifiable{

	public $email;

	public function __construct(){
		$this->statusField = 'email_status';
		$this->body = 'email_body';
		$this->globalTemplate = 'email_template';
		$this->notifyConfig = 'mail_config';
	}

	public function send(){
		if (!gs('en')) {
			return false;
		}
		$message = $this->getMessage();
		if ($message) {
			try{
				$provider = gs('mail_provider');
				if ($provider === 'brevo') {
					app(\App\Services\BrevoMailService::class)->sendHtml($this->email, $this->subject, $this->finalMessage);
				} elseif ($provider === 'zepto') {
					app(\App\Services\ZeptoMailService::class)->sendEmail($this->email, $this->subject, $this->finalMessage);
				} else {
					$methodName = gs('mail_config')->name ?? 'php';
					$method = $this->mailMethods($methodName);
					$this->$method();
				}
				$this->createLog('email');
			}catch(\Exception $e){
				$this->createErrorLog($e->getMessage());
				session()->flash('mail_error',$e->getMessage());
			}
		}
	}

	protected function mailMethods($name){
		$methods = [
			'php'=>'sendPhpMail',
			'smtp'=>'sendSmtpMail',
			'sendgrid'=>'sendSendGridMail',
			'mailjet'=>'sendMailjetMail',
		];
		return $methods[$name] ?? 'sendPhpMail';
	}

	protected function sendPhpMail(){
        $sentFromName = $this->getEmailFrom()['name'];
        $sentFromEmail = $this->getEmailFrom()['email'];
		$headers = "From: $sentFromName <$sentFromEmail> \r\n";
		$headers .= "Reply-To: $sentFromName <$sentFromEmail> \r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
	    @mail($this->email, $this->subject, $this->finalMessage, $headers);
	}

	protected function sendSmtpMail(){
		// SMTP mail requires PHPMailer — will be functional once phpmailer/phpmailer is installed
		if (!class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
			$this->sendPhpMail();
			return;
		}
		$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
		$config = gs('mail_config');
        $mail->isSMTP();
        $mail->Host       = $config->host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $config->username;
        $mail->Password   = $config->password;
        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        }else{
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port       = $config->port;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($this->getEmailFrom()['email'], $this->getEmailFrom()['name']);
        $mail->addAddress($this->email, $this->receiverName);
        $mail->addReplyTo($this->getEmailFrom()['email'], $this->getEmailFrom()['name']);
        $mail->isHTML(true);
        $mail->Subject = $this->subject;
        $mail->Body    = $this->finalMessage;
        $mail->send();
	}

	protected function sendSendGridMail(){
		// SendGrid mail — requires sendgrid/sendgrid package
		$this->sendPhpMail();
	}

	protected function sendMailjetMail()
	{
		// Mailjet mail — requires mailjet/mailjet-apiv3-php package
		$this->sendPhpMail();
	}

	public function prevConfiguration(){
		if ($this->user) {
			$this->email = $this->user->email;
			$this->receiverName = $this->user->fullname;
		}
		$this->toAddress = $this->email;
	}

    private function getEmailFrom(){
        $this->sentFrom = $this->template->email_sent_from_address ?? gs('email_from');
        return [
            'email'=>$this->sentFrom,
            'name'=>$this->replaceTemplateShortCode($this->template->email_sent_from_name ?? gs('site_name')),
        ];
    }
}
