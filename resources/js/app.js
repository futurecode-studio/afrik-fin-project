import './bootstrap';

// Styles pour l'éditeur riche (Quill)
import 'quill/dist/quill.snow.css';

// Quill (utilisé dans certaines vues admin)
import Quill from 'quill';
window.Quill = Quill;

// SweetAlert2 — toasts / modals (admin, client, public)
import './sweetalert';

// IMPORTANT: ne pas importer/démarrer Alpine ici.
// Livewire 3 embarque déjà Alpine via @livewireScripts.
// Un second Alpine.start() casse wire:submit (spinner puis silence).

const relationPath = '/demande-mise-en-relation';

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/adf-navigation-fix-sw.js').catch(() => {});
    });
}

document.addEventListener('click', (event) => {
    const link = event.target.closest('a[href]');
    if (!link) return;

    const url = new URL(link.href, window.location.origin);
    if (url.origin !== window.location.origin || url.pathname !== relationPath || url.search) {
        return;
    }

    event.preventDefault();
    window.location.assign(`${relationPath}?cachefix=${Date.now()}`);
});
