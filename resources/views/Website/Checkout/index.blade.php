@extends('Website._master')
@section('page_title', 'Choose your pickup')
@section('body')
    <div class="ml-container shop-checkout">
        <a href="{{ url('/cart') }}" class="basket-back">← Back to your basket</a>
        @include('Website.Partials.shop-banner', ['kind' => 'checkout'])
        <div class="checkout-steps"><span>01 · Your basket</span><strong>02 · Pickup details</strong><span>03 · See you at
                the market</span></div>
        @include('Website.Partials.alerts')
        @if ($cartItems->isEmpty())
            <div class="shop-empty"><i
                    class="fa-solid {{ session('success') ? 'fa-circle-check' : 'fa-basket-shopping' }}"></i>
                <h2>{{ session('success') ? "You're on the list." : 'A fresh start awaits.' }}</h2>
                <p>{{ session('success') ? 'Your pre-order is with your growers. Check your account for updates before pickup.' : 'Find your favourites and add them to your basket first.' }}
                </p><a class="shop-pill"
                    href="{{ session('success') ? route('customer_dashboard') : url('/products') }}">{{ session('success') ? 'Go to my account' : 'Explore the harvest' }}
                    ↗</a>
            </div>
            @if ($placedOrders->isNotEmpty())
                <div class="placed-orders">
                    <h2 class="placed-orders-title">What you ordered</h2>
                    @foreach ($placedOrders as $placed)
                        <section class="basket-grower">
                            <header class="basket-grower-head"><i class="fa-solid fa-tractor"></i>
                                <div><strong>Pre-order #{{ $placed->id }} · {{ $placed->farmer?->stall_name ?? 'Local grower' }}</strong>
                                    <span>{{ $placed->pickupSlot?->date?->format('D, d M Y') }}
                                        @if ($placed->pickupSlot)
                                            · {{ substr($placed->pickupSlot->start_time, 0, 5) }} – {{ substr($placed->pickupSlot->end_time, 0, 5) }}
                                        @endif
                                        @if ($placed->pickupSlot?->market)
                                            · {{ $placed->pickupSlot->market->name }}
                                        @endif
                                    </span></div>
                            </header>
                            @foreach ($placed->items as $line)
                                <div class="checkout-item">
                                    @if ($line->product)
                                        <img src="{{ $line->product->imageUrl() }}" alt="{{ $line->product->name }}">
                                    @endif
                                    <div><strong>{{ $line->product?->name ?? 'Unavailable product' }}</strong><small>{{ $line->quantity }}
                                            {{ $line->product?->unit }} · Rs. {{ number_format($line->price, 0) }} each</small></div>
                                    <b>Rs. {{ number_format($line->subtotal, 0) }}</b>
                                </div>
                            @endforeach
                            <div class="placed-order-total"><span>Total · pay at pickup</span><strong>Rs. {{ number_format($placed->total_amount, 0) }}</strong></div>
                            <a class="placed-order-link" href="{{ route('customer_order_detail', $placed) }}">View pre-order details ↗</a>
                        </section>
                    @endforeach
                    <div class="placed-order-total placed-order-grand"><span>All pre-orders</span><strong>Rs. {{ number_format($placedOrders->sum('total_amount'), 0) }}</strong></div>
                </div>
            @endif
        @else
            @php($missingSlots = $farmerGroups->keys()->contains(fn($id) => $pickupSlotsByFarmer->get($id, collect())->isEmpty()))
            <form method="POST" action="{{ url('/checkout') }}">@csrf
                <div class="basket-layout">
                    <div>
                        @foreach ($farmerGroups as $farmerId => $items)
                            @php($farmer = $items->first()['product']->farmer)
                            @php($slots = $pickupSlotsByFarmer->get($farmerId, collect()))
                            <section class="basket-grower" data-aos="fade-up">
                                <header class="basket-grower-head"><i class="fa-solid fa-tractor"></i>
                                    <div><strong>{{ $farmer->stall_name ?? 'Local grower' }}</strong><span>{{ $farmer->address ?? '' }},
                                            {{ $farmer->city ?? '' }}</span></div>
                                </header>
                                @foreach ($items as $item)
                                    <div class="checkout-item"><img src="{{ $item['product']->imageUrl() }}"
                                            alt="{{ $item['product']->name }}">
                                        <div><strong>{{ $item['product']->name }}</strong><small>{{ $item['quantity'] }}
                                                {{ $item['product']->unit }} · Rs.
                                                {{ number_format($item['product']->price, 0) }} each</small></div><b>Rs.
                                            {{ number_format($item['subtotal'], 0) }}</b>
                                    </div>
                                @endforeach
                                <div class="checkout-slot"><label for="pickup-{{ $farmerId }}">A time that works for
                                        you</label>
                                    @if ($slots->isNotEmpty())
                                        <select id="pickup-{{ $farmerId }}" name="pickup_slot[{{ $farmerId }}]"
                                            class="form-select" required>
                                            @foreach ($slots as $slot)
                                                <option value="{{ $slot->id }}" @selected(old("pickup_slot.$farmerId") == $slot->id)>
                                                    {{ $slot->date->format('D, d M Y') }} ·
                                                    {{ substr($slot->start_time, 0, 5) }} –
                                                    {{ substr($slot->end_time, 0, 5) }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <p>This grower has no available pickup times. <a href="{{ url('/cart') }}">Edit
                                                your basket</a> or check back later.</p>
                                    @endif
                                    @error('pickup_slot.' . $farmerId)
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </section>
                        @endforeach
                        <section class="basket-grower" data-aos="fade-up"><span class="shop-kicker">A PERSONAL TOUCH</span>
                            <h2 class="mt-2">Leave a little note.</h2><label for="pickup-notes"
                                class="small mb-2">Anything your growers should know? (optional)</label>
                            <textarea id="pickup-notes" name="notes" maxlength="1000" class="form-control" rows="3"
                                placeholder="For example, slightly firm tomatoes, please.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            @auth<div class="checkout-customer"><i class="fa-regular fa-user"></i>
                                    <div><strong>{{ $customer->name }}</strong><small>{{ $customer->email }}</small></div>
                            </div>@endauth
                        </section>
                    </div>
                    <aside class="basket-summary"><span class="shop-kicker">ONE STEP CLOSER TO FRESH</span>
                        <h2 class="mt-3">All set for pickup?</h2>
                        <dl>
                            <dt>{{ $cartItems->sum('quantity') }} items · {{ $farmerGroups->count() }}
                                {{ \Illuminate\Support\Str::plural('grower', $farmerGroups->count()) }}</dt>
                            <dd>Rs. {{ number_format($cartTotal, 0) }}</dd>
                        </dl>
                        <dl>
                            <dt>Market pickup</dt>
                            <dd>Free</dd>
                        </dl>
                        <div class="basket-total"><span>Pay when you collect</span><strong>Rs.
                                {{ number_format($cartTotal, 0) }}</strong></div>
                        @auth<button type="submit" class="shop-pill" @disabled($missingSlots)>Place my pre-order
                            <span>↗</span></button>@else<a href="{{ url('/login') }}" class="shop-pill">Sign in to
                            pre-order <span>↗</span></a>@endauth
                        <p><i class="fa-solid fa-leaf"></i> Collect at the stall. Pay in person.<br>No online payment
                            needed.</p>
                    </aside>
                </div>
            </form>
        @endif
    </div>
@endsection
