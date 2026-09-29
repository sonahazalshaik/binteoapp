<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - {{ gs('site_name') }}</title>
        <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
        @include('partials.pwa')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

        <!-- Dark Mode Init -->
        <script>
            if (localStorage.getItem('dark') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{ asset('assets/js/validation.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            body { font-family: 'Outfit', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-gray-50 dark:bg-[#0e0e11] text-gray-900 dark:text-[#e4e1e6] font-sans min-h-screen flex flex-col relative overflow-x-hidden selection:bg-[#ff571a] selection:text-white transition-colors duration-500">
        @include('partials.notify')
        
        <!-- Dynamic Backdrop -->
        <div class="fixed inset-0 z-0 bg-gray-50 dark:bg-[#0e0e11] transition-colors duration-500" 
             style="background-image: radial-gradient(at 0% 0%, rgba(255, 87, 26, 0.08) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(255, 87, 26, 0.04) 0px, transparent 50%);">
        </div>
        
        <!-- Dark Mode Only Cinematic Overlay -->
        <div class="fixed inset-0 z-[-1] opacity-30 mix-blend-overlay pointer-events-none overflow-hidden hidden dark:block">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAShGpXsK0ktb89Qm38WFYvpzijGsV-M215QqCvtsAoF18GeBxrBA2kRi__5jvuCivohtzuh69UPS_VvNlL-nR24aiWQFX8EqFbzyGHjKo1tC1BymocukyKorwZtnDDtVmuISE68fEQmNxjW-_XAuXD2GoH5-EzMF5unYRwq_Ch4F_u8_Iphz_J9Hq5SRgpb20zuMSv-yeUrmy8VxEDHUykjV9Zebm14OKhQADOZdv9_a6s6Bw4E-Tj2rPMUBNCfxL35__6Dul7IVGj" class="w-full h-full object-cover grayscale brightness-50">
        </div>

        <!-- Decorative Glow Elements -->
        <div class="fixed top-[10%] right-[-5%] w-[500px] h-[500px] rounded-full bg-[#ff571a]/5 dark:bg-[#ff571a]/20 blur-[120px] -z-10 animate-pulse"></div>
        <div class="fixed bottom-[-10%] left-[-5%] w-[400px] h-[400px] rounded-full bg-orange-500/5 dark:bg-[#980000]/10 blur-[100px] -z-10 animate-pulse" style="animation-delay: 2s;"></div>

        <!-- Header -->
        <header class="fixed top-0 w-full z-50 flex justify-between items-center px-4 md:px-8 py-4 md:py-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 dark:text-white/60 hover:text-[#ff571a] dark:hover:text-white hover:border-[#ff571a]/50 transition-all group shadow-sm dark:shadow-none">
                    <span class="material-symbols-rounded text-sm group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    <span class="hidden sm:inline">Back to Home</span>
                </a>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2.5 group">
                    <span class="text-lg font-black text-gray-900 dark:text-white tracking-tighter uppercase font-['Space_Grotesk'] group-hover:text-[#ff571a] transition-colors hidden xs:block">{{ gs('site_name') }}</span>
                    <div class="w-10 h-10 bg-gradient-to-br from-[#ff571a] to-[#ae3200] rounded-xl p-[1px] shadow-lg shadow-[#ff571a]/20 group-hover:scale-105 transition-all">
                        <div class="w-full h-full bg-white rounded-[9px] flex items-center justify-center p-1.5">
                            <img src="{{ siteLogo() }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow flex items-center justify-center px-4 py-24 relative z-10">
            <!-- Glassmorphism Card -->
            <div class="w-full max-w-[440px] bg-white dark:bg-white/5 backdrop-blur-3xl border border-gray-200 dark:border-white/10 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.08)] dark:shadow-[0_40px_80px_-20px_rgba(255,87,26,0.15)] p-6 md:p-10 animate-in fade-in zoom-in duration-700">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="p-8 flex flex-col md:flex-row justify-between items-center gap-4 text-gray-400 dark:text-[#e6beb2]/30 relative z-10">
            <p class="text-[10px] font-black tracking-[0.2em] uppercase font-['Space_Grotesk']">© {{ date('Y') }} {{ gs('site_name') }}. ALL RIGHTS RESERVED.</p>
            <div class="flex gap-8">
                <a href="#" class="text-[10px] font-black uppercase tracking-widest hover:text-[#ff571a] transition-colors font-['Space_Grotesk']">EULA</a>
                <a href="#" class="text-[10px] font-black uppercase tracking-widest hover:text-[#ff571a] transition-colors font-['Space_Grotesk']">Privacy Policy</a>
                <a href="#" class="text-[10px] font-black uppercase tracking-widest text-[#ff571a] font-['Space_Grotesk'] flex items-center gap-1">
                    <span class="w-1 h-1 rounded-full bg-[#ff571a] animate-ping"></span>
                    System Secure
                </a>
            </div>
        </footer>
    </body>
</html>
