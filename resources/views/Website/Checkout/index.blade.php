@extends('Website._master')

@section('page_title', 'Checkout')

@section('body')

<div class="ml-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ url('/cart') }}">Cart</a> / <span class="active">Checkout</span></nav>
        <span class="ml-badge ml-badge-mint"><i class="fa-solid fa-shield-halved"></i> 100% In-Person Stall Payment &middot; Zero Online Card Fees</span>
    </div>
</div>

<section class="pb-4">
    <div class="ml-container">
        <span class="ml-eyebrow"><i class="fa-solid fa-clipboard-check"></i> Pre-Order Checkout</span>
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="display-6">Confirm Your Order</h1>
                <p class="text-muted">Review your seasonal products and pick a guaranteed market pickup slot before placing your direct reservation.</p>
            </div>
            <div class="ml-progress-steps">
                <span class="ml-progress-step is-done"><span class="ml-step-num"><i class="fa-solid fa-check"></i></span> Harvest Basket</span>
                <span class="ml-progress-step is-active"><span class="ml-step-num">2</span> Pickup Details</span>
                <span class="ml-progress-step"><span class="ml-step-num">3</span> Confirm Order</span>
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
            'title' => 'Nothing to Checkout',
            'message' => 'Your cart is empty. Browse fresh weekly stock to start a reservation.',
            'actionUrl' => url('/products'),
            'actionLabel' => 'Browse Products',
        ])
        @else
        <form method="POST" action="{{ url('/checkout') }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="ml-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong><i class="fa-solid fa-bag-shopping text-success"></i> Review Your Products</strong>
                        <span class="ml-badge ml-badge-mint">{{ $cartItems->count() }} Items Reserved</span>
                    </div>
                    @foreach($cartItems as $item)
                    @php($product = $item['product'])
                    <div class="d-flex gap-3 align-items-center py-3" style="border-top:1px solid var(--ml-border);">
                        <img src="{{ $product->image ? asset('product_images/' . $product->image) : 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=150&q=60' }}" class="rounded-3" style="width:70px;height:70px;object-fit:cover;" alt="{{ $product->name }}">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ $product->name }}</strong>
                                <strong class="text-success">Rs. {{ number_format($item['subtotal'], 0) }}</strong>
                            </div>
                            <span class="small text-muted d-block"><i class="fa-solid fa-tractor"></i> {{ $product->farmer->stall_name ?? $product->farmer->business_name ?? '' }}</span>
                            <span class="small text-muted">Rate: Rs. {{ number_format($product->price, 0) }} / {{ $product->unit }} &middot; Qty: {{ $item['quantity'] }} {{ $product->unit }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="ml-card mb-4 d-flex align-items-start gap-3" style="background:var(--ml-mint-soft);">
                    <i class="fa-solid fa-people-group text-success fs-4"></i>
                    <div>
                        <strong>Multi-Farmer Harvest Basket</strong>
                        <p class="small text-muted mb-0 mt-1">Your cart gathers seasonal produce from {{ $farmerGroups->count() }} local {{ \Illuminate\Support\Str::plural('farm', $farmerGroups->count()) }}. Please select your pickup slot for each stall below.</p>
                    </div>
                </div>

                @foreach($farmerGroups as $farmerId => $items)
                @php($farmer = $items->first()['product']->farmer)
                @php($slots = $pickupSlotsByFarmer->get($farmerId, collect()))
                <div class="ml-card mb-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <strong>{{ $farmer->stall_name ?? $farmer->business_name }}</strong>
                            <span class="small text-muted d-block"><i class="fa-solid fa-location-dot"></i> {{ $farmer->city }}, {{ $farmer->state }}</span>
                        </div>
                        <span class="small text-muted">{{ $items->count() }} {{ \Illuminate\Support\Str::plural('Item', $items->count()) }} Reserved (Rs. {{ number_format($items->sum('subtotal'), 0) }})</span>
                    </div>
                    <div class="mt-3">
                        <label class="ml-form-label small">Select Pickup Slot</label>
                        @if($slots->isNotEmpty())
                        <select name="pickup_slot[{{ $farmerId }}]" class="form-select" required>
                            <option value="" disabled selected>Choose a pickup slot</option>
                            @foreach($slots as $slot)
                            <option value="{{ $slot->id }}">{{ \Illuminate\Support\Carbon::parse($slot->date)->format('D, d M Y') }} &middot; {{ $slot->start_time }} - {{ $slot->end_time }}</option>
                            @endforeach
                        </select>
                        @else
                        <p class="small text-danger mb-0"><i class="fa-solid fa-triangle-exclamation"></i> No published pickup slots for this farmer yet. Checkout cannot be completed until a pickup slot is available.</p>
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="ml-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong><i class="fa-solid fa-id-card text-success"></i> Your Profile</strong>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="ml-form-label">Full Name</label>
                            <input type="text" class="form-control" value="{{ $customer->name ?? '' }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="ml-form-label">Phone Number</label>
                            <input type="text" class="form-control" value="{{ $customer->phone ?? '' }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="ml-form-label">Email Address</label>
                            <input type="email" class="form-control" value="{{ $customer->email ?? '' }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="ml-form-label">Address</label>
                            <input type="text" class="form-control" value="{{ $customer->address ?? '' }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="ml-form-label">Note for Farmers (Optional)</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="E.g., Please select firm produce and pack carefully...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="ml-card mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Order Summary</strong>
                        <span class="ml-badge ml-badge-mint">Stall Settlement</span>
                    </div>
                    <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-3">
                        <li class="d-flex justify-content-between"><span>Selected Products ({{ $cartItems->count() }} Items, {{ $cartItems->sum('quantity') }} units)</span><strong class="text-dark">Rs. {{ number_format($cartTotal, 0) }}</strong></li>
                        <li class="d-flex justify-content-between"><span>Stall Collection &amp; Crate Tagging</span><strong class="text-success">Rs. 0 (Free)</strong></li>
                        <li class="d-flex justify-content-between"><span>Delivery &amp; Courier Charge</span><strong class="text-success">Rs. 0 (Zero Delivery)</strong></li>
                    </ul>
                    <div class="p-3" style="background:var(--ml-mint-soft); border-radius:var(--ml-radius);">
                        <span class="small text-muted d-block">Due at Stall</span>
                        <h3 class="text-success mb-0">Rs. {{ number_format($cartTotal, 0) }}</h3>
                        <span class="small text-muted">Zero upfront transaction fee &middot; Paid upon collection</span>
                    </div>
                    <p class="small text-muted mt-3"><i class="fa-solid fa-money-bill-wave text-success"></i> 100% In-Person Payment At Stall.</p>
                    @auth
                    <button type="submit" class="btn ml-btn-primary ml-btn-block"><i class="fa-solid fa-circle-check"></i> Place Pre-Order (Rs. {{ number_format($cartTotal, 0) }})</button>
                    @else
                    <a href="{{ url('/login') }}" class="btn ml-btn-primary ml-btn-block"><i class="fa-solid fa-right-to-bracket"></i> Login to Place Order</a>
                    @endauth
                    <p class="text-center small text-muted mt-2 mb-0">By clicking above, you confirm your reservation for physical collection.</p>
                    <a href="{{ url('/cart') }}" class="text-center d-block small ml-btn-link justify-content-center mt-2"><i class="fa-solid fa-arrow-left"></i> Return to Shopping Basket</a>
                </div>
            </div>
        </div>
        </form>
        @endif
    </div>
</section>

@endsection
