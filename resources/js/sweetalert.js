import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const BRAND = {
    confirmButtonColor: '#001a61',
    cancelButtonColor: '#757683',
    denyButtonColor: '#c53030',
};

const TITLES = {
    success: 'Succès',
    error: 'Erreur',
    warning: 'Attention',
    info: 'Information',
    question: 'Confirmation',
};

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4200,
    timerProgressBar: true,
    ...BRAND,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

function normalizePayload(payload = {}) {
    const raw = Array.isArray(payload) ? (payload[0] ?? {}) : payload;
    const type = (raw.type || raw.icon || 'info').toLowerCase();
    const icon = ['success', 'error', 'warning', 'info', 'question'].includes(type) ? type : 'info';
    const html = raw.html || undefined;
    const text = raw.message || raw.text || '';

    return {
        icon,
        title: raw.title || TITLES[icon] || '',
        text: html ? undefined : text,
        html,
        toast: raw.toast !== false && icon !== 'error' && icon !== 'question' && !html,
        timer: raw.timer,
        confirmButtonText: raw.confirmButtonText || 'OK',
        cancelButtonText: raw.cancelButtonText || 'Annuler',
        showCancelButton: Boolean(raw.showCancelButton),
    };
}

/**
 * Affiche une notification SweetAlert2 (toast pour success/info/warning, modal pour erreur).
 */
export function adfNotify(typeOrPayload, message) {
    const payload = typeof typeOrPayload === 'string'
        ? { type: typeOrPayload === 'message' ? 'success' : typeOrPayload, message }
        : typeOrPayload;

    const opts = normalizePayload(payload);

    if (!opts.text && !opts.html) {
        return Promise.resolve();
    }

    if (opts.toast) {
        return Toast.fire({
            icon: opts.icon,
            title: opts.text || opts.title,
            timer: opts.timer,
        });
    }

    return Swal.fire({
        icon: opts.icon,
        title: opts.title,
        text: opts.html ? undefined : opts.text,
        html: opts.html || undefined,
        confirmButtonText: opts.confirmButtonText,
        ...BRAND,
    });
}

/**
 * Dialogue de confirmation (promesse booléenne).
 */
export function adfConfirm({
    title = 'Confirmer ?',
    text = 'Cette action est irréversible.',
    confirmButtonText = 'Confirmer',
    cancelButtonText = 'Annuler',
    icon = 'warning',
} = {}) {
    return Swal.fire({
        icon,
        title,
        text,
        showCancelButton: true,
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true,
        focusCancel: true,
        ...BRAND,
        confirmButtonColor: '#c53030',
        cancelButtonColor: '#757683',
    }).then((result) => result.isConfirmed);
}

function bindLivewire() {
    if (!window.Livewire) {
        return;
    }

    const handle = (...args) => {
        // Livewire 3 : objet nommé, ou tableau d'args
        let payload = args[0];
        if (Array.isArray(payload) && payload.length === 1 && typeof payload[0] === 'object') {
            payload = payload[0];
        }
        if (typeof payload !== 'object' || payload === null) {
            payload = { message: String(payload ?? '') };
        }
        return adfNotify(payload);
    };

    // Livewire 3 : $this->dispatch('swal', ...)
    Livewire.on('swal', handle);
    Livewire.on('notify', handle);
    Livewire.on('alert', handle);

    // Événements navigateur
    window.addEventListener('swal', (e) => handle(e.detail ?? {}));
    window.addEventListener('notify', (e) => handle(e.detail ?? {}));
}

function showPageFlashes() {
    const el = document.getElementById('adf-flash-data');
    if (!el) {
        return;
    }

    let flashes = {};
    try {
        flashes = JSON.parse(el.textContent || '{}');
    } catch {
        return;
    }

    const order = ['error', 'warning', 'success', 'info', 'message'];
    order.forEach((key) => {
        if (flashes[key]) {
            adfNotify(key === 'message' ? 'success' : key, flashes[key]);
        }
    });

    el.remove();
}

window.Swal = Swal;
window.adfNotify = adfNotify;
window.adfConfirm = adfConfirm;
window.adfToast = (type, message) => adfNotify({ type, message, toast: true });

document.addEventListener('DOMContentLoaded', () => {
    showPageFlashes();
});

document.addEventListener('livewire:init', () => {
    bindLivewire();
});

document.addEventListener('livewire:navigated', () => {
    showPageFlashes();
});

export default Swal;
