<div class="customer-navigation">
    <div class="customer-mobile-bar"><a href="{{ route('customer_dashboard') }}" class="customer-brand"><i class="fa-solid fa-leaf"></i> MarketLink</a><button type="button" class="customer-icon-button" data-sidebar-open aria-controls="customer-sidebar" aria-expanded="false" aria-label="Open account menu"><i class="fa-solid fa-bars-staggered"></i></button></div>
    <button class="customer-sidebar-backdrop" data-sidebar-close aria-label="Close account menu" hidden></button>
    <aside class="customer-sidebar" id="customer-sidebar" aria-label="Account navigation">
        <div class="customer-sidebar-brand"><a href="{{ url('/') }}" class="customer-brand"><i class="fa-solid fa-leaf"></i> MarketLink<span>YOUR LOCAL CONNECTION</span></a><button class="customer-icon-button sidebar-close" data-sidebar-close type="button" aria-label="Close account menu"><i class="fa-solid fa-xmark"></i></button></div>
        <div class="customer-identity"><span class="customer-avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span><div><strong>{{ auth()->user()->name }}</strong><span>Your little local world</span></div></div>
        <span class="customer-nav-caption">YOUR SPACE</span>
        <nav class="customer-nav">
            @foreach([
                ['customer_dashboard', 'fa-border-all', 'Overview'],
                ['customer_orders', 'fa-bag-shopping', 'My pre-orders'],
                ['customer_favorites', 'fa-heart', 'Saved favorites'],
                ['customer_reviews', 'fa-star', 'My reviews'],
                ['customer_notifications', 'fa-bell', 'Updates'],
                ['customer_profile', 'fa-sliders', 'Account settings'],
            ] as [$destination, $icon, $label])
                @php($isCurrent = request()->routeIs($destination) || ($destination === 'customer_orders' && request()->routeIs('customer_order_detail')))
                <a href="{{ route($destination) }}" @if($isCurrent) aria-current="page" @endif><i class="fa-solid {{ $icon }}"></i><span>{{ $label }}</span>@if($isCurrent)<i class="fa-solid fa-arrow-up-right-from-square nav-active-mark"></i>@endif</a>
            @endforeach
        </nav>
        <div class="customer-sidebar-note"><i class="fa-solid fa-seedling"></i><h3>A little local.<br>A lot of good.</h3><p>Meet the people behind your next fresh find.</p><a href="{{ url('/products') }}">Explore the harvest <span>↗</span></a></div>
        <div class="customer-sidebar-bottom"><a href="{{ url('/contact') }}"><i class="fa-regular fa-circle-question"></i> Need a hand?</a><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sign out</button></form></div>
    </aside>
</div>
