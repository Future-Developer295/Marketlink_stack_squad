@extends('Website._master')

@section('page_title', $product->name)

@section('body')

<div class="ml-container">
    <div class="d-flex justify-content-between align-items-center">
        <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ url('/products') }}">Products</a> / <span class="active">{{ $product->name }}</span></nav>
        <a href="{{ url('/products') }}" class="ml-btn-link small"><i class="fa-solid fa-arrow-left"></i> Back to Products</a>
    </div>
</div>

<section class="pb-5">
    <div class="ml-container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="ml-media-card__image rounded-4" style="aspect-ratio:1/1;">
                    <img src="{{ $product->image ? asset('product_images/' . $product->image) : 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=900&q=70' }}" alt="{{ $product->name }}">
                    @if($product->category)
                    <span class="ml-badge ml-badge-mint ml-image-tag">{{ $product->category->name }}</span>
                    @endif
                    @auth
                        <form action="{{ route('favorite.toggle', ['type' => 'product', 'id' => $product->id]) }}" method="POST" class="ml-image-fav">
                            @csrf
                            <button class="ml-favorite-btn" type="submit" aria-label="{{ $isFavorited ? 'Remove from favorites' : 'Save product' }}">
                                <i class="fa-{{ $isFavorited ? 'solid' : 'regular' }} fa-heart"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="ml-favorite-btn ml-image-fav" aria-label="Save product"><i class="fa-regular fa-heart"></i></a>
                    @endauth
                </div>
            </div>

            <div class="col-lg-6">
                <h1 class="mt-2">{{ $product->name }}</h1>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <a href="{{ url('/farmers/'.$product->farmer_id) }}" class="small text-muted"><i class="fa-solid fa-tractor"></i> {{ $product->farmer->stall_name ?? $product->farmer->business_name ?? '' }} &middot; {{ $product->farmer->city ?? '' }}</a>
                    @if($ratingCount > 0)
                    <span class="ml-rating small"><i class="fa-solid fa-star"></i> {{ $ratingAverage }} ({{ $ratingCount }} Reviews)</span>
                    @endif
                </div>

                <div class="d-flex align-items-end gap-2 mt-4">
                    <h2 class="text-success mb-0">Rs. {{ number_format($product->price, 0) }}</h2>
                    <span class="text-muted">/ {{ $product->unit }} &middot; Market Stall Fair Rate</span>
                </div>
                <p class="text-muted mt-3">{{ $product->description }}</p>

                <form method="POST" action="{{ url('/cart/add/'.$product->id) }}">
                @csrf
                <div class="ml-card mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="ml-form-label mb-1">Quantity ({{ $product->unit }})</span>
                            <div class="ml-quantity" data-quantity data-min="1" data-max="{{ max($product->stock_quantity, 1) }}">
                                <button type="button" data-decrement>&minus;</button>
                                <input type="text" name="quantity" value="1" readonly>
                                <button type="button">+</button>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="small text-muted d-block">Available: {{ $product->stock_quantity }} {{ $product->unit }}</span>
                            <strong>Subtotal</strong>
                            <h4 class="text-success mb-0">Rs. {{ number_format($product->price, 0) }}</h4>
                        </div>
                    </div>
                </div>

                @if($product->stock_quantity > 0)
                <div class="d-flex gap-3 mt-3">
                    <button type="submit" class="btn ml-btn-primary flex-grow-1"><i class="fa-solid fa-cart-plus"></i> Add to Cart <span class="small d-block fw-normal">Reserve for pickup</span></button>
                    <button type="button" class="btn ml-btn-secondary"><i class="fa-regular fa-heart"></i> Save to Favorites</button>
                </div>
                @else
                <button type="button" class="btn ml-btn-secondary flex-grow-1 mt-3" disabled>Currently Out of Stock</button>
                @endif
                </form>
                <p class="small text-muted mt-3"><i class="fa-solid fa-circle-check text-success"></i> Pickup Only &middot; Reserve online and inspect your produce directly at stall before settling payment in person.</p>

                <div class="row g-3 mt-3">
                    <div class="col-6">
                        <div class="ml-card">
                            <span class="small text-muted d-block">Category</span><strong>{{ $product->category->name ?? '—' }}</strong>
                            <span class="small text-muted d-block mt-2">Available Stock</span><strong>{{ $product->stock_quantity }} {{ $product->unit }}</strong>
                            <span class="small text-muted d-block mt-2">Farmer &amp; Producer</span><strong>{{ $product->farmer->stall_name ?? $product->farmer->business_name ?? '' }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="ml-card">
                            <span class="small text-muted d-block">Price &amp; Unit</span><strong>Rs. {{ number_format($product->price, 0) }} / {{ $product->unit }}</strong>
                            <span class="small text-muted d-block mt-2">Status</span><strong>{{ $product->is_active ? 'Active Listing' : 'Inactive Listing' }}</strong>
                            <span class="small text-muted d-block mt-2">Field Origin</span><strong>{{ $product->farmer->city ?? '' }}, {{ $product->farmer->state ?? '' }}</strong>
                        </div>
                    </div>
                </div>

                @if($pickupSlots->isNotEmpty())
                <div class="ml-card mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong><i class="fa-regular fa-calendar"></i> Upcoming Pickup Slots</strong>
                    </div>
                    <div class="row g-2 small">
                        @foreach($pickupSlots as $slot)
                        <div class="col-6">
                            <div class="ml-card py-2">
                                <strong class="d-block">{{ \Illuminate\Support\Carbon::parse($slot->date)->format('D, d M') }}</strong>
                                <span class="text-muted">{{ $slot->start_time }} - {{ $slot->end_time }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="ml-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong><i class="fa-solid fa-tractor text-success"></i> Sold By Producer</strong>
                        @if($product->farmer && $product->farmer->approval_status === 'approved')
                        <span class="ml-badge ml-badge-mint">Verified</span>
                        @endif
                    </div>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="ml-avatar-lg d-flex align-items-center justify-content-center bg-light">
                            <i class="fa-solid fa-tractor text-success fs-3"></i>
                        </div>
                        <div>
                            <strong>{{ $product->farmer->user->name ?? '' }}</strong>
                            <span class="small text-muted d-block">{{ $product->farmer->stall_name ?? $product->farmer->business_name ?? '' }}</span>
                            <span class="small text-muted d-block">{{ $product->farmer->city ?? '' }}, {{ $product->farmer->state ?? '' }}</span>
                        </div>
                    </div>
                    <div class="row small text-muted mt-3">
                        <div class="col-6"><span class="d-block">Operating Days</span><strong class="text-dark">{{ $product->farmer->operating_days ?? '' }}</strong></div>
                        <div class="col-6"><span class="d-block">Pickup Window</span><strong class="text-dark">{{ $product->farmer->start_time ?? '' }} - {{ $product->farmer->end_time ?? '' }}</strong></div>
                    </div>
                    <a href="{{ url('/farmers/'.$product->farmer_id) }}" class="btn ml-btn-secondary ml-btn-block mt-3">View Farmer Profile</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ml-card h-100">
                    <strong><i class="fa-solid fa-map-pin text-success"></i> Pickup Address</strong>
                    <h5 class="mt-2">{{ $product->farmer->stall_name ?? $product->farmer->business_name ?? '' }}</h5>
                    <span class="small text-muted"><i class="fa-solid fa-location-dot"></i> {{ $product->farmer->address ?? '' }}, {{ $product->farmer->city ?? '' }}</span>
                    <div class="ml-map mt-3" style="min-height:140px;">
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=700&q=60" alt="Stall floor map">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ml-section ml-section--muted">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-3 text-center">
                <h1 class="display-4">{{ $ratingCount > 0 ? $ratingAverage : '—' }}</h1>
                <div class="ml-rating justify-content-center mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i > round($ratingAverage) ? 'text-muted' : '' }}"></i>
                    @endfor
                </div>
                <p class="text-muted small">Based on {{ $ratingCount }} verified market pickup reviews</p>
            </div>
            <div class="col-lg-9">
                @forelse($reviews as $review)
                @if($loop->first)
                <div class="row g-3">
                @endif
                    <div class="col-md-4">
                        <div class="ml-card h-100">
                            <div class="ml-rating small mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i > $review->rating ? 'text-muted' : '' }}"></i>
                                @endfor
                            </div>
                            <p class="small text-muted">{{ $review->comment }}</p>
                            <strong class="d-block small mt-2">{{ $review->user->name ?? 'Verified Buyer' }}</strong>
                            <span class="small text-muted">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @if($loop->last)
                </div>
                @endif
                @empty
                @include('Website.Partials.empty-state', [
                    'icon' => 'fa-comment-slash',
                    'title' => 'No Reviews Yet',
                    'message' => 'Be the first to review this product after your pickup.',
                ])
                @endforelse
            </div>
        </div>

        @if($canReviewProduct)
        <div class="row mt-4">
            <div class="col-lg-6 mx-auto">
                <div class="ml-card">
                    <h6 class="mb-3">{{ $myProductReview ? 'Update Your Review' : 'Rate This Product' }}</h6>
                    <form method="POST" action="{{ route('products.review.store', ['product' => $product->id]) }}">
                        @csrf
                        <div class="ml-form-label">Rating</div>
                        <select name="rating" class="form-select mb-3" required>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ optional($myProductReview)->rating == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                        <div class="ml-form-label">Comment</div>
                        <textarea name="comment" class="form-control mb-3" rows="3" placeholder="Share your experience with this product">{{ optional($myProductReview)->comment }}</textarea>
                        <button type="submit" class="btn ml-btn-primary">{{ $myProductReview ? 'Update Review' : 'Submit Review' }}</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<section class="ml-section">
    <div class="ml-container">
        <div class="d-flex justify-content-between align-items-end ml-heading-block mb-4">
            <div>
                <h2>You May Also Like</h2>
                <p>Fresh seasonal picks from the same farm &amp; category.</p>
            </div>
            <a href="{{ url('/products') }}" class="ml-link-more">Explore All Fresh Produce <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            @forelse($relatedProducts as $related)
            <div class="col-6 col-lg-3">
                <div class="ml-media-card">
                    <div class="ml-media-card__image">
                        <img src="{{ $related->image ?? 'https://images.unsplash.com/photo-1583119022894-919a68a3d0e3?auto=format&fit=crop&w=500&q=60' }}" alt="{{ $related->name }}">
                    </div>
                    <div class="ml-media-card__body">
                        <span class="small text-muted">{{ $related->farmer->stall_name ?? $related->farmer->business_name ?? '' }}</span>
                        <h6>{{ $related->name }}</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-success">Rs. {{ number_format($related->price, 0) }} <span class="fw-normal text-muted small">/ {{ $related->unit }}</span></strong>
                            <a href="{{ url('/products/'.$related->id) }}" class="btn ml-btn-primary ml-btn-sm"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted small">No related products to show right now.</div>
            @endforelse
        </div>
    </div>
</section>

@endsection
