const RELATION_PATH = '/demande-mise-en-relation';

self.addEventListener('install', (event) => {
    event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    if (request.mode !== 'navigate' || url.origin !== self.location.origin) {
        return;
    }

    if (url.pathname !== RELATION_PATH) {
        return;
    }

    event.respondWith(fetch(new Request(url.toString(), {
        method: 'GET',
        headers: request.headers,
        credentials: 'include',
        cache: 'reload',
        redirect: 'follow',
    })));
});
