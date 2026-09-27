@extends('Website._master')

@section('page_title', 'Farmers')

@section('body')

<section class="ml-section pt-4">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="ml-filter-panel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0"><i class="fa-solid fa-sliders me-2"></i>Filter Farmers</h5>
                        <a href="{{ url('/farmers') }}" class="ml-btn-link">Clear All</a>
                    </div>

                    <form action="{{ url('/farmers') }}" method="GET">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <div class="mb-4">
                            <label class="ml-form-label mb-2">Location</label>
                            <select name="city" class="form-select">
                                <option value="">All Cities</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="ml-form-label mb-2">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="top_rated" {{ request('sort', 'top_rated') == 'top_rated' ? 'selected' : '' }}>Top Rated</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="most_products" {{ request('sort') == 'most_products' ? 'selected' : '' }}>Most Products</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            </select>
                        </div>

                        <button type="submit" class="btn ml-btn-primary w-100">
                            <i class="fa-solid fa-filter me-2"></i>Apply Filters
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="mb-0">{{ $farmers->total() }} Farmers Found</h5>
                </div>

                <div class="row g-4">
                    @forelse($farmers as $farmer)
                        <div class="col-md-6 col-xl-4">
                            <div class="ml-media-card h-100">
                                <div class="ml-media-card__image position-relative" style="height:260px;">
                                    <img
                                        src="{{ $farmer->farmer_image ? asset('farmer_images/' . $farmer->farmer_image) : 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=700&q=80' }}"
                                        alt="{{ $farmer->stall_name ?? $farmer->business_name }}"
                                        style="width:100%;height:100%;object-fit:cover;"
                                    >
                                    @auth
                                        <form action="{{ route('favorite.toggle', ['type' => 'farmer', 'id' => $farmer->id]) }}" method="POST" class="ml-image-fav">
                                            @csrf
                                            <button type="submit" class="ml-favorite-btn" aria-label="{{ $favoritedFarmerIds->contains($farmer->id) ? 'Remove from favorites' : 'Save farmer' }}">
                                                <i class="fa-{{ $favoritedFarmerIds->contains($farmer->id) ? 'solid' : 'regular' }} fa-heart"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="ml-favorite-btn ml-image-fav" aria-label="Save farmer"><i class="fa-regular fa-heart"></i></a>
                                    @endauth
                                </div>

                                <div class="ml-media-card__body">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <h4 class="mb-1">{{ $farmer->stall_name ?? $farmer->business_name }}</h4>
                                        @if($farmer->reviews_count > 0)
                                            <span class="ml-rating">
                                                <i class="fa-solid fa-star"></i>
                                                {{ number_format($farmer->reviews_avg_rating ?? 0, 1) }}
                                                <span>({{ $farmer->reviews_count }})</span>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-muted mb-2">
                                        <i class="fa-solid fa-user me-2"></i>{{ $farmer->user->name ?? 'Farmer' }}
                                    </div>

                                    <div class="text-muted mb-4">
                                        <i class="fa-solid fa-location-dot me-2"></i>
                                        {{ $farmer->city ?: 'Location not added' }}@if($farmer->state), {{ $farmer->state }}@endif
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span class="text-muted">
                                            {{ $farmer->products_count }} {{ $farmer->products_count == 1 ? 'Product' : 'Products' }}
                                        </span>
                                        <a href="{{ url('/farmers/' . $farmer->id) }}" class="btn ml-btn-primary ml-btn-sm">View Farmer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="ml-card text-center py-5">
                                <i class="fa-solid fa-tractor fs-1 text-success mb-3"></i>
                                <h4>No Farmers Found</h4>
                                <p class="text-muted">Try changing your filters.</p>
                                <a href="{{ url('/farmers') }}" class="btn ml-btn-primary">Clear Filters</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($farmers->hasPages())
                    <div class="mt-5">
                        {{ $farmers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
