const CACHE_NAME = 'of2on-v3';
const urlsToCache = [
    '/',
    '/assets/global/css/bootstrap.min.css',
    '/assets/global/css/all.min.css',
    '/assets/global/css/line-awesome.min.css',
    '/assets/templates/basic/css/custom.css',
    '/assets/global/js/jquery-3.6.0.min.js',
    '/assets/global/js/bootstrap.bundle.min.js',
    '/offline.html',
    '/assets/images/logo_icon/icon-192x192.png'
];

// Install SW
self.addEventListener('install', (event) => {
    self.skipWaiting(); // Force update immediately
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(urlsToCache);
        })
    );
});

/*
// Listen for requests
self.addEventListener('fetch', (event) => {
    // Only intercept GET requests from the same origin
    if (event.request.method !== 'GET' || !event.request.url.startsWith(self.location.origin)) {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                // Try to fetch new version and update cache
                const fetchPromise = fetch(event.request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                        const cachePromise = caches.open(CACHE_NAME);
                        cachePromise.then((cache) => {
                            cache.put(event.request, networkResponse.clone());
                        });
                    }
                    return networkResponse;
                }).catch(() => {
                    return response;
                });

                // Return cached response if available, otherwise fetch
                return response || fetchPromise;
            })
            .catch(() => {
                // If both fail, show offline page
                if (event.request.mode === 'navigate') {
                    return caches.match('/offline.html');
                }
            })
    );
});
*/

// Activate the SW
self.addEventListener('activate', (event) => {
    const cacheWhitelist = [CACHE_NAME];

    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (!cacheWhitelist.includes(cacheName)) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
