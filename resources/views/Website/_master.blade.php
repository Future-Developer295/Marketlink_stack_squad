<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'MarketLink') | MarketLink</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('Assets/Website_Asset/css/dashboard.css') }}" rel="stylesheet">

    <link href="{{ asset('Assets/Website_Asset/css/chatbot.css') }}" rel="stylesheet">

    @if (request()->is(
            'products*',
            'cart',
            'checkout',
            'pickup-guidelines',
            'markets*',
            'farmers*',
            'about',
            '/',
            'dashboard/customer*'))
        <link
            href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap"
            rel="stylesheet">
    @endif

    @if (request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', '/'))
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

        <link href="{{ asset('Assets/Website_Asset/css/shop.css') }}" rel="stylesheet">
    @endif

    @if (request()->is('dashboard/customer*'))
        <link href="{{ asset('Assets/Website_Asset/css/customer-dashboard.css') }}" rel="stylesheet">
    @endif

    @if (request()->is('markets*', 'farmers*'))
        <link href="{{ asset('Assets/Website_Asset/css/discovery.css') }}" rel="stylesheet">
    @endif

    @yield('page_styles')
</head>

<body
    class="{{ request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', '/') ? 'harvest-shop' : '' }}
    {{ request()->is('dashboard/customer*') ? 'customer-area' : '' }}">

    @php($cartCount = app(\App\Services\Cart::class)->count())

    @unless ($__env->hasSection('hide_chrome'))
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

                        @php($favCount = auth()->check() && method_exists(auth()->user(), 'favorites') ? auth()->user()->favorites()->count() : 0)

                        <a href="{{ auth()->check() ? url('/dashboard/customer/favorites') : url('/login') }}"
                            class="ml-icon-btn ml-fav-btn {{ request()->is('dashboard/customer/favorites*') ? 'is-active' : '' }}"
                            aria-label="Favourites{{ $favCount ? ' (' . $favCount . ')' : '' }}">

                            <i class="{{ $favCount ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>

                            <span class="ml-cart-badge ml-fav-badge" data-fav-count
                                @if (!$favCount) hidden @endif>
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
                                            Customer Account
                                        </span>
                                    </li>

                                    <li>
                                        <hr class="ml-dropdown-divider">
                                    </li>

                                    <li>
                                        <a class="dropdown-item ml-dropdown-item" href="{{ route('customer_dashboard') }}">

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


    @unless ($__env->hasSection('hide_chrome'))
        <footer class="ml-footer">

            <div class="ml-container">

                <div class="row g-5">

                    <div class="col-lg-4">

                        <a class="navbar-brand mb-3 d-inline-flex" href="{{ url('/') }}">

                            <span class="ml-logo-icon">
                                <i class="fa-solid fa-leaf"></i>
                            </span>

                            MarketLink

                        </a>

                        <p class="text-muted small mt-2">
                            Connecting local farmers, fresh seasonal produce, and
                            health-conscious communities through direct field-to-stall
                            pre-orders.
                        </p>

                        <span class="ml-badge ml-badge-mint mt-2">
                            <i class="fa-solid fa-shield-halved"></i>
                            100% Pre-Order &amp; Market Pickup
                        </span>

                    </div>


                    <div class="col-6 col-lg-2">

                        <h6>Explore</h6>

                        <a href="{{ url('/markets') }}">
                            Market Hubs
                        </a>

                        <a href="{{ url('/farmers') }}">
                            Verified Growers
                        </a>

                        <a href="{{ url('/products') }}">
                            Seasonal Harvest
                        </a>

                    </div>


                    <div class="col-6 col-lg-2">

                        <h6>Company</h6>

                        <a href="{{ url('/about') }}">
                            Our Mission
                        </a>

                        <a href="{{ url('/contact') }}">
                            Contact Stalls
                        </a>

                        <a href="{{ url('/pickup-guidelines') }}">
                            Pickup Guidelines
                        </a>

                    </div>


                    <div class="col-6 col-lg-2">

                        <h6>For Farmers</h6>

                        <a href="{{ url('/register') }}">
                            Become a Farmer
                        </a>

                        <a href="{{ url('/login') }}">
                            Stallholder Sign In
                        </a>

                        <a href="{{ url('/farmers') }}">
                            Harvest Calendar
                        </a>

                    </div>

                </div>


                <div class="ml-footer-bottom">

                    <span>
                        &copy; {{ date('Y') }}
                        MarketLink Marketplace.
                        Fresh community provenance.
                    </span>

                    <div class="d-flex gap-3">

                        <a href="{{ url('/contact') }}">
                            Contact &amp; Support
                        </a>

                        <a href="{{ url('/pickup-guidelines') }}">
                            Pickup Guide
                        </a>

                    </div>

                </div>

            </div>

        </footer>
    @endunless


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('Assets/Website_Asset/js/website.js') }}"></script>


    @if (request()->is('products*', 'cart', 'checkout', 'pickup-guidelines', 'markets*', 'farmers*', 'about', '/'))
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
