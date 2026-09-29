@if ($cartItems->isEmpty())
    <div class="shop-empty">
        <i class="fa-solid {{ session('success') ? 'fa-circle-check' : 'fa-basket-shopping' }}"></i>
        <h2>{{ session('success') ? "You're on the list." : 'A fresh start awaits.' }}</h2>
        <p>{{ session('success') ? 'Your pre-order is with your growers. Details are below.' : 'Find your favourites and add them to your basket first.' }}</p>
        <a class="shop-pill" href="{{ session('success') ? route('customer_dashboard') : url('/products') }}">
            {{ session('success') ? 'Go to my account' : 'Explore the harvest' }}
            <span><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
        </a>
    </div>

    @if ($placedOrders->isNotEmpty())
        <div class="placed-orders">
            <h2 class="placed-orders-title"><i class="fa-solid fa-clipboard-check"></i> What you ordered</h2>

            @foreach ($placedOrders as $placed)
                <section class="basket-grower">
                    <header class="basket-grower-head">
                        <i class="fa-solid fa-tractor"></i>
                        <div>
                            <strong>Pre-order #{{ $placed->id }} · {{ $placed->farmer?->stall_name ?? 'Local grower' }}</strong>
                            <span>
                                <i class="fa-regular fa-calendar"></i> {{ $placed->pickupSlot?->date?->format('D, d M Y') }}
                                @if ($placed->pickupSlot)
                                    · <i class="fa-regular fa-clock"></i> {{ substr($placed->pickupSlot->start_time, 0, 5) }} – {{ substr($placed->pickupSlot->end_time, 0, 5) }}
                                @endif
                                @if ($placed->pickupSlot?->market)
                                    · <i class="fa-solid fa-location-dot"></i> {{ $placed->pickupSlot->market->name }}
                                @endif
                            </span>
                        </div>
                    </header>

                    @foreach ($placed->items as $line)
                        <div class="checkout-item">
                            @if ($line->product)
                                <img src="{{ $line->product->imageUrl() }}" alt="{{ $line->product->name }}">
                            @endif
                            <div>
                                <strong>{{ $line->product?->name ?? 'Unavailable product' }}</strong>
                                <small>{{ $line->quantity }} {{ $line->product?->unit }} · Rs. {{ number_format($line->price, 0) }} each</small>
                            </div>
                            <b>Rs. {{ number_format($line->subtotal, 0) }}</b>
                        </div>
                    @endforeach

                    <div class="placed-order-total">
                        <span><i class="fa-solid fa-hand-holding-dollar"></i> Total · pay at pickup</span>
                        <strong>Rs. {{ number_format($placed->total_amount, 0) }}</strong>
                    </div>
                    <a class="placed-order-link" href="{{ route('customer_order_detail', $placed) }}">
                        View pre-order details <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </section>
            @endforeach

            <div class="placed-order-total placed-order-grand">
                <span><i class="fa-solid fa-receipt"></i> All pre-orders</span>
                <strong>Rs. {{ number_format($placedOrders->sum('total_amount'), 0) }}</strong>
            </div>
        </div>
    @endif
@else