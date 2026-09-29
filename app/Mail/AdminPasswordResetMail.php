<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $token;
    public $resetUrl;

    public function __construct($admin, $token)
    {
        $this->admin = $admin;
        $this->token = $token;
        $this->resetUrl = route('admin.password.reset', ['token' => $token, 'email' => $admin->email]);
        $this->subject = 'Admin Password Reset Request - ' . gs('site_name');
    }

    public function build()
    {
        return $this->view('emails.admin_password_reset');
    }
}
