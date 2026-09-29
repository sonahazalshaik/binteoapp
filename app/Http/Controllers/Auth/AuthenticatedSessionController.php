<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        \App\Lib\Intended::identifyRoute();
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            \App\Lib\Intended::reAssignSession();
            throw $e;
        }

        $request->session()->regenerate();

        // Force logout of any marketplace user session to enforce strict separation
        if ($request->session()->has('marketplace_user_id')) {
            $request->session()->forget('marketplace_user_id');
        }

        $user = Auth::user();
        if ($user->status == \App\Constants\Status::USER_BAN) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with(['user_blocked' => true, 'ban_reason' => $user->ban_reason]);
        }

        $user->tv = $user->ts == \App\Constants\Status::VERIFIED ? \App\Constants\Status::UNVERIFIED : \App\Constants\Status::VERIFIED;
        $user->save();

        $ip = getRealIP();
        $exist = \App\Models\UserLogin::where('user_ip',$ip)->first();
        $userLogin = new \App\Models\UserLogin();
        
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',', (array)@$info['long']);
            $userLogin->latitude =  @implode(',', (array)@$info['lat']);
            $userLogin->city =  @implode(',', (array)@$info['city']);
            $userLogin->country_code = @implode(',', (array)@$info['code']);
            $userLogin->country =  @implode(',', (array)@$info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;
        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();

        $redirection = \App\Lib\Intended::getRedirection();

        if ($user->role === 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->withNotify([['error', 'Admins are not allowed to log in through the frontend. Please use the administrative portal.']]);
        }

        // Prevent unwanted redirects to /notifications or /admin after login
        $intendedUrl = session('url.intended');
        if ($intendedUrl && (str_contains($intendedUrl, '/admin') || str_contains($intendedUrl, '/notifications'))) {
            session()->forget('url.intended');
        }

        return redirect()->route('studio.dashboard')->withNotify([['success', 'Welcome back, ' . $user->name]]);
    }

    /**
     * Authenticate session via bridge token from the mobile app.
     */
    public function bridgeLogin(Request $request): RedirectResponse
    {
        $tokenStr = $request->query('token');
        
        if (empty($tokenStr)) {
            \Illuminate\Support\Facades\Log::warning("WebView bridge login: Request received but no token was provided in the query string.");
            return redirect()->route('login')->withNotify([['error', 'Authentication token missing.']]);
        }

        \Illuminate\Support\Facades\Log::info("WebView bridge login: Request received. Token string: " . substr($tokenStr, 0, 15) . "...");

        $tokenParts = explode('|', $tokenStr);
        $tokenModel = null;

        if (count($tokenParts) === 2) {
            $tokenId = $tokenParts[0];
            $plainTextToken = $tokenParts[1];
            
            \Illuminate\Support\Facades\Log::info("WebView bridge login: Parsed 2-part Sanctum token. Token ID: {$tokenId}. Fetching token record...");
            $tokenModel = \Laravel\Sanctum\PersonalAccessToken::find($tokenId);

            if ($tokenModel) {
                \Illuminate\Support\Facades\Log::info("WebView bridge login: Token record ID {$tokenId} found. Verifying token signature...");
                if (hash_equals($tokenModel->token, hash('sha256', $plainTextToken))) {
                    \Illuminate\Support\Facades\Log::info("WebView bridge login: Signature verification SUCCESS for Token ID: {$tokenId}.");
                } else {
                    \Illuminate\Support\Facades\Log::warning("WebView bridge login: Signature verification FAILED for Token ID: {$tokenId}. Hash mismatch.");
                    $tokenModel = null;
                }
            } else {
                \Illuminate\Support\Facades\Log::warning("WebView bridge login: Token record ID {$tokenId} not found in the database.");
            }
        } else {
            \Illuminate\Support\Facades\Log::info("WebView bridge login: Non-standard token structure (no pipe symbol). Trying to resolve as raw hash...");
            $hashedToken = hash('sha256', $tokenStr);
            $tokenModel = \Laravel\Sanctum\PersonalAccessToken::where('token', $hashedToken)->first();
            
            if ($tokenModel) {
                \Illuminate\Support\Facades\Log::info("WebView bridge login: Successfully located token record by raw hash.");
            } else {
                \Illuminate\Support\Facades\Log::warning("WebView bridge login: Token record not found by raw hash lookup.");
            }
        }

        if ($tokenModel) {
            \Illuminate\Support\Facades\Log::info("WebView bridge login: Resolving associated tokenable entity (User)...");
            $user = $tokenModel->tokenable;

            if ($user) {
                \Illuminate\Support\Facades\Log::info("WebView bridge login: User found. ID: {$user->id}, Email: {$user->email}, Name: {$user->name}. Logging user in...");
                
                Auth::login($user);
                $request->session()->regenerate();
                
                \Illuminate\Support\Facades\Log::info("WebView bridge login: Login successful. Session regenerated. Redirecting Webview to studio.dashboard.");
                return redirect()->route('studio.dashboard')->withNotify([['success', 'Logged in successfully via App!']]);
            } else {
                \Illuminate\Support\Facades\Log::error("WebView bridge login: Token record exists, but the associated User record was not found or is null.");
            }
        }

        \Illuminate\Support\Facades\Log::warning("WebView bridge login: Bridge login authentication FAILED. Redirecting Webview back to login screen.");
        return redirect()->route('login')->withNotify([['error', 'Single sign-on session synchronization failed. Please log in manually.']]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        return redirect('/')->withNotify([['success', 'You have been logged out.']]);
    }
}