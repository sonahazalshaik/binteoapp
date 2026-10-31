<?php

namespace App\Services;

use App\Models\User;
use App\Constants\Status;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function handleGoogleCallback(string $code, string $callbackUrl): array
    {
        $lockKey = 'google_code_' . md5($code);
        if (cache()->has($lockKey)) {
            return ['already_processing' => true];
        }
        cache()->put($lockKey, true, 30);

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl($callbackUrl)
                ->stateless()
                ->user();

            return $this->handleGoogleUser($googleUser);
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());

            if (auth()->check()) {
                return ['already_authenticated' => true];
            }

            return ['error' => 'Something went wrong with Google login.'];
        }
    }

    public function handleGoogleUser($googleUser): array
    {
        try {
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                if (empty($user->google_id)) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }

                if ($this->authService->checkUserBan($user)) {
                    return ['error' => 'Your account has been blocked.'];
                }

                return ['user' => $user, 'is_new' => false];
            }

            if (!gs('registration')) {
                return ['error' => 'Registration is currently disabled'];
            }

            $nameParts = explode(' ', $googleUser->name, 2);
            $username = $this->generateUsername($googleUser->name);

            $user = User::create([
                'name'      => $googleUser->name,
                'firstname' => $nameParts[0] ?? 'Google',
                'lastname'  => $nameParts[1] ?? 'User',
                'email'     => $googleUser->email,
                'google_id' => $googleUser->id,
                'password'  => Hash::make(Str::random(16)),
                'username'  => $username,
                'kv'        => Status::NO,
                'ev'        => gs('ev') ? Status::NO : Status::YES,
                'status'    => Status::USER_ACTIVE,
            ]);

            $this->authService->createAdminNotification($user->id, 'New member registered via Google');
            $this->authService->logUserLogin($user);
            event(new Registered($user));

            return ['user' => $user, 'is_new' => true];
        } catch (\Exception $e) {
            Log::error('Handle Google User Error: ' . $e->getMessage());
            return ['error' => 'Authentication failed. Please try again.'];
        }
    }

    public function generateUsername(string $name): string
    {
        $username = Str::slug($name, '');
        if (User::where('username', $username)->exists()) {
            $username .= rand(100, 999);
        }
        return $username;
    }
}
