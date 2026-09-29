<!-- PWA Meta Tags -->
<meta name="theme-color" content="#F97316">
<link rel="apple-touch-icon" href="{{ siteFavicon() }}">
<link rel="manifest" href="{{ route('pwa.manifest') }}">

<!-- PWA Install Prompt Logic -->
<script src="{{ asset('pwa-install.js') }}"></script>

<!-- Service Worker Registration -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js').then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
</script>
