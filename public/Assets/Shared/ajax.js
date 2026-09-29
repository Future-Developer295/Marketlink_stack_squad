/*
 * MarketLink shared AJAX layer (Website + Dashboard).
 *
 * Forms / buttons
 *   form[data-ajax]                 send with fetch instead of a full page load
 *   data-confirm="Question?"        styled confirm dialog (works with or without data-ajax)
 *   data-ajax-remove=".selector"    remove the closest matching row/card on success
 *   data-ajax-refresh="#a, #b"      re-fetch the current page and swap only those regions
 *   data-ajax-reset                 reset the form after success
 *   data-ajax-follow                go to the redirect URL the server returned
 *   data-ajax-saved="Saved"         (favorites) turn the heart solid + change the label
 *
 * Lists (pagination + search/filter without reload)
 *   <div id="x" data-ajax-list> ... table + pager ... </div>
 *   <form data-ajax-filter="#x" method="GET" action="...">   search / filter form
 *   Links inside .pagination / .ml-pagination / [data-ajax-nav] load via AJAX.
 *
 * Every feature falls back to a normal page load when JavaScript is off.
 */
(() => {
    'use strict';
    if (window.MLAjax) return;

    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const sleep = ms => new Promise(r => setTimeout(r, ms));
    const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const csrf = () => $('meta[name="csrf-token"]')?.content || '';

    /* ---------- Toast ---------- */
    function toast(message, type = 'success') {
        if (!message) return;
        let box = $('#ml-toasts');
        if (!box) {
            box = document.createElement('div');
            box.id = 'ml-toasts';
            box.setAttribute('aria-live', 'polite');
            document.body.appendChild(box);
        }
        const el = document.createElement('div');
        el.className = 'ml-toast ml-toast-' + type;
        el.setAttribute('role', type === 'error' ? 'alert' : 'status');
        el.textContent = message; // textContent: never inject server text as HTML
        box.appendChild(el);
        requestAnimationFrame(() => el.classList.add('is-in'));
        setTimeout(() => { el.classList.remove('is-in'); setTimeout(() => el.remove(), 250); }, type === 'error' ? 6000 : 3500);
    }

    /* ---------- Confirm dialog ---------- */
    function confirmDialog(message) {
        return new Promise(resolve => {
            const overlay = document.createElement('div');
            overlay.className = 'ml-confirm';
            overlay.innerHTML = '<div class="ml-confirm-box" role="alertdialog" aria-modal="true" aria-labelledby="ml-confirm-msg">' +
                '<p id="ml-confirm-msg"></p><div class="ml-confirm-actions">' +
                '<button type="button" data-no>Cancel</button><button type="button" data-yes>Yes, continue</button></div></div>';
            $('#ml-confirm-msg', overlay).textContent = message;
            const previous = document.activeElement;
            const onKey = e => { if (e.key === 'Escape') done(false); };
            const done = value => {
                document.removeEventListener('keydown', onKey);
                overlay.remove();
                previous?.focus?.();
                resolve(value);
            };
            overlay.addEventListener('click', e => { if (e.target === overlay) done(false); });
            $('[data-no]', overlay).addEventListener('click', () => done(false));
            $('[data-yes]', overlay).addEventListener('click', () => done(true));
            document.addEventListener('keydown', onKey);
            document.body.appendChild(overlay);
            $('[data-yes]', overlay).focus();
        });
    }

    /* ---------- Helpers ---------- */
    const firstError = d => (d && d.errors ? Object.values(d.errors).flat()[0] : null);

    function errorFor(status, data) {
        if (status === 401) return 'Please log in again to continue.';
        if (status === 419) return 'Your session expired. Please refresh the page and try again.';
        if (status === 403) return 'You are not allowed to do that.';
        if (status === 404) return 'This item no longer exists. Please refresh the page.';
        if (status === 429) return 'Too many requests. Please wait a moment and try again.';
        if (status >= 500) return 'Something went wrong on our side. Please try again.';
        return firstError(data) || (data && data.message) || 'Could not complete that action.';
    }

    function setBusy(buttons, busy) {
        buttons.forEach(btn => {
            if (busy && !btn.disabled) { btn.dataset.mlBusy = '1'; btn.disabled = true; btn.classList.add('is-loading'); }
            if (!busy && btn.dataset.mlBusy) { delete btn.dataset.mlBusy; btn.disabled = false; btn.classList.remove('is-loading'); }
        });
    }

    function clearFieldErrors(form) { $$('.ml-field-error', form).forEach(el => el.remove()); }

    function showFieldErrors(form, errors) {
        if (!errors) return;
        Object.entries(errors).forEach(([key, messages]) => {
            const bracket = key.replace(/\.(\w+)/g, '[$1]');
            const field = form.querySelector('[name="' + CSS.escape(bracket) + '"]') || form.querySelector('[name="' + CSS.escape(key) + '"]');
            if (!field) return;
            const note = document.createElement('div');
            note.className = 'ml-field-error';
            note.textContent = [].concat(messages)[0];
            field.insertAdjacentElement('afterend', note);
        });
    }

    function updateCart(count) {
        $$('[data-cart-count], .ml-cart-badge').forEach(el => { el.textContent = count; });
    }

    async function fetchDoc(url, signal) {
        const res = await fetch(url, {
            signal, credentials: 'same-origin', cache: 'no-store',
            headers: { Accept: 'text/html', 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (res.redirected && /\/login/.test(res.url)) { location.href = res.url; throw new Error('redirect'); }
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return new DOMParser().parseFromString(await res.text(), 'text/html');
    }

    /* ---------- Region refresh ---------- */
    async function refreshRegions(selectors, url = location.href) {
        try {
            const doc = await fetchDoc(url);
            selectors.split(',').map(s => s.trim()).filter(Boolean).forEach(sel => {
                const current = $(sel), next = $(sel, doc);
                if (current && next) current.replaceWith(next);
            });
        } catch (e) {
            if (e.message !== 'redirect') toast('Saved. Refresh the page to see the latest details.', 'warning');
        }
    }

    /* ---------- AJAX lists (pagination + filters) ---------- */
    const controllers = new WeakMap();

    async function loadList(list, url, { push = true, scroll = true } = {}) {
        if (!list) { location.href = url; return; }
        controllers.get(list)?.abort();
        const ctrl = new AbortController();
        controllers.set(list, ctrl);
        list.setAttribute('aria-busy', 'true');
        list.classList.add('ml-loading');
        try {
            const doc = await fetchDoc(url, ctrl.signal);
            const next = list.id ? doc.getElementById(list.id) : $('[data-ajax-list]', doc);
            if (!next) { location.href = url; return; }
            list.innerHTML = next.innerHTML;
            if (push && url !== location.href) history.pushState({ mlList: true }, '', url);
            if (scroll) {
                const top = list.getBoundingClientRect().top;
                if (top < 0 || top > innerHeight * 0.6) list.scrollIntoView({ block: 'start', behavior: reduced ? 'auto' : 'smooth' });
            }
            list.dispatchEvent(new CustomEvent('ml:list:loaded', { bubbles: true }));
        } catch (e) {
            if (e.name !== 'AbortError' && e.message !== 'redirect') toast('Could not load this list. Please try again.', 'error');
        } finally {
            list.removeAttribute('aria-busy');
            list.classList.remove('ml-loading');
        }
    }

    function filterUrl(form) {
        const url = new URL(form.getAttribute('action') || location.pathname, location.origin);
        const params = new URLSearchParams();
        new FormData(form).forEach((value, key) => {
            if (key !== '_token' && typeof value === 'string' && value.trim() !== '') params.append(key, value.trim());
        });
        url.search = params.toString();
        return url.toString();
    }

    function syncFilters() {
        const params = new URLSearchParams(location.search);
        $$('form[data-ajax-filter]').forEach(form => {
            $$('[name]', form).forEach(field => {
                if (field.name === '_token' || field.type === 'hidden' && !params.has(field.name)) return;
                if (field.type === 'checkbox' || field.type === 'radio') field.checked = params.get(field.name) === field.value;
                else field.value = params.get(field.name) ?? '';
            });
        });
    }

    /* ---------- Form success handling ---------- */
    function markSaved(form) {
        const btn = $('button', form);
        if (!btn) return;
        const icon = $('i', btn);
        if (icon) { icon.classList.remove('fa-regular'); icon.classList.add('fa-solid'); }
        btn.classList.add('is-saved');
        const label = form.dataset.ajaxSaved;
        const text = [...btn.childNodes].reverse().find(n => n.nodeType === 3);
        if (text) text.textContent = ' ' + label;
        btn.setAttribute('aria-label', label);
    }

    async function removeTarget(form, selector) {
        const el = form.closest(selector);
        if (!el) return;
        const list = el.closest('[data-ajax-list]');
        el.style.transition = 'opacity .2s, transform .2s';
        el.style.opacity = '0';
        el.style.transform = 'scale(.98)';
        await sleep(reduced ? 0 : 200);
        el.remove();
        if (!list) return;
        // Re-fetch the list so ids, counts, the pager and the empty state stay correct.
        const url = new URL(location.href);
        const page = parseInt(url.searchParams.get('page') || '1', 10);
        if (list.querySelectorAll(selector).length === 0 && page > 1) {
            url.searchParams.set('page', String(page - 1));
            await loadList(list, url.toString(), { scroll: false });
        } else {
            await loadList(list, location.href, { push: false, scroll: false });
        }
    }

    async function onSuccess(form, data, submitter) {
        if (data.message) toast(data.message, data.type === 'warning' ? 'warning' : 'success');
        if (typeof data.cart_count === 'number') updateCart(data.cart_count);
        form.dispatchEvent(new CustomEvent('ml:ajax:success', { bubbles: true, detail: data }));
        const d = form.dataset;
        if (d.ajaxFollow !== undefined && data.redirect) { location.href = data.redirect; return; }
        if (d.ajaxSaved) markSaved(form);
        if (d.ajaxReset !== undefined) { form.reset(); clearFieldErrors(form); }
        if (d.ajaxRemove) await removeTarget(form, d.ajaxRemove);
        if (d.ajaxRefresh) await refreshRegions(d.ajaxRefresh);
    }

    async function sendForm(form, submitter) {
        clearFieldErrors(form);
        const buttons = $$('button[type=submit], button:not([type]), input[type=submit]', form);
        setBusy(buttons, true);
        try {
            const body = new FormData(form);
            if (submitter && submitter.name) body.append(submitter.name, submitter.value);
            const res = await fetch(form.getAttribute('action') || location.href, {
                method: 'POST', body, credentials: 'same-origin',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf() },
            });
            const data = res.headers.get('content-type')?.includes('json') ? await res.json() : null;
            if (res.status === 401) {
                toast(errorFor(401), 'error');
                setTimeout(() => { location.href = '/login'; }, 1200);
                return;
            }
            if (!res.ok || (data && data.success === false)) {
                showFieldErrors(form, data && data.errors);
                toast(errorFor(res.status, data), 'error');
                form.dispatchEvent(new CustomEvent('ml:ajax:error', { bubbles: true, detail: { status: res.status, data } }));
                return;
            }
            await onSuccess(form, data || {}, submitter);
        } catch (e) {
            toast('Connection problem. Please check your internet and try again.', 'error');
        } finally {
            setBusy(buttons, false);
        }
    }

    /* ---------- Event delegation (survives DOM swaps) ---------- */
    document.addEventListener('submit', async event => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;

        if (form.matches('form[data-ajax-filter]')) {
            event.preventDefault();
            loadList($(form.dataset.ajaxFilter), filterUrl(form));
            return;
        }

        const submitter = event.submitter || null;
        const message = (submitter && submitter.dataset.confirm) || form.dataset.confirm;
        const isAjax = form.hasAttribute('data-ajax');
        if (!isAjax && !message) return;

        event.preventDefault();
        if (form.dataset.mlSending) return;
        if (message && !(await confirmDialog(message))) return;

        if (isAjax) {
            form.dataset.mlSending = '1';
            await sendForm(form, submitter);
            delete form.dataset.mlSending;
        } else {
            HTMLFormElement.prototype.submit.call(form); // plain form that only wanted a confirm
        }
    });

    document.addEventListener('click', event => {
        if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        const link = event.target.closest('a[href]');
        if (!link) return;
        const list = link.closest('[data-ajax-list]');
        if (!list || !link.closest('.pagination, .ml-pagination, [data-ajax-nav]')) return;
        if (link.classList.contains('disabled') || link.getAttribute('aria-disabled') === 'true' || link.target === '_blank') return;
        const href = link.href;
        if (!href || href === location.href + '#') return;
        event.preventDefault();
        loadList(list, href);
    });

    // Live search (debounced) and instant filters
    const timers = new WeakMap();
    document.addEventListener('input', event => {
        const form = event.target.closest?.('form[data-ajax-filter]');
        if (!form || !event.target.matches('input:not([type=checkbox]):not([type=radio]):not([type=hidden])')) return;
        clearTimeout(timers.get(form));
        timers.set(form, setTimeout(() => form.requestSubmit(), 350));
    });
    document.addEventListener('change', event => {
        const form = event.target.closest?.('form[data-ajax-filter]');
        if (form && event.target.matches('select, input[type=checkbox], input[type=radio], input[type=date]')) form.requestSubmit();
    });

    addEventListener('popstate', () => {
        const lists = $$('[data-ajax-list]');
        if (!lists.length) return;
        lists.forEach(list => loadList(list, location.href, { push: false, scroll: false }));
        syncFilters();
    });

    window.MLAjax = { toast, confirm: confirmDialog, refresh: refreshRegions, loadList };
})();
