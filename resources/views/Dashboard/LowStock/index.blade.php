@extends('Dashboard._master')

@section('nav_low_stock')
    active
@endsection

@section('page_title', 'Low Stock Alerts')

@section('body')

    <div class="stats-row">
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-num">{{ $lowStockProducts->count() }}</div>
            <div class="stat-label">Low Stock Products</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Across all farmers</div>
        </div>
        <div class="stat-card sc-rose">
            <div class="stat-icon-wrap"><i class="bi bi-x-octagon-fill"></i></div>
            <div class="stat-num">{{ $outOfStockCount }}</div>
            <div class="stat-label">Out of Stock</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> Needs immediate restock</div>
        </div>
    </div>

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Low Stock Alerts
            </span>

            <div class="panel-tools">

                <form class="search-box" method="GET" action="{{ route('low_stock_alerts') }}">
                    <i class="bi bi-search"></i>

                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search product or farmer...">

                    @if (request('q'))
                        <a href="{{ route('low_stock_alerts') }}" class="search-clear" title="Clear search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>

                <a class="btn-ghost {{ request('status') !== 'out' ? 'active' : '' }}" href="{{ route('low_stock_alerts') }}">
                    All
                </a>

                <a class="btn-ghost {{ request('status') === 'out' ? 'active' : '' }}" href="{{ route('low_stock_alerts', ['status' => 'out']) }}">
                    Out of Stock Only
                </a>

            </div>

        </div>

        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Product</th>
                        <th>Farmer</th>
                        <th>Category</th>
                        <th>Stock Left</th>
                        <th>Threshold</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($lowStockProducts as $product)

                        <tr>

                            <td>
                                <span class="id-chip">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            <td>
                                <div class="prod-cell">
                                    <div class="prod-img">
                                        @if($product->image)
                                            <img src="{{ asset('product_images/' . $product->image) }}" alt="{{ $product->name }}">
                                        @else
                                            <i class="bi bi-box-seam"></i>
                                        @endif
                                    </div>
                                    <div class="prod-info">
                                        <strong>{{ $product->name }}</strong>
                                        <span>Unit: {{ $product->unit }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $product->farmer->stall_name ?? $product->farmer->business_name ?? 'N/A' }}
                                <br>
                                <span style="color:var(--muted); font-size:12px;">
                                    {{ $product->farmer->user->name ?? '' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge-cat">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                <span class="qty-tag">
                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                </span>
                            </td>

                            <td>
                                {{ $product->low_stock_threshold }}
                            </td>

                            <td>
                                @if($product->stock_quantity <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @else
                                    <span class="badge bg-warning">Low Stock</span>
                                @endif
                            </td>

                            <td>
                                <div class="action-wrap">
                                    <a class="btn-ghost sm" href="{{ route('product_view', $product->id) }}" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a class="btn-ghost sm" href="{{ route('product_edit', $product->id) }}" title="Edit / Restock">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" style="text-align:center; padding:40px;">
                                <i class="bi bi-check2-circle" style="font-size:35px; color:var(--emerald);"></i>
                                <br>
                                <strong>All good, no low stock products right now.</strong>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="hr-thin"></div>

        <div style="padding:14px 22px; color:var(--muted); font-size:13px">
            A product shows here once its remaining stock drops to or below its configured low-stock threshold (default: 5 units).
        </div>

    </div>

@endsection
