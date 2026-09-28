(() => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (window.gsap) {
        gsap.matchMedia().add('(prefers-reduced-motion: no-preference)', () => {
            gsap.from('.about-hero-copy > *', { y: 24, opacity: 0, stagger: .1, duration: .8, ease: 'power3.out' });
            gsap.from('.about-hero-visual', { y: 30, opacity: 0, duration: 1.1, ease: 'power3.out' });
        });
    }
    const carousel = document.querySelector('[data-review-carousel]');
    if (!carousel) return;
    const track = carousel.querySelector('.about-review-track');
    const slides = [...track.children];
    if (slides.length < 2) return;
    const controls = carousel.querySelector('.about-carousel-controls');
    const pauseButton = carousel.querySelector('[data-review-pause]');
    const dots = [...carousel.querySelectorAll('[data-review-dot]')];
    const status = carousel.querySelector('[data-review-status]');
    let currentIndex = 0;
    let paused = reducedMotion.matches;
    let hovered = false;
    let visible = false;
    let timer;
    let scrollTimer;
    controls.hidden = false;
    carousel.classList.add('is-enhanced');
    const syncPlayback = () => {
        clearInterval(timer);
        pauseButton.setAttribute('aria-label', paused ? 'Play automatic reviews' : 'Pause automatic reviews');
        pauseButton.querySelector('i').className = paused ? 'fa-solid fa-play' : 'fa-solid fa-pause';
        if (!paused && !hovered && visible && !document.hidden) timer = setInterval(() => show(currentIndex + 1), 7000);
    };
    const show = (index, announce = false) => {
        currentIndex = (index + slides.length) % slides.length;
        track.scrollTo({ left: slides[currentIndex].offsetLeft - slides[0].offsetLeft, behavior: reducedMotion.matches ? 'instant' : 'smooth' });
        if (announce) status.textContent = `Review ${currentIndex + 1} of ${slides.length}`;
    };
    const stop = () => { paused = true; syncPlayback(); };
    carousel.querySelector('[data-review-next]').addEventListener('click', () => { stop(); show(currentIndex + 1, true); });
    carousel.querySelector('[data-review-prev]').addEventListener('click', () => { stop(); show(currentIndex - 1, true); });
    dots.forEach((dot, index) => dot.addEventListener('click', () => { stop(); show(index, true); }));
    pauseButton.addEventListener('click', () => { paused = !paused; syncPlayback(); });
    carousel.addEventListener('focusin', event => { if (event.target !== pauseButton) stop(); });
    carousel.addEventListener('mouseenter', () => { hovered = true; syncPlayback(); });
    carousel.addEventListener('mouseleave', () => { hovered = false; syncPlayback(); });
    track.addEventListener('pointerdown', stop, { passive: true });
    track.addEventListener('keydown', event => {
        if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
        event.preventDefault();
        stop();
        show(currentIndex + (event.key === 'ArrowRight' ? 1 : -1), true);
    });
    track.addEventListener('scroll', () => {
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(() => {
            currentIndex = Math.max(0, Math.min(slides.length - 1, Math.round(track.scrollLeft / track.clientWidth)));
            dots.forEach((dot, index) => {
                if (index === currentIndex) dot.setAttribute('aria-current', 'true');
                else dot.removeAttribute('aria-current');
            });
        }, 100);
    }, { passive: true });
    new IntersectionObserver(entries => { visible = entries[0].isIntersecting; syncPlayback(); }, { threshold: .25 }).observe(carousel);
    new ResizeObserver(() => track.scrollTo({ left: currentIndex * track.clientWidth, behavior: 'instant' })).observe(track);
    document.addEventListener('visibilitychange', syncPlayback);
    reducedMotion.addEventListener('change', () => { if (reducedMotion.matches) stop(); });
    syncPlayback();
})();
