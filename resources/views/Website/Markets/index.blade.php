@extends('Website._master')

@section('page_title', 'Browse Markets')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <span class="active">Markets</span></nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <span class="ml-eyebrow"><i class="fa-solid fa-store"></i> Explore Local Markets</span>
            <h1 class="display-6">Browse Local Markets</h1>
            <p class="text-muted mt-2">Discover nearby market pavilions, meet verified regional growers, and reserve fresh harvest crates directly for stall pickup.</p>
            <form class="d-flex flex-column flex-md-row gap-2 mt-4" action="{{ url('/markets') }}" method="GET">
                <div class="flex-grow-1 position-relative">
                    <i class="fa-solid fa-magnifying-glass position-absolute" style="left:16px; top:14px; color:var(--ml-text-muted);"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control ps-5" placeholder="Search markets, city or area...">
                </div>
                <button type="submit" class="btn ml-btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </form>
            <p class="small text-muted mt-3 mb-0"><i class="fa-solid fa-shield-halved text-success"></i> Zero shipping fees &middot; Direct stall-side grower collection</p>
        </div>
    </div>
</section>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="ml-filter-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class="fa-solid fa-sliders"></i> Filter Markets</h6>
                        <a href="{{ url('/markets') }}" class="small ml-btn-link">Clear All</a>
                    </div>
                    <form action="{{ url('/markets') }}" method="GET">
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
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="most_farmers" {{ request('sort') == 'most_farmers' ? 'selected' : '' }}>Most Farmers</option>
                        </select>

                        <button type="submit" class="btn ml-btn-primary ml-btn-block"><i class="fa-solid fa-filter"></i> Apply Filters</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <strong>{{ $markets->total() }} Markets Found</strong>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse($markets as $market)
                    <div class="col-md-6 col-xl-4">
                        <div class="ml-media-card">
                            <div class="ml-media-card__image">
                                <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=700&q=60" alt="{{ $market->name }}">
                                <button class="ml-favorite-btn ml-image-fav" type="button" aria-label="Save market"><i class="fa-regular fa-heart"></i></button>
                            </div>
                            <div class="ml-media-card__body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="ml-badge ml-badge-mint">{{ $market->market_farmers_count }} Farmers</span>
                                </div>
                                <h5>{{ $market->name }}</h5>
                                <span class="text-muted small"><i class="fa-solid fa-location-dot"></i> {{ $market->city }}, {{ $market->state }}</span>
                                <span class="text-muted small"><i class="fa-solid fa-calendar-days"></i> {{ $market->operating_days }}</span>
                                <span class="text-muted small"><i class="fa-regular fa-clock"></i> {{ $market->start_time }} - {{ $market->end_time }}</span>
                                <a href="{{ url('/markets/'.$market->id) }}" class="btn ml-btn-primary ml-btn-block mt-2">View Details <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        @include('Website.Partials.empty-state', [
                            'icon' => 'fa-store-slash',
                            'title' => 'No Markets Found',
                            'message' => 'Try adjusting your search query or clearing filters to discover produce hubs in neighboring areas.',
                            'actionUrl' => url('/markets'),
                            'actionLabel' => 'Reset Search Filters',
                        ])
                    </div>
                    @endforelse
                </div>

                @include('Website.Partials.pagination', ['paginator' => $markets])
            </div>
        </div>
    </div>
</section>

@endsection
