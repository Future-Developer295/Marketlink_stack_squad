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

                <form method="GET" action="{{ route('products') }}" class="d-flex align-items-center gap-2" data-ajax-filter="#products-list">

                    <div class="search-box">
                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search..."
                        >

                        @if(request('q'))
                            <a
                                href="{{ route('products') }}"
                                class="search-clear"
                                title="Clear search"
                            >
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>

                    <select
                        name="approval_status"
                        class="form-select"
                        style="width:180px;"
                    >
                        <option value="">All Approval</option>

                        <option
                            value="pending"
                            @selected(request('approval_status') === 'pending')
                        >
                            Pending Approval
                        </option>

                        <option
                            value="approved"
                            @selected(request('approval_status') === 'approved')
                        >
                            Approved
                        </option>

                        <option
                            value="rejected"
                            @selected(request('approval_status') === 'rejected')
                        >
                            Rejected
                        </option>
                    </select>

                    <button type="submit" class="btn-primary">
                        <i class="bi bi-funnel"></i>
                        Filter
                    </button>

                </form>

                @can('add products')
                    <a
                        class="btn-primary"
                        href="{{ route('product_add') }}"
                    >
                        <i class="bi bi-plus-lg"></i>
                        Add Product
                    </a>
                @endcan

            </div>

        </div>

        @if(session('success'))
            <div style="padding:12px 22px; color:green;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="padding:12px 22px; color:red;">
                {{ session('error') }}
            </div>
        @endif

        <div id="products-list" data-ajax-list>
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
                        <th>Approval</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($products as $product)

                        <tr>

                            <td>
                                <span class="id-chip">
                                    {{ $product->id }}
                                </span>
                            </td>

                            <td>

                                <div class="prod-cell">

                                    <div class="prod-img">

                                        @if($product->image)

                                            <img
                                                src="{{ asset('product_images/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                            >

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
                                {{ $product->farmer?->user?->name ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="badge-cat">
                                    {{ $product->category?->name ?? 'N/A' }}
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

                                @if($product->approval_status === 'approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif($product->approval_status === 'rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                    @if($product->rejection_reason)

                                        <div
                                            class="small text-danger mt-1"
                                            style="max-width:220px;"
                                        >
                                            {{ $product->rejection_reason }}
                                        </div>

                                    @endif

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Pending Approval
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($product->is_active)

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

                                        <a
                                            class="btn-ghost sm"
                                            href="{{ route('product_view', $product->id) }}"
                                            title="View"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    @endcan

                                    @can('edit products')

                                        <a
                                            class="btn-ghost sm"
                                            href="{{ route('product_edit', $product->id) }}"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                    @endcan

                                    @canany(['approve products', 'reject products'])
                                        @if($product->approval_status === 'pending')
                                            @can('approve products')
                                                <form
                                                    action="{{ route('product_approve', $product->id) }}"
                                                    method="POST"
                                                    style="display:inline"
                                                    data-ajax
                                                    data-ajax-refresh="#products-list"
                                                    data-confirm="Approve this product?"
                                                >
                                                    @csrf
                                                    <button class="btn-ghost sm" type="submit" title="Approve">
                                                        <i class="bi bi-check-lg"></i>
                                                        Approve
                                                    </button>
                                                </form>
                                            @endcan
                                            @can('reject products')
                                                <details style="display:inline-block; position:relative;">
                                                    <summary class="btn-ghost sm danger" title="Not approve">
                                                        <i class="bi bi-x-lg"></i>
                                                        Not Approve
                                                    </summary>
                                                    <div style="position:absolute; right:0; top:42px; z-index:50; width:260px; background:#fff; border:1px solid #ddd; border-radius:10px; padding:12px; box-shadow:0 8px 24px rgba(0,0,0,0.12);">
                                                        <form
                                                            action="{{ route('product_reject', $product->id) }}"
                                                            method="POST"
                                                            data-ajax
                                                            data-ajax-refresh="#products-list"
                                                            data-confirm="Reject this product?"
                                                        >
                                                            @csrf
                                                            <textarea
                                                                name="rejection_reason"
                                                                rows="3"
                                                                class="form-control"
                                                                placeholder="Enter rejection reason..."
                                                                required
                                                            ></textarea>
                                                            <button
                                                                type="submit"
                                                                class="btn btn-danger btn-sm mt-2"
                                                                style="width:100%;"
                                                            >
                                                                <i class="bi bi-x-circle"></i>
                                                                Not Approve
                                                            </button>
                                                        </form>
                                                    </div>
                                                </details>
                                            @endcan
                                        @endif
                                    @endcanany

                                    @can('delete products')

                                        <form action="{{ route('product_delete', $product->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this product?">

                                            @csrf

                                            <button
                                                class="btn-ghost sm danger"
                                                type="submit"
                                                title="Delete"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                style="text-align:center; padding:40px;"
                            >

                                <i
                                    class="bi bi-box-seam"
                                    style="font-size:35px;"
                                ></i>

                                <br>

                                <strong>
                                    No products found
                                </strong>

                                <br>

                                <span style="color:var(--muted);">
                                    No products available.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
@include('Dashboard._pager', ['paginator' => $products, 'label' => 'products'])
</div>

    </div>

@endsection
