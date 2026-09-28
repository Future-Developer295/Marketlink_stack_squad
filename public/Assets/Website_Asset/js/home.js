(() => {
    const reduced = matchMedia('(prefers-reduced-motion: reduce)');
    const motionButton = document.querySelector('[data-home-motion]');

    let motionPaused = reduced.matches;
    let heroTimeline;
    let floating;
    let scrollMotion;
    let heroStarted = false;

    const syncMotion = () => {
        [heroTimeline, floating, scrollMotion].forEach(animation => {
            animation?.paused(motionPaused);
        });

        if (scrollMotion?.scrollTrigger) {
            if (motionPaused) {
                scrollMotion.scrollTrigger.disable(false);
            } else {
                scrollMotion.scrollTrigger.enable();
            }
        }

        if (motionButton) {
            motionButton.setAttribute(
                'aria-pressed',
                String(motionPaused)
            );

            motionButton.innerHTML = `
                <i class="fa-solid fa-${motionPaused ? 'play' : 'pause'}" aria-hidden="true"></i>
                ${motionPaused ? 'Play' : 'Pause'} motion
            `;
        }
    };

    const startHeroMotion = () => {
        if (heroStarted || !window.gsap) {
            return;
        }

        heroStarted = true;

        if (motionButton) {
            motionButton.hidden = false;

            motionButton.addEventListener('click', () => {
                motionPaused = !motionPaused;
                syncMotion();
            });
        }

        gsap.matchMedia().add(
            '(prefers-reduced-motion: no-preference)',
            () => {
                heroTimeline = gsap.timeline()
                    .from('.home-hero-heading > *', {
                        y: 25,
                        opacity: 0,
                        stagger: .1,
                        duration: .7
                    })
                    .from(
                        '.home-basket',
                        {
                            scale: .65,
                            y: 90,
                            rotation: -12,
                            opacity: 0,
                            duration: 1.35,
                            ease: 'back.out(1.2)'
                        },
                        .15
                    )
                    .from(
                        '.home-ingredient',
                        {
                            scale: .25,
                            x: (_, el) =>
                                el.classList.contains('ingredient-one')
                                    ? -170
                                    : 100,
                            y: 100,
                            opacity: 0,
                            stagger: .08,
                            duration: 1.25,
                            ease: 'power3.out'
                        },
                        .4
                    )
                    .from(
                        '.home-giant-word',
                        {
                            y: 35,
                            opacity: 0,
                            duration: 1
                        },
                        .15
                    );

                floating = gsap.to('.home-ingredient', {
                    y: '+=18',
                    rotation: '+=9',
                    duration: 3.3,
                    stagger: .3,
                    yoyo: true,
                    repeat: -1,
                    ease: 'sine.inOut',
                    delay: 1.8
                });

                if (window.ScrollTrigger) {
                    gsap.registerPlugin(ScrollTrigger);

                    scrollMotion = gsap.to('.home-basket', {
                        y: 65,
                        rotation: 5,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: '.home-hero',
                            start: 'top top',
                            end: 'bottom top',
                            scrub: 1
                        }
                    });
                }

                syncMotion();
            }
        );

        if (motionButton) {
            syncMotion();
        }
    };

    /*
     * Hero animation loader finish hone ke baad start hogi.
     */
    window.addEventListener(
        'marketlink:loader-finished',
        startHeroMotion
    );

    reduced.addEventListener('change', () => {
        motionPaused = reduced.matches;
        syncMotion();
    });

    /*
     * Swiper
     */
    if (!window.Swiper) {
        return;
    }

    const categoryElement = document.querySelector(
        '.home-category-slider'
    );

    if (categoryElement) {
        const categoryCount =
            categoryElement.querySelectorAll('.swiper-slide').length;

        const categorySlider = new Swiper(categoryElement, {
            effect: reduced.matches ? 'slide' : 'coverflow',
            slidesPerView: 'auto',
            centeredSlides: true,
            initialSlide: Math.floor(categoryCount / 2),
            grabCursor: true,
            speed: reduced.matches ? 0 : 700,
            rewind: true,

            coverflowEffect: {
                rotate: 0,
                stretch: 20,
                depth: 170,
                modifier: 1,
                slideShadows: false
            },

            keyboard: {
                enabled: true,
                onlyInViewport: true
            },

            a11y: {
                slideRole: 'link',
                slideLabelMessage: ''
            }
        });

        const categoryPrev = document.querySelector(
            '.home-category-prev'
        );

        const categoryNext = document.querySelector(
            '.home-category-next'
        );

        const categoryControls = document.querySelector(
            '[data-category-controls]'
        );

        categoryPrev?.addEventListener(
            'click',
            () => categorySlider.slidePrev()
        );

        categoryNext?.addEventListener(
            'click',
            () => categorySlider.slideNext()
        );

        if (categoryControls) {
            categoryControls.hidden = categoryCount < 2;
        }
    }

    /*
     * Reviews
     */
    const reviewElement = document.querySelector(
        '.home-review-slider'
    );

    if (!reviewElement) {
        return;
    }

    const reviewCount =
        reviewElement.querySelectorAll('.swiper-slide').length;

    const reviewSlider = new Swiper(reviewElement, {
        effect: reduced.matches ? 'slide' : 'coverflow',
        slidesPerView: 'auto',
        centeredSlides: true,
        initialSlide: Math.floor(reviewCount / 2),
        grabCursor: true,
        speed: reduced.matches ? 0 : 800,
        rewind: true,

        coverflowEffect: {
            rotate: 0,
            stretch: 35,
            depth: 180,
            modifier: 1,
            slideShadows: false
        },

        keyboard: {
            enabled: true,
            onlyInViewport: true
        },

        a11y: {
            prevSlideMessage: 'Previous community review',
            nextSlideMessage: 'Next community review'
        },

        autoplay: {
            enabled: false,
            delay: 6000,
            disableOnInteraction: true,
            pauseOnMouseEnter: true
        }
    });

    const reviewPrev = document.querySelector(
        '.home-review-prev'
    );

    const reviewNext = document.querySelector(
        '.home-review-next'
    );

    reviewPrev?.addEventListener('click', () => {
        reviewSlider.autoplay.stop();
        reviewSlider.slidePrev();
    });

    reviewNext?.addEventListener('click', () => {
        reviewSlider.autoplay.stop();
        reviewSlider.slideNext();
    });

    const reviewControls = document.querySelector(
        '[data-review-controls]'
    );

    if (reviewControls) {
        reviewControls.hidden = reviewCount < 2;
    }

    const playButton = document.querySelector(
        '[data-review-play]'
    );

    const updatePlaybackButton = () => {
        if (!playButton) {
            return;
        }

        const playing = reviewSlider.autoplay.running;

        playButton.setAttribute(
            'aria-label',
            `${playing ? 'Pause' : 'Play'} automatic reviews`
        );

        playButton.innerHTML = `
            <i class="fa-solid fa-${playing ? 'pause' : 'play'}" aria-hidden="true"></i>
        `;
    };

    playButton?.addEventListener('click', () => {
        if (reviewSlider.autoplay.running) {
            reviewSlider.autoplay.stop();
        } else {
            reviewSlider.autoplay.start();
        }

        updatePlaybackButton();
    });

    reviewSlider.on(
        'autoplayStart',
        updatePlaybackButton
    );

    reviewSlider.on(
        'autoplayStop',
        updatePlaybackButton
    );

    reviewElement.addEventListener(
        'focusin',
        () => reviewSlider.autoplay.stop()
    );

    reduced.addEventListener('change', () => {
        reviewSlider.autoplay.stop();

        reviewSlider.params.speed =
            reduced.matches ? 0 : 800;
    });
})();