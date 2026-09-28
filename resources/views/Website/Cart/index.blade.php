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
            <div class="basket-layout" data-basket-layout>

                <div data-basket-items>

                    @foreach ($farmerGroups as $farmerId => $items)
                        @php
                            $farmer = $items->first()['product']->farmer;
                        @endphp

                        <section class="basket-grower" data-grower-section>

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


                                                    <input type="number" value="{{ $quantity }}" min="1"
                                                        max="{{ $maxQuantity }}" step="1" inputmode="numeric"
                                                        data-ajax-value aria-label="{{ $product->name }} quantity">


                                                    <button type="button" data-ajax-plus
                                                        aria-label="Increase {{ $product->name }} quantity"
                                                        @disabled($quantity >= $maxQuantity)>
                                                        +
                                                    </button>

                                                </div>

                                            </form>


                                            <form method="POST" action="{{ url('/cart/remove/' . $product->id) }}"
                                                data-ajax-remove-form>

                                                @csrf

                                                <button class="basket-remove" type="submit" data-ajax-remove
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

            const basketPage =
                document.querySelector('[data-basket-page]');

            if (!basketPage) {
                return;
            }


            function updateCartSummary(data) {

                const count =
                    basketPage.querySelector(
                        '[data-ajax-cart-count]'
                    );

                const summary =
                    basketPage.querySelector(
                        '[data-ajax-cart-summary]'
                    );

                const total =
                    basketPage.querySelector(
                        '[data-ajax-cart-total]'
                    );


                if (count) {

                    count.textContent =
                        Number(data.cart_count || 0) + ' items';

                }


                if (summary) {

                    summary.textContent =
                        'Rs. ' +
                        Number(
                            data.cart_total || 0
                        ).toLocaleString();

                }


                if (total) {

                    total.textContent =
                        'Rs. ' +
                        Number(
                            data.cart_total || 0
                        ).toLocaleString();

                }


                if (data.count !== undefined) {

                    document
                        .querySelectorAll(
                            '[data-cart-count], .ml-cart-badge'
                        )
                        .forEach(function(badge) {

                            badge.textContent =
                                data.count;

                        });

                }
            }


            function setQuantityButtons(
                form,
                quantity
            ) {

                const quantityBox =
                    form.querySelector(
                        '[data-ajax-quantity]'
                    );

                const plus =
                    form.querySelector(
                        '[data-ajax-plus]'
                    );

                const minus =
                    form.querySelector(
                        '[data-ajax-minus]'
                    );

                const input =
                    form.querySelector(
                        '[data-ajax-value]'
                    );


                if (
                    !quantityBox ||
                    !plus ||
                    !minus ||
                    !input
                ) {
                    return;
                }


                const max =
                    parseInt(
                        quantityBox.dataset.max,
                        10
                    ) || 1;


                quantity =
                    Math.min(
                        max,
                        Math.max(1, quantity)
                    );


                input.value =
                    quantity;


                minus.disabled =
                    quantity <= 1;


                plus.disabled =
                    quantity >= max;

            }


            async function updateQuantity(
                form,
                newQuantity
            ) {

                if (
                    form.dataset.loading === '1'
                ) {
                    return;
                }


                const quantityBox =
                    form.querySelector(
                        '[data-ajax-quantity]'
                    );

                const input =
                    form.querySelector(
                        '[data-ajax-value]'
                    );

                const plus =
                    form.querySelector(
                        '[data-ajax-plus]'
                    );

                const minus =
                    form.querySelector(
                        '[data-ajax-minus]'
                    );

                const row =
                    form.closest(
                        '[data-cart-row]'
                    );


                if (
                    !quantityBox ||
                    !input ||
                    !plus ||
                    !minus ||
                    !row
                ) {
                    return;
                }


                const max =
                    parseInt(
                        quantityBox.dataset.max,
                        10
                    ) || 1;


                let quantity =
                    parseInt(
                        newQuantity,
                        10
                    );


                if (
                    Number.isNaN(quantity)
                ) {
                    quantity = 1;
                }


                quantity =
                    Math.min(
                        max,
                        Math.max(1, quantity)
                    );


                const currentQuantity =
                    parseInt(
                        input.value,
                        10
                    ) || 1;


                if (
                    quantity === currentQuantity
                ) {

                    setQuantityButtons(
                        form,
                        quantity
                    );

                    return;
                }


                const token =
                    form.querySelector(
                        'input[name="_token"]'
                    )?.value;


                if (!token) {
                    return;
                }


                form.dataset.loading =
                    '1';


                input.disabled =
                    true;

                plus.disabled =
                    true;

                minus.disabled =
                    true;


                const formData =
                    new FormData();


                formData.append(
                    '_token',
                    token
                );


                formData.append(
                    'quantity',
                    quantity
                );


                try {

                    const response =
                        await fetch(
                            form.action, {
                                method: 'POST',
                                body: formData,
                                credentials: 'same-origin',

                                headers: {
                                    'Accept': 'application/json',

                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }
                        );


                    const contentType =
                        response.headers.get(
                            'content-type'
                        ) || '';


                    if (
                        !contentType.includes(
                            'application/json'
                        )
                    ) {

                        throw new Error(
                            'Could not update your basket. Please refresh and try again.'
                        );

                    }


                    const data =
                        await response.json();


                    if (
                        !response.ok ||
                        !data.success
                    ) {

                        throw new Error(
                            data.message ||
                            'Could not update your basket.'
                        );

                    }


                    input.value =
                        Number(
                            data.quantity
                        );


                    const subtotal =
                        row.querySelector(
                            '[data-cart-subtotal]'
                        );


                    if (subtotal) {

                        subtotal.textContent =
                            'Rs. ' +
                            Number(
                                data.subtotal || 0
                            ).toLocaleString();

                    }


                    updateCartSummary(
                        data
                    );


                    setQuantityButtons(
                        form,
                        Number(data.quantity)
                    );


                } catch (error) {

                    console.error(
                        'CART UPDATE ERROR:',
                        error
                    );


                    input.value =
                        currentQuantity;


                } finally {

                    form.dataset.loading =
                        '0';


                    input.disabled =
                        false;


                    const finalQuantity =
                        parseInt(
                            input.value,
                            10
                        ) || 1;


                    setQuantityButtons(
                        form,
                        finalQuantity
                    );

                }

            }


            document
                .querySelectorAll(
                    '[data-ajax-cart-form]'
                )
                .forEach(function(form) {


                    const input =
                        form.querySelector(
                            '[data-ajax-value]'
                        );

                    const plus =
                        form.querySelector(
                            '[data-ajax-plus]'
                        );

                    const minus =
                        form.querySelector(
                            '[data-ajax-minus]'
                        );


                    if (
                        !input ||
                        !plus ||
                        !minus
                    ) {
                        return;
                    }


                    plus.addEventListener(
                        'click',
                        function(event) {

                            event.preventDefault();
                            event.stopPropagation();


                            const current =
                                parseInt(
                                    input.value,
                                    10
                                ) || 1;


                            updateQuantity(
                                form,
                                current + 1
                            );

                        }
                    );


                    minus.addEventListener(
                        'click',
                        function(event) {

                            event.preventDefault();
                            event.stopPropagation();


                            const current =
                                parseInt(
                                    input.value,
                                    10
                                ) || 1;


                            updateQuantity(
                                form,
                                current - 1
                            );

                        }
                    );


                    input.addEventListener(
                        'change',
                        function() {

                            let quantity =
                                parseInt(
                                    input.value,
                                    10
                                );


                            if (
                                Number.isNaN(quantity)
                            ) {
                                quantity = 1;
                            }


                            updateQuantity(
                                form,
                                quantity
                            );

                        }
                    );


                    input.addEventListener(
                        'blur',
                        function() {

                            let quantity =
                                parseInt(
                                    input.value,
                                    10
                                );


                            if (
                                Number.isNaN(quantity)
                            ) {
                                quantity = 1;
                            }


                            updateQuantity(
                                form,
                                quantity
                            );

                        }
                    );


                    input.addEventListener(
                        'keydown',
                        function(event) {

                            if (
                                event.key === 'Enter'
                            ) {

                                event.preventDefault();

                                input.blur();

                            }

                        }
                    );


                    input.addEventListener(
                        'input',
                        function() {

                            let quantity =
                                parseInt(
                                    input.value,
                                    10
                                );


                            if (
                                Number.isNaN(quantity)
                            ) {
                                return;
                            }


                            const quantityBox =
                                form.querySelector(
                                    '[data-ajax-quantity]'
                                );


                            const max =
                                parseInt(
                                    quantityBox?.dataset.max,
                                    10
                                ) || 1;


                            if (
                                quantity > max
                            ) {
                                input.value = max;
                            }


                            if (
                                quantity < 1
                            ) {
                                input.value = 1;
                            }

                        }
                    );


                    setQuantityButtons(
                        form,
                        parseInt(
                            input.value,
                            10
                        ) || 1
                    );

                });


            document
                .querySelectorAll(
                    '[data-ajax-remove-form]'
                )
                .forEach(function(form) {


                    form.addEventListener(
                        'submit',
                        async function(event) {

                            event.preventDefault();
                            event.stopPropagation();


                            if (
                                form.dataset.loading === '1'
                            ) {
                                return;
                            }


                            const button =
                                form.querySelector(
                                    '[data-ajax-remove]'
                                );

                            const row =
                                form.closest(
                                    '[data-cart-row]'
                                );

                            const grower =
                                row?.closest(
                                    '[data-grower-section]'
                                );


                            if (
                                !button ||
                                !row
                            ) {
                                return;
                            }


                            const token =
                                form.querySelector(
                                    'input[name="_token"]'
                                )?.value;


                            if (!token) {
                                return;
                            }


                            form.dataset.loading =
                                '1';

                            button.disabled =
                                true;


                            const formData =
                                new FormData();


                            formData.append(
                                '_token',
                                token
                            );


                            try {

                                const response =
                                    await fetch(
                                        form.action, {
                                            method: 'POST',

                                            body: formData,

                                            credentials: 'same-origin',

                                            headers: {
                                                'Accept': 'application/json',

                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        }
                                    );


                                const contentType =
                                    response.headers.get(
                                        'content-type'
                                    ) || '';


                                if (
                                    !contentType.includes(
                                        'application/json'
                                    )
                                ) {

                                    throw new Error(
                                        'Could not remove this product. Please refresh and try again.'
                                    );

                                }


                                const data =
                                    await response.json();


                                if (
                                    !response.ok ||
                                    !data.success
                                ) {

                                    throw new Error(
                                        data.message ||
                                        'Could not remove this product.'
                                    );

                                }


                                row.remove();


                                if (
                                    grower &&
                                    !grower.querySelector(
                                        '[data-cart-row]'
                                    )
                                ) {

                                    grower.remove();

                                }


                                updateCartSummary(
                                    data
                                );


                                const remaining =
                                    basketPage.querySelectorAll(
                                        '[data-cart-row]'
                                    );


                                if (
                                    remaining.length === 0 ||
                                    Number(
                                        data.cart_count || 0
                                    ) === 0
                                ) {

                                    const layout =
                                        basketPage.querySelector(
                                            '[data-basket-layout]'
                                        );


                                    if (layout) {

                                        layout.innerHTML = `
                                    <div class="shop-empty">

                                        <i class="fa-solid fa-basket-shopping"></i>

                                        <h2>
                                            Good things grow from here.
                                        </h2>

                                        <p>
                                            Your basket is empty. Find something fresh from a local grower.
                                        </p>

                                        <a
                                            href="{{ url('/products') }}"
                                            class="shop-pill"
                                        >
                                            Find your favourites ↗
                                        </a>

                                    </div>
                                `;

                                    }

                                }


                            } catch (error) {

                                console.error(
                                    'REMOVE CART ERROR:',
                                    error
                                );


                                button.disabled =
                                    false;

                            } finally {

                                form.dataset.loading =
                                    '0';

                            }

                        }
                    );

                });

        });
    </script>
@endpush
