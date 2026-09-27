@extends('Dashboard._master')

@section('nav_product_sales_analytics')
    active
@endsection

@section('page_title', 'Product Sales Analytics')

@push('styles')
<style>
    .move-pill {
        display: inline-block;
        padding: 2px 9px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .move-pill.fast { background: rgba(35, 118, 76, 0.12); color: var(--brand, #23764c); }
    .move-pill.steady { background: var(--surface-2, #f1f5f3); color: var(--muted); }
    .move-pill.slow { background: #fdecea; color: #b3261e; }
    .move-pill.no_sales { background: #f1f1f1; color: #9a9a9a; }
</style>
@endpush

@section('body')

    <div class="stats-row">
        <div class="stat-card sc-emerald">
            <div class="stat-icon-wrap"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-num">PKR {{ number_format($totals['revenue'], 2) }}</div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> {{ $totals['units'] }} units sold</div>
        </div>
        <div class="stat-card sc-rose">
            <div class="stat-icon-wrap"><i class="bi bi-graph-down-arrow"></i></div>
            <div class="stat-num">{{ $totals['slow_count'] }}</div>
            <div class="stat-label">Slow-Moving Products</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> Well below your average</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap"><i class="bi bi-box-seam"></i></div>
            <div class="stat-num">{{ $totals['no_sales_count'] }}</div>
            <div class="stat-label">No Sales Yet</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> In this period</div>
        </div>
    </div>

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-graph-up-arrow"></i>
                Product Sales Analytics
            </span>

            <div class="panel-tools">

                <a class="btn-ghost {{ $period === 'all' ? 'active' : '' }}"
                    href="{{ route('product_sales_analytics', ['sort' => $sort, 'period' => 'all']) }}">All Time</a>

                <a class="btn-ghost {{ $period === 'month' ? 'active' : '' }}"
                    href="{{ route('product_sales_analytics', ['sort' => $sort, 'period' => 'month']) }}">This Month</a>

                <a class="btn-ghost {{ $period === '30days' ? 'active' : '' }}"
                    href="{{ route('product_sales_analytics', ['sort' => $sort, 'period' => '30days']) }}">Last 30 Days</a>

                <a class="btn-ghost {{ $period === '7days' ? 'active' : '' }}"
                    href="{{ route('product_sales_analytics', ['sort' => $sort, 'period' => '7days']) }}">Last 7 Days</a>

            </div>

        </div>

        <div style="display:flex; gap:10px; padding:12px 22px; border-bottom:1px solid var(--border);">
            <span style="font-size:13px; color:var(--muted); align-self:center;">Sort by:</span>

            <a class="btn-ghost {{ $sort === 'revenue' ? 'active' : '' }}"
                href="{{ route('product_sales_analytics', ['sort' => 'revenue', 'period' => $period]) }}">
                <i class="bi bi-cash-stack"></i> Revenue
            </a>

            <a class="btn-ghost {{ $sort === 'units' ? 'active' : '' }}"
                href="{{ route('product_sales_analytics', ['sort' => 'units', 'period' => $period]) }}">
                <i class="bi bi-boxes"></i> Units Sold
            </a>
        </div>

        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Units Sold</th>
                        <th>Revenue</th>
                        <th>Orders</th>
                        <th>Last Sold</th>
                        <th>Movement</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $product)

                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <span style="color:var(--muted); font-size:12px;">
                                    Stock: {{ $product->stock_quantity }} {{ $product->unit }}
                                </span>
                            </td>

                            <td>
                                <span class="qty-tag">{{ $product->units_sold }}</span>
                            </td>

                            <td>
                                <span class="price-mono">PKR {{ number_format($product->revenue, 2) }}</span>
                            </td>

                            <td>
                                {{ $product->orders_count }}
                            </td>

                            <td>
                                @if($product->last_sold_at)
                                    {{ $product->last_sold_at->format('d M Y') }}
                                @else
                                    <span style="color:var(--muted);">Never</span>
                                @endif
                            </td>

                            <td>
                                @php
                                    $labels = [
                                        'fast' => 'Fast Moving',
                                        'steady' => 'Steady',
                                        'slow' => 'Slow Moving',
                                        'no_sales' => 'No Sales',
                                    ];
                                @endphp
                                <span class="move-pill {{ $product->movement }}">
                                    {{ $labels[$product->movement] }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" style="text-align:center; padding:40px;">
                                <i class="bi bi-box-seam" style="font-size:35px;"></i>
                                <br>
                                <strong>No products yet.</strong>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="hr-thin"></div>

        <div style="padding:14px 22px; color:var(--muted); font-size:13px">
            "Fast/Slow Moving" compares each product's units sold against your own average for this period &mdash; not against other farmers. Cancelled orders are excluded.
        </div>

    </div>

@endsection
