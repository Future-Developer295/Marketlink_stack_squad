@extends('Website._master')

@section('page_title', 'Cart & Reservation')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <span class="active">Cart &amp; Reservation</span></nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <span class="ml-eyebrow"><i class="fa-solid fa-bag-shopping"></i> Your Cart &amp; Pre-Orders</span>
                    <h1 class="display-6">Review Your Reservation</h1>
                    <p class="text-muted mt-2 mb-0">Review your selected products and quantities before placing your reservation. Settle payment in person upon inspecting your fresh harvest directly at the market stall.</p>
                </div>
                <div class="col-lg-4">
                    <div class="ml-card text-center">
                        <i class="fa-solid fa-shield-halved text-success fs-3"></i>
                        <strong class="d-block mt-2">100% In-Person Settlement</strong>
                        <span class="small text-muted">No cards or upfront fees required</span>
                    </div>
                </div>
            </div>
            <div class="ml-progress-steps mt-4">
                <span class="ml-progress-step is-done"><span class="ml-step-num"><i class="fa-solid fa-check"></i></span> Browse Products</span>
                <span class="ml-progress-step is-active"><span class="ml-step-num">2</span> Review Cart &amp; Reservation</span>
                <span class="ml-progress-step"><span class="ml-step-num">3</span> Pickup at Market Stall</span>
            </div>
        </div>
    </div>
</section>

<section class="ml-section pt-2">
    <div class="ml-container">
        @include('Website.Partials.alerts')

        @if($cartItems->isEmpty())
        @include('Website.Partials.empty-state', [
            'icon' => 'fa-cart-shopping',
            'title' => 'Your Cart Is Empty',
            'message' => 'Browse fresh weekly stock and add products to reserve them for market pickup.',
            'actionUrl' => url('/products'),
            'actionLabel' => 'Browse Products',
        ])
        @else
        <div class="ml-card d-flex align-items-center gap-3 mb-4">
            <i class="fa-solid fa-store text-success fs-4"></i>
            <div>
                <strong>Multi-Farmer Market Hub Reservation</strong> <span class="ml-badge ml-badge-mint ms-1">{{ $farmerGroups->count() }} {{ \Illuminate\Support\Str::plural('Grower', $farmerGroups->count()) }} Included</span>
                <p class="small text-muted mb-0 mt-1">Your cart contains products from {{ $farmerGroups->count() }} local {{ \Illuminate\Support\Str::plural('farmer', $farmerGroups->count()) }}. Pickup dates and time slots must be confirmed individually within each farmer's verified operating schedule.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                @foreach($farmerGroups as $farmerId => $items)
                @php($farmer = $items->first()['product']->farmer)
                <div class="ml-card mb-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <strong>{{ $farmer->stall_name ?? $farmer->business_name }}</strong>
                            <span class="small text-muted d-block"><i class="fa-solid fa-location-dot"></i> {{ $farmer->city }}, {{ $farmer->state }}</span>
                        </div>
                        <span class="small text-muted"><i class="fa-regular fa-clock"></i> {{ $farmer->operating_days }} &middot; {{ $farmer->start_time }} - {{ $farmer->end_time }}</span>
                    </div>

                    @foreach($items as $item)
                    @php($product = $item['product'])
                    <div class="d-flex gap-3 align-items-center py-3" style="border-top:1px solid var(--ml-border);">
                        <img src="{{ $product->image ? asset('product_images/' . $product->image) : 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=150&q=60' }}" class="rounded-3" style="width:80px;height:80px;object-fit:cover;" alt="{{ $product->name }}">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $product->name }}</strong>
                                <strong class="text-success">Rs. {{ number_format($item['subtotal'], 0) }}</strong>
                            </div>
                            <span class="small text-muted d-block">Rs. {{ number_format($product->price, 0) }} / {{ $product->unit }}</span>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <form method="POST" action="{{ url('/cart/update/'.$product->id) }}">
                                    @csrf
                                    <div class="ml-quantity" data-quantity data-auto-submit data-min="0" data-max="{{ $product->stock_quantity }}">
                                        <button type="button" data-decrement>&minus;</button>
                                        <input type="text" name="quantity" value="{{ $item['quantity'] }}" readonly>
                                        <button type="button">+</button>
                                    </div>
                                </form>
                                <form method="POST" action="{{ url('/cart/remove/'.$product->id) }}">
                                    @csrf
                                    <button type="submit" class="ml-btn-link small"><i class="fa-solid fa-trash"></i> Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="ml-card mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Reservation Summary</strong>
                        <span class="ml-badge ml-badge-mint">Zero Online Prepay</span>
                    </div>
                    <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-3">
                        <li class="d-flex justify-content-between"><span>{{ $cartItems->count() }} {{ \Illuminate\Support\Str::plural('Product', $cartItems->count()) }} ({{ $cartItems->sum('quantity') }} Units)</span><strong class="text-dark">Rs. {{ number_format($cartTotal, 0) }}</strong></li>
                        <li class="d-flex justify-content-between"><span>Stall Collection Fee</span><strong class="text-success">Rs. 0 (Free Pickup)</strong></li>
                    </ul>
                    <div class="p-3" style="background:var(--ml-mint-soft); border-radius:var(--ml-radius);">
                        <span class="small text-muted d-block">Estimated Due at Stall</span>
                        <h3 class="text-success mb-0">Rs. {{ number_format($cartTotal, 0) }}</h3>
                        <span class="small text-muted">Paid directly upon inspection</span>
                    </div>
                    <a href="{{ url('/checkout') }}" class="btn ml-btn-primary ml-btn-block mt-3"><i class="fa-solid fa-square-check"></i> Proceed to Checkout</a>
                    <a href="{{ url('/products') }}" class="text-center d-block small ml-btn-link justify-content-center mt-2"><i class="fa-solid fa-arrow-left"></i> Continue Browsing Products</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
