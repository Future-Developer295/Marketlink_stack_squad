@extends('Website._master')

@section('page_title', 'The local harvest')

@section('body')

    <div class="ml-container shop-catalog">

        <header class="harvest-hero">

            <div class="harvest-hero-copy">

                <span class="shop-kicker">
                    <span></span>
                    GOOD FOOD. CLOSE TO HOME.
                </span>

                <h1>
                    A little local.<br>
                    A lot of <em>good.</em>
                </h1>

                <p>
                    Meet your growers. Find your favourites.<br>
                    Fill your week with a fresher kind of shopping.
                </p>

                <a href="#harvest" class="shop-pill">
                    Explore the harvest
                    <span>↗</span>
                </a>

                <div class="harvest-note">
                    <i class="fa-solid fa-basket-shopping"></i>
                    Pick online. Pay at the market.
                </div>

            </div>

            <div class="harvest-hero-art" aria-hidden="true">

                <div class="harvest-orbit"></div>

                <span class="harvest-art-label">
                    THE SEASONAL<br>
                    COLLECTION
                </span>

                <img src="{{ asset('Assets/Website_Asset/images/product.webp') }}" alt="Seasonal Collection"
                    fetchpriority="high">

                <span class="harvest-stamp">
                    GROWN<br>
                    WITH CARE
                    <span>✳</span>
                </span>

                <span class="harvest-art-footer">
                    FROM THEIR FIELDS TO YOUR TABLE ↗
                </span>

            </div>

        </header>


        <section class="shop-categories" aria-labelledby="category-title">

            <div class="shop-section-heading">

                <div>

                    <span class="shop-kicker">
                        FOLLOW YOUR APPETITE
                    </span>

                    <h2 id="category-title">
                        What's your pick?
                    </h2>

                </div>

                <div class="carousel-controls">

                    <button type="button" data-carousel-prev aria-label="Previous categories">
                        ←
                    </button>

                    <button type="button" data-carousel-next aria-label="Next categories">
                        →
                    </button>

                </div>

            </div>


            <div class="category-carousel" id="category-carousel" tabindex="0" aria-label="Product categories">

                <a href="{{ url('/products') . '?' . http_build_query(request()->except('category_id', 'page')) }}"
                    class="category-chip {{ !request('category_id') ? 'selected' : '' }}"
                    @if (!request('category_id')) aria-current="true" @endif>

                    <span>✳</span>

                    <strong>
                        All goodness
                    </strong>

                    <small>
                        Explore everything
                    </small>

                </a>


                @foreach ($categories as $category)
                    @php(
    $categoryIcon = match (true) {
        str_contains(strtolower($category->name), 'fruit') => 'fa-apple-whole',
        str_contains(strtolower($category->name), 'veget') => 'fa-carrot',
        str_contains(strtolower($category->name), 'dairy') => 'fa-cheese',
        str_contains(strtolower($category->name), 'bak') => 'fa-bread-slice',
        default => 'fa-seedling'
    }
)

                    <a href="{{ url('/products') . '?' . http_build_query(array_merge(request()->except('page'), ['category_id' => $category->id])) }}"
                        class="category-chip {{ request('category_id') == $category->id ? 'selected' : '' }}"
                        @if (request('category_id') == $category->id) aria-current="true" @endif>

                        <span>
                            <i class="fa-solid {{ $categoryIcon }}"></i>
                        </span>

                        <strong>
                            {{ $category->name }}
                        </strong>

                        <small>
                            {{ $category->products_count }} fresh picks
                        </small>

                    </a>
                @endforeach

            </div>

        </section>


        <section id="harvest" class="harvest-collection">

            <div class="shop-section-heading">

                <div>

                    <span class="shop-kicker">
                        YOUR NEXT MARKET HAUL
                    </span>

                    <h2>
                        Fresh finds, great days.
                    </h2>

                </div>

                <span class="shop-count">
                    {{ $products->total() }}
                    {{ \Illuminate\Support\Str::plural('find', $products->total()) }}
                </span>

            </div>


            <div class="catalog-workspace">


                <details class="catalog-sidebar" aria-label="Product filters" data-lenis-prevent open>

                    <summary class="mobile-filter-summary">

                        <span>
                            <i class="fa-solid fa-sliders"></i>
                            Filter &amp; sort your picks
                        </span>

                        <i class="fa-solid fa-chevron-down"></i>

                    </summary>


                    <form method="GET" action="{{ url('/products') }}" class="shop-filters">

                        @if (request('farmer_id'))
                            <input type="hidden" name="farmer_id" value="{{ request('farmer_id') }}">
                        @endif


                        <label class="filter-field">

                            Market

                            <select name="market_id" aria-label="Market">

                                <option value="">
                                    All markets
                                </option>

                                @foreach ($marketOptions as $marketOption)
                                    <option value="{{ $marketOption->id }}" @selected(request('market_id') == $marketOption->id)>
                                        {{ $marketOption->name }}
                                    </option>
                                @endforeach

                            </select>

                        </label>


                        <label class="filter-field">

                            Grower operating day

                            <select name="day" aria-label="Grower operating day">

                                <option value="">
                                    Any day
                                </option>

                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <option @selected(request('day') === $day)>
                                        {{ $day }}
                                    </option>
                                @endforeach

                            </select>

                        </label>


                        <div class="filter-title">

                            <h3>
                                <i class="fa-solid fa-sliders"></i>
                                Refine your picks
                            </h3>

                            <a href="{{ url('/products') }}" class="shop-reset">
                                Reset
                            </a>

                        </div>


                        <label class="filter-field">

                            Find something fresh

                            <span class="shop-search">

                                <i class="fa-solid fa-magnifying-glass"></i>

                                <input type="search" name="q" value="{{ request('q') }}"
                                    placeholder="Products or growers" aria-label="Search products or growers">

                            </span>

                        </label>


                        <fieldset class="filter-categories">

                            <legend>
                                Shop by category
                            </legend>


                            <label>

                                <input type="radio" name="category_id" value="" @checked(!request('category_id'))>

                                <span>
                                    All categories
                                </span>

                            </label>


                            @foreach ($categories as $category)
                                <label>

                                    <input type="radio" name="category_id" value="{{ $category->id }}"
                                        @checked(request('category_id') == $category->id)>

                                    <span>
                                        {{ $category->name }}
                                    </span>

                                    <small>
                                        {{ $category->products_count }}
                                    </small>

                                </label>
                            @endforeach

                        </fieldset>


                        <label class="filter-field">

                            Maximum price (Rs.)

                            <input name="max_price" type="number" min="0" value="{{ request('max_price') }}"
                                placeholder="Any price">

                        </label>


                        <label class="filter-stock">

                            <input type="checkbox" name="in_stock_only" value="1" @checked(request('in_stock_only'))>

                            Available now only

                        </label>


                        <label class="filter-field">

                            Sort your picks

                            <select name="sort" aria-label="Sort products">

                                <option value="newest">
                                    Newest first
                                </option>

                                <option value="price_low" @selected(request('sort') === 'price_low')>
                                    Price: low to high
                                </option>

                                <option value="price_high" @selected(request('sort') === 'price_high')>
                                    Price: high to low
                                </option>

                            </select>

                        </label>


                        <button type="submit" class="shop-pill">
                            Show my picks
                            <span>→</span>
                        </button>


                        <div class="filter-note">

                            <i class="fa-solid fa-seedling"></i>

                            <p>
                                Local growers.<br>
                                Fresh possibilities.
                            </p>

                        </div>

                    </form>

                </details>


                <div class="catalog-results">

                    @include('Website.Partials.alerts')


                    <div class="harvest-grid">

                        @forelse ($products as $product)
                            @include('Website.Partials.shop-product', ['product' => $product])

                        @empty

                            <div class="shop-empty">

                                <i class="fa-solid fa-seedling"></i>

                                <h3>
                                    No picks just yet.
                                </h3>

                                <p>
                                    Try a different category or a broader search.
                                </p>

                                <a href="{{ url('/products') }}" class="shop-pill">
                                    Explore all products ↗
                                </a>

                            </div>
                        @endforelse

                    </div>


                    <div class="mt-4">

                        @include('Website.Partials.pagination', ['paginator' => $products])

                    </div>

                </div>

            </div>

        </section>


        <aside class="harvest-manifesto" data-aos="fade-up">

            <span>
                ✳
            </span>

            <h2>
                Small farms.<br>
                <em>Big difference.</em>
            </h2>

            <p>
                Every basket brings you closer to the people who grow your food.
            </p>

            <a href="{{ url('/farmers') }}">
                Meet the growers ↗
            </a>

        </aside>

    </div>

@endsection
