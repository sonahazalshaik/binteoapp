<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;

class AdminAuthService
{
    public function login(Request $request): array
    {
        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }

            $request->session()->regenerate();

            $intended = redirect()->intended(route('admin.dashboard'))->getTargetUrl();
            if (!str_contains($intended, '/admin')) {
                $intended = route('admin.dashboard');
            }

            return ['success' => true, 'redirect' => $intended];
        }

        return ['success' => false, 'error' => trans('auth.failed')];
    }

    public function register(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'admin',
        ]);

        event(new Registered($user));
        Auth::guard('admin')->login($user);

        return $user;
    }

    public function sendResetLink(string $email): array
    {
        $user = User::where('email', $email)->first();
        if (!$user || $user->role !== 'admin') {
            return ['success' => false, 'error' => trans('passwords.user')];
        }

        $status = Password::sendResetLink(['email' => $email]);

        return [
            'success' => $status == Password::RESET_LINK_SENT,
            'message' => __($status),
        ];
    }

    public function resetPassword(array $data): array
    {
        $status = Password::reset(
            $data,
            function (User $user) use ($data) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                    'remember_token' => \Illuminate\Support\Str::random(60),
                ])->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        return [
            'success' => $status == Password::PASSWORD_RESET,
            'message' => __($status),
        ];
    }

    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }
}
