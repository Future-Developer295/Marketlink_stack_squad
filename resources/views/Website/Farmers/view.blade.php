@extends('Website._master')

@section('page_title', $farmer->stall_name ?? $farmer->business_name)

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ url('/farmers') }}">Farmers</a> / <span class="active">{{ $farmer->stall_name ?? $farmer->business_name }}</span></nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <div class="row g-4 align-items-center">
                <div class="col-auto">
                    <div class="ml-avatar-lg d-flex align-items-center justify-content-center bg-light" style="width:120px;height:120px;">
                        <i class="fa-solid fa-tractor text-success fs-1"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h1 class="h3 mb-0">{{ $farmer->stall_name ?? $farmer->business_name }}</h1>
                        @if($ratingCount > 0)
                        <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-star"></i> {{ $ratingAverage }} ({{ $ratingCount }} Reviews)</span>
                        @endif
                    </div>
                    <p class="text-muted mt-2 mb-3">{{ $farmer->description }}</p>
                    <div class="row g-2 small">
                        <div class="col-6 col-md-3"><span class="text-muted d-block">Farmer</span><strong>{{ $farmer->user->name ?? '' }}</strong></div>
                        <div class="col-6 col-md-3"><span class="text-muted d-block">Schedule</span><strong>{{ $farmer->operating_days }}</strong></div>
                        <div class="col-6 col-md-3"><span class="text-muted d-block">Pickup Window</span><strong>{{ $farmer->start_time }} - {{ $farmer->end_time }}</strong></div>
                        <div class="col-6 col-md-3"><span class="text-muted d-block">Products</span><strong>{{ $farmer->products_count }}</strong></div>
                    </div>
                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <a href="#stock" class="btn ml-btn-primary"><i class="fa-solid fa-basket-shopping"></i> Browse Weekly Stock</a>
                        <button class="btn ml-btn-secondary ml-favorite-btn-inline"><i class="fa-regular fa-heart"></i> Save Farmer</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-6 col-lg-3"><div class="ml-card"><span class="small text-muted d-block">Business Name</span><strong>{{ $farmer->business_name ?? '—' }}</strong></div></div>
            <div class="col-6 col-lg-3"><div class="ml-card"><span class="small text-muted d-block">Location</span><strong>{{ $farmer->city }}, {{ $farmer->state }}</strong></div></div>
            <div class="col-6 col-lg-3"><div class="ml-card"><span class="small text-muted d-block">Operating Days</span><strong>{{ $farmer->operating_days }}</strong></div></div>
            <div class="col-6 col-lg-3"><div class="ml-card"><span class="small text-muted d-block">Pickup Window</span><strong>{{ $farmer->start_time }} - {{ $farmer->end_time }}</strong></div></div>
        </div>
    </div>
</section>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="ml-eyebrow">Sustainable Agrarian Ethos</span>
                <h2>About This Farmer</h2>
                <p class="text-muted mt-3">{{ $farmer->description ?? 'This farmer has not added a description yet.' }}</p>
                <p class="text-muted">{{ $farmer->address }}, {{ $farmer->city }}, {{ $farmer->state }}, {{ $farmer->country }}</p>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1595855759920-86582396756c?auto=format&fit=crop&w=900&q=60" class="rounded-4 w-100" style="height:280px; object-fit:cover;" alt="{{ $farmer->stall_name }} fields">
                    <div class="ml-card position-absolute bottom-0 start-0 m-3 py-2 px-3">
                        <strong class="d-block small">{{ $farmer->city }}, {{ $farmer->state }}</strong>
                        <span class="small text-muted">{{ $farmer->approval_status === 'approved' ? 'Verified Grower' : $farmer->approval_status }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted" id="stock">
    <div class="ml-container">
        <div class="ml-heading-block mb-4">
            <span class="ml-eyebrow">Real-Time Field Inventory</span>
            <h2>Current Weekly Stock</h2>
            <p>See what this farmer currently has available for pre-order and market pickup.</p>
        </div>
        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-6 col-lg-3">
                <div class="ml-media-card">
                    <div class="ml-media-card__image">
                        <img src="{{ $product->image ? asset('product_images/' . $product->image) : 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=500&q=60' }}" alt="{{ $product->name }}">
                        <span class="ml-badge {{ $product->stock_quantity > 5 ? 'ml-badge-mint' : 'ml-badge-warning' }} ml-image-tag">{{ $product->stock_quantity > 5 ? 'In Stock' : 'Low Stock' }}</span>
                        <button class="ml-favorite-btn ml-image-fav" type="button" aria-label="Save product"><i class="fa-regular fa-heart"></i></button>
                    </div>
                    <div class="ml-media-card__body">
                        @if($product->category)
                        <span class="ml-badge ml-badge-mint align-self-start">{{ $product->category->name }}</span>
                        @endif
                        <h6>{{ $product->name }}</h6>
                        <p class="small text-muted mb-0">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <strong class="text-success">Rs. {{ number_format($product->price, 0) }} <span class="fw-normal text-muted small">/ {{ $product->unit }}</span></strong>
                        </div>
                        <span class="small text-muted">{{ $product->stock_quantity }} {{ $product->unit }} available</span>
                        <a href="{{ url('/products/'.$product->id) }}" class="btn ml-btn-primary ml-btn-sm ml-btn-block mt-1">View Product</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                @include('Website.Partials.empty-state', [
                    'icon' => 'fa-basket-shopping',
                    'title' => 'No Stock Listed',
                    'message' => 'This farmer has not published any products yet.',
                ])
            </div>
            @endforelse
        </div>
    </div>
</section>

@if($weeklyStock->isNotEmpty())
<section class="ml-section">
    <div class="ml-container">
        <div class="ml-heading-block mb-4">
            <h2>Weekly Schedule &amp; Field Availability</h2>
            <p>Track this farmer's weekly stock templates by day.</p>
        </div>
        <div class="row g-3 text-center">
            @foreach($weeklyStock as $template)
            <div class="col-6 col-md-3 col-lg">
                <div class="ml-card">
                    <strong class="d-block small">{{ $template->day_of_week }}</strong>
                    <i class="fa-solid fa-check text-success my-2"></i>
                    <span class="small text-muted d-block">{{ $template->product->name ?? '' }} &middot; {{ $template->quantity }} {{ $template->unit }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($markets->isNotEmpty())
<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block mb-4">
            <h2>Markets This Farmer Sells At</h2>
            <p>Explore the local community markets where this stall is open for pre-order collection.</p>
        </div>
        <div class="row g-4">
            @foreach($markets as $market)
            <div class="col-md-6">
                <div class="ml-card d-flex gap-3">
                    <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=200&q=60" class="rounded-3" style="width:100px;height:80px;object-fit:cover;" alt="{{ $market->name }}">
                    <div>
                        <strong class="d-block mt-1">{{ $market->name }}</strong>
                        <span class="small text-muted d-block">{{ $market->city }}, {{ $market->state }}</span>
                        <span class="small text-muted d-block">{{ $market->operating_days }} &middot; {{ $market->start_time }} - {{ $market->end_time }}</span>
                        <a href="{{ url('/markets/'.$market->id) }}" class="ml-btn-link small mt-1">View Market Details <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-heading-block mb-4">
            <h2>Customer Reviews</h2>
            <p>Feedback from shoppers with verified stall pickups.</p>
        </div>
        @if($reviews->isNotEmpty())
        <div class="row g-4">
            <div class="col-lg-3 text-center">
                <h1 class="display-4">{{ $ratingAverage }}</h1>
                <div class="ml-rating justify-content-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i > round($ratingAverage) ? 'text-muted' : '' }}"></i>
                    @endfor
                </div>
                <p class="text-muted small">Based on {{ $ratingCount }} verified reviews</p>
            </div>
            <div class="col-lg-9">
                <div class="row g-3">
                    @foreach($reviews as $review)
                    <div class="col-md-4">
                        <div class="ml-card h-100">
                            <div class="ml-rating small mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i > $review->rating ? 'text-muted' : '' }}"></i>
                                @endfor
                            </div>
                            <p class="small text-muted">{{ $review->comment }}</p>
                            <strong class="d-block small mt-2">{{ $review->user->name ?? 'Verified Customer' }}</strong>
                            <span class="small text-muted">{{ $review->created_at->diffForHumans() }}</span>
                            @if($review->reply)
                            <div class="mt-2 pt-2" style="border-top:1px solid var(--ml-border);">
                                <span class="small text-success d-block"><i class="fa-solid fa-reply"></i> Farmer replied</span>
                                <span class="small text-muted">{{ $review->reply->response }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        @include('Website.Partials.empty-state', [
            'icon' => 'fa-comment-slash',
            'title' => 'No Reviews Yet',
            'message' => 'Be the first to review this farmer after your pickup.',
        ])
        @endif
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-5">
                <span class="ml-eyebrow">Stall Geography</span>
                <h2>Pickup Location &amp; Stall Pin</h2>
                <p class="text-muted mt-2">Locate this farmer's stall easily during operational pickup hours.</p>
                <div class="ml-card mt-3">
                    <span class="small text-muted d-block">Stall Address</span>
                    <strong>{{ $farmer->address }}, {{ $farmer->city }}, {{ $farmer->state }}</strong>
                    <div class="row mt-3 small">
                        <div class="col-6"><span class="text-muted d-block">Pickup Window</span><strong>{{ $farmer->start_time }} - {{ $farmer->end_time }}</strong></div>
                        <div class="col-6"><span class="text-muted d-block">GPS Coordinates</span><strong>{{ $farmer->latitude }}, {{ $farmer->longitude }}</strong></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ml-map">
                    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1200&q=60" alt="Map to stall">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="ml-cta-banner text-center">
            <h2>Shop Local. Pick Up Fresh.</h2>
            <p class="mt-2 mb-4" style="color: rgba(255,255,255,0.85);">Discover fresh products from local farmers and reserve what you need before market day.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ url('/products') }}" class="btn ml-btn-primary">Browse Products</a>
                <a href="{{ url('/markets') }}" class="btn ml-btn-secondary">Explore Markets</a>
            </div>
        </div>
    </div>
</section>

@endsection
