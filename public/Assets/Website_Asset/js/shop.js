if (!window.marketLinkShopJSLoaded) {
    window.marketLinkShopJSLoaded = true;

    (() => {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
        const desktopFilters = window.matchMedia('(min-width: 992px)');

        const syncFilters = () => {
            const sidebar = document.querySelector('details.catalog-sidebar');

            if (sidebar) {
                sidebar.open = desktopFilters.matches;
            }
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

            toastTimer = setTimeout(() => {
                toast.classList.remove('visible');
            }, 4500);
        };

        if (window.gsap) {
            gsap.matchMedia().add(
                '(prefers-reduced-motion: no-preference)',
                () => {

                    if (document.querySelector('.harvest-hero-copy')) {
                        gsap.from('.harvest-hero-copy > *', {
                            y: 24,
                            opacity: 0,
                            stagger: .1,
                            duration: .8,
                            ease: 'power3.out'
                        });
                    }

                    if (document.querySelector('.harvest-hero-art')) {
                        gsap.from('.harvest-hero-art', {
                            opacity: 0,
                            duration: 1.2
                        });
                    }

                    if (document.querySelector('.basket-heading')) {
                        gsap.from('.basket-heading > *', {
                            y: 18,
                            opacity: 0,
                            stagger: .12,
                            duration: .7
                        });
                    }

                    if (document.querySelector('.shop-detail-visual')) {
                        gsap.from('.shop-detail-visual', {
                            y: 20,
                            opacity: 0,
                            duration: .7
                        });
                    }

                    if (document.querySelector('.journey-banner')) {
                        gsap.from(
                            '.journey-banner-copy > *, .journey-illustration',
                            {
                                y: 20,
                                opacity: 0,
                                stagger: .08,
                                duration: .7,
                                ease: 'power3.out'
                            }
                        );
                    }
                }
            );
        }

        if (window.AOS && !reduced.matches) {
            AOS.init({
                duration: 650,
                once: true,
                offset: 30
            });
        }

        if (
            window.Lenis &&
            !reduced.matches &&
            window.matchMedia('(pointer: fine)').matches
        ) {
            const lenis = new Lenis({
                autoRaf: true,
                anchors: true
            });

            reduced.addEventListener('change', () => {
                if (reduced.matches) {
                    lenis.destroy();
                }
            });
        }

        document.addEventListener('click', event => {

            const carouselButton = event.target.closest(
                '[data-carousel-prev], [data-carousel-next]'
            );

            if (carouselButton) {
                document
                    .querySelector('.category-carousel')
                    ?.scrollBy({
                        left: carouselButton.hasAttribute('data-carousel-next')
                            ? 280
                            : -280,
                        behavior: reduced.matches
                            ? 'instant'
                            : 'smooth'
                    });

                return;
            }

            const button = event.target.closest(
                '[data-quantity] button'
            );

            if (!button || button.disabled) {
                return;
            }

            const wrapper = button.closest('[data-quantity]');
            const input = wrapper?.querySelector('input');

            if (!wrapper || !input) {
                return;
            }

            const min = Number(wrapper.dataset.min ?? 1);
            const max = Number(wrapper.dataset.max ?? 999);
            const current = Number(input.value) || min;

            let nextValue = current;

            if (button.hasAttribute('data-decrement')) {
                nextValue = current - 1;
            } else if (button.hasAttribute('data-increment')) {
                nextValue = current + 1;
            }

            nextValue = Math.min(
                max,
                Math.max(min, nextValue)
            );

            input.value = nextValue;

            input.dispatchEvent(
                new Event('input', {
                    bubbles: true
                })
            );

            input.dispatchEvent(
                new Event('change', {
                    bubbles: true
                })
            );
        });

        document.addEventListener('input', event => {

            if (!event.target.matches('[data-quantity] input')) {
                return;
            }

            const input = event.target;
            const wrapper = input.closest('[data-quantity]');

            if (!wrapper) {
                return;
            }

            const min = Number(wrapper.dataset.min ?? 1);
            const max = Number(wrapper.dataset.max ?? 999);

            let value = parseInt(input.value, 10);

            if (Number.isNaN(value)) {
                return;
            }

            value = Math.min(
                max,
                Math.max(min, value)
            );

            input.value = value;

            const form = input.closest('form');
            const subtotal = form?.querySelector('[data-unit-price]');

            if (subtotal) {
                subtotal.textContent =
                    'Rs. ' +
                    (
                        Number(subtotal.dataset.unitPrice) * value
                    ).toLocaleString(
                        'en-PK',
                        {
                            maximumFractionDigits: 2
                        }
                    );
            }
        });

        document.addEventListener('change', event => {

            if (!event.target.matches('[data-quantity] input')) {
                return;
            }

            const input = event.target;
            const wrapper = input.closest('[data-quantity]');

            if (!wrapper) {
                return;
            }

            const min = Number(wrapper.dataset.min ?? 1);
            const max = Number(wrapper.dataset.max ?? 999);

            let value = parseInt(input.value, 10);

            if (Number.isNaN(value)) {
                value = min;
            }

            value = Math.min(
                max,
                Math.max(min, value)
            );

            input.value = value;

            const form = input.closest('form');
            const subtotal = form?.querySelector('[data-unit-price]');

            if (subtotal) {
                subtotal.textContent =
                    'Rs. ' +
                    (
                        Number(subtotal.dataset.unitPrice) * value
                    ).toLocaleString(
                        'en-PK',
                        {
                            maximumFractionDigits: 2
                        }
                    );
            }
        });

        let busy = false;
        let catalogRequest;

        async function updateCatalog(url) {

            catalogRequest?.abort();

            const controller = new AbortController();

            catalogRequest = controller;

            const collection =
                document.querySelector('.harvest-collection');

            if (!collection) {
                return;
            }

            collection.setAttribute(
                'aria-busy',
                'true'
            );

            try {

                const response = await fetch(
                    url,
                    {
                        signal: controller.signal,
                        headers: {
                            Accept: 'text/html'
                        }
                    }
                );

                if (!response.ok) {
                    throw new Error(
                        'Could not load these picks. Please try again.'
                    );
                }

                const next =
                    new DOMParser().parseFromString(
                        await response.text(),
                        'text/html'
                    );

                const nextCollection =
                    next.querySelector('.harvest-collection');

                if (!nextCollection) {
                    throw new Error(
                        'Could not load these picks. Please refresh the page.'
                    );
                }

                collection.replaceWith(nextCollection);

                syncFilters();

                const carousel =
                    document.querySelector('.category-carousel');

                const nextCarousel =
                    next.querySelector('.category-carousel');

                if (carousel && nextCarousel) {

                    const position = carousel.scrollLeft;

                    carousel.replaceWith(nextCarousel);

                    const currentCarousel =
                        document.querySelector('.category-carousel');

                    if (currentCarousel) {
                        currentCarousel.scrollLeft = position;
                    }
                }

                history.pushState(
                    {
                        catalog: true
                    },
                    '',
                    url
                );

                if (window.AOS && !reduced.matches) {
                    AOS.refreshHard();
                }

                nextCollection.scrollIntoView({
                    behavior: reduced.matches
                        ? 'instant'
                        : 'smooth',
                    block: 'start'
                });

                const heading =
                    nextCollection.querySelector('h2');

                if (heading) {
                    heading.tabIndex = -1;

                    heading.focus({
                        preventScroll: true
                    });
                }

                const count =
                    nextCollection.querySelector('.shop-count');

                notify(
                    count
                        ? count.textContent + ' ready to explore.'
                        : 'Products updated.'
                );

            } catch (error) {

                if (error.name !== 'AbortError') {
                    notify(
                        error.message,
                        true
                    );
                }

            } finally {

                const currentCollection =
                    document.querySelector('.harvest-collection');

                if (currentCollection) {
                    currentCollection.removeAttribute('aria-busy');
                }
            }
        }

        document.addEventListener('click', event => {

            const link = event.target.closest(
                '.category-chip, .harvest-collection .ml-pagination a'
            );

            if (
                !link ||
                event.ctrlKey ||
                event.metaKey ||
                event.shiftKey ||
                event.altKey ||
                event.button !== 0
            ) {
                return;
            }

            event.preventDefault();

            if (!link.classList.contains('disabled')) {
                updateCatalog(link.href);
            }
        });

        document.addEventListener('submit', event => {

            if (!event.target.matches('.shop-filters')) {
                return;
            }

            event.preventDefault();

            const url = new URL(
                event.target.action
            );

            url.search =
                new URLSearchParams(
                    new FormData(event.target)
                ).toString();

            updateCatalog(
                url.toString()
            );
        });

        window.addEventListener('popstate', () => {

            if (document.querySelector('.shop-catalog')) {
                window.location.reload();
            }
        });

        async function submitCart(form) {

            if (busy) {
                return;
            }

            const quantityInput =
                form.querySelector(
                    '[data-quantity] input[name="quantity"]'
                );

            const quantityWrapper =
                form.querySelector('[data-quantity]');

            if (quantityInput && quantityWrapper) {

                const min =
                    Number(quantityWrapper.dataset.min ?? 1);

                const max =
                    Number(quantityWrapper.dataset.max ?? 999);

                let quantity =
                    parseInt(quantityInput.value, 10);

                if (Number.isNaN(quantity)) {
                    quantity = min;
                }

                quantity = Math.min(
                    max,
                    Math.max(min, quantity)
                );

                quantityInput.value = quantity;
            }

            busy = true;

            const controls = [
                ...document.querySelectorAll(
                    '[data-cart-form] button'
                )
            ];

            const states =
                controls.map(
                    button => button.disabled
                );

            controls.forEach(button => {
                button.disabled = true;
            });

            form.setAttribute(
                'aria-busy',
                'true'
            );

            try {

                const response =
                    await fetch(
                        form.action,
                        {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        }
                    );

                const contentType =
                    response.headers.get('content-type') || '';

                if (!contentType.includes('application/json')) {

                    throw new Error(
                        response.status === 419
                            ? 'Your session expired. Refresh the page and try again.'
                            : 'Could not update your basket. Please refresh and try again.'
                    );
                }

                const data =
                    await response.json();

                if (!response.ok || !data.success) {

                    throw new Error(
                        Object.values(
                            data.errors || {}
                        ).flat()[0]
                        ||
                        data.message
                        ||
                        'Could not update your basket. Please try again.'
                    );
                }

                document
                    .querySelectorAll(
                        '[data-cart-count], .ml-cart-badge'
                    )
                    .forEach(el => {
                        el.textContent = data.count ?? 0;
                    });

                notify(
                    data.message ||
                    'Product added to your basket.'
                );

                if (
                    window.gsap &&
                    !reduced.matches &&
                    document.querySelector('.shop-dock')
                ) {
                    gsap.fromTo(
                        '.shop-dock',
                        {
                            scale: .95
                        },
                        {
                            scale: 1,
                            duration: .4,
                            ease: 'back.out(2)'
                        }
                    );
                }

            } catch (error) {

                notify(
                    error.message ||
                    'Connection lost. Please check your basket before trying again.',
                    true
                );

            } finally {

                controls.forEach(
                    (button, index) => {
                        button.disabled =
                            states[index];
                    }
                );

                form.removeAttribute(
                    'aria-busy'
                );

                busy = false;
            }
        }

        const handleSubmit = event => {

            const form =
                event.target.closest(
                    '[data-cart-form]'
                );

            if (!form) {
                return;
            }

            event.preventDefault();

            submitCart(form);
        };

        if (window.jQuery) {

            jQuery(document).on(
                'submit',
                '[data-cart-form]',
                handleSubmit
            );

        } else {

            document.addEventListener(
                'submit',
                handleSubmit
            );
        }

    })();
}