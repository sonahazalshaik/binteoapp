<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="color-scheme" content="light dark">

        <title><?php echo e(config('app.name', 'Laravel')); ?> - <?php echo e(gs('site_name')); ?></title>
        <link rel="shortcut icon" type="image/png" href="<?php echo e(siteFavicon()); ?>">
        <?php echo $__env->make('partials.pwa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Theme Color follows the device-driven <html> class -->
        <meta name="theme-color" id="theme-color-meta" content="#FFFFFF">
        <script>
            (function(){
                var meta = document.getElementById('theme-color-meta');
                function sync(){ meta.content = document.documentElement.classList.contains('dark') ? '#0A0A0A' : '#FFFFFF'; }
                sync();
                new MutationObserver(sync).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

        <!-- Device Theme (single source of truth, runs synchronously
             before first paint): follows the OS/browser `prefers-color-scheme`
             by default. Tapping the sun/moon toggle stores a manual override
             (`binteo-theme` = dark/light); "Follow system" clears it (null). -->
        <script>
            (function(){
                try{
                    var KEY = 'binteo-theme';
                    // Obsolete legacy key is never honored; drop it so it
                    // cannot silently latch an override.
                    try { localStorage.removeItem('dark'); } catch(e) {}
                    var stored = null;
                    try { stored = localStorage.getItem(KEY); } catch(e) {}
                    if (stored !== 'dark' && stored !== 'light') stored = null;
                    var mq = window.matchMedia('(prefers-color-scheme: dark)');
                    var apply = function(dark){ document.documentElement.classList.toggle('dark', !!dark); document.documentElement.style.colorScheme = dark ? 'dark' : 'light'; };
                    var resolve = function(){
                        if (stored === 'dark') return true;
                        if (stored === 'light') return false;
                        return !!(mq && mq.matches);
                    };
                    var broadcast = function(dark){
                        window.dispatchEvent(new CustomEvent('system-theme', { detail: { dark: !!dark, mode: stored } }));
                    };
                    apply(resolve());
                    // Manual override API. mode: true/false (dark/light) to
                    // override, null/'system' to clear and follow the device.
                    window.__setTheme = window.__setTheme || function(mode){
                        if (mode === null || mode === 'system') {
                            stored = null;
                            try { localStorage.removeItem(KEY); } catch(e) {}
                            var dark = !!(mq && mq.matches);
                            apply(dark);
                            broadcast(dark);
                        } else {
                            stored = mode ? 'dark' : 'light';
                            try { localStorage.setItem(KEY, stored); } catch(e) {}
                            apply(stored === 'dark');
                            broadcast(stored === 'dark');
                        }
                    };
                    window.__getTheme = window.__getTheme || function(){ return document.documentElement.classList.contains('dark'); };
                    var onChange = function(e){
                        // The OS always wins: apply the new system theme
                        // immediately and drop any manual override, so
                        // auto-switching keeps working after the toggle
                        // was used. (A tap still switches manually until
                        // the next system change or "Follow system".)
                        stored = null;
                        try { localStorage.removeItem(KEY); } catch(err) {}
                        apply(e.matches);
                        broadcast(e.matches);
                    };
                    if (mq && mq.addEventListener) { mq.addEventListener('change', onChange); }
                    else if (mq && mq.addListener) { mq.addListener(onChange); }
                }catch(e){}
            })();
        </script>

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <script src="<?php echo e(asset('assets/js/validation.js')); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            /* Same as app layout: UA defaults must follow the site theme. */
            html { color-scheme: light; }
            html.dark { color-scheme: dark; }
            body { font-family: 'Outfit', sans-serif; }
            [x-cloak] { display: none !important; }
            /* Anti-FOUT: reveal Material Symbols only when the font is ready. */
            .material-symbols-rounded { visibility: hidden; }
            html.msr-ready .material-symbols-rounded { visibility: visible; }
        </style>
        <script>
            (function() {
                var doc = document.documentElement, done = false;
                function reveal() { if (done) return; done = true; doc.classList.add('msr-ready'); }
                try {
                    if (document.fonts && document.fonts.load) {
                        document.fonts.load('24px "Material Symbols Rounded"').then(reveal, reveal);
                        setTimeout(reveal, 2500);
                    } else { reveal(); }
                } catch (e) { reveal(); }
            })();

            // Aggressive Site-Wide Event Listener: Prevent native long-press URL callouts
            (function() {
                var isEditable = function(el) {
                    if (el && el.nodeType === 3) el = el.parentNode;
                    return el && typeof el.closest === 'function' && el.closest('input, textarea, [contenteditable="true"], .selectable');
                };

                window.addEventListener('contextmenu', function(e) {
                    if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                        return; // Let desktop users inspect
                    }
                    if (!isEditable(e.target)) {
                        e.preventDefault(); e.stopPropagation(); return false;
                    }
                }, { capture: true });

                window.addEventListener('dragstart', function(e) {
                    if (!isEditable(e.target)) {
                        e.preventDefault(); e.stopPropagation(); return false;
                    }
                }, { capture: true });
                
                window.addEventListener('selectstart', function(e) {
                    if (!isEditable(e.target)) e.preventDefault();
                }, { capture: true });

                var longPressTimeout;
                var activeLink = null;
                var activeHref = null;

                function restoreLink() {
                    if (activeLink && activeHref) {
                        activeLink.setAttribute('href', activeHref);
                        activeLink = null;
                        activeHref = null;
                    }
                }

                window.addEventListener('touchstart', function(e) {
                    if (isEditable(e.target)) return;
                    var target = e.target;
                    if (target && target.nodeType === 3) target = target.parentNode;
                    var link = target && typeof target.closest === 'function' ? target.closest('a[href]') : null;
                    if (link) {
                        activeLink = link;
                        activeHref = link.getAttribute('href');
                        longPressTimeout = setTimeout(function() {
                            if (activeLink) activeLink.removeAttribute('href');
                        }, 250);
                    }
                }, { capture: true, passive: true });

                window.addEventListener('touchmove', function() {
                    clearTimeout(longPressTimeout); restoreLink();
                }, { capture: true, passive: true });

                window.addEventListener('touchend', function() {
                    clearTimeout(longPressTimeout); restoreLink();
                }, { capture: true, passive: true });

                window.addEventListener('touchcancel', function() {
                    clearTimeout(longPressTimeout); restoreLink();
                }, { capture: true, passive: true });
            })();
        </script>
    </head>
    <body class="bg-gray-50 dark:bg-[#0e0e11] text-gray-900 dark:text-[#e4e1e6] font-sans min-h-screen flex flex-col relative overflow-x-hidden selection:bg-[#ff571a] selection:text-white transition-colors duration-500">
        <?php echo $__env->make('partials.notify', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
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
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 dark:text-white/60 hover:text-[#ff571a] dark:hover:text-white hover:border-[#ff571a]/50 transition-all group shadow-sm dark:shadow-none">
                    <span class="material-symbols-rounded text-sm group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    <span class="hidden sm:inline">Back to Home</span>
                </a>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2.5 group">
                    <span class="text-lg font-black text-gray-900 dark:text-white tracking-tighter uppercase font-['Space_Grotesk'] group-hover:text-[#ff571a] transition-colors hidden xs:block"><?php echo e(gs('site_name')); ?></span>
                    <div class="w-10 h-10 bg-gradient-to-br from-[#ff571a] to-[#ae3200] rounded-xl p-[1px] shadow-lg shadow-[#ff571a]/20 group-hover:scale-105 transition-all">
                        <div class="w-full h-full bg-white rounded-[9px] flex items-center justify-center p-1.5">
                            <img src="<?php echo e(siteLogo()); ?>" alt="Logo" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow flex items-center justify-center px-4 py-24 relative z-10">
            <!-- Glassmorphism Card -->
            <div class="w-full max-w-[440px] bg-white dark:bg-white/5 backdrop-blur-3xl border border-gray-200 dark:border-white/10 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.08)] dark:shadow-[0_40px_80px_-20px_rgba(255,87,26,0.15)] p-6 md:p-10 animate-in fade-in zoom-in duration-700">
                <?php echo e($slot); ?>

            </div>
        </main>

        <!-- Footer -->
        <footer class="p-8 flex flex-col md:flex-row justify-between items-center gap-4 text-gray-400 dark:text-[#e6beb2]/30 relative z-10">
            <p class="text-[10px] font-black tracking-[0.2em] uppercase font-['Space_Grotesk']">© <?php echo e(date('Y')); ?> <?php echo e(gs('site_name')); ?>. ALL RIGHTS RESERVED.</p>
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
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\layouts\guest.blade.php ENDPATH**/ ?>