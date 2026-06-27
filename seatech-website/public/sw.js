/* SEATECH service worker
 * - Precaches the app shell on install.
 * - Cache-first for static assets (/build/, /images/).
 * - Network-first for HTML navigations, falls back to cached shell, then /offline.
 * - Bypasses admin / student / API routes (network-only) to avoid stale data.
 */
const CACHE_NAME = 'seatech-v1';
const RUNTIME_CACHE = 'seatech-runtime-v1';
const PRECACHE = [
    '/',
    '/offline.html',
    '/manifest.json',
    '/images/icon-192.png',
    '/images/icon-512.png',
    '/images/icon-maskable-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE).catch(() => {
            // Precache failures shouldn't abort the install; assets will be cached on first fetch.
        }))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.filter((k) => ! k.startsWith('seatech-')).map((k) => caches.delete(k))
            )
        )
    );
    self.clients.claim();
});

const isHtmlRequest = (request) =>
    request.mode === 'navigate' ||
    (request.method === 'GET' && (request.headers.get('accept') || '').includes('text/html'));

const isStaticAsset = (url) =>
    url.pathname.startsWith('/build/') ||
    url.pathname.startsWith('/images/') ||
    url.pathname.startsWith('/fonts/') ||
    url.pathname.startsWith('/storage/') ||
    url.pathname === '/manifest.json' ||
    url.pathname === '/favicon.ico';

const shouldBypass = (url) =>
    url.pathname.startsWith('/admin') ||
    url.pathname.startsWith('/student') ||
    url.pathname.startsWith('/api/') ||
    url.pathname.startsWith('/login') ||
    url.pathname.startsWith('/register') ||
    url.pathname.startsWith('/_debugbar') ||
    url.pathname.startsWith('/livewire/');

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) return;
    if (shouldBypass(url)) return;

    if (isStaticAsset(url)) {
        event.respondWith(
            caches.match(request).then((cached) => cached || fetch(request).then((response) => {
                const copy = response.clone();
                caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
                return response;
            }).catch(() => cached))
        );
        return;
    }

    if (isHtmlRequest(request)) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
                    return response;
                })
                .catch(async () => {
                    const cachedShell = await caches.match('/');
                    return cachedShell || caches.match('/offline.html') || new Response(
                        '<!doctype html><meta charset="utf-8"><title>Offline</title><h1>You are offline</h1>',
                        { headers: { 'Content-Type': 'text/html' } }
                    );
                })
        );
    }
});
