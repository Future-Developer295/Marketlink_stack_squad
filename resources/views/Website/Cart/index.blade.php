@extends('Website._master')

@section('page_title', 'Your harvest basket')

@section('body')

    <div class="ml-container" data-basket-page>

        <a href="{{ url('/products') }}" class="basket-back">
            ← Back to the harvest
        </a>

        @include('Website.Partials.shop-banner', ['kind' => 'cart'])
        @include('Website.Partials.alerts')

        @if ($cartItems->isEmpty())

            <div class="shop-empty">

                <i class="fa-solid fa-basket-shopping"></i>

                <h2>
                    Good things grow from here.
                </h2>

                <p>
                    Your basket is empty. Find something fresh from a local grower.
                </p>

                <a href="{{ url('/products') }}" class="shop-pill">
                    Find your favourites ↗
                </a>

            </div>
        @else
            <div class="basket-layout">

                <div>

                    @foreach ($farmerGroups as $farmerId => $items)
                        @php
                            $farmer = $items->first()['product']->farmer;
                        @endphp

                        <section class="basket-grower">

                            <header class="basket-grower-head">

                                <i class="fa-solid fa-tractor"></i>

                                <div>

                                    <strong>
                                        {{ $farmer->stall_name ?? 'Local grower' }}
                                    </strong>

                                    <span>
                                        {{ $farmer->city ?? '' }} · Market pickup
                                    </span>

                                </div>

                            </header>

                            @foreach ($items as $item)
                                @php
                                    $product = $item['product'];
                                    $quantity = (int) $item['quantity'];
                                    $maxQuantity = (int) $product->stock_quantity;
                                @endphp

                                <article class="basket-row" data-cart-row data-product-id="{{ $product->id }}">

                                    @if ($product->image)
                                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="harvest-placeholder" aria-label="No product image">
                                            <i class="fa-solid fa-seedling"></i>
                                        </div>
                                    @endif

                                    <div>

                                        <div class="basket-row-title">

                                            <a href="{{ url('/products/' . $product->id) }}">
                                                {{ $product->name }}
                                            </a>

                                            <strong data-cart-subtotal>
                                                Rs. {{ number_format($item['subtotal'], 0) }}
                                            </strong>

                                        </div>

                                        <small>
                                            Rs. {{ number_format($product->price, 0) }}
                                            / {{ $product->unit }}
                                        </small>

                                        <div class="basket-row-actions">

                                            <form method="POST" action="{{ url('/cart/update/' . $product->id) }}"
                                                data-ajax-cart-form>

                                                @csrf

                                                <div class="ml-quantity" data-ajax-quantity data-max="{{ $maxQuantity }}">

                                                    <button type="button" data-ajax-minus
                                                        aria-label="Decrease {{ $product->name }} quantity"
                                                        @disabled($quantity <= 1)>
                                                        −
                                                    </button>

                                                    <input type="text" value="{{ $quantity }}" readonly
                                                        data-ajax-value aria-label="{{ $product->name }} quantity">

                                                    <button type="button" data-ajax-plus
                                                        aria-label="Increase {{ $product->name }} quantity"
                                                        @disabled($quantity >= $maxQuantity)>
                                                        +
                                                    </button>

                                                </div>

                                            </form>

                                            <form method="POST" action="{{ url('/cart/remove/' . $product->id) }}">

                                                @csrf

                                                <button class="basket-remove" type="submit"
                                                    aria-label="Remove {{ $product->name }}">
                                                    Remove
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </article>
                            @endforeach

                        </section>
                    @endforeach

                    <a class="basket-back" href="{{ url('/products') }}">
                        + A few more fresh finds
                    </a>

                </div>

                <aside class="basket-summary">

                    <span class="shop-kicker">
                        THE GOOD STUFF, ALL TOGETHER
                    </span>

                    <h2 class="mt-3">
                        Your basket, at a glance.
                    </h2>

                    <dl>

                        <dt data-ajax-cart-count>
                            {{ $cartItems->sum('quantity') }} items
                        </dt>

                        <dd data-ajax-cart-summary>
                            Rs. {{ number_format($cartTotal, 0) }}
                        </dd>

                    </dl>

                    <dl>

                        <dt>
                            Market pickup
                        </dt>

                        <dd>
                            Always free
                        </dd>

                    </dl>

                    <div class="basket-total">

                        <span>
                            Pay at pickup
                        </span>

                        <strong data-ajax-cart-total>
                            Rs. {{ number_format($cartTotal, 0) }}
                        </strong>

                    </div>

                    <a class="shop-pill" href="{{ url('/checkout') }}">
                        Choose your pickup
                        <span>↗</span>
                    </a>

                    <p>
                        <i class="fa-solid fa-leaf"></i>
                        No online payment. Just fresh possibilities.
                    </p>

                </aside>

            </div>

        @endif

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('[data-ajax-cart-form]').forEach(function(form) {

                const quantityBox = form.querySelector('[data-ajax-quantity]');
                const input = form.querySelector('[data-ajax-value]');
                const plus = form.querySelector('[data-ajax-plus]');
                const minus = form.querySelector('[data-ajax-minus]');
                const row = form.closest('[data-cart-row]');

                if (!quantityBox || !input || !plus || !minus || !row) {
                    return;
                }

                const max = parseInt(quantityBox.dataset.max, 10) || 0;

                let loading = false;

                function updateButtons() {

                    const quantity = parseInt(input.value, 10) || 1;

                    minus.disabled = loading || quantity <= 1;
                    plus.disabled = loading || quantity >= max;
                }

                async function changeQuantity(quantity) {

                    if (loading) {
                        return;
                    }

                    const currentQuantity = parseInt(input.value, 10) || 1;

                    if (quantity === currentQuantity) {
                        return;
                    }

                    if (quantity < 1 || quantity > max) {
                        return;
                    }

                    loading = true;
                    updateButtons();

                    const token = form.querySelector('input[name="_token"]').value;

                    const body = new URLSearchParams();

                    body.append('_token', token);
                    body.append('quantity', quantity);

                    try {

                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: body.toString()
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Cart update failed.');
                        }

                        input.value = data.quantity;

                        const subtotal = row.querySelector('[data-cart-subtotal]');

                        if (subtotal) {
                            subtotal.textContent =
                                'Rs. ' + Number(data.subtotal).toLocaleString();
                        }

                        const cartCount =
                            document.querySelector('[data-ajax-cart-count]');

                        const cartSummary =
                            document.querySelector('[data-ajax-cart-summary]');

                        const cartTotal =
                            document.querySelector('[data-ajax-cart-total]');

                        if (cartCount) {
                            cartCount.textContent =
                                data.cart_count + ' items';
                        }

                        if (cartSummary) {
                            cartSummary.textContent =
                                'Rs. ' + Number(data.cart_total).toLocaleString();
                        }

                        if (cartTotal) {
                            cartTotal.textContent =
                                'Rs. ' + Number(data.cart_total).toLocaleString();
                        }

                    } catch (error) {

                        console.error(error);

                    } finally {

                        loading = false;
                        updateButtons();

                    }

                }

                plus.addEventListener('click', function(event) {

                    event.preventDefault();

                    const current =
                        parseInt(input.value, 10) || 1;

                    if (current < max) {
                        changeQuantity(current + 1);
                    }

                });

                minus.addEventListener('click', function(event) {

                    event.preventDefault();

                    const current =
                        parseInt(input.value, 10) || 1;

                    if (current > 1) {
                        changeQuantity(current - 1);
                    }

                });

                updateButtons();

            });

        });
    </script>
@endpush
