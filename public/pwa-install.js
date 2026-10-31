let deferredPrompt;

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', () => {
    const installBtns = document.querySelectorAll('.pwa-install-btn');

    if (installBtns.length === 0) {
        console.error('PWA Install button not found in DOM');
        return;
    }

    installBtns.forEach(installBtn => {
        // Hide button ONLY if already running inside the app (standalone mode)
        if (window.matchMedia('(display-mode: standalone)').matches) {
            installBtn.style.setProperty('display', 'none', 'important');
        } else {
            // Ensure it's visible by default in standard browser
            installBtn.style.setProperty('display', 'flex', 'important');
        }

        // Click logic
        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;

            const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
            const bgColor = isDark ? '#131316' : '#FFFFFF';
            const titleColor = isDark ? '#FFFFFF' : '#131316';
            const descColor = isDark ? '#9CA3AF' : '#4B5563';
            const btnBg = isDark ? 'bg-white/5' : 'bg-gray-100';
            
            // Get logo for popup (fallback if img not found)
            let logoSrc = '/assets/images/logoIcon/logo.png';
            const logoImg = document.querySelector('.pwa-install-btn img');
            if (logoImg) logoSrc = logoImg.src;

            if (window.Swal) {
                Swal.fire({
                    html: `
                        <div class="flex flex-col items-center py-6">
                            <!-- App Hero Section -->
                            <div class="relative mb-8">
                                <div class="absolute -inset-4 bg-orange-500/20 blur-3xl rounded-full animate-pulse"></div>
                                <div class="w-24 h-24 rounded-full gradient-orange p-[3px] relative z-10 shadow-[0_15px_40px_rgba(249,115,22,0.4)]">
                                    <div class="w-full h-full bg-white rounded-full flex items-center justify-center p-4">
                                        <img src="${logoSrc}" class="w-full h-full object-contain" alt="Logo">
                                    </div>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <h3 class="text-2xl font-black uppercase tracking-tighter mb-2 font-['Space_Grotesk']" style="color: ${titleColor}">Install Binteo App</h3>
                            <p class="text-sm mb-8 max-w-[260px] leading-relaxed uppercase font-bold tracking-widest text-[10px]" style="color: ${descColor}">Experience lightning fast speeds, offline viewing, and instant updates.</p>

                            <!-- Action Buttons -->
                            <button id="custom-swal-install" class="w-full h-14 gradient-orange rounded-2xl text-white font-black uppercase tracking-[0.2em] shadow-xl shadow-orange-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all mb-4">
                                Install Now
                            </button>
                            <button onclick="Swal.close()" class="text-[10px] font-black uppercase tracking-[0.2em] transition-all" style="color: ${descColor}">
                                Maybe Later
                            </button>
                        </div>
                    `,
                    showConfirmButton: false,
                    background: bgColor,
                    customClass: {
                        popup: `rounded-[2.5rem] border ${isDark ? 'border-white/5' : 'border-gray-100'} backdrop-blur-3xl`,
                    },
                    didOpen: () => {
                        document.getElementById('custom-swal-install').addEventListener('click', async () => {
                            Swal.close();
                            deferredPrompt.prompt();
                            const { outcome } = await deferredPrompt.userChoice;
                            console.log(`User response: ${outcome}`);
                            if (outcome === 'accepted') {
                                document.querySelectorAll('.pwa-install-btn').forEach(btn => {
                                    btn.style.setProperty('display', 'none', 'important');
                                });
                                showInstallingLoader(isDark);
                            }
                            deferredPrompt = null;
                        });
                    }
                });
            }
        });
    });
});

function showInstallingLoader(isDark) {
    if (window.Swal) {
        Swal.fire({
            title: 'Installing...',
            html: 'Please wait while we set up the app for you.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
            background: isDark ? '#131316' : '#FFFFFF',
            color: isDark ? '#FFFFFF' : '#131316',
            customClass: {
                popup: `rounded-[2.5rem] border ${isDark ? 'border-white/5' : 'border-gray-100'}`,
            }
        });
    }
}

// Event listener for beforeinstallprompt
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('beforeinstallprompt event fired');
    e.preventDefault();
    deferredPrompt = e;
    
    document.querySelectorAll('.pwa-install-btn').forEach(btn => {
        btn.style.setProperty('display', 'flex', 'important');
    });
});

window.addEventListener('appinstalled', () => {
    console.log('appinstalled event fired');
    document.querySelectorAll('.pwa-install-btn').forEach(btn => {
        btn.style.setProperty('display', 'none', 'important');
    });
    
    localStorage.setItem('pwa_installed', 'true');
    
    if (window.Swal) {
        Swal.close();
        if (!window.matchMedia('(display-mode: standalone)').matches) {
            Swal.fire({
                title: 'App Installed!',
                text: 'The app has been successfully installed on your device.',
                icon: 'success',
                confirmButtonText: 'Great!',
                confirmButtonColor: '#F97316',
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        }
    }
});
