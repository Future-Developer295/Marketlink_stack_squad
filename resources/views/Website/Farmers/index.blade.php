@extends('Website._master')

@section('page_title', 'Browse Farmers')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <span class="active">Farmers</span></nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <span class="ml-eyebrow"><i class="fa-solid fa-tractor"></i> Verified Regional Growers</span>
            <h1 class="display-6">Meet Our Local Farmers</h1>
            <p class="text-muted mt-2">Browse verified grower profiles, review harvest history and explore their weekly stock before you reserve.</p>
            <form class="d-flex flex-column flex-md-row gap-2 mt-4" action="{{ url('/farmers') }}" method="GET">
                <div class="flex-grow-1 position-relative">
                    <i class="fa-solid fa-magnifying-glass position-absolute" style="left:16px; top:14px; color:var(--ml-text-muted);"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control ps-5" placeholder="Search farmers, stalls or produce...">
                </div>
                <button type="submit" class="btn ml-btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </form>
        </div>
    </div>
</section>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="ml-filter-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class="fa-solid fa-sliders"></i> Filter Farmers</h6>
                        <a href="{{ url('/farmers') }}" class="small ml-btn-link">Clear All</a>
                    </div>
                    <form action="{{ url('/farmers') }}" method="GET">
                        <input type="hidden" name="q" value="{{ request('q') }}">

                        <div class="ml-form-label">Location</div>
                        <select class="form-select mb-4" name="city">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>

                        <div class="ml-form-label">Sort By</div>
                        <select class="form-select mb-4" name="sort">
                            <option value="top_rated" {{ request('sort', 'top_rated') == 'top_rated' ? 'selected' : '' }}>Top Rated</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="most_products" {{ request('sort') == 'most_products' ? 'selected' : '' }}>Most Products</option>
                        </select>

                        <button type="submit" class="btn ml-btn-primary ml-btn-block"><i class="fa-solid fa-filter"></i> Apply Filters</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <strong>{{ $farmers->total() }} Farmers Found</strong>
                </div>

                <div class="row g-4">
                    @forelse($farmers as $farmer)
                    <div class="col-md-6 col-xl-4">
                        <div class="ml-media-card">
                            <div class="ml-media-card__image">
                                <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=700&q=60" alt="{{ $farmer->stall_name }}">
                                <button class="ml-favorite-btn ml-image-fav" type="button" aria-label="Save farmer"><i class="fa-regular fa-heart"></i></button>
                            </div>
                            <div class="ml-media-card__body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5>{{ $farmer->stall_name ?? $farmer->business_name }}</h5>
                                    @if($farmer->reviews_avg_rating)
                                    <span class="ml-rating small"><i class="fa-solid fa-star"></i> {{ number_format($farmer->reviews_avg_rating, 1) }} ({{ $farmer->reviews_count }})</span>
                                    @endif
                                </div>
                                <span class="text-muted small"><i class="fa-solid fa-user"></i> {{ $farmer->user->name ?? '' }}</span>
                                <span class="text-muted small"><i class="fa-solid fa-location-dot"></i> {{ $farmer->city }}, {{ $farmer->state }}</span>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="small text-muted">{{ $farmer->products_count }} Products</span>
                                    <a href="{{ url('/farmers/'.$farmer->id) }}" class="btn ml-btn-primary ml-btn-sm">View Farmer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        @include('Website.Partials.empty-state', [
                            'icon' => 'fa-tractor',
                            'title' => 'No Farmers Found',
                            'message' => 'Try adjusting your search query or clearing filters.',
                            'actionUrl' => url('/farmers'),
                            'actionLabel' => 'Reset Search Filters',
                        ])
                    </div>
                    @endforelse
                </div>

                @include('Website.Partials.pagination', ['paginator' => $farmers])
            </div>
        </div>
    </div>
</section>

@endsection
