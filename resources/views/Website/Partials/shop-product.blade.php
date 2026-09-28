<article class="harvest-card" data-aos="fade-up">
    <a class="harvest-card-image" href="{{ url('/products/' . $product->id) }}">
        @if ($product->image)
            <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy">
        @else
            <div class="harvest-placeholder" aria-label="Product image unavailable"><i class="fa-solid fa-seedling"></i>
            </div>
        @endif
        <span
            class="harvest-tag">{{ $product->stock_quantity > 0 ? $product->category->name ?? 'Local harvest' : 'Sold out' }}</span>
        <span class="harvest-open" aria-hidden="true">↗</span>
    </a>
    <div class="harvest-card-body">
        <span class="harvest-farmer">{{ $product->farmer->stall_name ?? 'Local grower' }}</span>
        <h3><a href="{{ url('/products/' . $product->id) }}">{{ $product->name }}</a></h3>
        <div class="harvest-card-bottom">
            <p><strong>Rs. {{ number_format($product->price, 0) }}</strong><span>/ {{ $product->unit }}</span></p>
            <form method="POST" action="{{ url('/cart/add/' . $product->id) }}" data-cart-form>@csrf
                <input type="hidden" name="quantity" value="1">
                <button class="harvest-add" type="submit" aria-label="Add {{ $product->name }} to basket"
                    @disabled($product->stock_quantity < 1)><i class="fa-solid fa-plus"></i></button>
            </form>
        </div>
        @include('Website.Partials.save-favorite', ['kind'=>'product', 'favoriteId'=>$product->id, 'favoriteLabel'=>'Save for later', 'buttonClass'=>'harvest-save'])
    </div>
</article>
