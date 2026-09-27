@extends('Website._master')
@section('page_title', 'Your local world')
@section('body')
<div class="customer-shell">
    @include('Website.Dashboard._sidebar')
    <div class="customer-content">
        <header class="customer-topbar"><div><span class="customer-eyebrow">MY MARKETLINK</span><span class="customer-page-label">Overview <span>/ Your local world</span></span></div><div class="customer-top-actions"><span class="customer-today">{{ now()->format('l, d M') }}</span><a href="{{ route('customer_notifications') }}" class="customer-icon-button" aria-label="Your updates"><i class="fa-regular fa-bell"></i>@if($unreadUpdates->isNotEmpty())<span class="update-dot"></span>@endif</a><a href="{{ url('/cart') }}" class="customer-button customer-button-small"><i class="fa-solid fa-basket-shopping"></i> Basket <span>{{ collect(session('cart', []))->sum() }}</span></a></div></header>
        @include('Website.Partials.alerts')
        <section class="customer-welcome" aria-labelledby="welcome-heading">
            <div class="welcome-copy"><span class="customer-eyebrow"><span class="living-dot"></span> ROOTED IN YOUR COMMUNITY</span><h1 id="welcome-heading">Good to see you,<br><em>{{ \Illuminate\Support\Str::before($user->name, ' ') }}.</em></h1><p>Your next pickup. Your favorite growers.<br>A fresher day starts right here.</p><a href="{{ url('/products') }}" class="customer-button">Find something fresh <span>↗</span></a></div>
            <div class="welcome-art" aria-hidden="true"><span class="welcome-orbit"></span><span class="welcome-spark">✳</span><svg viewBox="0 0 400 340"><ellipse cx="210" cy="307" rx="123" ry="15" fill="#1d392d" opacity=".13"/><g transform="rotate(-8 210 160)"><path d="M176 133Q85 105 112 35q73 3 72 89Q180 24 251 18q26 66-55 120 82-68 125-15-36 49-109 32" fill="#758c48"/><path d="M176 149Q151 73 197 69q52-1 37 81" fill="#d9a459"/><circle cx="136" cy="174" r="41" fill="#c87251"/><path d="m121 136 17 16 19-13-11 24-27-4z" fill="#456444"/><circle cx="267" cy="169" r="39" fill="#b7c37a"/><path d="m94 165 25 133h179l28-133z" fill="#f5e9c9"/><path d="M144 187v-51q0-55 66-55t66 55v51" stroke="#fff6df" stroke-width="13" fill="none"/><path d="M114 217h195m-188 39h180m-134-90 8 128m36-128v128m43-128-6 128" stroke="#d2c4a1" stroke-width="4"/><rect x="174" y="223" width="75" height="51" rx="15" fill="#3a5940"/><path d="M196 255q-5-27 28-23 4 28-28 23" fill="#d6e2a4"/></g></svg><span class="welcome-stamp">GROWN WITH CARE<br><strong>picked by you.</strong></span></div>
        </section>
        <section class="customer-stats" aria-label="Your account at a glance">
            @foreach([
                ['total_orders', 'Total pre-orders', 'fa-bag-shopping', 'Every local connection', 'customer_orders'],
                ['active_orders', 'Active pre-orders', 'fa-clock', 'Good things on the way', 'customer_orders'],
                ['completed_orders', 'Completed orders', 'fa-check', 'Collected at the market', 'customer_orders'],
                ['favorites', 'Saved favorites', 'fa-heart', 'Worth coming back for', 'customer_favorites'],
            ] as [$key, $label, $icon, $caption, $target])
            <a class="customer-stat stat-{{ $key }}" href="{{ route($target) }}"><div><span>{{ $label }}</span><i class="fa-solid {{ $icon }}"></i></div><strong>{{ number_format($stats[$key]) }}</strong><small>{{ $caption }}</small></a>
            @endforeach
        </section>
        <div class="customer-main-grid">
            @include('Website.Dashboard._pickups')
            <div class="customer-right-column">
                @include('Website.Dashboard._discovery')
                <section class="customer-local-note"><span class="note-spark">✳</span><span class="customer-eyebrow">A DIFFERENT KIND OF SHOPPING</span><h3>Good food.<br>Real connections.</h3><p>Reserve here, collect at the stall, and pay your grower in person.</p><a href="{{ url('/farmers') }}">Meet the growers <span>↗</span></a></section>
            </div>
        </div>
        @include('Website.Dashboard._saved-picks')
        @if($unreadUpdates->isNotEmpty())<section class="customer-panel customer-updates"><div class="customer-section-heading"><h2>A little heads-up.</h2><a href="{{ route('customer_notifications') }}" class="customer-text-link">All updates ↗</a></div>@foreach($unreadUpdates as $update)<a href="{{ route('customer_notifications') }}"><span class="update-icon"><i class="fa-regular fa-bell"></i></span><div><strong>{{ $update->title }}</strong><p>{{ $update->message }}</p></div><time>{{ $update->created_at->diffForHumans() }}</time></a>@endforeach</section>@endif
        <footer class="customer-bottom-note"><span><i class="fa-solid fa-leaf"></i> A little closer to the source.</span><span>Made for your local community.</span></footer>
    </div>
</div>
<dialog class="customer-confirm-dialog" id="cancel-order-dialog"><form method="dialog"><span class="dialog-icon"><i class="fa-regular fa-calendar-xmark"></i></span><h2>Plans changed?</h2><p>Cancel this pre-order? Your grower will see its updated status.</p><div><button value="keep" class="customer-button customer-button-light">Keep my pre-order</button><button value="cancel" class="customer-button">Cancel pre-order</button></div></form></dialog>
@endsection
