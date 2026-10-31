const CACHE_VERSION = "binteo-v2";
const STATIC_CACHE = CACHE_VERSION + "-static";
const PAGE_CACHE = CACHE_VERSION + "-pages";
const LEGACY_CACHES = ["offline"];

const CORE_ASSETS = [
    '/offline.html'
];

self.addEventListener("install", function (event) {
    event.waitUntil(
        caches.open(STATIC_CACHE).then(function (cache) {
            return cache.addAll(CORE_ASSETS);
        }).then(function () {
            return self.skipWaiting();
        })
    );
});

self.addEventListener("activate", function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(keys.map(function (key) {
                // Drop the legacy unversioned cache (may hold truncated
                // responses cached during single-threaded dev-server races)
                // and any older versioned caches.
                if (LEGACY_CACHES.indexOf(key) !== -1 || key.indexOf("binteo-") !== 0 || (key !== STATIC_CACHE && key !== PAGE_CACHE)) {
                    return caches.delete(key);
                }
            }));
        }).then(function () {
            return self.clients.claim();
        })
    );
});

function isStaticAsset(url) {
    return /\.(png|jpg|jpeg|webp|svg|gif|ico|woff2|woff|ttf|css|js)(\?.*)?$/i.test(url.pathname);
}

function putIfOk(cacheName, request, response) {
    if (response && (response.status === 200 || response.type === "opaque")) {
        // Guard against caching truncated bodies: only store when a
        // Content-Length header exists and matches, or when absent.
        var expected = null;
        try { expected = response.headers.get("content-length"); } catch (e) {}
        var clone = response.clone();
        if (expected !== null) {
            return clone.arrayBuffer().then(function (buf) {
                if (buf.byteLength === parseInt(expected, 10)) {
                    return caches.open(cacheName).then(function (cache) {
                        return cache.put(request, new Response(buf, {
                            status: response.status,
                            statusText: response.statusText,
                            headers: response.headers
                        }));
                    });
                }
            }).catch(function () {});
        }
        var copy = response.clone();
        return caches.open(cacheName).then(function (cache) {
            return cache.put(request, copy);
        });
    }
    return Promise.resolve();
}

self.addEventListener("fetch", function (event) {
    if (event.request.method !== 'GET' || !event.request.url.startsWith('http')) {
        return;
    }

    var url = new URL(event.request.url);
    var isNavigation = event.request.mode === 'navigate';

    if (isStaticAsset(url)) {
        // Stale-while-revalidate: instant render from cache, silent
        // background refresh so a once-poisoned entry heals itself.
        event.respondWith(
            caches.match(event.request).then(function (cachedResponse) {
                var networkUpdate = fetch(event.request).then(function (networkResponse) {
                    putIfOk(STATIC_CACHE, event.request, networkResponse);
                    return networkResponse;
                }).catch(function () {});
                return cachedResponse || networkUpdate;
            })
        );
        return;
    }

    if (isNavigation) {
        // Network-first for pages; offline fallback ONLY for navigations,
        // never substituted for CSS/JS.
        event.respondWith(
            fetch(event.request).then(function (networkResponse) {
                if (networkResponse && networkResponse.status === 200) {
                    var copy = networkResponse.clone();
                    caches.open(PAGE_CACHE).then(function (cache) {
                        cache.put(event.request, copy);
                    });
                }
                return networkResponse;
            }).catch(function () {
                return caches.open(STATIC_CACHE).then(function (cache) {
                    return cache.match('/offline.html');
                });
            })
        );
        return;
    }

    // Anything else (API/XHR): network only, never cached, never replaced.
});
