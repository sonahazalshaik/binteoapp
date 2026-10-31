<?php

namespace App\Services\Api;

use App\Models\User;
use App\Models\AdminNotification;
use App\Models\UserLogin;
use App\Constants\Status;
use App\Services\BrevoMailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;

class ApiAuthService
{
    public function register(array $data): array
    {
        if (!gs('registration')) {
            return ['status' => 'error', 'message' => 'Registration is currently disabled'];
        }

        $baseUsername = Str::slug($data['firstname'] . $data['lastname']);
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
        $countryCode = null;
        $dialCode = null;
        if (isset($data['country_name'])) {
            foreach ($countries as $key => $country) {
                if ($country['country'] === $data['country_name']) {
                    $countryCode = $key;
                    $dialCode = $country['dial_code'];
                    break;
                }
            }
        }

        $user = User::create([
            'name' => trim($data['firstname'] . ' ' . $data['lastname']),
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $username,
            'email' => $data['email'],
            'mobile' => $data['mobile'] ?? null,
            'country_name' => $data['country_name'] ?? null,
            'country_code' => $countryCode,
            'dial_code' => $dialCode,
            'password' => Hash::make($data['password']),
            'password_text' => encrypt($data['password']),
            'kv' => Status::NO,
            'ev' => gs('ev') ? Status::NO : Status::YES,
            'status' => Status::USER_ACTIVE,
        ]);

        $this->createAdminNotification($user->id, 'New member registered');
        $this->logUserLogin($user);
        event(new Registered($user));

        $token = $user->createToken('API Token')->plainTextToken;

        if (request()->has('fcm_token') && request()->fcm_token) {
            \App\Models\FirebaseToken::updateOrCreate(
                ['token' => request()->fcm_token],
                [
                    'user_id' => $user->id,
                    'device_type' => request()->device_type ?? 'mobile',
                ]
            );
            Log::info("[FCM TOKEN SAVED] Token saved during API registration for User ID: {$user->id}");
        }

        Log::info("API Registration successful for User ID: {$user->id} ({$user->email}). Bridge token generated. Redirecting APK WebView.");


        return [
            'status' => 'success',
            'message' => 'Registration successful',
            'bridge_token' => $token,
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ];
    }

    public function login(array $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status == Status::USER_BAN) {
                return ['status' => 'error', 'message' => 'Your account has been blocked.'];
            }

            $this->logUserLogin($user);

            $token = $user->createToken('API Token')->plainTextToken;

            if (request()->has('fcm_token') && request()->fcm_token) {
                \App\Models\FirebaseToken::updateOrCreate(
                    ['token' => request()->fcm_token],
                    [
                        'user_id' => $user->id,
                        'device_type' => request()->device_type ?? 'mobile',
                    ]
                );
                Log::info("[FCM TOKEN SAVED] Token saved during API login for User ID: {$user->id}");
            }

            Log::info("API Login successful for User ID: {$user->id} ({$user->email}). Bridge token generated. Redirecting APK WebView.");


            return [
                'status' => 'success',
                'message' => 'Login successful',
                'bridge_token' => $token,
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ]
            ];
        }

        return ['status' => 'error', 'message' => 'Invalid credentials'];
    }

    public function googleLogin(string $accessToken): array
    {
        try {
            $googleUser = Socialite::driver('google')->userFromToken($accessToken);
        } catch (\Exception $e) {
            Log::error('Google API login error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Google authentication failed'];
        }

        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if ($user) {
            if (empty($user->google_id)) {
                $user->google_id = $googleUser->id;
                $user->save();
            }

            if ($user->status == Status::USER_BAN) {
                return ['status' => 'error', 'message' => 'Your account has been blocked.'];
            }
        } else {
            if (!gs('registration')) {
                return ['status' => 'error', 'message' => 'Registration is currently disabled'];
            }

            $nameParts = explode(' ', $googleUser->name, 2);
            $username = Str::slug($googleUser->name, '');
            if (User::where('username', $username)->exists()) {
                $username .= rand(100, 999);
            }

            $user = User::create([
                'name' => $googleUser->name,
                'firstname' => $nameParts[0] ?? 'Google',
                'lastname' => $nameParts[1] ?? 'User',
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(Str::random(16)),
                'username' => $username,
                'kv' => Status::NO,
                'ev' => gs('ev') ? Status::NO : Status::YES,
                'status' => Status::USER_ACTIVE,
            ]);

            $this->createAdminNotification($user->id, 'New member registered via Google');
            $this->logUserLogin($user);
            event(new Registered($user));
        }

        $token = $user->createToken('API Token')->plainTextToken;

        if (request()->has('fcm_token') && request()->fcm_token) {
            \App\Models\FirebaseToken::updateOrCreate(
                ['token' => request()->fcm_token],
                [
                    'user_id' => $user->id,
                    'device_type' => request()->device_type ?? 'mobile',
                ]
            );
            Log::info("[FCM TOKEN SAVED] Token saved during API Google login for User ID: {$user->id}");
        }

        Log::info("API Google Login successful for User ID: {$user->id} ({$user->email}). Bridge token generated. Redirecting APK WebView.");


        return [
            'status' => 'success',
            'message' => 'Google login successful',
            'bridge_token' => $token,
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ];
    }

    public function sendResetLink(string $email): array
    {
        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            Log::info("Password reset link sent to: {$email}");
            return ['status' => 'success', 'message' => __($status)];
        }

        return ['status' => 'error', 'message' => __($status)];
    }

    public function resetPassword(array $data): array
    {
        $status = Password::reset(
            $data,
            function (User $user) use ($data) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            Log::info("Password reset successful for email: {$data['email']}");
            return ['status' => 'success', 'message' => __($status)];
        }

        return ['status' => 'error', 'message' => __($status)];
    }

    public function logout(): array
    {
        request()->user()->currentAccessToken()->delete();

        return ['status' => 'success', 'message' => 'Logged out successfully'];
    }

    private function createAdminNotification(int $userId, string $title): void
    {
        $notification = new AdminNotification();
        $notification->user_id = $userId;
        $notification->title = $title;
        $notification->click_url = urlPath('admin.users.detail', $userId);
        $notification->save();
    }

    private function logUserLogin($user): void
    {
        $ip = getRealIP();
        $exist = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude = $exist->longitude;
            $userLogin->latitude = $exist->latitude;
            $userLogin->city = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country = $exist->country;
        } else {
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude = @implode(',', (array) @$info['long']);
            $userLogin->latitude = @implode(',', (array) @$info['lat']);
            $userLogin->city = @implode(',', (array) @$info['city']);
            $userLogin->country_code = @implode(',', (array) @$info['code']);
            $userLogin->country = @implode(',', (array) @$info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();
    }
}