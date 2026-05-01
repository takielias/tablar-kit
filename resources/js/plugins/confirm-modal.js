/**
 * Tablar-Kit Confirm Modal — single shared modal driven by data-confirm-*
 * triggers + a Promise-returning programmatic API.
 *
 * Mount the modal once via `<x-confirm-modal />` (id="tablar-kit-confirm-modal")
 * anywhere in the page (typically in your master layout). Then either:
 *
 *   1. Drop a `<x-confirm url="..." method="DELETE" message="...">Delete</x-confirm>`
 *      anywhere — the click is delegated; no per-page JS needed.
 *
 *   2. Call `await window.tablarConfirm({ url, method, title, message })`
 *      from your own JS. Resolves with parsed JSON on success, `null` on
 *      dismiss, rejects on transport errors.
 *
 * Outcome priority on success: onSuccess > redirect > event > reload.
 */
// Import only the Modal + Toast classes — NOT the full `bootstrap` bundle.
// The full bundle re-registers data-API event delegation on dropdowns,
// collapses, etc., which double-fires if @tabler/core has already done so.
import BsModal from 'bootstrap/js/dist/modal';
import BsToast from 'bootstrap/js/dist/toast';
const TablarConfirmModal = {
    MODAL_ID: 'tablar-kit-confirm-modal',

    csrf() {
        return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    },

    el() {
        return document.getElementById(this.MODAL_ID);
    },

    instance() {
        const el = this.el();
        if (!el) {
            console.error('[tablar-kit] #' + this.MODAL_ID + ' not in DOM — drop <x-confirm-modal /> in your layout.');
            return null;
        }
        // Prefer the global Bootstrap if @tabler/core or another bundle has
        // already exposed it; fall back to the directly-imported class so
        // tablar-kit works even when the host app doesn't expose `window.bootstrap`.
        const ModalCtor = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? bootstrap.Modal : BsModal;
        if (!ModalCtor) {
            console.error('[tablar-kit] No Bootstrap Modal class available.');
            return null;
        }
        return ModalCtor.getOrCreateInstance(el);
    },

    fill(opts) {
        const el = this.el();
        if (!el) return;

        const titleEl = el.querySelector('#tablar-kit-confirm-modal-title');
        const messageEl = el.querySelector('#tablar-kit-confirm-modal-message');
        const buttonEl = el.querySelector('#tablar-kit-confirm-modal-button');
        const alertEl = el.querySelector('#tablar-kit-confirm-modal-alert');
        const statusEl = el.querySelector('.modal-status');

        if (titleEl) titleEl.textContent = opts.title || 'Are you sure?';
        if (messageEl) messageEl.textContent = opts.message || '';
        if (buttonEl) {
            buttonEl.textContent = opts.button || 'Confirm';
            buttonEl.className = opts.confirmClass || 'btn btn-danger';
            buttonEl.disabled = false;
        }
        if (alertEl) {
            alertEl.classList.add('d-none');
            alertEl.textContent = '';
        }
        if (statusEl) {
            statusEl.className = 'modal-status bg-' + (opts.status || 'danger');
        }
    },

    showAlert(messageOrErrors) {
        const alertEl = this.el()?.querySelector('#tablar-kit-confirm-modal-alert');
        if (!alertEl) return;

        let text;
        if (Array.isArray(messageOrErrors)) {
            text = messageOrErrors.join(' ');
        } else if (typeof messageOrErrors === 'object' && messageOrErrors !== null) {
            text = Object.values(messageOrErrors).flat().join(' ');
        } else {
            text = String(messageOrErrors);
        }

        alertEl.textContent = text;
        alertEl.classList.remove('d-none');
    },

    async request(opts) {
        const headers = {
            'X-CSRF-TOKEN': this.csrf(),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };
        let body;
        if (opts.method !== 'GET' && opts.payload) {
            headers['Content-Type'] = 'application/json';
            body = JSON.stringify(opts.payload);
        }

        const res = await fetch(opts.url, {
            method: opts.method || 'POST',
            headers,
            body,
            credentials: 'same-origin',
        });

        const text = await res.text();
        let json = null;
        try { json = text ? JSON.parse(text) : null; } catch (_) { /* non-JSON body */ }

        return { ok: res.ok, status: res.status, json };
    },

    runOutcome(json, opts) {
        const message = (json && typeof json.message === 'string') ? json.message : null;

        if (typeof opts.onSuccess === 'function') {
            if (message) this.toast('success', message);
            opts.onSuccess(json);
            return;
        }
        if (opts.redirect) {
            if (message) this.persistFlash('success', message);
            window.location.assign(opts.redirect);
            return;
        }
        if (opts.event) {
            if (message) this.toast('success', message);
            document.dispatchEvent(new CustomEvent(opts.event, { detail: json }));
            return;
        }
        if (opts.reload !== false) {
            if (message) this.persistFlash('success', message);
            window.location.reload();
        } else if (message) {
            this.toast('success', message);
        }
    },

    persistFlash(type, message) {
        try {
            sessionStorage.setItem('tablar-kit:flash', JSON.stringify({ type, message }));
        } catch (_) { /* storage disabled — non-fatal */ }
    },

    consumeFlash() {
        try {
            const raw = sessionStorage.getItem('tablar-kit:flash');
            if (!raw) return;
            sessionStorage.removeItem('tablar-kit:flash');
            const data = JSON.parse(raw);
            if (data && data.message) {
                this.toast(data.type || 'success', data.message);
            }
        } catch (_) { /* corrupt storage — ignore */ }
    },

    toastEnabled() {
        const el = this.el();
        if (!el) return true;
        const flag = el.dataset.toastEnabled;
        return flag !== '0' && flag !== 'false';
    },

    toast(type, message) {
        if (!this.toastEnabled()) return;
        const containerId = 'tablar-kit-toast-container';
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1080';
            document.body.appendChild(container);
        }

        const variant = ({ success: 'success', danger: 'danger', warning: 'warning', info: 'info' })[type] || 'success';

        const toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-bg-' + variant + ' border-0';
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');

        const flex = document.createElement('div');
        flex.className = 'd-flex';

        const body = document.createElement('div');
        body.className = 'toast-body';
        body.textContent = message;

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'btn-close btn-close-white me-2 m-auto';
        close.setAttribute('data-bs-dismiss', 'toast');
        close.setAttribute('aria-label', 'Close');

        flex.appendChild(body);
        flex.appendChild(close);
        toastEl.appendChild(flex);
        container.appendChild(toastEl);

        const ToastCtor = (typeof bootstrap !== 'undefined' && bootstrap.Toast) ? bootstrap.Toast : BsToast;
        const inst = ToastCtor.getOrCreateInstance(toastEl, { delay: 4000 });
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove(), { once: true });
        inst.show();
    },

    open(opts) {
        const inst = this.instance();
        if (!inst) {
            return Promise.reject(new Error('Confirm modal mount missing.'));
        }

        this.fill(opts);
        document.dispatchEvent(new CustomEvent('tablar-kit:confirm:open', {
            detail: { options: opts },
        }));

        return new Promise((resolve, reject) => {
            const el = this.el();
            const confirmBtn = el.querySelector('#tablar-kit-confirm-modal-button');
            let settled = false;

            const onClick = async () => {
                if (settled) return;
                confirmBtn.disabled = true;
                try {
                    const result = await this.request(opts);

                    if (!result.ok) {
                        if (result.status === 422 && result.json?.errors) {
                            this.showAlert(result.json.errors);
                        } else {
                            this.showAlert(result.json?.message || 'Request failed.');
                        }
                        document.dispatchEvent(new CustomEvent('tablar-kit:confirm:error', {
                            detail: { status: result.status, body: result.json, options: opts },
                        }));
                        confirmBtn.disabled = false;
                        return;
                    }

                    settled = true;
                    confirmBtn.removeEventListener('click', onClick);
                    inst.hide();
                    document.dispatchEvent(new CustomEvent('tablar-kit:confirm:success', {
                        detail: { response: result.json, options: opts },
                    }));
                    this.runOutcome(result.json, opts);
                    resolve(result.json);
                } catch (err) {
                    console.error('[tablar-kit] Confirm request failed:', err);
                    this.showAlert(err.message || 'Request failed.');
                    confirmBtn.disabled = false;
                    reject(err);
                }
            };

            const onHidden = () => {
                if (settled) return;
                settled = true;
                confirmBtn.removeEventListener('click', onClick);
                el.removeEventListener('hidden.bs.modal', onHidden);
                resolve(null);
            };

            confirmBtn.addEventListener('click', onClick);
            el.addEventListener('hidden.bs.modal', onHidden, { once: true });

            inst.show();
        });
    },

    readDataAttrs(el) {
        return {
            url: el.dataset.confirmUrl,
            method: (el.dataset.confirmMethod || 'POST').toUpperCase(),
            title: el.dataset.confirmTitle,
            message: el.dataset.confirmMessage,
            button: el.dataset.confirmButton,
            status: el.dataset.confirmStatus,
            event: el.dataset.confirmEvent,
            redirect: el.dataset.confirmRedirect,
            reload: !el.hasAttribute('data-confirm-no-reload'),
            confirmClass: el.dataset.confirmClass,
        };
    },

    init() {
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-confirm]');
            if (!trigger || !trigger.dataset.confirmUrl) return;
            e.preventDefault();
            this.open(this.readDataAttrs(trigger));
        });

        // Surface any flash message stored before a redirect/reload
        // (e.g. server's `response()->json(['message' => ...])` after delete).
        this.consumeFlash();

        // Warn if a consumer accidentally mounts the modal twice — JS targets
        // the first instance via getElementById; the second is dead weight.
        if (document.querySelectorAll('#tablar-kit-confirm-modal').length > 1) {
            console.warn('[tablar-kit] Multiple <x-confirm-modal /> mounts detected — only the first is used.');
        }
    },
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => TablarConfirmModal.init());
} else {
    TablarConfirmModal.init();
}

window.tablarConfirm = (opts) => TablarConfirmModal.open(opts);
