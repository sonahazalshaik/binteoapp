<?php

namespace App\Notify;

use App\Notify\NotifyProcess;
use App\Notify\Notifiable;

class Push extends NotifyProcess implements Notifiable{

	public $deviceId;
    public $redirectUrl;
    public $pushImage;

	public function __construct(){
		$this->statusField = 'push_status';
		$this->body = 'push_body';
		$this->globalTemplate = 'push_template';
		$this->notifyConfig = 'firebase_config';
	}

    public function redirectForApp($getTemplateName){
        $screens = [];
        foreach($screens as $screen => $array){
            if(in_array($getTemplateName ,$array)){
                return $screen;
            }
        }
        return 'HOME';
    }

	public function send(){
        if (!gs('pn')) {
            \Illuminate\Support\Facades\Log::warning('[FCM PUSH SKIPPED] Push notification system is globally disabled in General Settings.');
			return false;
		}
        $message = $this->getMessage();
        if ($message) {
            try {
                $credentialsFilePath = getFilePath('pushConfig').'/push_config.json';
                if (!file_exists($credentialsFilePath)) {
                    \Illuminate\Support\Facades\Log::error('[FCM PUSH FAILED] Credentials file push_config.json missing at: ' . $credentialsFilePath);
                    return false;
                }

                $client = new \Google_Client();
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $client->fetchAccessTokenWithAssertion();
                $token = $client->getAccessToken();
                $access_token = $token['access_token'];
                $headers = [
                    "Authorization: Bearer $access_token",
                    'Content-Type: application/json'
                ];
                $data['notification'] = [
                    'body'=>$message,
                    'title'=>$this->getTitle(),
                    'image' => $this->pushImage ? asset(getFilePath('push')) . '/' . $this->pushImage : null,
                ];
                $data['data'] = [
                    'icon'=>siteFavicon(),
                    'click_action'=>$this->redirectUrl,
                    'app_click_action'=>$this->redirectForApp($this->templateName)
                ];
				foreach ($this->toAddress as $toAddress) {
                    $tokenRecord = \App\Models\FirebaseToken::where('token', $toAddress)->first();
                    $deviceId = $tokenRecord?->id ?? 'Unknown/Legacy';

                    $data['token'] = $toAddress;
                    $payloadData['message'] = $data;
                    $payload = json_encode($payloadData);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/'.gs('firebase_config')->projectId.'/messages:send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    $result = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($httpCode == 200) {
                        \Illuminate\Support\Facades\Log::info('[FCM PUSH SUCCESS] Push Notification Sent Successfully', [
                            'status' => 'SUCCESS',
                            'device_id' => $deviceId,
                            'user_id' => $this->user?->id,
                            'device_token' => $toAddress,
                            'device_type' => $tokenRecord?->device_type ?? 'mobile',
                            'title' => $this->getTitle(),
                            'body' => $message,
                            'fcm_response' => json_decode($result, true)
                        ]);
                    } else {
                        \Illuminate\Support\Facades\Log::error('[FCM PUSH FAILED] Push Notification Delivery Failed', [
                            'status' => 'FAILED',
                            'device_id' => $deviceId,
                            'user_id' => $this->user?->id,
                            'device_token' => $toAddress,
                            'http_code' => $httpCode,
                            'error_response' => json_decode($result, true) ?? $result
                        ]);
                    }
                }
                $this->createLog('push');
            } catch(\Exception $e){
                \Illuminate\Support\Facades\Log::error('[FCM PUSH EXCEPTION] Error executing push dispatch', [
                    'status' => 'EXCEPTION_ERROR',
                    'user_id' => $this->user?->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $this->createErrorLog($e->getMessage());
                session()->flash('firebase_error',$e->getMessage());
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('[FCM PUSH SKIPPED] No message content found for notification');
        }
	}

	public function prevConfiguration(){
		if ($this->user) {
            // Check both deviceTokens and firebaseTokens for device tokens
            $tokensFromDeviceTokens = $this->user->deviceTokens()->pluck('token')->toArray();
            $tokensFromFirebaseTokens = method_exists($this->user, 'firebaseTokens') ? $this->user->firebaseTokens()->pluck('token')->toArray() : [];
            $allTokens = array_unique(array_merge($tokensFromDeviceTokens, $tokensFromFirebaseTokens));

            $this->deviceId = $allTokens;
			$this->receiverName = $this->user->fullname;

            \Illuminate\Support\Facades\Log::info('[FCM PUSH CONFIG] Loaded recipient device tokens for sending', [
                'user_id' => $this->user->id,
                'user_name' => $this->user->fullname,
                'tokens_count' => count($allTokens),
                'device_tokens' => $allTokens
            ]);
		}
		$this->toAddress = $this->deviceId;
	}



    private function getTitle(){
        return $this->replaceTemplateShortCode($this->template->push_title ?? gs('push_title'));
    }
}
