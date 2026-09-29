<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Admin - System Control</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/validation.js') }}"></script>
    <style>body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-black text-white antialiased h-screen overflow-hidden selection:bg-orange-500/30 selection:text-orange-200">
    @include('partials.notify')

    <!-- Ambient Dynamic Background -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[70vw] h-[70vw] bg-orange-700/40 rounded-full blur-[120px] mix-blend-screen opacity-50 animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute top-[40%] -right-[20%] w-[80vw] h-[80vw] bg-orange-600/30 rounded-full blur-[140px] mix-blend-screen opacity-50 animate-pulse" style="animation-duration: 12s; animation-delay: 2s;"></div>
        <div class="absolute -bottom-[30%] left-[20%] w-[60vw] h-[60vw] bg-orange-900/40 rounded-full blur-[100px] mix-blend-screen opacity-50 animate-pulse" style="animation-duration: 10s; animation-delay: 1s;"></div>
        
        <!-- Texture overlay -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/noise-pattern-with-subtle-cross-lines.png')] opacity-[0.03]"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full h-full flex flex-col justify-end sm:justify-center items-center p-0 sm:p-6">
        
        <!-- Elevated Floating Auth Sheet (M3 Bottom Sheet Style) -->
        <div class="w-full sm:max-w-[420px] bg-white/[0.03] backdrop-blur-3xl border-t border-white/10 sm:border sm:rounded-[2.5rem] rounded-t-[2.5rem] p-6 sm:p-8 shadow-[0_-20px_60px_-15px_rgba(0,0,0,0.5)] sm:shadow-2xl relative transition-all flex flex-col max-h-[100dvh] sm:h-auto">
            
            <!-- Orange Top Edge Accent -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600"></div>

            <div class="mx-auto w-12 h-1.5 bg-white/20 rounded-full mb-6 sm:hidden flex-shrink-0"></div> <!-- Handle bar -->

            <!-- Header -->
            <div class="text-center mb-6 flex-shrink-0">
                <div class="relative inline-flex items-center justify-center w-14 h-14 rounded-[1.25rem] bg-gradient-to-tr from-orange-900/50 to-orange-600/20 text-orange-500 mb-4 border border-orange-500/20 shadow-[0_0_30px_rgba(249,115,22,0.2)]">
                    <span class="material-symbols-rounded text-[28px] drop-shadow-lg">admin_panel_settings</span>
                </div>
                <h2 class="text-2xl font-black text-white tracking-tight leading-none mb-2">Admin Login</h2>
                <p class="text-[14px] font-medium text-white/50">Sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-emerald-400 bg-emerald-400/10 p-3 rounded-xl border border-emerald-400/20 text-sm font-medium" :status="session('status')" />

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4 flex-grow overflow-y-auto mb-2 scrollbar-hide px-1" x-data="{ loading: false }" @submit.prevent="if (!$el.dataset.busy) { $el.dataset.busy = '1'; loading = true; setTimeout(() => $el.submit(), 150) }">
                @csrf

                <!-- Admin Email -->
                <x-input 
                    name="email" 
                    type="email" 
                    label="Email Address" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="admin@example.com"
                    hint="Enter your authorized admin email address."
                />

                <!-- Admin Password -->
                <div class="mb-10" x-data="{ show: false }">
                    <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30 px-1 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            :type="show ? 'text' : 'password'" 
                            name="password" 
                            id="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            class="w-full h-16 px-6 pr-14 rounded-2xl border transition-all duration-300 outline-none text-[13px] font-black text-slate-900 dark:text-white border-slate-200/60 dark:border-white/5 bg-white dark:bg-black/20 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10"
                        >
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors p-1">
                            <span class="material-symbols-rounded text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between px-1 pt-1">
                    <label for="remember_me" class="inline-flex flex-row-reverse items-center justify-end gap-2 group cursor-pointer w-fit">
                        <span class="text-[13px] font-medium text-white/50 group-hover:text-white transition-colors">Remember me</span>
                        <div class="relative flex items-center justify-center w-4 h-4">
                            <input id="remember_me" type="checkbox" class="peer appearance-none w-4 h-4 border border-white/20 rounded bg-white/5 checked:bg-orange-600 checked:border-orange-600 transition-colors cursor-pointer focus:ring-0 focus:ring-offset-0 focus:outline-none" name="remember">
                            <span class="material-symbols-rounded absolute text-white text-[14px] opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity">check</span>
                        </div>
                    </label>
                </div>

                <!-- Submit Action -->
                <button type="submit" :disabled="loading" :class="loading ? 'opacity-70 pointer-events-none cursor-not-allowed' : ''" class="relative w-full h-[56px] flex items-center justify-center bg-gradient-to-r from-orange-600 to-orange-500 overflow-hidden rounded-2xl text-white font-bold text-[16px] shadow-[0_8px_25px_rgba(249,115,22,0.3)] hover:shadow-[0_10px_35px_rgba(249,115,22,0.4)] active:scale-[0.98] transition-all group mt-2 border border-orange-500/50">
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                    <span x-show="!loading" class="flex items-center gap-2">Sign In <span class="material-symbols-rounded text-lg">login</span></span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing In...
                    </span>
                </button>
            </form>

            <div class="mt-5 text-center flex-shrink-0 flex flex-col">
                <div class="flex items-center justify-between px-1 text-[13px] font-medium w-full">
                    <a href="{{ route('admin.password.request') }}" class="font-bold text-orange-500 hover:text-orange-400 transition-colors">
                        Forgot password?
                    </a>
                    <a href="{{ route('admin.register') }}" class="font-bold text-white hover:text-orange-400 transition-colors">
                        Create Account
                    </a>
                </div>

                <div class="mt-4 pt-4 border-t border-white/5">
                    <p class="text-[12px] font-medium text-white/30">
                        Not an admin? 
                        <a href="{{ route('home') }}" class="text-white/50 hover:text-white transition-colors ml-1">Return Home</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
