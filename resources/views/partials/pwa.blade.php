<!-- PWA Meta Tags (theme-color is managed dynamically per layout via
     #theme-color-meta so it follows the device-driven <html> class) -->
<link rel="apple-touch-icon" href="{{ siteFavicon() }}">
<link rel="manifest" href="{{ route('pwa.manifest') }}">

<!-- PWA Install Prompt Logic -->
<script src="{{ asset('pwa-install.js') }}"></script>

<!-- Service Worker Registration (skipped on local dev servers: the
     single-threaded PHP dev server + an offline-first worker poisons the
     asset cache with truncated responses, which then renders every page
     without CSS until a hard reload) -->
<script>
    (function () {
        var host = window.location.hostname;
        var isLocalDev = host === 'localhost' || host === '127.0.0.1' || host === '[::1]' || host.indexOf('.test') !== -1 || host.indexOf('.local') !== -1;
        if (!('serviceWorker' in navigator)) return;
        if (isLocalDev) {
            // Heal browsers that already run the old poisoned worker:
            // unregister it and drop every CacheStorage entry it made.
            try {
                navigator.serviceWorker.getRegistrations().then(function (regs) {
                    regs.forEach(function (r) { r.unregister(); });
                });
                if (window.caches) {
                    caches.keys().then(function (keys) {
                        keys.forEach(function (k) { caches.delete(k); });
                    });
                }
            } catch (e) {}
            return;
        }
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js').then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    })();
</script>
