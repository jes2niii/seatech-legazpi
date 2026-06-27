// Service worker registration + PWA install prompt capture.
// Custom "Install" UI can later read window.deferredPrompt to show a button.

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((reg) => {
                if (window.console && reg && reg.scope) {
                    console.log('PWA service worker registered:', reg.scope);
                }
            })
            .catch((err) => {
                if (window.console) {
                    console.warn('PWA service worker registration failed:', err);
                }
            });
    });
}

window.deferredPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    window.deferredPrompt = e;
    window.dispatchEvent(new CustomEvent('pwa-installable'));
});

window.addEventListener('appinstalled', () => {
    window.deferredPrompt = null;
});
