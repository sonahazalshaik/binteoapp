@extends('layouts.app')

@section('content')
<div id="auth-login" class="w-full max-w-[440px] px-4 py-8 lg:py-12 animate-in fade-in zoom-in duration-500">
    <!-- Login Card -->
    <div class="bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-[2.5rem] shadow-2xl shadow-black/10 overflow-hidden relative group">
        
        <!-- Premium Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600"></div>

        <div class="p-6 sm:p-8">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter ">Sign In</h1>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.25em] mt-1">Access your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest px-1">Email Address</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-0 h-12 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-gray-400 group-focus-within:text-orange-500 transition-colors text-xl">alternate_email</span>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                               class="w-full h-12 bg-gray-50 dark:bg-white/5 border @error('email') border-rose-500 @else border-transparent @enderror focus:border-orange-500/50 rounded-xl pl-12 pr-6 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-400 transition-all outline-none focus:ring-4 focus:ring-orange-500/10"
                               placeholder="your@email.com">
                        @error('email')
                            <div class="mt-1 flex items-center gap-1 text-rose-500 px-1">
                                <span class="material-symbols-rounded text-xs">error</span>
                                <span class="text-[9px] font-bold uppercase tracking-tight">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between px-1">
                        <label class="text-[10px] font-black text-gray-400 dark:text-white/20 uppercase tracking-widest">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[9px] font-black text-orange-500 hover:text-orange-400 uppercase tracking-widest transition-colors">Forgot Password</a>
                        @endif
                    </div>
                    <div class="relative group" x-data="{ show: false }">
                        <div class="absolute left-4 top-0 h-12 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-gray-400 group-focus-within:text-orange-500 transition-colors text-xl">lock</span>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" required 
                               class="w-full h-12 bg-gray-50 dark:bg-white/5 border @error('password') border-rose-500 @else border-transparent @enderror focus:border-orange-500/50 rounded-xl pl-12 pr-12 text-sm font-bold text-gray-900 dark:text-white placeholder:text-gray-400 transition-all outline-none focus:ring-4 focus:ring-orange-500/10"
                               placeholder="••••••••">
                        @error('password')
                            <div class="mt-1 flex items-center gap-1 text-rose-500 px-1">
                                <span class="material-symbols-rounded text-xs">error</span>
                                <span class="text-[9px] font-bold uppercase tracking-tight">{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="absolute right-4 top-0 h-12 flex items-center">
                            <button type="button" @click="show = !show" class="text-gray-400 hover:text-orange-500 transition-colors">
                                <span class="material-symbols-rounded text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative w-4 h-4 flex items-center justify-center">
                            <input type="checkbox" name="remember" class="peer appearance-none w-4 h-4 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-md checked:bg-orange-500 checked:border-orange-500 transition-all cursor-pointer">
                            <span class="material-symbols-rounded absolute text-white text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none">check</span>
                        </div>
                        <span class="text-[11px] font-bold text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Keep me signed in</span>
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit" :disabled="loading" class="relative w-full h-14 bg-gradient-to-r from-orange-600 to-orange-500 rounded-xl text-white font-black uppercase tracking-[0.2em] shadow-lg shadow-orange-500/20 hover:scale-[1.01] active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed transition-all overflow-hidden group/btn text-sm">
                    <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-1000"></div>
                    
                    <span x-show="!loading" class="relative flex items-center justify-center gap-3">
                        Sign In
                        <span class="material-symbols-rounded text-lg">login</span>
                    </span>

                    <span x-show="loading" class="relative flex items-center justify-center gap-3" x-cloak>
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing In...
                    </span>
                </button>
            </form>

            <!-- Social Logins -->
            <div class="mt-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-px bg-gray-100 dark:bg-white/5 flex-grow"></div>
                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Or</span>
                    <div class="h-px bg-gray-100 dark:bg-white/5 flex-grow"></div>
                </div>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('frontend.googlePage') }}" class="h-12 bg-gray-50 dark:bg-white/5 border border-transparent hover:border-gray-200 dark:hover:border-white/10 rounded-xl flex items-center justify-center gap-2 transition-all active:scale-95 group/social">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" class="w-5 h-5 group-hover/social:scale-110 transition-transform">
                        <span class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest">Sign in with Google</span>
                    </a>
                </div>
            </div>

            <!-- Footer Link -->
            <div class="mt-6 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-orange-500 hover:text-orange-400 transition-colors ml-1 font-black">Signup New</a>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Blocked User Modal --}}
@if(session('user_blocked') || session('ban_modal'))
<div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-[100000] flex items-center lg:items-start justify-center px-4 py-24 lg:p-4 overflow-y-auto">
    <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="show = false"></div>
    <div class="relative w-full max-w-md lg:mt-24 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300 my-auto lg:my-0 flex flex-col">
        <div class="h-1.5 bg-gradient-to-r from-rose-500 to-orange-600 shrink-0"></div>
        <div class="p-6 sm:p-10 text-center overflow-y-auto">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-rose-500/10 rounded-2xl sm:rounded-3xl flex items-center justify-center mx-auto mb-6 sm:mb-8 shadow-inner">
                <span class="material-symbols-rounded text-rose-500 text-3xl sm:text-4xl">block</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-4">Access Denied</h3>
            <p class="text-[9px] sm:text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-relaxed sm:leading-loose mb-6 sm:mb-8">
                Your account has been restricted by system security.
                @if(session('ban_reason'))
                    <br><span class="text-rose-500 mt-2 block">Reason: {{ session('ban_reason') }}</span>
                @endif
            </p>
            
            <div class="p-4 sm:p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 mb-6 sm:mb-8">
                <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3 sm:mb-4 ">Support Channels:</p>
                <div class="flex flex-col gap-2 sm:gap-3">
                    @if(@gs('support_config')->email1)
                        <a href="mailto:{{ gs('support_config')->email1 }}" class="text-[10px] sm:text-xs font-black text-orange-500 hover:text-orange-400 transition-colors break-all">{{ gs('support_config')->email1 }}</a>
                    @endif
                    @if(@gs('support_config')->email2)
                        <a href="mailto:{{ gs('support_config')->email2 }}" class="text-[10px] sm:text-xs font-black text-orange-500 hover:text-orange-400 transition-colors break-all">{{ gs('support_config')->email2 }}</a>
                    @endif
                </div>
            </div>

            <button @click="show = false" class="relative w-full h-12 sm:h-14 bg-gradient-to-r from-orange-600 to-orange-500 rounded-xl text-white font-black uppercase tracking-[0.2em] shadow-lg shadow-orange-500/20 hover:scale-[1.01] active:scale-95 transition-all overflow-hidden group/btn text-[9px] sm:text-[10px] shrink-0">
                <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-1000"></div>
                <span class="relative flex items-center justify-center gap-2">
                    Dismiss Notice
                </span>
            </button>
        </div>
    </div>
</div>
@endif

{{-- Pending Deletion Modal --}}
@if(session('deletion_pending'))
<div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-[100000] flex items-center lg:items-start justify-center px-4 py-24 lg:p-4 overflow-y-auto">
    <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="show = false"></div>
    <div class="relative w-full max-w-md lg:mt-24 bg-white dark:bg-[#1A1A1A] border border-gray-100 dark:border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300 my-auto lg:my-0 flex flex-col">
        <div class="h-1.5 bg-gradient-to-r from-rose-500 to-orange-600 shrink-0"></div>
        <div class="p-6 sm:p-10 text-center overflow-y-auto max-h-[85vh] scrollbar-hide">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-rose-500/10 rounded-2xl sm:rounded-3xl flex items-center justify-center mx-auto mb-6 sm:mb-8 shadow-inner">
                <span class="material-symbols-rounded text-rose-500 text-3xl sm:text-4xl">no_accounts</span>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-4">Deletion Pending</h3>
            <p class="text-[9px] sm:text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest leading-relaxed sm:leading-loose mb-6 sm:mb-8">
                Your request to terminate this account is pending administrator approval. All public channels, videos, comments, and profile details are scheduled for deletion.
                @if(session('deletion_reason'))
                    <br><span class="text-rose-500 mt-2 block">Reason Selected: {{ session('deletion_reason') }}</span>
                @endif
            </p>
            
            <div class="p-4 sm:p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5 mb-6 sm:mb-8 text-left">
                <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3 sm:mb-4 text-center">To cancel this request, please contact support:</p>
                <div class="flex flex-col gap-2.5">
                    @if(@gs('support_config')->email1)
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-orange-500 text-base">mail</span>
                            <a href="mailto:{{ gs('support_config')->email1 }}" class="text-[10px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-orange-500 transition-colors break-all">{{ gs('support_config')->email1 }}</a>
                        </div>
                    @endif
                    @if(@gs('support_config')->email2)
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-orange-500 text-base">mail</span>
                            <a href="mailto:{{ gs('support_config')->email2 }}" class="text-[10px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-orange-500 transition-colors break-all">{{ gs('support_config')->email2 }}</a>
                        </div>
                    @endif
                </div>
            </div>

            <button @click="show = false" class="relative w-full h-12 sm:h-14 bg-gradient-to-r from-orange-600 to-orange-500 rounded-xl text-white font-black uppercase tracking-[0.2em] shadow-lg shadow-orange-500/20 hover:scale-[1.01] active:scale-95 transition-all overflow-hidden group/btn text-[9px] sm:text-[10px] shrink-0">
                <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-1000"></div>
                <span class="relative flex items-center justify-center gap-2">
                    Dismiss Notice
                </span>
            </button>
        </div>
    </div>
</div>
@endif

    {{-- Mobile Bottom Spacer --}}
    <div class="lg:hidden h-24"></div>

<style>
    /* Page-scoped icon visibility fix (this page only).
       The layout globally forces `.material-symbols-rounded` to `color: inherit`,
       which beats Tailwind's text-color utilities and leaves icons dark-on-dark
       in dark mode. Light mode restores the original colors; dark mode renders
       the icons white as designed. */
    html:not(.dark) #auth-login span.material-symbols-rounded.text-gray-400 { color: #9ca3af !important; }
    html:not(.dark) #auth-login .group:focus-within span.material-symbols-rounded.group-focus-within\:text-orange-500 { color: #f97316 !important; }
    html:not(.dark) #auth-login span.material-symbols-rounded.text-white { color: #ffffff !important; }
    html:not(.dark) #auth-login span.material-symbols-rounded.text-rose-500 { color: #f43f5e !important; }
    html:not(.dark) #auth-login span.material-symbols-rounded.text-orange-500 { color: #f97316 !important; }
    .dark #auth-login span.material-symbols-rounded.text-gray-400 { color: rgba(255,255,255,0.7) !important; }
    .dark #auth-login span.material-symbols-rounded.text-white { color: #ffffff !important; }
    .dark #auth-login span.material-symbols-rounded.text-rose-500 { color: #ffffff !important; }
    .dark #auth-login span.material-symbols-rounded.text-orange-500 { color: #ffffff !important; }
</style>
@endsection

