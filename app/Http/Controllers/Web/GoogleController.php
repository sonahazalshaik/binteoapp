<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\AdminNotification;
use App\Models\UserLogin;
use App\Constants\Status;
use App\Services\MailService;
use App\Services\SocialAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;

class GoogleController extends Controller
{
    protected SocialAuthService $socialAuthService;

    public function __construct(SocialAuthService $socialAuthService)
    {
        $this->socialAuthService = $socialAuthService;
    }

    public function googlePage(Request $request)
    {
        if (!gs('google_client_id')) {
            return redirect()->route('login')->withNotify([['error', 'Google login is not configured.']]);
        }

        $callbackUrl = request()->getSchemeAndHttpHost() . '/auth/google/callback';

        $driver = Socialite::driver('google')->redirectUrl($callbackUrl);

        return $driver->with(['prompt' => 'select_account'])->redirect();
    }

    public function googleCallBack(Request $request)
    {
        $code = $request->code;
        if (!$code) {
            return redirect()->route('login')->withNotify([['error', 'Something went wrong with Google login.']]);
        }

        $callbackUrl = request()->getSchemeAndHttpHost() . '/auth/google/callback';

        $result = $this->socialAuthService->handleGoogleCallback($code, $callbackUrl);

        if (isset($result['already_processing'])) {
            return redirect()->route('home');
        }

        if (isset($result['already_authenticated'])) {
            return redirect()->route('home');
        }

        if (isset($result['error'])) {
            return redirect()->route('login')->withNotify([['error', $result['error']]]);
        }

        $user = $result['user'];
        $isNew = $result['is_new'];

        if ($isNew) {
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

            return $this->loginUser($user, 'Account created successfully with Google!');
        }

        return $this->loginUser($user);
    }

    private function loginUser($user, $successMessage = 'Logged in successfully!')
    {
        Auth::login($user);

        if (!session()->has('logged_via_google')) {
            $this->logUserLogin($user);
            session()->put('logged_via_google', true);
        }

        return redirect()->route('studio.dashboard')->withNotify([['success', $successMessage]]);
    }

    private function logUserLogin($user)
    {
        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', (array)@$info['long']);
            $userLogin->latitude     = @implode(',', (array)@$info['lat']);
            $userLogin->city         = @implode(',', (array)@$info['city']);
            $userLogin->country_code = @implode(',', (array)@$info['code']);
            $userLogin->country      = @implode(',', (array)@$info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();
    }
}
