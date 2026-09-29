<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        $token = $request->route('token');
        $email = $request->email;
        Log::info('Password reset page accessed', ['email' => $email, 'token_prefix' => substr($token, 0, 8) . '...']);

        $existing = \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $email)->first();
        if ($existing) {
            $hashValid = \Illuminate\Support\Facades\Hash::check($token, $existing->token);
            Log::info('Password reset token verification on page load', [
                'email' => $email,
                'hash_valid' => $hashValid,
                'created_at' => $existing->created_at,
                'expired' => now()->diffInMinutes($existing->created_at) > 60,
            ]);
        } else {
            Log::warning('No password reset token found in DB for email', ['email' => $email]);
        }

        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Log::info('Password reset form submitted', [
            'email' => $request->email,
            'token_prefix' => substr($request->token, 0, 8) . '...',
        ]);

        $existing = \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if ($existing) {
            $hashValid = \Illuminate\Support\Facades\Hash::check($request->token, $existing->token);
            Log::info('Password reset token validation', [
                'email' => $request->email,
                'hash_valid' => $hashValid,
                'created_at' => $existing->created_at,
                'expired' => now()->diffInMinutes($existing->created_at) > 60,
            ]);
        } else {
            Log::warning('No password reset token in DB for submitted email', ['email' => $request->email]);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        Log::info('Password reset result', ['email' => $request->email, 'status' => $status]);

        $notify[] = ['success', __($status)];
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->withNotify($notify)
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
