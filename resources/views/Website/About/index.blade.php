@extends('Website._master')
@section('page_title', 'Our Story')
@section('page_styles')
<link href="{{ asset('Assets/Website_Asset/css/about.css') }}" rel="stylesheet">
@endsection
@section('body')
<div class="about-page">
    <div class="shop-shell">
        <nav class="shop-breadcrumb" aria-label="Breadcrumb"><a href="{{ url('/') }}">Home</a><span>/</span><span>Our story</span></nav>
        <section class="about-hero" aria-labelledby="about-title">
            <div class="about-hero-copy">
                <span class="shop-kicker"><i class="fa-solid fa-seedling" aria-hidden="true"></i> SMALL STALLS. SHARED STORIES.</span>
                <h1 id="about-title">A little closer<br>to the people<br><em>who grow it.</em></h1>
                <p>Fresh produce. Familiar faces. MarketLink brings your local market online, so you can plan your basket and meet your grower in person.</p>
                <div class="about-actions"><a class="shop-pill" href="{{ url('/markets') }}">Find your market <span aria-hidden="true">↗</span></a><a class="about-text-link" href="#how-it-works">See how it works <i class="fa-solid fa-arrow-down" aria-hidden="true"></i></a></div>
                <div class="about-hero-note"><i class="fa-solid fa-basket-shopping" aria-hidden="true"></i><span>Pre-order online. Pick up & pay at the stall.</span></div>
            </div>
            <div class="about-hero-visual">
                <img src="{{ asset('Assets/Website_Asset/images/vegetables/mix.jpg') }}" alt="Colourful tomatoes, peppers and fresh vegetables at a market" width="612" height="405" fetchpriority="high">
                <span class="about-orbit" aria-hidden="true"><i class="fa-solid fa-leaf"></i> GOOD FOOD<br>STARTS LOCAL</span>
                <div class="about-photo-caption"><span>THE LOCAL CONNECTION</span><strong>From their harvest.<br>To your everyday.</strong></div>
                <span class="about-photo-label"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> See you at the market</span>
            </div>
        </section>
    </div>
    <div class="about-belief-strip" aria-label="Our approach"><span><i class="fa-solid fa-seedling" aria-hidden="true"></i> Seasonal discoveries</span><span><i class="fa-solid fa-store" aria-hidden="true"></i> Local market pickup</span><span><i class="fa-solid fa-handshake" aria-hidden="true"></i> Face-to-face connection</span></div>
    <section class="shop-shell about-section about-story" aria-labelledby="story-title">
        <div><span class="shop-kicker">WHY WE’RE HERE</span><h2 id="story-title">Keep the market feeling.<br><em>Lose the guesswork.</em></h2><span class="about-story-mark" aria-hidden="true">✳</span></div>
        <div class="about-story-body"><p class="about-lead">A good market trip starts before you leave home.</p><p>Knowing who’s at the market, what’s available and when to collect makes shopping local easier. MarketLink puts those details in one place, while keeping the personal connection at the heart of every visit.</p><p>Farmers share their products, prices and weekly stock. You explore, place a pre-order and choose a pickup window. Then meet at the stall, collect your produce and pay the farmer directly.</p><a class="about-text-link" href="{{ url('/farmers') }}">Meet the people behind your basket <span aria-hidden="true">↗</span></a></div>
    </section>
    <section class="about-journey" id="how-it-works" aria-labelledby="journey-title">
        <div class="shop-shell about-section">
            <div class="about-section-heading"><div><span class="shop-kicker">FROM BROWSING TO BASKET</span><h2 id="journey-title">Your next market morning,<br><em>already taking shape.</em></h2></div><a class="about-text-link" href="{{ url('/pickup-guidelines') }}">Read the pickup guide <span aria-hidden="true">↗</span></a></div>
            <ol class="about-steps">
                <li><div class="about-step-top"><span>01</span><i class="fa-solid fa-map-location-dot" aria-hidden="true"></i></div><h3>Find your local spot.</h3><p>Explore markets by location and operating day. Get directions and discover participating growers.</p></li>
                <li><div class="about-step-top"><span>02</span><i class="fa-solid fa-carrot" aria-hidden="true"></i></div><h3>Build a seasonal basket.</h3><p>Browse products, compare prices and check stock. Save your favourite products and farmers for next time.</p></li>
                <li><div class="about-step-top"><span>03</span><i class="fa-regular fa-calendar-check" aria-hidden="true"></i></div><h3>Plan your pickup.</h3><p>Place a pre-order, choose an available pickup window and follow the farmer’s updates in your dashboard.</p></li>
                <li><div class="about-step-top"><span>04</span><i class="fa-solid fa-handshake" aria-hidden="true"></i></div><h3>Meet. Collect. Enjoy.</h3><p>Head to the farmer’s stall, collect your order and pay in person. Leave a review after your completed pickup.</p></li>
            </ol>
            <div class="about-pickup-note"><i class="fa-solid fa-store" aria-hidden="true"></i><p><strong>The final step is always personal.</strong> Orders are collected at market stalls. MarketLink does not process online payments or arrange delivery.</p></div>
        </div>
    </section>
    <section class="shop-shell about-section about-community" aria-labelledby="community-title">
        <div class="about-review-intro"><span class="shop-kicker">VOICES FROM THE MARKET</span><h2 id="community-title">Good food.<br>Real people.<br><em>Their words.</em></h2><p>Recent published reviews from our community, shared on local grower profiles.</p><div class="about-review-flower" aria-hidden="true"><i class="fa-solid fa-comments"></i><span>LOCAL PEOPLE<br>LOCAL STORIES</span></div></div>
        @if($reviews->isNotEmpty())
        <div class="about-carousel" role="region" aria-roledescription="carousel" aria-label="Community reviews" data-review-carousel>
            <div class="about-review-track" id="community-review-track" tabindex="0" aria-label="Reviews. Swipe or use the arrow controls.">
                @foreach($reviews as $review)
                <article class="about-review-slide" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $reviews->count() }}">
                    <div class="about-review-top"><i class="fa-solid fa-quote-left" aria-hidden="true"></i><span class="about-review-stars" aria-label="{{ $review->rating }} out of 5 stars">@for($star = 1; $star <= 5; $star++)<i class="{{ $star <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star" aria-hidden="true"></i>@endfor</span></div>
                    <blockquote>{{ $review->comment }}</blockquote>
                    <div class="about-review-person"><span class="about-review-avatar" aria-hidden="true">{{ mb_substr($review->user->name, 0, 1) }}</span><div><strong>{{ $review->user->name }}</strong><span>Reviewed {{ $review->created_at->format('M Y') }}</span></div></div>
                    <a class="about-text-link" href="{{ url('/farmers/'.$review->farmer_id) }}#grower-reviews">{{ $review->farmer->stall_name }} <span aria-hidden="true">↗</span></a>
                </article>
                @endforeach
            </div>
            @if($reviews->count() > 1)
            <div class="about-carousel-controls" hidden>
                <div class="about-review-dots">@foreach($reviews as $review)<button type="button" data-review-dot="{{ $loop->index }}" aria-label="Show review {{ $loop->iteration }}" aria-controls="community-review-track" @if($loop->first) aria-current="true" @endif><span></span></button>@endforeach</div>
                <div class="about-review-buttons"><button type="button" data-review-pause aria-label="Pause automatic reviews"><i class="fa-solid fa-pause" aria-hidden="true"></i></button><button type="button" data-review-prev aria-label="Previous review" aria-controls="community-review-track"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></button><button type="button" data-review-next aria-label="Next review" aria-controls="community-review-track"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button></div>
            </div>
            <span class="visually-hidden" data-review-status role="status"></span>
            @endif
        </div>
        @else
        <div class="about-review-empty"><i class="fa-regular fa-comment-dots" aria-hidden="true"></i><h3>Every market visit has a story.</h3><p>Community reviews will appear here as they’re published. Complete a pickup and share your own experience from your dashboard.</p><a class="shop-pill" href="{{ url('/markets') }}">Start your first visit <span aria-hidden="true">↗</span></a></div>
        @endif
    </section>
    <section class="shop-shell about-section about-growers" aria-labelledby="growers-title">
        <div class="about-grower-art" aria-hidden="true"><i class="fa-solid fa-seedling"></i><span>GROW LOCAL.<br><em>Go further.</em></span></div>
        <div><span class="shop-kicker">FOR THE HANDS THAT GROW</span><h2 id="growers-title">Your stall has a story.<br><em>Give it a place online.</em></h2><p>Share your harvest, publish weekly stock and manage pre-orders around your market schedule. Keep customers up to date and organise pickup windows from your farmer dashboard.</p><div class="about-actions"><a class="shop-pill" href="{{ route('register') }}">Join as a farmer <span aria-hidden="true">↗</span></a><a class="about-text-link" href="{{ url('/contact') }}">Talk to us <span aria-hidden="true">↗</span></a></div></div>
    </section>
    <section class="shop-shell about-section about-local" aria-labelledby="local-title">
        <div><span class="shop-kicker">MAKE IT A MARKET DAY</span><h2 id="local-title">Find your next<br><em>local favourite.</em></h2><p>Check the location, opening days and growers before you set off.</p><a class="about-text-link" href="{{ url('/markets') }}">Explore all markets <span aria-hidden="true">↗</span></a></div>
        <div class="about-market-list">@forelse($markets as $market)<a href="{{ url('/markets/'.$market->id) }}"><span class="about-market-number">0{{ $loop->iteration }}</span><span><strong>{{ $market->name }}</strong><small><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $market->city }} <span>· {{ $market->operating_days }}</span></small></span><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>@empty<p>New market locations will appear here when they’re added.</p>@endforelse</div>
    </section>
    <section class="about-final shop-shell" aria-labelledby="cta-title"><i class="fa-solid fa-basket-shopping" aria-hidden="true"></i><span class="shop-kicker">A FRESH START, CLOSE TO HOME</span><h2 id="cta-title">Make room for<br><em>something local.</em></h2><p>Your next basket starts with a grower, a market and a little planning.</p><div class="about-actions"><a class="shop-pill" href="{{ url('/products') }}">Explore the harvest <span aria-hidden="true">↗</span></a><a class="about-text-link" href="{{ url('/markets') }}">Find a market <span aria-hidden="true">↗</span></a></div><span class="about-final-flower" aria-hidden="true">✳</span></section>
</div>
@endsection
@section('page_scripts')
<script src="{{ asset('Assets/Website_Asset/js/about.js') }}" defer></script>
@endsection
