@extends('Website._master')

@section('page_title', 'Contact Us')

@section('body')

{{-- Move this <link> to the place where home.css / about.css are loaded if your master has a styles slot --}}
<link rel="stylesheet" href="{{ asset('Assets/Website_Asset/css/contact.css') }}">

<div class="contact-page" id="contactPage">

   
    <div class="contact-shell">
        <nav class="contact-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a> <span>/</span> <span>Contact Us</span>
        </nav>

        <section class="contact-hero">
            <div class="contact-hero-copy reveal">
                <span class="contact-kicker"><i class="fa-solid fa-tower-broadcast"></i> Get in touch</span>
                <h1>Let's talk <em>fresh.</em></h1>
                <p>Questions, farm sign-ups or feedback. One message away.</p>

                <div class="contact-quick">
                    <a class="contact-btn" href="#contact-form">Send a message <i class="fa-solid fa-paper-plane"></i></a>
                    <a class="contact-btn is-ghost" href="tel:+923000000000">Call us <i class="fa-solid fa-phone"></i></a>
                </div>

                <ul class="contact-hero-meta">
                    <li><i class="fa-solid fa-people-group"></i> 3 desks</li>
                    <li><i class="fa-regular fa-clock"></i> Mon&ndash;Fri</li>
                    <li><i class="fa-solid fa-location-dot"></i> Karachi</li>
                </ul>
            </div>

            <div class="contact-hero-visual reveal" style="--d:.15s">
                <span class="contact-hero-plate" aria-hidden="true"></span>

                <div class="contact-hero-frame">
                    {{-- Illustrated market stall (always visible, also the fallback if the photo is missing) --}}
                    <svg viewBox="0 0 520 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
                        <defs>
                            <linearGradient id="chSky" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="#f1f5de"/><stop offset="1" stop-color="#d8e3bc"/>
                            </linearGradient>
                            <pattern id="chStripe" width="100" height="10" patternUnits="userSpaceOnUse">
                                <rect width="50" height="10" fill="#435a2d"/><rect x="50" width="50" height="10" fill="#f5f0df"/>
                            </pattern>
                        </defs>

                        <rect width="520" height="600" fill="url(#chSky)"/>
                        <circle cx="400" cy="120" r="82" fill="#f6ecc0" opacity=".35"/>
                        <circle cx="400" cy="120" r="54" fill="#f6ecc0"/>
                        <path d="M0 330 C90 270 170 270 260 320 S440 300 520 270 V600 H0Z" fill="#c5d5a3"/>
                        <path d="M0 400 C110 350 210 370 300 400 S460 380 520 360 V600 H0Z" fill="#9db77a"/>
                        <rect y="500" width="520" height="100" fill="#40572c"/>

                        <!-- stall posts -->
                        <rect x="88" y="180" width="10" height="322" rx="4" fill="#6b5a3a"/>
                        <rect x="422" y="180" width="10" height="322" rx="4" fill="#6b5a3a"/>

                        <!-- awning -->
                        <path d="M50 215 L90 140 H430 L470 215 Z" fill="url(#chStripe)"/>
                        <rect x="90" y="132" width="340" height="10" rx="5" fill="#2f4224"/>
                        <g>
                            <circle cx="75"  cy="215" r="25" fill="#f5f0df"/>
                            <circle cx="125" cy="215" r="25" fill="#435a2d"/>
                            <circle cx="175" cy="215" r="25" fill="#f5f0df"/>
                            <circle cx="225" cy="215" r="25" fill="#435a2d"/>
                            <circle cx="275" cy="215" r="25" fill="#f5f0df"/>
                            <circle cx="325" cy="215" r="25" fill="#435a2d"/>
                            <circle cx="375" cy="215" r="25" fill="#f5f0df"/>
                            <circle cx="425" cy="215" r="25" fill="#435a2d"/>
                        </g>

                        <!-- hanging sign -->
                        <line x1="225" y1="238" x2="225" y2="262" stroke="#6b5a3a" stroke-width="2"/>
                        <line x1="295" y1="238" x2="295" y2="262" stroke="#6b5a3a" stroke-width="2"/>
                        <rect x="200" y="258" width="120" height="38" rx="10" fill="#fff9e8"/>
                        <text x="260" y="283" text-anchor="middle" font-family="Georgia,serif" font-style="italic" font-size="19" fill="#435a2d">Fresh today</text>

                        <!-- counter -->
                        <rect x="70" y="430" width="380" height="72" rx="8" fill="#a9814a"/>
                        <rect x="70" y="430" width="380" height="12" rx="6" fill="#c19a63"/>
                        <rect x="110" y="452" width="4" height="50" fill="#94703f"/>
                        <rect x="200" y="452" width="4" height="50" fill="#94703f"/>
                        <rect x="316" y="452" width="4" height="50" fill="#94703f"/>
                        <rect x="406" y="452" width="4" height="50" fill="#94703f"/>

                        <!-- crate 1: tomatoes -->
                        <g>
                            <circle cx="122" cy="382" r="19" fill="#c2503a"/><circle cx="156" cy="378" r="19" fill="#cf5a41"/><circle cx="190" cy="382" r="19" fill="#c2503a"/>
                            <circle cx="140" cy="362" r="18" fill="#cf5a41"/><circle cx="174" cy="360" r="18" fill="#c2503a"/>
                            <circle cx="134" cy="356" r="4" fill="#ffffff55"/><circle cx="168" cy="354" r="4" fill="#ffffff55"/>
                            <rect x="102" y="386" width="108" height="46" rx="6" fill="#b58a55"/>
                            <rect x="102" y="400" width="108" height="4" fill="#94703f"/><rect x="102" y="416" width="108" height="4" fill="#94703f"/>
                        </g>

                        <!-- crate 2: leafy greens -->
                        <g>
                            <circle cx="248" cy="376" r="21" fill="#5b8434"/><circle cx="282" cy="372" r="22" fill="#6f9a3d"/><circle cx="316" cy="376" r="21" fill="#5b8434"/>
                            <circle cx="264" cy="356" r="19" fill="#7d973f"/><circle cx="300" cy="356" r="19" fill="#6f9a3d"/>
                            <rect x="228" y="386" width="108" height="46" rx="6" fill="#b58a55"/>
                            <rect x="228" y="400" width="108" height="4" fill="#94703f"/><rect x="228" y="416" width="108" height="4" fill="#94703f"/>
                        </g>

                        <!-- crate 3: carrots -->
                        <g>
                            <path d="M356 388 L366 336 L376 388Z" fill="#d98a3d"/>
                            <path d="M380 388 L390 328 L400 388Z" fill="#e39a4c"/>
                            <path d="M404 388 L414 340 L424 388Z" fill="#d98a3d"/>
                            <path d="M366 336 l-6 -16 M366 336 l6 -18 M390 328 l-6 -16 M390 328 l7 -18 M414 340 l-6 -16 M414 340 l6 -16" stroke="#5b8434" stroke-width="4" stroke-linecap="round"/>
                            <rect x="344" y="386" width="90" height="46" rx="6" fill="#b58a55"/>
                            <rect x="344" y="400" width="90" height="4" fill="#94703f"/><rect x="344" y="416" width="90" height="4" fill="#94703f"/>
                        </g>

                        <!-- foreground leaves -->
                        <path d="M0 600 C0 530 44 500 96 492 C98 552 62 596 0 600Z" fill="#2f4224"/>
                        <path d="M520 600 C520 530 476 500 424 492 C422 552 458 596 520 600Z" fill="#2f4224"/>
                        <path d="M0 600 C10 560 40 536 70 526 C66 566 40 590 0 600Z" fill="#435a2d"/>
                        <path d="M520 600 C510 560 480 536 450 526 C454 566 480 590 520 600Z" fill="#435a2d"/>
                    </svg>

                    {{-- Optional real photo: drop any market photo at public/images/contact-hero.jpg.
                         If the file is missing, it removes itself and the illustration stays. --}}
                    <img src="{{ asset('images/contact-hero.jpg') }}" alt="Farmers market stall" loading="eager" onerror="this.remove()">
                </div>

                <span class="contact-emblem" aria-hidden="true"><i class="fa-solid fa-seedling"></i>GROWERS<br>WELCOME</span>

                <a class="contact-float f1" href="mailto:team@marketlink-project.example">
                    <i class="fa-solid fa-envelope"></i>
                    <span><strong>Email us</strong><small>Team desk</small></span>
                </a>
                <a class="contact-float f2" href="tel:+923000000000">
                    <i class="fa-solid fa-phone"></i>
                    <span><strong>Call us</strong><small>+92 300 0000000</small></span>
                </a>
                <a class="contact-float f3" href="#contact-form">
                    <i class="fa-regular fa-clock"></i>
                    <span><strong>Mon&ndash;Fri</strong><small>9 AM &ndash; 5 PM PKT</small></span>
                </a>
            </div>
        </section>
    </div>

  

    <div class="contact-strip">
        <span><i class="fa-solid fa-store"></i> Local pickup</span>
        <span><i class="fa-solid fa-hand-holding-dollar"></i> Pay at the stall</span>
        <span><i class="fa-solid fa-ban"></i> No delivery</span>
    </div>

    {{-- ============ DESKS ============ --}}
    <section class="contact-section">
        <div class="contact-shell contact-desks">
            <div class="contact-desks-intro reveal">
                <span class="contact-kicker"><i class="fa-solid fa-address-book"></i> Our desks</span>
                <h2>Pick the <em>right</em> desk.</h2>
                <p>Every question lands with the right person.</p>
            </div>

            <ul class="contact-desk-list">
                <li class="contact-desk reveal">
                    <span class="contact-desk-num">01</span>
                    <span class="contact-desk-icon"><i class="fa-solid fa-people-group"></i></span>
                    <div>
                        <h3>Team desk</h3>
                        <small>Platform questions</small>
                        <div class="contact-desk-links">
                            <a class="contact-pill" href="mailto:team@marketlink-project.example"><i class="fa-solid fa-envelope"></i> team@marketlink-project.example</a>
                            <a class="contact-pill" href="tel:+923000000000"><i class="fa-solid fa-phone"></i> +92 300 0000000</a>
                            <span class="contact-pill is-tint"><i class="fa-regular fa-clock"></i> Mon&ndash;Fri, 9&ndash;5 PKT</span>
                        </div>
                    </div>
                </li>

                <li class="contact-desk reveal" style="--d:.08s">
                    <span class="contact-desk-num">02</span>
                    <span class="contact-desk-icon"><i class="fa-solid fa-tractor"></i></span>
                    <div>
                        <h3>Stallholder desk</h3>
                        <small>Farmer onboarding</small>
                        <div class="contact-desk-links">
                            <a class="contact-pill" href="mailto:farmers@marketlink-project.example"><i class="fa-solid fa-envelope"></i> farmers@marketlink-project.example</a>
                            <span class="contact-pill is-tint"><i class="fa-solid fa-seedling"></i> Verified in 24h</span>
                        </div>
                    </div>
                </li>

                <li class="contact-desk reveal" style="--d:.16s">
                    <span class="contact-desk-num">03</span>
                    <span class="contact-desk-icon"><i class="fa-solid fa-code"></i></span>
                    <div>
                        <h3>Project desk</h3>
                        <small>Built by a small team</small>
                        <div class="contact-desk-links">
                            <span class="contact-pill is-tint"><i class="fa-solid fa-users"></i> 4 core devs</span>
                            <span class="contact-pill is-tint"><i class="fa-solid fa-book-open"></i> Open SRS</span>
                            <a class="contact-pill" href="{{ url('/about') }}"><i class="fa-solid fa-arrow-right"></i> About MarketLink</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    {{-- ============ FORM ============ --}}
    <section class="contact-section contact-form-wrap" id="contact-form">
        <div class="contact-shell">
            <div class="contact-form-panel reveal">

                <aside class="contact-form-side">
                    <div>
                        <span class="contact-kicker"><i class="fa-solid fa-paper-plane"></i> Write to us</span>
                        <h2 style="margin-top:16px">Send a <em>message.</em></h2>
                    </div>
                    <ul class="contact-facts">
                        <li><i class="fa-regular fa-clock"></i> Mon&ndash;Fri, 9&ndash;5 PKT</li>
                        <li><i class="fa-solid fa-envelope"></i> <a href="mailto:team@marketlink-project.example">team@marketlink-project.example</a></li>
                        <li><i class="fa-solid fa-hand-holding-dollar"></i> Pickup only, pay at the stall</li>
                    </ul>
                    <i class="fa-solid fa-seedling bg" aria-hidden="true"></i>
                </aside>

                <div class="contact-form-main">
                    @include('Website.Partials.alerts')

                    <form method="POST" action="{{ url('/contact') }}" id="contactForm" data-ajax data-ajax-reset>
                        @csrf

                        {{-- Honeypot (hidden from humans, catches bots) --}}
                        <div style="position:absolute;left:-9999px;" aria-hidden="true">
                            <label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                        </div>

                        <div class="contact-grid-2">
                            <div class="contact-field">
                                <label class="contact-label" for="cfName">Full name</label>
                                <input type="text" id="cfName" name="full_name" value="{{ old('full_name', auth()->user()->name ?? '') }}" class="contact-input @error('full_name') is-invalid @enderror" placeholder="e.g., Tariq Mahmood" autocomplete="name" required>
                                @error('full_name')<div class="contact-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="contact-field">
                                <label class="contact-label" for="cfEmail">Email</label>
                                <input type="email" id="cfEmail" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" class="contact-input @error('email') is-invalid @enderror" placeholder="you@example.com" autocomplete="email" required>
                                @error('email')<div class="contact-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="contact-field">
                            <span class="contact-label">What's it about?</span>
                            <div class="contact-topics" role="radiogroup" aria-label="Inquiry subject">
                                @foreach([
                                    ['Market & Pickup Questions', 'Market & pickup', 'fa-store'],
                                    ['Farmer Onboarding', 'Farmer onboarding', 'fa-tractor'],
                                    ['Platform Feedback', 'Feedback', 'fa-comment-dots'],
                                    ['Other', 'Other', 'fa-ellipsis'],
                                ] as $i => $topic)
                                    <div class="contact-topic">
                                        <input type="radio" name="subject" id="topic{{ $i }}" value="{{ $topic[0] }}" {{ old('subject') == $topic[0] ? 'checked' : '' }} required>
                                        <label for="topic{{ $i }}"><i class="fa-solid {{ $topic[2] }}"></i> {{ $topic[1] }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('subject')<div class="contact-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="contact-field">
                            <label class="contact-label" for="cfMessage">Message</label>
                            <textarea id="cfMessage" name="message" rows="5" minlength="10" maxlength="2000" class="contact-input @error('message') is-invalid @enderror" placeholder="Write your message..." required>{{ old('message') }}</textarea>
                            @error('message')<div class="contact-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="contact-form-foot">
                            <small><i class="fa-regular fa-clock"></i> We reply Mon&ndash;Fri</small>
                            <button type="submit" class="contact-btn"><span>Send message</span> <i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

  

   
    <section class="contact-section contact-loc-wrap">
        <div class="contact-shell contact-loc">
            <div class="reveal">
                <span class="contact-kicker"><i class="fa-solid fa-location-dot"></i> Find us</span>
                <h2>Visit the <em>hub.</em></h2>

                <ul class="contact-loc-list">
                    <li>
                        <i class="fa-solid fa-building"></i>
                        <span>Innovation Pavilion, Main University Road<small>Gulshan-e-Iqbal Campus, Karachi</small></span>
                    </li>
                    <li>
                        <i class="fa-regular fa-calendar"></i>
                        <span>Mon&ndash;Fri<small>Closed on market weekends</small></span>
                    </li>
                    <li>
                        <i class="fa-regular fa-clock"></i>
                        <span>9:00 AM &ndash; 5:00 PM PKT</span>
                    </li>
                </ul>

                <div class="contact-loc-btns">
                    <a class="contact-btn" href="https://www.google.com/maps/search/?api=1&query=Gulshan-e-Iqbal%2C+Karachi%2C+Pakistan" target="_blank" rel="noopener">Google Maps <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                    <a class="contact-btn is-ghost" href="https://www.openstreetmap.org/?mlat=24.9215&mlon=67.0925#map=15/24.9215/67.0925" target="_blank" rel="noopener">OpenStreetMap <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                </div>
            </div>

            <div class="contact-map reveal" style="--d:.12s">
                <iframe title="MarketLink hub location map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.openstreetmap.org/export/embed.html?bbox=67.0700%2C24.9050%2C67.1150%2C24.9380&amp;layer=mapnik&amp;marker=24.9215%2C67.0925"></iframe>
                <span class="contact-map-label"><i class="fa-solid fa-location-dot"></i> Karachi, Sindh</span>
            </div>
        </div>
    </section>

    {{-- ============ FAQ ============ --}}
    <section class="contact-section">
        <div class="contact-shell contact-faq">
            <div class="contact-faq-side reveal">
                <div class="contact-faq-mark" aria-hidden="true"><span>?</span><i class="fa-solid fa-leaf"></i></div>
                <span class="contact-kicker"><i class="fa-solid fa-circle-question"></i> FAQ</span>
                <h2>Quick <em>answers.</em></h2>
                <p>Still stuck? Just ask.</p>
                <a class="contact-btn is-ghost" href="#contact-form">Ask us <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="contact-faq-list" id="contactFaq">
                @foreach([
                    ['fa-leaf',             'What is MarketLink?',              'A platform linking local growers with neighborhood shoppers for weekly pre-orders and in-person stall pickups.'],
                    ['fa-user-group',       'Who can use it?',                  'Any customer, and any verified farmer at a partner market.'],
                    ['fa-hand-holding-dollar','How do I pay?',                  'In person at the stall when you pick up. No online payments.'],
                    ['fa-truck',            'Do you deliver?',                  'No. MarketLink is pickup only.'],
                    ['fa-headset',          'How do I reach the team?',         'Use the form above, or email team@marketlink-project.example, Mon-Fri.'],
                ] as $i => $faq)
                    <div class="contact-faq-item reveal {{ $i === 0 ? 'is-open' : '' }}" style="--d:{{ $i * .06 }}s">
                        <button class="contact-faq-btn" type="button" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="faq{{ $i }}">
                            <span class="contact-faq-no">0{{ $i + 1 }}</span>
                            <span class="contact-faq-ic"><i class="fa-solid {{ $faq[0] }}"></i></span>
                            <span class="contact-faq-q">{{ $faq[1] }}</span>
                            <span class="contact-faq-plus"><i class="fa-solid fa-plus"></i></span>
                        </button>
                        <div class="contact-faq-panel" id="faq{{ $i }}" role="region">
                            <div><p>{{ $faq[2] }}</p></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ EXPLORE ============ --}}
    <section class="contact-section contact-explore-wrap">
        <div class="contact-shell">
            <div class="contact-head reveal">
                <div>
                    <span class="contact-kicker"><i class="fa-solid fa-compass"></i> Keep exploring</span>
                    <h2>Fresh <em>finds</em> await.</h2>
                </div>
            </div>
            <div class="contact-tiles">
                <a class="contact-tile reveal" href="{{ url('/markets') }}">
                    <i class="ic fa-solid fa-store"></i>
                    <span><strong>Markets</strong><small>Weekend pickup spots</small></span>
                    <i class="go fa-solid fa-arrow-right"></i>
                </a>
                <a class="contact-tile reveal" style="--d:.08s" href="{{ url('/products') }}">
                    <i class="ic fa-solid fa-carrot"></i>
                    <span><strong>Products</strong><small>This week's harvest</small></span>
                    <i class="go fa-solid fa-arrow-right"></i>
                </a>
                <a class="contact-tile reveal" style="--d:.16s" href="{{ url('/farmers') }}">
                    <i class="ic fa-solid fa-tractor"></i>
                    <span><strong>Farmers</strong><small>Meet your growers</small></span>
                    <i class="go fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

</div>

<script>
(function () {
    var page = document.getElementById('contactPage');
    page.classList.add('js-ready');

    // Scroll reveal
    var items = page.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
            });
        }, { threshold: .12, rootMargin: '0px 0px -40px 0px' });
        items.forEach(function (el) { io.observe(el); });
    } else {
        items.forEach(function (el) { el.classList.add('is-in'); });
    }

    // FAQ accordion (one open at a time)
    var faq = document.getElementById('contactFaq');
    faq.addEventListener('click', function (ev) {
        var btn = ev.target.closest('.contact-faq-btn');
        if (!btn) return;
        var item = btn.parentElement;
        var willOpen = !item.classList.contains('is-open');
        faq.querySelectorAll('.contact-faq-item').forEach(function (i) {
            i.classList.remove('is-open');
            i.querySelector('.contact-faq-btn').setAttribute('aria-expanded', 'false');
        });
        if (willOpen) { item.classList.add('is-open'); btn.setAttribute('aria-expanded', 'true'); }
    });

    // Form: prevent double submit
    var form = document.getElementById('contactForm');
    form.addEventListener('submit', function () {
        if (form.hasAttribute('data-ajax')) return; // the shared AJAX layer shows its own busy state
        var b = form.querySelector('button[type=submit]');
        b.classList.add('is-loading');
        b.querySelector('span').textContent = 'Sending…';
    });
})();
</script>

@endsection