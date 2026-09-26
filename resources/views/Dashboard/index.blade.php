@extends('Dashboard._master')
@section('body')
    <div class="stats-row">
        <div class="stat-card sc-indigo">
            <div class="stat-icon-wrap"><i class="bi bi-box-seam-fill"></i></div>
            <div class="stat-num">5</div>
            <div class="stat-label">Total Products</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> +2 this week</div>
        </div>
        <div class="stat-card sc-emerald">
            <div class="stat-icon-wrap"><i class="bi bi-bookmarks-fill"></i></div>
            <div class="stat-num">1</div>
            <div class="stat-label">Categories</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> All active</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-num">2</div>
                  <div class="stat-label">In Stock</div>

            <div class="stat-trend up">
                <i class="bi bi-arrow-up-right"></i>
               3% available
            </div>
          

        </div>
        <div class="stat-card sc-rose">
            <div class="stat-icon-wrap"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-num"></div>
            <div class="stat-num">0</div>
            <div class="stat-label">Out of Stock</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> Needs restock</div>
             
        </div>
        <div class="stat-card sc-purple">
            <div class="stat-icon-wrap"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-num">
                PKR 500
            </div>
            <div class="stat-label">Inventory Value</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Est. total</div>
        </div>
    </div>


    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-clock-history"></i> Recent Products</span>
            <a class="btn-ghost" href="{{route('products')}}">View all <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($products as $product)
                        <tr>
                            <td>
                                <div class="prod-cell">

                                    <div class="prod-img">
                                        <img src="{{ asset('Assets/product_images/' . $product->product_image) }}"
                                            alt="{{ $product->product_name }}">
                                    </div>

                                    <div class="prod-info">
                                        <strong>{{ $product->product_name }}</strong>
                                        <span>{{ $product->product_code }}</span>
                                    </div>

                                </div>
                            </td>

                            <td>
                                <span class="badge-cat">
                                    {{ $product->category->category_name ?? 'No Category' }}
                                </span>
                            </td>

                            <td>
                                <span class="price-mono">
                                    {{ $product->product_price }}
                                </span>
                            </td>

                            <td>
                                <span class="qty-tag">
                                    {{ $product->product_quantity }}
                                </span>
                            </td>

                            <td>
                                @if ($product->product_status)
                                    <span class="badge-status bs-in">
                                        <i class="bi bi-circle-fill"></i> InStock
                                    </span>
                                @else
                                    <span class="badge-status bs-out">
                                        <i class="bi bi-circle-fill"></i> OutStock
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
@endsection
