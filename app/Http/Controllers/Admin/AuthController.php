<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    protected AdminAuthService $adminAuthService;

    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function showLoginForm()
    {
        $pageTitle = 'Login';
        return view('admin.auth.login', compact('pageTitle'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $result = $this->adminAuthService->login($request);

        if ($result['success']) {
            return redirect($result['redirect'])->withNotify([['success', 'Welcome to Admin Dashboard']]);
        }

        return back()->withErrors([
            'email' => $result['error'],
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . \App\Models\User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $this->adminAuthService->register($request->all());

        return redirect()->route('admin.dashboard');
    }

    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $result = $this->adminAuthService->sendResetLink($request->email);

        if ($result['success']) {
            return back()->withNotify([['success', $result['message']]]);
        }

        return back()->withErrors(['email' => $result['error']]);
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        return view('admin.auth.reset-password', ['request' => $request, 'token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $result = $this->adminAuthService->resetPassword($request->all());

        if ($result['success']) {
            return redirect()->route('admin.login')->withNotify([['success', $result['message']]]);
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $result['message']]);
    }

    public function logout(Request $request)
    {
        $this->adminAuthService->logout();

        return redirect()->route('admin.login')->withNotify([['success', 'Logged out successfully']]);
    }
}
