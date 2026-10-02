<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title', 'MarketLink') | MarketLink</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('Assets/Website_Asset/css/dashboard.css') }}" rel="stylesheet">

    <link href="{{ asset('Assets/Website_Asset/css/chatbot.css') }}" rel="stylesheet">
    <link href="{{ asset('Assets/Website_Asset/css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('Assets/Website_Asset/css/about.css') }}" rel="stylesheet">

    @if (request()->is(
            'products*',
            'cart',
            'checkout',
            'pickup-guidelines',
            'markets*',
            'farmers*',
            'about',
            'contact',
            '/',
            'dashboard/customer*'))
        <link
            href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
            rel="stylesheet">
    @endif

    @if (request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about','contact', '/'))
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

        <link href="{{ asset('Assets/Website_Asset/css/shop.css') }}" rel="stylesheet">
    @endif

    @if (request()->is('dashboard/customer*'))
        <link href="{{ asset('Assets/Website_Asset/css/customer-dashboard.css') }}" rel="stylesheet">
    @endif

    @if (request()->is('markets*', 'farmers*'))
        <link href="{{ asset('Assets/Website_Asset/css/discovery.css') }}" rel="stylesheet">
    @endif

    <link href="{{ asset('Assets/Shared/ajax.css') }}" rel="stylesheet">

    @yield('page_styles')
</head>

<body
    class="{{ request()->is(
        'products*',
        'cart',
        'checkout',
        'pickup-guidelines',
        'markets*',
        'farmers*',
        'about',
        'contact',
        '/',
    )
        ? 'harvest-shop'
        : '' }}
    {{ request()->is('dashboard/customer*') ? 'customer-area' : '' }}">

    @php
        $cartCount = app(\App\Services\Cart::class)->count();

        $favCount = 0;

        if (auth()->check() && method_exists(auth()->user(), 'favorites')) {
            $favCount = auth()->user()->favorites()->count();
        }
    @endphp

    @unless ($__env->hasSection('hide_chrome') || request()->is('login', 'register'))
        <nav class="navbar navbar-expand-lg ml-navbar">

            <div class="ml-container d-flex align-items-center justify-content-between w-100">

                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="ml-logo-icon">
                        <i class="fa-solid fa-leaf"></i>
                    </span>

                    MarketLink
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mlNavbar"
                    aria-controls="mlNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="mlNavbar">

                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                        <li class="nav-item">
                            <a class="nav-link ml-nav-link {{ request()->is('/') ? 'active' : '' }}"
                                href="{{ url('/') }}">
                                Home
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link ml-nav-link {{ request()->is('markets*') ? 'active' : '' }}"
                                href="{{ url('/markets') }}">
                                Markets
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link ml-nav-link {{ request()->is('farmers*') ? 'active' : '' }}"
                                href="{{ url('/farmers') }}">
                                Farmers
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link ml-nav-link {{ request()->is('products*') ? 'active' : '' }}"
                                href="{{ url('/products') }}">
                                Products
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link ml-nav-link {{ request()->is('about*') ? 'active' : '' }}"
                                href="{{ url('/about') }}">
                                About
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link ml-nav-link {{ request()->is('contact*') ? 'active' : '' }}"
                                href="{{ url('/contact') }}">
                                Contact
                            </a>
                        </li>

                    </ul>

                    <div class="ml-navbar-actions">

                        <a class="ml-icon-btn" href="{{ url('/products') . '#harvest' }}" aria-label="Search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>

                        <a href="{{ auth()->check() ? url('/dashboard/customer/favorites') : url('/login') }}"
                            class="ml-icon-btn ml-fav-btn {{ request()->is('dashboard/customer/favorites*') ? 'is-active' : '' }}"
                            aria-label="Favourites{{ $favCount ? ' (' . $favCount . ')' : '' }}">

                            <i class="{{ $favCount ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>

                            <span class="ml-fav-badge" data-fav-count @if (!$favCount) hidden @endif>
                                {{ $favCount }}
                            </span>

                        </a>

                        <a href="{{ url('/cart') }}" class="ml-icon-btn" aria-label="Cart">

                            <i class="fa-solid fa-cart-shopping"></i>

                            <span class="ml-cart-badge" data-cart-count>
                                {{ $cartCount }}
                            </span>

                        </a>

                        @auth

                            <div class="dropdown d-inline-block">

                                <button class="btn p-0 border-0 bg-transparent shadow-none dropdown-toggle-no-caret"
                                    type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                                    <span class="ml-avatar-circle">
                                        <i class="fa-solid fa-user"></i>
                                    </span>

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end ml-dropdown-menu" aria-labelledby="userMenuDropdown">

                                    <li class="ml-dropdown-header">

                                        <span class="user-name">
                                            {{ auth()->user()->name }}
                                        </span>

                                        <span class="user-role">
                                            {{ ucfirst(auth()->user()->role) }}
                                            Account
                                        </span>

                                    </li>

                                    <li>
                                        <hr class="ml-dropdown-divider">
                                    </li>

                                    <li>
                                        <a class="dropdown-item ml-dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="fa-solid fa-gauge"></i>
                                            Dashboard
                                        </a>
                                    </li>

                                    <li>

                                        <form action="{{ url('/logout') }}" method="POST" class="m-0">

                                            @csrf

                                            <button type="submit" class="dropdown-item ml-dropdown-item text-danger">
                                                <i class="fa-solid fa-right-from-bracket"></i>
                                                Logout
                                            </button>

                                        </form>

                                    </li>

                                </ul>

                            </div>
                        @else
                            <a href="{{ url('/login') }}" class="ml-nav-link d-none d-lg-inline me-2">
                                Login
                            </a>

                            <a href="{{ url('/register') }}" class="btn ml-btn-primary ml-btn-sm">
                                Register
                            </a>

                        @endauth

                    </div>

                </div>

            </div>

        </nav>
    @endunless


    @if (request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', '/'))
        <div class="shop-mobile-head">

            <a href="{{ url('/products') }}">

                <i class="fa-solid fa-leaf"></i>

                MarketLink

                <span>
                    THE LOCAL EDIT
                </span>

            </a>

            <a class="ml-icon-btn" href="{{ url('/cart') }}" aria-label="View basket">

                <i class="fa-solid fa-bag-shopping"></i>

                <span class="ml-cart-badge" data-cart-count>
                    {{ $cartCount }}
                </span>

            </a>

        </div>
    @endif


    <main>
        @yield('body')
    </main>


    @unless ($__env->hasSection('hide_chrome') || request()->is('login', 'register'))
        <footer class="mlf">
            <div class="mlf-card">

                <div class="mlf-top">
                    <div class="mlf-brand">
                        <a class="mlf-logo" href="{{ url('/') }}">
                            <span class="mlf-logo-icon"><i class="fa-solid fa-leaf" aria-hidden="true"></i></span>
                            MarketLink
                        </a>
                        <p>Fresh from local growers <i class="fa-solid fa-seedling" aria-hidden="true"></i></p>
                        <span class="mlf-badge"><i class="fa-solid fa-store" aria-hidden="true"></i> Pre-order &amp;
                            market pickup</span>
                    </div>

                    <nav class="mlf-col" aria-label="Explore">
                        <h6>Explore</h6>
                        <a href="{{ url('/markets') }}">Market Hubs</a>
                        <a href="{{ url('/farmers') }}">Verified Growers</a>
                        <a href="{{ url('/products') }}">Seasonal Harvest</a>
                    </nav>

                    <nav class="mlf-col" aria-label="Company">
                        <h6>Company</h6>
                        <a href="{{ url('/about') }}">Our Mission</a>
                        <a href="{{ url('/contact') }}">Contact Stalls</a>
                        <a href="{{ url('/pickup-guidelines') }}">Pickup Guidelines</a>
                    </nav>

                    <nav class="mlf-col" aria-label="For farmers">
                        <h6>For Farmers</h6>
                        <a href="{{ url('/register') }}">Become a Farmer</a>
                        <a href="{{ url('/login') }}">Stallholder Sign In</a>
                        <a href="{{ url('/farmers') }}">Harvest Calendar</a>
                    </nav>
                </div>

                <span class="mlf-watermark" aria-hidden="true">MarketLink</span>

                <svg class="mlf-scene" viewBox="0 0 1200 340" preserveAspectRatio="xMidYMax slice" aria-hidden="true"
                    focusable="false">
                    <path d="M0 190 C120 140 230 118 360 165 S560 150 640 130 S900 100 1010 140 S1130 150 1200 130 V340 H0Z"
                        fill="#d3dfbb" />
                    <path d="M0 215 C110 165 240 150 340 195 S520 200 620 170 S880 130 1000 175 S1120 190 1200 165 V340 H0Z"
                        fill="#b6cb95" />
                    <path d="M0 250 C90 195 220 185 330 225 S500 245 580 215 L600 340 H0Z" fill="#8fae66" />
                    <path d="M1200 235 C1110 190 980 180 880 215 S720 245 640 215 L610 340 H1200Z" fill="#7da058" />
                    <path
                        d="M585 205 C540 235 650 250 600 285 C575 305 520 315 490 340 H770 C770 320 715 300 705 272 C695 240 655 222 615 205Z"
                        fill="#d6e8de" />
                    <path d="M600 222 C585 240 625 252 610 272 C600 288 585 298 575 312" fill="none" stroke="#ffffff"
                        stroke-opacity=".7" stroke-width="3" stroke-linecap="round" />
                    <path d="M0 268 C110 240 260 262 420 312 C455 325 475 334 490 340 H0Z" fill="#5f8d3c" />
                    <path d="M1200 262 C1090 238 940 262 800 312 C765 325 745 334 730 340 H1200Z" fill="#4f7a34" />

                    <rect x="92" y="105" width="12" height="235" rx="5" fill="#6b4a32" />
                    <rect x="60" y="150" width="8" height="190" rx="4" fill="#7a573c" />
                    <circle cx="95" cy="88" r="58" fill="#4f7a34" />
                    <circle cx="52" cy="122" r="44" fill="#5f8d3c" />
                    <circle cx="140" cy="120" r="46" fill="#3f6a2b" />
                    <circle cx="98" cy="140" r="40" fill="#6f9a3d" />
                    <rect x="222" y="200" width="7" height="140" rx="3" fill="#6b4a32" />
                    <circle cx="225" cy="192" r="30" fill="#5f8d3c" />
                    <circle cx="248" cy="208" r="22" fill="#4f7a34" />

                    <rect x="1072" y="90" width="9" height="250" rx="4" fill="#6b4a32" />
                    <polygon points="1076,30 1030,96 1122,96" fill="#3f6a2b" />
                    <polygon points="1076,66 1018,138 1134,138" fill="#4f7a34" />
                    <polygon points="1076,108 1004,190 1148,190" fill="#5f8d3c" />
                    <rect x="960" y="190" width="6" height="150" rx="3" fill="#6b4a32" />
                    <polygon points="963,150 934,196 992,196" fill="#3f6a2b" />
                    <polygon points="963,178 924,232 1002,232" fill="#4f7a34" />

                    <g fill="#6a7db5">
                        <rect x="880" y="228" width="10" height="70" rx="5" />
                        <rect x="900" y="214" width="10" height="84" rx="5" />
                        <rect x="920" y="236" width="10" height="62" rx="5" fill="#586ca6" />
                        <rect x="330" y="262" width="9" height="56" rx="4" fill="#586ca6" />
                        <rect x="348" y="250" width="9" height="68" rx="4" />
                    </g>
                    <g stroke="#3f6a2b" stroke-width="3" stroke-linecap="round" fill="none">
                        <path d="M860 340 C862 310 870 296 880 280" />
                        <path d="M930 340 C930 312 938 300 950 288" />
                        <path d="M380 340 C382 318 372 304 362 292" />
                        <path d="M300 340 C302 322 312 310 322 300" />
                    </g>
                    <g fill="#fbfaf0">
                        <circle cx="1120" cy="300" r="9" />
                        <circle cx="1160" cy="312" r="8" />
                        <circle cx="1090" cy="322" r="7" />
                        <circle cx="820" cy="318" r="7" />
                        <circle cx="410" cy="322" r="7" />
                        <circle cx="160" cy="305" r="8" />
                    </g>
                    <g fill="#e3b447">
                        <circle cx="1120" cy="300" r="3" />
                        <circle cx="1160" cy="312" r="3" />
                        <circle cx="1090" cy="322" r="2.6" />
                        <circle cx="820" cy="318" r="2.6" />
                        <circle cx="410" cy="322" r="2.6" />
                        <circle cx="160" cy="305" r="3" />
                    </g>
                    <g fill="none" stroke="#3f6a2b" stroke-width="2" stroke-linecap="round">
                        <path d="M500 70 q5 -6 10 0 q5 -6 10 0" />
                        <path d="M560 40 q4 -5 8 0 q4 -5 8 0" />
                        <path d="M640 90 q4 -5 8 0 q4 -5 8 0" />
                        <path d="M700 55 q5 -6 10 0 q5 -6 10 0" />
                    </g>
                </svg>

                <div class="mlf-bar">
                    <span>&copy; {{ date('Y') }} MarketLink Marketplace. Fresh community provenance.</span>
                    <div class="mlf-bar-links">
                        <a href="{{ url('/contact') }}">Contact &amp; Support</a>
                        <a href="{{ url('/pickup-guidelines') }}">Pickup Guide</a>
                        <a class="mlf-social" href="#" aria-label="Facebook"><i
                                class="fa-brands fa-facebook-f"></i></a>
                        <a class="mlf-social" href="#" aria-label="Instagram"><i
                                class="fa-brands fa-instagram"></i></a>
                        <a class="mlf-social" href="#" aria-label="YouTube"><i
                                class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    @endunless


    <script src="{{ asset('Assets/Website_Asset/js/website.js') }}"></script>

    <script src="{{ asset('Assets/Shared/ajax.js') }}" defer></script>

    @auth
        @include('Website.Partials.welcome-back')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    @if (request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', 'contact', '/'))
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

        <script src="https://unpkg.com/lenis@1.1.20/dist/lenis.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

        <script src="{{ asset('Assets/Website_Asset/js/shop.js') }}"></script>
    @endif


    @if (request()->is('dashboard/customer*'))
        <script src="{{ asset('Assets/Website_Asset/js/customer-dashboard.js') }}" defer></script>
    @endif


    @include('Website.Partials.chatbot')

    <script src="{{ asset('Assets/Website_Asset/js/chatbot.js') }}" defer></script>


    @if (request()->is('markets*', 'farmers*'))
        <script src="{{ asset('Assets/Website_Asset/js/discovery.js') }}" defer></script>
    @endif


    @yield('page_scripts')

    @stack('scripts')

</body>

</html>
