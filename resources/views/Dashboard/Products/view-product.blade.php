@extends('Dashboard._master')

@section('nav_products_list')
active
@endsection

@section('page_title', 'Product Details')

@section('body')

<div class="panel" style="max-width:1440px">


<div class="panel-header">

    <span class="panel-title">
        <i class="bi bi-box-seam-fill"></i>
        Product Details
    </span>

    <a
        class="btn-ghost sm"
        href="{{ route('products') }}"
    >
        <i class="bi bi-x"></i>
        Back
    </a>

</div>


<div style="padding:22px">

    <div class="grid-2">

        <div class="view-img-area">

            @if($product->image)

                <img
                    src="{{ asset('product_images/' . $product->image) }}"
                    alt="{{ $product->name }}"
                >

            @else

                <div style="font-size:60px; color:var(--muted);">
                    <i class="bi bi-box-seam"></i>
                </div>

            @endif

        </div>


        <div>

            <div class="detail-grid">

                <div class="detail-cell">

                    <span class="dl">
                        Name
                    </span>

                    <span class="dv">
                        {{ $product->name }}
                    </span>

                </div>


                <div class="detail-cell">

                    <span class="dl">
                        Farmer
                    </span>

                    <span class="dv">
                        {{ $product->farmer->name ?? 'N/A' }}
                    </span>

                </div>


                <div class="detail-cell">

                    <span class="dl">
                        Category
                    </span>

                    <span class="dv">
                        {{ $product->category->name ?? 'N/A' }}
                    </span>

                </div>


                <div class="detail-cell">

                    <span class="dl">
                        Price
                    </span>

                    <span class="dv">
                        PKR {{ number_format($product->price, 2) }}
                    </span>

                </div>


                <div class="detail-cell">

                    <span class="dl">
                        Stock
                    </span>

                    <span class="dv">
                        {{ $product->stock_quantity }}
                        {{ $product->unit }}
                    </span>

                </div>


                <div class="detail-cell">

                    <span class="dl">
                        Status
                    </span>

                    <span class="dv">

                        @if($product->is_active)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Inactive
                            </span>

                        @endif

                    </span>

                </div>


                <div
                    class="detail-cell"
                    style="grid-column:1 / -1"
                >

                    <span class="dl">
                        Description
                    </span>

                    <span class="dv">
                        {{ $product->description ?: '—' }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    <div class="spacer-16"></div>


    <div style="display:flex; gap:10px">

        <a
            class="btn-primary"
            href="{{ route('product_edit', $product->id) }}"
        >
            <i class="bi bi-pencil"></i>
            Edit Product
        </a>


        <form action="{{ route('product_delete', $product->id) }}" method="POST" style="display:inline" data-confirm="Remove this product?">

            @csrf

            <button
                class="btn-ghost sm danger"
                type="submit"
            >
                <i class="bi bi-trash"></i>
                Remove Product
            </button>

        </form>

    </div>

</div>

</div>

@endsection
