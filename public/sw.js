const CACHE_NAME = 'amikomhub-v1';
const OFFLINE_URL = '/offline.html';

// Assets to cache immediately on install
const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/manifest.json',
    '/css/neo-brutalism.css',
    '/assets/icons/icon-192x192.png',
    '/assets/icons/icon-512x512.png',
    'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap'
];

// Routes that should NEVER be cached (Network Only)
const NETWORK_ONLY_ROUTES = [
    '/api/',
    '/login',
    '/logout',
    '/checkout',
    '/midtrans',
    '/organizer',
    '/admin'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // 1. Network Only for specific routes (API, Auth, Checkout, Admin panels)
    const isNetworkOnly = NETWORK_ONLY_ROUTES.some(route => url.pathname.startsWith(route));
    if (isNetworkOnly || event.request.method !== 'GET') {
        event.respondWith(fetch(event.request));
        return;
    }

    // 2. Stale-While-Revalidate for Static Assets
    if (
        url.pathname.startsWith('/assets/') || 
        url.pathname.startsWith('/css/') ||
        url.hostname === 'fonts.googleapis.com' ||
        url.hostname === 'fonts.gstatic.com' ||
        url.hostname === 'unpkg.com' ||
        url.hostname === 'cdnjs.cloudflare.com'
    ) {
        event.respondWith(
            caches.open(CACHE_NAME).then(async (cache) => {
                const cachedResponse = await cache.match(event.request);
                const fetchPromise = fetch(event.request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        cache.put(event.request, networkResponse.clone());
                    }
                    return networkResponse;
                }).catch(() => {
                    // Ignore network errors on background fetch
                });

                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // 3. Network First (Fallback to Cache, then Offline Page) for HTML pages
    if (event.request.headers.get('accept').includes('text/html')) {
        event.respondWith(
            fetch(event.request).then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return networkResponse;
            }).catch(async () => {
                const cachedResponse = await caches.match(event.request);
                if (cachedResponse) {
                    return cachedResponse;
                }
                return caches.match(OFFLINE_URL);
            })
        );
        return;
    }

    // Default strategy (Cache First) for everything else
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            return cachedResponse || fetch(event.request);
        })
    );
});
