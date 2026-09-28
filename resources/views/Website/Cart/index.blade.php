@extends('Website._master')
@section('page_title', 'Your harvest basket')
@section('body')
<div class="ml-container" data-basket-page>
    <a href="{{ url('/products') }}" class="basket-back">← Back to the harvest</a>
    @include('Website.Partials.shop-banner', ['kind' => 'cart'])
    @include('Website.Partials.alerts')
    @if($cartItems->isEmpty())
    <div class="shop-empty"><i class="fa-solid fa-basket-shopping"></i><h2>Good things grow from here.</h2><p>Your basket is empty. Find something fresh from a local grower.</p><a href="{{ url('/products') }}" class="shop-pill">Find your favourites ↗</a></div>
    @else
    <div class="basket-layout"><div>
        @foreach($farmerGroups as $farmerId => $items)
        @php($farmer = $items->first()['product']->farmer)
        <section class="basket-grower"><header class="basket-grower-head"><i class="fa-solid fa-tractor"></i><div><strong>{{ $farmer->stall_name ?? 'Local grower' }}</strong><span>{{ $farmer->city ?? '' }} · Market pickup</span></div></header>
            @foreach($items as $item)
            @php($product = $item['product'])
            <article class="basket-row">
                @if($product->image)<img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">@else<div class="harvest-placeholder" aria-label="No product image"><i class="fa-solid fa-seedling"></i></div>@endif
                <div><div class="basket-row-title"><a href="{{ url('/products/'.$product->id) }}">{{ $product->name }}</a><strong>Rs. {{ number_format($item['subtotal'], 0) }}</strong></div><small>Rs. {{ number_format($product->price, 0) }} / {{ $product->unit }}</small>
                    <div class="basket-row-actions"><form method="POST" action="{{ url('/cart/update/'.$product->id) }}" data-cart-form>@csrf
                        <div class="ml-quantity" data-quantity data-auto-submit data-min="0" data-max="{{ $product->stock_quantity }}"><button type="button" data-decrement aria-label="Decrease {{ $product->name }} quantity">−</button><input type="text" name="quantity" value="{{ $item['quantity'] }}" aria-label="{{ $product->name }} quantity" readonly><button type="button" aria-label="Increase {{ $product->name }} quantity">+</button></div>
                        <noscript><input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="{{ $product->stock_quantity }}" aria-label="Quantity"><button type="submit">Update</button></noscript>
                    </form><form method="POST" action="{{ url('/cart/remove/'.$product->id) }}" data-cart-form>@csrf<button class="basket-remove" type="submit" aria-label="Remove {{ $product->name }}">Remove</button></form></div>
                </div>
            </article>
            @endforeach
        </section>
        @endforeach
        <a class="basket-back" href="{{ url('/products') }}">+ A few more fresh finds</a>
    </div><aside class="basket-summary"><span class="shop-kicker">THE GOOD STUFF, ALL TOGETHER</span><h2 class="mt-3">Your basket, at a glance.</h2><dl><dt>{{ $cartItems->sum('quantity') }} items</dt><dd>Rs. {{ number_format($cartTotal, 0) }}</dd></dl><dl><dt>Market pickup</dt><dd>Always free</dd></dl><div class="basket-total"><span>Pay at pickup</span><strong>Rs. {{ number_format($cartTotal, 0) }}</strong></div><a class="shop-pill" href="{{ url('/checkout') }}">Choose your pickup <span>↗</span></a><p><i class="fa-solid fa-leaf"></i> No online payment. Just fresh possibilities.</p></aside></div>
    @endif
</div>
@endsection
