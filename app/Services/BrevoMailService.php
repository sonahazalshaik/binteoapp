<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoMailService
{
    private function getApiKey()
    {
        $config = gs('brevo_config');
        return $config->api_key ?? config('services.brevo.key');
    }

    private function getSenderEmail()
    {
        $config = gs('brevo_config');
        return $config->sender_email ?? config('services.brevo.sender_email');
    }

    private function getSenderName()
    {
        $config = gs('brevo_config');
        return $config->sender_name ?? config('services.brevo.sender_name');
    }

    public function send(string $to, string $subject, array $data, string $view = 'emails.test', array $cc = [], array $bcc = [], array $attachments = [], ?int $templateId = null)
    {
        return $this->sendPayload($to, $subject, $data, $cc, $bcc, $attachments, $templateId, $view);
    }

    public function sendHtml(string $to, string $subject, string $htmlContent, array $cc = [], array $bcc = [], array $attachments = [], ?int $templateId = null)
    {
        return $this->sendPayload($to, $subject, [], $cc, $bcc, $attachments, $templateId, null, $htmlContent);
    }

    protected function sendPayload(string $to, string $subject, array $data, array $cc = [], array $bcc = [], array $attachments = [], ?int $templateId = null, ?string $view = null, ?string $htmlContent = null)
    {
        $payload = [
            'to' => [
                ['email' => $to],
            ],
        ];

        if ($templateId) {
            $payload['templateId'] = $templateId;
            $payload['params'] = $data;
            $payload['params']['subject'] = $subject;
        } else {
            $payload['sender'] = [
                'email' => $this->getSenderEmail(),
                'name' => $this->getSenderName(),
            ];
            $payload['subject'] = $subject;
            $payload['htmlContent'] = $htmlContent ?? view($view, $data)->render();
        }

        if (!empty($cc)) {
            $payload['cc'] = array_map(fn($email) => ['email' => $email], $cc);
        }

        if (!empty($bcc)) {
            $payload['bcc'] = array_map(fn($email) => ['email' => $email], $bcc);
        }

        if (!empty($attachments)) {
            $payload['attachment'] = $attachments;
        }

        if (!empty($data)) {
            $payload['params'] = $data;
        }

        Log::info('Attempting Brevo send to ' . $to . ' subject: ' . $subject);

        $response = Http::withHeaders([
            'api-key' => $this->getApiKey(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if ($response->failed()) {
            Log::error('Brevo API Error for ' . $to . ': ' . $response->body());
            throw new \Exception($response->body());
        }

        Log::info('Brevo API Success for ' . $to . ': ' . json_encode($response->json()));
        return $response->json();
    }

    public function sendTemplate(string $to, int $templateId, array $params)
    {
        $payload = [
            'to' => [
                ['email' => $to],
            ],
            'templateId' => $templateId,
            'params' => $params,
        ];

        Log::info('Attempting Brevo template send to ' . $to . ' templateId: ' . $templateId);

        $response = Http::withHeaders([
            'api-key' => $this->getApiKey(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if ($response->failed()) {
            Log::error('Brevo Template API Error for ' . $to . ': ' . $response->body());
            throw new \Exception($response->body());
        }

        Log::info('Brevo Template API Success for ' . $to . ': ' . json_encode($response->json()));
        return $response->json();
    }
}
