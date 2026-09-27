@extends('Dashboard._master')

@section('nav_products_list')
    active
@endsection

@section('page_title', 'Products')

@section('body')

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-box-seam-fill"></i>
                Products
            </span>

            <div class="panel-tools">

                <form class="search-box" method="GET" action="{{ route('products') }}">
                    <i class="bi bi-search"></i>

                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search...">

                    @if (request('q'))
                        <a href="{{ route('products') }}" class="search-clear" title="Clear search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
                @can('add products')
                    <a class="btn-primary" href="{{ route('product_add') }}">
                        <i class="bi bi-plus-lg"></i>
                        Add Product
                    </a>
                @endcan

            </div>

        </div>


        @if (session('success'))
            <div style="padding: 12px 22px; color: green;">
                {{ session('success') }}
            </div>
        @endif


        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Product</th>
                        <th>Farmer</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>


                <tbody>
                    <?php
                    $index = 1;
                    ?>
                    @forelse($products as $product)
                        <tr>

                            <td>
                                <span class="id-chip">
                                    {{ $index++ }}
                                </span>
                            </td>


                            <td>

                                <div class="prod-cell">

                                    <div class="prod-img">

                                        @if ($product->image)
                                            <img src="{{ asset('product_images/' . $product->image) }}"
                                                alt="{{ $product->name }}">
                                        @else
                                            <i class="bi bi-box-seam"></i>
                                        @endif

                                    </div>


                                    <div class="prod-info">

                                        <strong>
                                            {{ $product->name }}
                                        </strong>

                                        <span>
                                            Unit: {{ $product->unit }}
                                        </span>

                                    </div>

                                </div>

                            </td>

                            <td>
                                {{ $product->farmer->name ?? 'N/A' }}
                            </td>

                            <td>

                                <span class="badge-cat">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>

                            </td>

                            <td>

                                <span class="price-mono">
                                    PKR {{ number_format($product->price, 2) }}
                                </span>

                            </td>


                            <td>

                                <span class="qty-tag">
                                    {{ $product->stock_quantity }}
                                </span>

                            </td>

                            <td>

                                @if ($product->is_active)
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                            <td>
                                <div class="action-wrap">

                                    @can('view products')
                                        <a class="btn-ghost sm" href="{{ route('product_view', $product->id) }}"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan

                                    @can('edit products')
                                        <a class="btn-ghost sm" href="{{ route('product_edit', $product->id) }}"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan

                                    @can('delete products')
                                        <form action="{{ route('product_delete', $product->id) }}" method="POST"
                                            style="display:inline">
                                            @csrf

                                            <button class="btn-ghost sm danger" type="submit" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" style="text-align:center; padding:40px;">
                                <i class="bi bi-box-seam" style="font-size:35px;"></i>

                                <br>

                                <strong>No products found</strong>

                                <br>

                                <span style="color:var(--muted);">
                                    Add your first product to get started.
                                </span>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="hr-thin"></div>

        <div style="padding:14px 22px; color:var(--muted); font-size:13px">

            Showing your products only.

        </div>

    </div>

@endsection
