<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class MailService
{
    public function send(string $to, string $subject, array $data, string $view, array $cc = [], array $bcc = [], array $attachments = []): mixed
    {
        $provider = gs('mail_provider');
        Log::info('MailService: sending to ' . $to . ' via provider: ' . $provider);

        if ($provider === 'zepto') {
            $htmlContent = view($view, $data)->render();
            return app(ZeptoMailService::class)->sendEmail($to, $subject, $htmlContent);
        }

        return app(BrevoMailService::class)->send($to, $subject, $data, $view, $cc, $bcc, $attachments);
    }

    public function sendMailable(string $to, Mailable $mailable): mixed
    {
        $provider = gs('mail_provider');
        Log::info('MailService: sending mailable to ' . $to . ' via provider: ' . $provider);

        if ($provider === 'zepto') {
            return app(ZeptoMailService::class)->sendMailable($to, $mailable);
        }

        $htmlContent = $mailable->render();
        return app(BrevoMailService::class)->sendHtml($to, $mailable->subject, $htmlContent);
    }
}
