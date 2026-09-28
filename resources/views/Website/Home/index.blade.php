@extends('Website._master')
@section('page_title', 'Good food, close to home')
@section('page_styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@14.2.0/swiper-bundle.min.css">
<link rel="stylesheet" href="{{ asset('Assets/Website_Asset/css/home.css') }}">
@endsection
@section('body')
<div class="home-page">
    <section class="home-hero" aria-labelledby="home-title">
        <div class="ml-container home-hero-inner">
            <div class="home-hero-heading"><span class="shop-kicker">THE NEIGHBOURHOOD HARVEST</span><h1 id="home-title">Fresh finds.<br><em>Closer connections.</em></h1><p>Your local growers. Their seasonal best.<br>A basket worth making a market morning for.</p></div>
            <div class="home-stage" aria-hidden="true">
                <span class="home-giant-word">FRESH</span><span class="home-orbit"></span>
                <img class="home-basket" src="{{ asset('Assets/Website_Asset/images/home-harvest-basket.png') }}" alt="" width="1280" height="1280" fetchpriority="high">
                <img class="home-ingredient ingredient-one" src="{{ asset('Assets/Website_Asset/images/home-floating-tomato.png') }}" alt="" width="1280" height="1280">
                <img class="home-ingredient ingredient-two" src="{{ asset('Assets/Website_Asset/images/home-floating-tomato.png') }}" alt="" width="1280" height="1280">
                <i class="fa-solid fa-leaf home-ingredient ingredient-leaf-one"></i><i class="fa-solid fa-leaf home-ingredient ingredient-leaf-two"></i>
                <span class="home-handwritten">Good things<br>grow locally.</span><span class="home-stamp">PICK ONLINE<br><i class="fa-solid fa-basket-shopping"></i><br>PAY AT THE STALL</span>
            </div>
            <div class="home-hero-bottom"><div><a href="{{ url('/products') }}" class="shop-pill">Build your fresh basket <span>↗</span></a><a href="{{ url('/markets') }}" class="home-text-link">Find a local market <span>↗</span></a></div><p><i class="fa-solid fa-store" aria-hidden="true"></i> Pre-order. Meet your grower. Collect.</p><button type="button" class="home-motion-toggle" data-home-motion hidden aria-pressed="false"><i class="fa-solid fa-pause" aria-hidden="true"></i> Pause motion</button></div>
        </div>
    </section>
    <div class="home-ribbon"><span><i class="fa-solid fa-seedling" aria-hidden="true"></i> Seasonal discoveries</span><span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Local market pickup</span><span><i class="fa-solid fa-handshake" aria-hidden="true"></i> Pay your grower in person</span></div>

    <section class="home-section home-categories" aria-labelledby="home-categories-title">
        <div class="ml-container home-heading centered"><span class="shop-kicker">LET YOUR APPETITE WANDER</span><h2 id="home-categories-title">A little of <em>what you love.</em></h2><p>Explore the season, one fresh discovery at a time.</p></div>
        <div class="ml-container home-category-tabs">@foreach($categories as $category)<a href="{{ url('/products').'?category_id='.$category->id }}">{{ $category->name }}</a>@endforeach<a href="{{ url('/products') }}">All the goodness ↗</a></div>
        <div class="swiper home-category-slider" aria-label="Explore product categories"><div class="swiper-wrapper">
            @forelse($categories as $category)
            @php($categoryPhoto = match (true) {
                str_contains(strtolower($category->name), 'fruit') => 'fruits/Fresh-apple.png',
                str_contains(strtolower($category->name), 'veget') => 'vegetables/carrot.jpg',
                str_contains(strtolower($category->name), 'grain') => 'grains/rice.jpg',
                default => 'vegetables/mix.jpg'
            })
            <a class="swiper-slide home-category-slide" href="{{ url('/products').'?category_id='.$category->id }}"><img src="{{ asset('Assets/Website_Asset/images/'.$categoryPhoto) }}" alt="" loading="lazy" width="600" height="600"><span class="home-category-caption"><small>{{ $category->products_count }} available picks</small><strong>{{ $category->name }}</strong><span aria-hidden="true">↗</span></span></a>
            @empty<div class="swiper-slide home-category-slide"><img src="{{ asset('Assets/Website_Asset/images/vegetables/mix.jpg') }}" alt="Fresh seasonal vegetables" loading="lazy"><span class="home-category-caption"><strong>A new season is coming.</strong></span></div>@endforelse
        </div></div>
        <div class="home-slider-controls" data-category-controls hidden><button type="button" class="home-category-prev" aria-label="Previous category">←</button><span>FIND YOUR FRESH</span><button type="button" class="home-category-next" aria-label="Next category">→</button></div>
    </section>

    <section class="ml-container home-section home-products" aria-labelledby="home-products-title"><div class="home-heading"><div><span class="shop-kicker">THE CURRENT HARVEST</span><h2 id="home-products-title">Fresh picks.<br><em>Good days ahead.</em></h2></div><a href="{{ url('/products') }}" class="home-text-link">Shop the whole harvest <span>↗</span></a></div><div class="home-product-grid">@forelse($products as $product)@include('Website.Partials.shop-product',['product'=>$product])@empty<p>The growers are preparing their next harvest. Check back for fresh stock soon.</p>@endforelse</div></section>

    <section class="home-market-section"><div class="ml-container home-section home-market-layout"><div class="home-market-intro"><span class="shop-kicker">YOUR NEXT LOCAL STOP</span><h2>A place to meet.<br><em>A reason to return.</em></h2><p>Make a little time for market day. Find your local spot, meet the growers and bring home something good.</p><a href="{{ url('/markets') }}" class="shop-pill">Explore the markets <span>↗</span></a><div class="home-stall-art" aria-hidden="true"><i class="fa-solid fa-store"></i><span>See you<br><em>at the stall.</em></span></div></div><div class="home-market-list">@forelse($markets as $market)<a href="{{ url('/markets/'.$market->id) }}"><span class="home-market-number">0{{ $loop->iteration }}</span><div><small><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $market->city }}</small><h3>{{ $market->name }}</h3><p>{{ $market->operating_days }}<br>{{ substr($market->start_time,0,5) }} – {{ substr($market->end_time,0,5) }}</p></div><span class="home-market-arrow" aria-hidden="true">↗</span></a>@empty<p>New market locations will appear here when they’re added.</p>@endforelse</div></div></section>

    <section class="ml-container home-section home-story"><div class="home-story-photo"><img src="{{ asset('Assets/Website_Asset/images/vegetables/mix.jpg') }}" alt="A colourful selection of vegetables at a local market" width="612" height="405" loading="lazy"><span>Small stalls.<br><em>Shared stories.</em></span></div><div><span class="shop-kicker">MORE THAN A BASKET</span><h2>Know your food.<br><em>Meet your people.</em></h2><p>MarketLink brings the local market online, while keeping its best part in person. Farmers share their stock. You plan your basket. The connection happens at the stall.</p><ol class="home-simple-steps"><li><b>01</b> Discover a grower & their harvest.</li><li><b>02</b> Pre-order & choose a pickup window.</li><li><b>03</b> Collect at the market & pay directly.</li></ol><a href="{{ url('/about') }}" class="home-text-link">A little more about us <span>↗</span></a></div></section>

    <section class="home-section home-reviews" aria-labelledby="home-reviews-title"><div class="ml-container home-heading centered"><span class="shop-kicker">WORDS FROM THE COMMUNITY</span><h2 id="home-reviews-title">Local food.<br><em>Lasting impressions.</em></h2><p>Recent published reviews, straight from our grower profiles.</p></div>
        @if($reviews->isNotEmpty())<div class="swiper home-review-slider" aria-label="Community reviews"><div class="swiper-wrapper">@foreach($reviews as $review)<article class="swiper-slide home-review-slide"><i class="fa-solid fa-quote-left home-quote" aria-hidden="true"></i><blockquote>{{ $review->comment }}</blockquote><span class="home-review-stars" aria-label="{{ $review->rating }} out of 5 stars">@for($star=1;$star<=5;$star++)<i class="{{ $star <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star" aria-hidden="true"></i>@endfor</span><div class="home-review-person"><span aria-hidden="true">{{ mb_substr($review->user->name,0,1) }}</span><div><strong>{{ $review->user->name }}</strong><a href="{{ url('/farmers/'.$review->farmer_id).'#grower-reviews' }}">{{ $review->farmer->stall_name }} ↗</a></div></div></article>@endforeach</div></div><div class="home-slider-controls" data-review-controls hidden><button type="button" class="home-review-prev" aria-label="Previous community review">←</button><button type="button" data-review-play aria-label="Play automatic reviews"><i class="fa-solid fa-play" aria-hidden="true"></i></button><button type="button" class="home-review-next" aria-label="Next community review">→</button></div>@else<p class="home-review-empty">Every market visit has a story. Community reviews will appear here when they’re published.</p>@endif
    </section>

    <section class="ml-container home-final"><div><span class="shop-kicker">MAKE YOUR NEXT BASKET LOCAL</span><h2>Good things are<br><em>growing near you.</em></h2><p>Find your favourites. Choose your pickup. See you at the market.</p></div><div><a href="{{ url('/products') }}" class="shop-pill">Explore fresh produce <span>↗</span></a><a href="{{ route('register') }}" class="home-text-link">Have a harvest to share? Join us <span>↗</span></a></div><i class="fa-solid fa-seedling" aria-hidden="true"></i></section>
</div>
@endsection
@section('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@14.2.0/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="{{ asset('Assets/Website_Asset/js/home.js') }}" defer></script>
@endsection
