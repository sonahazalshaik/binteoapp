<?php

namespace App\Services;

use App\Models\User;
use App\Models\AdminNotification;
use App\Models\UserLogin;
use App\Constants\Status;
use App\Services\MailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class AuthService
{
    public function registerUser(array $data, ?string $trafficSource = null): User
    {
        $baseUsername = Str::slug($data['firstname'] . $data['lastname']);
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'name'          => trim($data['firstname'] . ' ' . $data['lastname']),
            'firstname'     => $data['firstname'],
            'lastname'      => $data['lastname'],
            'username'      => $username,
            'email'         => $data['email'],
            'mobile'        => $data['mobile'] ?? null,
            'country_name'  => $data['country_name'] ?? null,
            'password'      => Hash::make($data['password']),
            'password_text' => encrypt($data['password']),
            'kv'            => Status::NO,
            'ev'            => gs('ev') ? Status::NO : Status::YES,
            'traffic_source' => $trafficSource ?? session('traffic_source', 'organic'),
            'utm_source'     => session('utm_source'),
            'utm_medium'     => session('utm_medium'),
            'utm_campaign'   => session('utm_campaign'),
            'referral_url'   => session('referral_url'),
        ]);

        $this->createAdminNotification($user->id, 'New member registered');
        $this->logUserLogin($user);
        event(new Registered($user));

        return $user;
    }

    public function logUserLogin($user): void
    {
        $ip = getRealIP();
        $exist = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', (array)@$info['long']);
            $userLogin->latitude     = @implode(',', (array)@$info['lat']);
            $userLogin->city         = @implode(',', (array)@$info['city']);
            $userLogin->country_code = @implode(',', (array)@$info['code']);
            $userLogin->country      = @implode(',', (array)@$info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();
    }

    public function createAdminNotification(int $userId, string $title, ?string $clickUrl = null): void
    {
        $notification = new AdminNotification();
        $notification->user_id = $userId;
        $notification->title = $title;
        $notification->click_url = $clickUrl ?? urlPath('admin.users.detail', $userId);
        $notification->save();
    }

    public function sendWelcomeEmail(User $user): void
    {
        try {
            app(MailService::class)->send(
                $user->email,
                'Welcome to ' . gs('site_name'),
                ['user' => $user],
                'emails.user_welcome'
            );
        } catch (\Exception $e) {
            Log::error('User Welcome Email Failed for ' . $user->email . ': ' . $e->getMessage());
        }
    }

    public function checkUserBan(User $user): bool
    {
        return $user->status == Status::USER_BAN;
    }
}
