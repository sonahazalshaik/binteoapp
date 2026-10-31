<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ZeptoMailService
{
    protected $client;
    protected $apiKey;
    protected $fromEmail;
    protected $fromName;
    protected $bounceAddress;

    public function __construct()
    {
        $this->client = new Client();
        $config = gs('zeptomail_config');
        $this->apiKey = $config->api_key ?? env('ZEPTOMAIL_API_KEY');
        $this->fromEmail = env('MAIL_FROM_ADDRESS', 'noreply@binteoapp.in');
        $this->fromName = env('MAIL_FROM_NAME', env('APP_NAME'));
        $this->bounceAddress = env('ZEPTOMAIL_BOUNCE_ADDRESS');
    }

    public function sendEmail($to, $subject, $htmlContent)
    {
        return $this->executeSend($to, $subject, $htmlContent);
    }

    public function sendMailable($to, \Illuminate\Mail\Mailable $mailable)
    {
        $htmlContent = $mailable->render();
        $subject = $mailable->subject;
        return $this->executeSend($to, $subject, $htmlContent);
    }

    protected function executeSend($to, $subject, $htmlContent)
    {
        try {
            Log::info('Attempting ZeptoMail with Token Prefix: ' . substr($this->apiKey, 0, 20) . '...');

            $response = $this->client->post('https://api.zeptomail.eu/v1.1/email', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => trim($this->apiKey),
                ],
                'json' => [
                    'from' => [
                        'address' => $this->fromEmail,
                    ],
                    'to' => [
                        [
                            'email_address' => [
                                'address' => $to,
                            ],
                        ],
                    ],
                    'subject' => $subject,
                    'htmlbody' => $htmlContent,
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            Log::info('ZeptoMail API Success for ' . $to . ': ' . json_encode($result));
            return true;
        } catch (\Exception $e) {
            Log::error('ZeptoMail API Error for ' . $to . ': ' . $e->getMessage());
            return false;
        }
    }
}
