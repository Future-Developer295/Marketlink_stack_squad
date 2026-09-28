(() => {
    const sidebar = document.querySelector('#customer-sidebar');
    const opener = document.querySelector('[data-sidebar-open]');
    const backdrop = document.querySelector('.customer-sidebar-backdrop');
    const mobile = matchMedia('(max-width: 991px)');
    function setMenu(open, restore = true) {
        if (!sidebar) return;
        sidebar.classList.toggle('is-open', open);
        sidebar.inert = mobile.matches && !open;
        opener?.setAttribute('aria-expanded', String(open));
        backdrop.hidden = !open;
        document.body.style.overflow = open ? 'hidden' : '';
        document.querySelector('.customer-content').inert = open;
        if (open) {
            sidebar.setAttribute('role', 'dialog'); sidebar.setAttribute('aria-modal', 'true');
            sidebar.querySelector('[data-sidebar-close]').focus();
        } else {
            sidebar.removeAttribute('role'); sidebar.removeAttribute('aria-modal');
            if (restore) opener?.focus();
        }
    }
    opener?.addEventListener('click', () => setMenu(true));
    document.querySelectorAll('[data-sidebar-close]').forEach(button => button.addEventListener('click', () => setMenu(false)));
    mobile.addEventListener('change', () => setMenu(false, false));
    setMenu(false, false);
    document.addEventListener('keydown', event => {
        if (!sidebar?.classList.contains('is-open')) return;
        if (event.key === 'Escape') setMenu(false);
        if (event.key === 'Tab') {
            const focusable = [...sidebar.querySelectorAll('a,button')].filter(el => el.offsetParent !== null);
            const first = focusable[0], last = focusable.at(-1);
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        }
    });
    const dialog = document.querySelector('#cancel-order-dialog');
    let pendingForm;
    document.querySelectorAll('[data-cancel-order]').forEach(form => form.addEventListener('submit', event => {
        if (!dialog) return;
        event.preventDefault(); pendingForm = form; dialog.returnValue = ''; dialog.showModal();
    }));
    dialog?.addEventListener('close', () => {
        if (dialog.returnValue === 'cancel' && pendingForm) {
            pendingForm.querySelector('button').disabled = true;
            HTMLFormElement.prototype.submit.call(pendingForm);
        }
        pendingForm = null;
    });
    document.querySelectorAll('[data-carousel-target]').forEach(button => button.addEventListener('click', () => {
        document.getElementById(button.dataset.carouselTarget)?.scrollBy({left: Number(button.dataset.carouselDirection || 1) * 300, behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth'});
    }));
    const select = document.querySelector('#dashboard-map-place');
    select?.addEventListener('change', () => {
        const place = JSON.parse(document.querySelector('#dashboard-map-data').textContent)[select.value];
        if (!place) return;
        const lat = Number(place.latitude), lng = Number(place.longitude);
        const params = new URLSearchParams({bbox: [lng-.018,lat-.012,lng+.018,lat+.012].join(','),layer:'mapnik',marker: `${lat},${lng}`});
        document.querySelector('#dashboard-map').src = 'https://www.openstreetmap.org/export/embed.html?' + params;
        const name = document.querySelector('#map-place-name'); name.textContent = place.name; name.href = place.url;
        document.querySelector('#map-place-address').textContent = `${place.address}, ${place.city}`;
        document.querySelector('#map-directions').href = 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(`${lat},${lng}`);
    });
})();
