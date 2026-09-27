(() => {
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
    const desktopFilters = window.matchMedia('(min-width: 992px)');
    const syncFilters = () => {
        const sidebar = document.querySelector('details.catalog-sidebar');
        if (sidebar) sidebar.open = desktopFilters.matches;
    };
    syncFilters();
    desktopFilters.addEventListener('change', syncFilters);
    let toastTimer;
    const notify = (message, error = false) => {
        let toast = document.querySelector('.shop-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'shop-toast';
            toast.setAttribute('role', 'status');
            toast.setAttribute('aria-live', 'polite');
            document.body.append(toast);
        }
        toast.textContent = message;
        toast.classList.toggle('error', error);
        toast.classList.add('visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('visible'), 4500);
    };
    if (window.gsap) {
        gsap.matchMedia().add('(prefers-reduced-motion: no-preference)', () => {
            if (document.querySelector('.harvest-hero-copy')) gsap.from('.harvest-hero-copy > *', { y: 24, opacity: 0, stagger: .1, duration: .8, ease: 'power3.out' });
            if (document.querySelector('.harvest-hero-art')) gsap.from('.harvest-hero-art', { opacity: 0, duration: 1.2 });
            if (document.querySelector('.basket-heading')) gsap.from('.basket-heading > *', { y: 18, opacity: 0, stagger: .12, duration: .7 });
            if (document.querySelector('.shop-detail-visual')) gsap.from('.shop-detail-visual', { y: 20, opacity: 0, duration: .7 });
            if (document.querySelector('.journey-banner')) gsap.from('.journey-banner-copy > *, .journey-illustration', { y: 20, opacity: 0, stagger: .08, duration: .7, ease: 'power3.out' });
        });
    }
    if (window.AOS && !reduced.matches) {
        AOS.init({ duration: 650, once: true, offset: 30 });
    }
    if (window.Lenis && !reduced.matches && window.matchMedia('(pointer: fine)').matches) {
        const lenis = new Lenis({ autoRaf: true, anchors: true });
        reduced.addEventListener('change', () => { if (reduced.matches) lenis.destroy(); });
    }
    document.addEventListener('click', event => {
        const carouselButton = event.target.closest('[data-carousel-prev], [data-carousel-next]');
        if (carouselButton) document.querySelector('.category-carousel')?.scrollBy({ left: carouselButton.hasAttribute('data-carousel-next') ? 280 : -280, behavior: reduced.matches ? 'instant' : 'smooth' });
        const button = event.target.closest('[data-quantity] button');
        if (!button || button.disabled) return;
        const wrapper = button.closest('[data-quantity]');
        const input = wrapper.querySelector('input');
        const oldValue = Number(input.value);
        const min = Number(wrapper.dataset.min ?? 1);
        const max = Number(wrapper.dataset.max ?? 999);
        input.value = Math.min(max, Math.max(min, oldValue + (button.hasAttribute('data-decrement') ? -1 : 1)));
        input.dispatchEvent(new Event('change', { bubbles: true }));
        if (wrapper.hasAttribute('data-auto-submit') && oldValue !== Number(input.value)) wrapper.closest('form').requestSubmit();
    });
    document.addEventListener('change', event => {
        if (!event.target.matches('[data-quantity] input')) return;
        const form = event.target.closest('form');
        const subtotal = form.querySelector('[data-unit-price]');
        if (subtotal) subtotal.textContent = 'Rs. ' + (Number(subtotal.dataset.unitPrice) * Number(event.target.value)).toLocaleString('en-PK', { maximumFractionDigits: 2 });
    });
    let busy = false;
    let catalogRequest;
    async function updateCatalog(url) {
        catalogRequest?.abort();
        const controller = new AbortController();
        catalogRequest = controller;
        const collection = document.querySelector('.harvest-collection');
        collection.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(url, { signal: controller.signal, headers: { Accept: 'text/html' } });
            if (!response.ok) throw new Error('Could not load these picks. Please try again.');
            const next = new DOMParser().parseFromString(await response.text(), 'text/html');
            const nextCollection = next.querySelector('.harvest-collection');
            if (!nextCollection) throw new Error('Could not load these picks. Please refresh the page.');
            collection.replaceWith(nextCollection);
            syncFilters();
            const carousel = document.querySelector('.category-carousel');
            const position = carousel.scrollLeft;
            carousel.replaceWith(next.querySelector('.category-carousel'));
            document.querySelector('.category-carousel').scrollLeft = position;
            history.pushState({ catalog: true }, '', url);
            if (window.AOS && !reduced.matches) AOS.refreshHard();
            nextCollection.scrollIntoView({ behavior: reduced.matches ? 'instant' : 'smooth', block: 'start' });
            const heading = nextCollection.querySelector('h2');
            heading.tabIndex = -1;
            heading.focus({ preventScroll: true });
            notify(nextCollection.querySelector('.shop-count').textContent + ' ready to explore.');
        } catch (error) {
            if (error.name !== 'AbortError') notify(error.message, true);
        } finally {
            collection.removeAttribute('aria-busy');
        }
    }
    document.addEventListener('click', event => {
        const link = event.target.closest('.category-chip, .harvest-collection .ml-pagination a');
        if (!link || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.button !== 0) return;
        event.preventDefault();
        if (!link.classList.contains('disabled')) updateCatalog(link.href);
    });
    document.addEventListener('submit', event => {
        if (!event.target.matches('.shop-filters')) return;
        event.preventDefault();
        const url = new URL(event.target.action);
        url.search = new URLSearchParams(new FormData(event.target)).toString();
        updateCatalog(url.toString());
    });
    window.addEventListener('popstate', () => {
        if (document.querySelector('.shop-catalog')) window.location.reload();
    });
    async function submitCart(form) {
        if (busy) return;
        busy = true;
        const controls = [...document.querySelectorAll('[data-cart-form] button')];
        const states = controls.map(button => button.disabled);
        controls.forEach(button => button.disabled = true);
        form.setAttribute('aria-busy', 'true');
        try {
            const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
            if (!response.headers.get('content-type')?.includes('application/json')) throw new Error(response.status === 419 ? 'Your session expired. Refresh the page and try again.' : 'Could not update your basket. Please refresh and try again.');
            const data = await response.json();
            if (!response.ok) throw new Error(Object.values(data.errors || {}).flat()[0] || data.message || 'Could not update your basket. Please try again.');
            document.querySelectorAll('[data-cart-count], .ml-cart-badge').forEach(el => el.textContent = data.count);
            if (document.querySelector('[data-basket-page]')) {
                const page = await fetch(window.location.href, { headers: { Accept: 'text/html' }, cache: 'no-store' });
                if (!page.ok) throw new Error('Basket saved. Refresh the page to see the latest totals.');
                const next = new DOMParser().parseFromString(await page.text(), 'text/html');
                const basket = next.querySelector('[data-basket-page]');
                if (!basket) throw new Error('Basket saved. Refresh the page to see the latest totals.');
                document.querySelector('[data-basket-page]').replaceWith(basket);
            }
            notify(data.message);
            if (window.gsap && !reduced.matches) gsap.fromTo('.shop-dock', { scale: .95 }, { scale: 1, duration: .4, ease: 'back.out(2)' });
        } catch (error) {
            notify(error.message || 'Connection lost. Please check your basket before trying again.', true);
            const quantity = form.querySelector('[data-auto-submit] input');
            if (quantity) quantity.value = quantity.defaultValue;
        } finally {
            controls.forEach((button, index) => button.disabled = states[index]);
            form.removeAttribute('aria-busy');
            busy = false;
        }
    }
    const handleSubmit = event => {
        const form = event.target.closest('[data-cart-form]');
        if (!form) return;
        event.preventDefault();
        submitCart(form);
    };
    if (window.jQuery) jQuery(document).on('submit', '[data-cart-form]', handleSubmit);
    else document.addEventListener('submit', handleSubmit);
})();
