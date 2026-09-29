<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Admin - Request Clearance</title>
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
    <div class="relative z-10 w-full h-full flex flex-col justify-end sm:justify-center items-center p-0 sm:p-6 pb-0">
        
        <!-- Elevated Floating Auth Sheet (M3 Bottom Sheet Style) -->
        <div class="w-full sm:max-w-[420px] bg-white/[0.03] backdrop-blur-3xl border-t border-white/10 sm:border sm:rounded-[2.5rem] rounded-t-[2.5rem] p-6 sm:p-8 shadow-[0_-20px_60px_-15px_rgba(0,0,0,0.5)] sm:shadow-2xl relative overflow-hidden transition-all flex flex-col max-h-[100dvh] sm:h-auto">
            
            <!-- Orange Top Edge Accent -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600"></div>

            <div class="mx-auto w-12 h-1.5 bg-white/20 rounded-full mb-6 sm:hidden flex-shrink-0"></div> <!-- Handle bar -->

            <!-- Header -->
            <div class="text-center mb-6 flex-shrink-0">
                <div class="relative inline-flex items-center justify-center w-14 h-14 rounded-[1.25rem] bg-gradient-to-tr from-orange-900/50 to-orange-600/20 text-orange-500 mb-4 border border-orange-500/20 shadow-[0_0_30px_rgba(249,115,22,0.2)]">
                    <span class="material-symbols-rounded text-[28px] drop-shadow-lg">policy</span>
                </div>
                <h2 class="text-2xl font-black text-white tracking-tight leading-none mb-2">Create Account</h2>
                <p class="text-[14px] font-medium text-white/50">Register for admin access</p>
            </div>

            <!-- Form specific container -> absolute height + scroll -->
            <form method="POST" action="{{ route('admin.register') }}" class="space-y-4 overflow-y-auto pb-4 scrollbar-hide flex-grow px-1">
                @csrf

                <x-input 
                    name="name" 
                    label="Full Name" 
                    required 
                    autofocus 
                    autocomplete="name"
                    placeholder="John Doe"
                    hint="Your full legal name for administrative identification."
                />

                <x-input 
                    name="email" 
                    type="email" 
                    label="Email Address" 
                    required 
                    autocomplete="username"
                    placeholder="admin@example.com"
                    hint="A valid administrative email address."
                />

                <x-input 
                    name="password" 
                    type="password" 
                    label="Password" 
                    required 
                    autocomplete="new-password"
                    placeholder="••••••••"
                    hint="Minimum 8 characters with mixed complexity recommended."
                />

                <x-input 
                    name="password_confirmation" 
                    type="password" 
                    label="Confirm Password" 
                    required 
                    autocomplete="new-password"
                    placeholder="••••••••"
                    hint="Please re-type your password to confirm."
                />

                <!-- Submit Action -->
                <button class="relative w-full h-[56px] flex items-center justify-center bg-gradient-to-r from-orange-600 to-orange-500 overflow-hidden rounded-2xl text-white font-bold text-[16px] shadow-[0_8px_25px_rgba(249,115,22,0.3)] hover:shadow-[0_10px_35px_rgba(249,115,22,0.4)] active:scale-[0.98] transition-all group border border-orange-500/50 mt-2">
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
                    <span class="flex items-center gap-2">Sign Up <span class="material-symbols-rounded text-lg">check_circle</span></span>
                </button>
            </form>

            <div class="mt-4 text-center flex-shrink-0">
                <p class="text-[13px] font-medium text-white/40">
                    Already have an account? 
                    <a href="{{ route('admin.login') }}" class="font-bold text-orange-500 hover:text-orange-400 transition-colors ml-1">Sign In</a>
                </p>
            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</body>
</html>
