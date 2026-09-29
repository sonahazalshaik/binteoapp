<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('auth.register', compact('countries'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function checkAvailability(Request $request)
    {
        $field = $request->field;
        $value = $request->value;

        if ($field === 'email') {
            // 1. Check manual typo domains
            $typoDomains = ['gmasil.com', 'gsail.com', 'gmial.com', 'gmai.com', 'yaho.com', 'hotmial.com', 'yail.com'];
            $domain = strtolower(explode('@', $value)[1] ?? '');
            
            if (in_array($domain, $typoDomains)) {
                return response()->json(['error' => 'Invalid email domain format. Please check for spelling mistakes.']);
            }

            // 2. Check dynamic disposable email list
            try {
                $disposableDomains = cache()->remember('disposable_email_domains', 86400, function () {
                    $content = @file_get_contents('https://raw.githubusercontent.com/disposable-email-domains/disposable-email-domains/master/disposable_email_blocklist.conf');
                    return $content ? array_filter(array_map('trim', explode("\n", strtolower($content)))) : [];
                });
                if (in_array($domain, $disposableDomains)) {
                    return response()->json(['error' => 'Disposable or temporary email addresses are not allowed.']);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to fetch disposable domains list: ' . $e->getMessage());
            }

            // Then check DNS & MX Mail Server records
            $validator = \Illuminate\Support\Facades\Validator::make(
                ['email' => $value],
                ['email' => 'email:rfc,dns']
            );

            if ($validator->fails() || ($domain && !checkdnsrr($domain, 'MX'))) {
                return response()->json(['error' => 'Invalid email domain or no active mail server found.']);
            }

            if (User::where('email', $value)->exists()) {
                return response()->json(['error' => 'This email is already registered.']);
            }
        }

        if ($field === 'mobile') {
            if (User::where('mobile', $value)->exists()) {
                return response()->json(['error' => 'This phone number is already registered.']);
            }
        }

        return response()->json(['success' => true]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!gs('registration')) {
            return back()->withNotify([['error', 'Registration is currently disabled']]);
        }

        $request->validate([
            'firstname' => ['required', 'string', 'max:13'],
            'lastname'  => ['required', 'string', 'max:13'],
            'email'     => [
                'required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:'.User::class,
                function ($attribute, $value, $fail) {
                    $typoDomains = ['gmasil.com', 'gsail.com', 'gmial.com', 'gmai.com', 'yaho.com', 'hotmial.com', 'yail.com'];
                    $domain = strtolower(explode('@', $value)[1] ?? '');
                    if (in_array($domain, $typoDomains)) {
                        $fail('The '.$attribute.' domain is invalid or incorrectly spelled.');
                    }

                    if ($domain && !checkdnsrr($domain, 'MX')) {
                        $fail('The '.$attribute.' domain does not have a valid mail server.');
                    }
                    
                    try {
                        $disposableDomains = cache()->remember('disposable_email_domains', 86400, function () {
                            $content = @file_get_contents('https://raw.githubusercontent.com/disposable-email-domains/disposable-email-domains/master/disposable_email_blocklist.conf');
                            return $content ? array_filter(array_map('trim', explode("\n", strtolower($content)))) : [];
                        });
                        if (in_array($domain, $disposableDomains)) {
                            $fail('Disposable or temporary email addresses are not allowed.');
                        }
                    } catch (\Exception $e) {}
                }
            ],
            'mobile'    => ['required', 'string', 'regex:/^(0\d{10}|[6-9]\d{9})$/', 'unique:'.User::class],
            'country_name' => [
                'required', 'string', 'max:100',
                function ($attribute, $value, $fail) {
                    $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')), true);
                    $validCountries = array_column($countries, 'country');
                    if (!in_array($value, $validCountries)) {
                        $fail('Please select a valid country from the list.');
                    }
                }
            ],
            'password'  => ['required', 'confirmed', Rules\Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ], [
            'mobile.regex' => 'Enter a valid Indian mobile number. (10 digits starting with 6-9, or 11 digits starting with 0)',
        ]);


        $baseUsername = Str::slug($request->firstname . $request->lastname);
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        // Phase 1 Schema uses firstname and lastname
        $user = User::create([
            'name'      => trim($request->firstname . ' ' . $request->lastname), // Satisfy legacy DB constraint
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'username'  => $username,
            'email'     => $request->email,
            'mobile'    => $request->mobile,
            'country_name' => $request->country_name,
            'password'  => Hash::make($request->password),
            'password_text' => encrypt($request->password),
            'kv'        => \App\Constants\Status::NO, // Always require KYC validation
            'ev'        => gs('ev') ? \App\Constants\Status::NO : \App\Constants\Status::YES,
            'traffic_source' => session('traffic_source', 'organic'),
            'utm_source'     => session('utm_source'),
            'utm_medium'     => session('utm_medium'),
            'utm_campaign'   => session('utm_campaign'),
            'referral_url'   => session('referral_url'),
        ]);

        $adminNotification            = new \App\Models\AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();

        $ip        = getRealIP();
        $exist     = \App\Models\UserLogin::where('user_ip', $ip)->first();
        $userLogin = new \App\Models\UserLogin();

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

        // Removed automatic channel creation so it can be handled via the header button

        event(new Registered($user));

        Auth::login($user);

        try {
            app(\App\Services\MailService::class)->sendMailable($user->email, new \App\Mail\UserWelcomeMail($user));
        } catch (\Exception $e) {
            Log::error('User Welcome Email Failed for ' . $user->email . ': ' . $e->getMessage());
        }

        return redirect(route('studio.dashboard', absolute: false))->withNotify([['success', 'Registration successful! Welcome to ' . gs('site_name')]]);
    }
}
