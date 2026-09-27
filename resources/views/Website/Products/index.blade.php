@extends('Website._master')

@section('page_title', 'Products')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <span class="active">Products</span></nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <span class="ml-eyebrow"><i class="fa-solid fa-shield-halved"></i> Local Farms &middot; Weekly Fresh Stock &middot; Pre-Order &amp; Stall Pickup</span>
            <h1 class="display-6">Fresh Products From Local Farmers</h1>
            <p class="text-muted mt-2">Browse fresh weekly stock, compare prices and reserve products for convenient market pickup directly from verified local growers.</p>
            <form class="d-flex flex-column flex-md-row gap-2 mt-4" action="{{ url('/products') }}" method="GET">
                <div class="flex-grow-1 position-relative">
                    <i class="fa-solid fa-magnifying-glass position-absolute" style="left:16px; top:14px; color:var(--ml-text-muted);"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control ps-5" placeholder="Search products or farmers...">
                </div>
                <button type="submit" class="btn ml-btn-primary">Search Catalog <i class="fa-solid fa-arrow-right"></i></button>
            </form>
        </div>
    </div>
</section>

<section class="ml-section pt-3">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="ml-filter-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0"><i class="fa-solid fa-sliders"></i> Filter Products</h6>
                        <a href="{{ url('/products') }}" class="small ml-btn-link">Clear All</a>
                    </div>
                    <form action="{{ url('/products') }}" method="GET">
                        <input type="hidden" name="q" value="{{ request('q') }}">

                        <div class="ml-form-label">Category</div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="category_id" value="" id="catAll" {{ ! request('category_id') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label d-flex justify-content-between w-100" for="catAll">All Categories</label>
                        </div>
                        @foreach($categories as $category)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="category_id" value="{{ $category->id }}" id="cat{{ $category->id }}" {{ request('category_id') == $category->id ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label d-flex justify-content-between w-100" for="cat{{ $category->id }}">{{ $category->name }} <span class="text-muted">{{ $category->products_count }}</span></label>
                        </div>
                        @endforeach

                        <div class="ml-form-label mt-3">Max Price (Rs.)</div>
                        <input type="number" name="max_price" min="0" value="{{ request('max_price') }}" class="form-control mb-4" placeholder="No limit">

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="in_stock_only" value="1" id="inStockOnly" {{ request('in_stock_only') ? 'checked' : '' }}>
                            <label class="form-check-label" for="inStockOnly">In Stock Only</label>
                        </div>

                        <div class="ml-form-label">Sort By</div>
                        <select class="form-select mb-4" name="sort">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>

                        <button type="submit" class="btn ml-btn-primary ml-btn-block"><i class="fa-solid fa-check"></i> Apply Filters</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <strong>{{ $products->total() }} Products Found</strong>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                    <div class="col-md-6 col-xl-4">
                        <div class="ml-media-card">
                            <div class="ml-media-card__image">
                                <img src="{{ $product->image ? asset('product_images/' . $product->image) : 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=500&q=60' }}" alt="{{ $product->name }}">
                                <span class="ml-badge {{ $product->stock_quantity <= 0 ? 'ml-badge-danger' : 'ml-badge-mint' }} ml-image-tag">{{ $product->stock_quantity <= 0 ? 'Out of Stock' : ($product->category->name ?? 'Fresh') }}</span>
                                <button class="ml-favorite-btn ml-image-fav" type="button" aria-label="Save product"><i class="fa-regular fa-heart"></i></button>
                            </div>
                            <div class="ml-media-card__body">
                                <span class="small text-muted d-flex justify-content-between"><span><i class="fa-solid fa-user"></i> {{ $product->farmer->stall_name ?? $product->farmer->business_name ?? '' }}</span><span>Available: {{ $product->stock_quantity }} {{ $product->unit }}</span></span>
                                <h6>{{ $product->name }}</h6>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <strong class="text-success">Rs. {{ number_format($product->price, 0) }} <span class="fw-normal text-muted small">/ {{ $product->unit }}</span></strong>
                                    <span class="small text-muted">Stall pickup</span>
                                </div>
                                <div class="d-flex gap-2 mt-1">
                                    <a href="{{ url('/products/'.$product->id) }}" class="btn ml-btn-secondary ml-btn-sm flex-grow-1">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        @include('Website.Partials.empty-state', [
                            'icon' => 'fa-carrot',
                            'title' => 'No Products Found',
                            'message' => 'Try adjusting your search query or clearing filters.',
                            'actionUrl' => url('/products'),
                            'actionLabel' => 'Reset Search Filters',
                        ])
                    </div>
                    @endforelse
                </div>

                @include('Website.Partials.pagination', ['paginator' => $products])
            </div>
        </div>
    </div>
</section>

@endsection
