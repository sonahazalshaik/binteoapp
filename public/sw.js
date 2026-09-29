const preLoad = function () {
    return caches.open("offline").then(function (cache) {
        // caching index and important routes
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", function (event) {
    event.waitUntil(preLoad());
});

const filesToCache = [
    '/',
    '/offline.html'
];

const checkResponse = function (request) {
    return new Promise(function (fulfill, reject) {
        fetch(request).then(function (response) {
            if (response.status !== 404) {
                fulfill(response);
            } else {
                reject();
            }
        }, reject);
    });
};

const addToCache = function (request) {
    // Only cache http(s) requests
    if (!request.url.startsWith('http')) {
        return Promise.resolve();
    }
    return caches.open("offline").then(function (cache) {
        return fetch(request).then(function (response) {
            // Only cache successful full responses (206 Partial Content is not supported by Cache API)
            if (response && response.status === 200) {
                return cache.put(request, response);
            }
            return Promise.resolve();
        }).catch(function() {
            // Ignore fetch errors to prevent unhandled promise rejections
            return Promise.resolve();
        });
    });
};


const returnFromCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status === 404) {
                return cache.match("offline.html");
            } else {
                return matching;
            }
        });
    });
};

self.addEventListener("fetch", function (event) {
    // Only intercept GET requests
    if (event.request.method === 'GET') {
        event.respondWith(checkResponse(event.request).catch(function () {
            return returnFromCache(event.request);
        }));
        
        // Only cache http/https GET requests
        if (event.request.url.startsWith('http')) {
            event.waitUntil(addToCache(event.request));
        }
    }
});
