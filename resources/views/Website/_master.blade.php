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
@yield('page_styles')
</head>
<body>

<nav class="navbar navbar-expand-lg ml-navbar">
    <div class="ml-container d-flex align-items-center justify-content-between w-100">
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="ml-logo-icon"><i class="fa-solid fa-leaf"></i></span>
            MarketLink
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mlNavbar">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="mlNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link ml-nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link ml-nav-link {{ request()->is('markets*') ? 'active' : '' }}" href="{{ url('/markets') }}">Markets</a></li>
                <li class="nav-item"><a class="nav-link ml-nav-link {{ request()->is('farmers*') ? 'active' : '' }}" href="{{ url('/farmers') }}">Farmers</a></li>
                <li class="nav-item"><a class="nav-link ml-nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ url('/products') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link ml-nav-link {{ request()->is('about*') ? 'active' : '' }}" href="{{ url('/about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link ml-nav-link {{ request()->is('contact*') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a></li>
            </ul>

            <div class="ml-navbar-actions">
                <button class="ml-icon-btn" type="button" aria-label="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="{{ url('/cart') }}" class="ml-icon-btn" aria-label="Cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @php($cartCount = collect(session('cart', []))->sum())
                    @if($cartCount > 0)
                        <span class="ml-cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <a href="{{ route('customer_dashboard') }}" class="ml-nav-link d-none d-lg-inline">
    <i class="fa-solid fa-gauge"></i>
    {{ auth()->user()->name }}
</a>
                    <form action="{{ url('/logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn ml-btn-primary ml-btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="ml-nav-link d-none d-lg-inline">Login</a>
                    <a href="{{ url('/register') }}" class="btn ml-btn-primary ml-btn-sm">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main>
@yield('body')
</main>

<footer class="ml-footer">
    <div class="ml-container">
        <div class="row g-5">
            <div class="col-lg-4">
                <a class="navbar-brand mb-3 d-inline-flex" href="{{ url('/') }}">
                    <span class="ml-logo-icon"><i class="fa-solid fa-leaf"></i></span>
                    MarketLink
                </a>
                <p class="text-muted small mt-2">Connecting local farmers, fresh seasonal produce, and health-conscious communities through direct field-to-stall pre-orders.</p>
                <span class="ml-badge ml-badge-mint mt-2"><i class="fa-solid fa-shield-halved"></i> 100% Pre-Order &amp; Market Pickup</span>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Explore</h6>
                <a href="{{ url('/markets') }}">Market Hubs</a>
                <a href="{{ url('/farmers') }}">Verified Growers</a>
                <a href="{{ url('/products') }}">Seasonal Harvest</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Company</h6>
                <a href="{{ url('/about') }}">Our Mission</a>
                <a href="{{ url('/contact') }}">Contact Stalls</a>
                <a href="{{ url('/pickup-guidelines') }}">Pickup Guidelines</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6>For Farmers</h6>
                <a href="{{ url('/register') }}">Become a Farmer</a>
                <a href="{{ url('/login') }}">Stallholder Sign In</a>
                <a href="{{ url('/farmers') }}">Harvest Calendar</a>
            </div>
        </div>
        <div class="ml-footer-bottom">
            <span>&copy; {{ date('Y') }} MarketLink Marketplace. Fresh community provenance.</span>
            <div class="d-flex gap-3">
                <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>
                <a href="{{ url('/terms') }}">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('Assets/Website_Asset/js/website.js') }}"></script>
@yield('page_scripts')
</body>
</html>
