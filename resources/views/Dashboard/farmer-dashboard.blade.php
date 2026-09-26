@extends('Dashboard._master')

@section('nav_dashboard_farmer')
active
@endsection

@section('page_title', 'Farmer Dashboard')

@push('styles')
<style>
    .chart-grid { display: grid; gap: 16px; margin-bottom: 16px; }
    .chart-grid.g-2-1 { grid-template-columns: 2fr 1fr; }
    .chart-grid.g-1-1 { grid-template-columns: 1fr 1fr; }
    .chart-panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .chart-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
    }
    .chart-panel-header .panel-title { font-size: 14px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
    .chart-panel-header .panel-title i { color: var(--emerald); }
    .chart-panel-sub { font-size: 11.5px; color: var(--muted); }
    .chart-body { padding: 18px 20px; flex: 1; }
    .chart-canvas-wrap { position: relative; height: 240px; }
    .chart-canvas-wrap.short { height: 190px; }

    .gauge-wrap { position: relative; display: flex; align-items: center; justify-content: center; height: 190px; }
    .gauge-center {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -46%);
        text-align: center;
        pointer-events: none;
    }
    .gauge-value { font-size: 30px; font-weight: 800; color: var(--text); letter-spacing: -1px; line-height: 1; }
    .gauge-caption { font-size: 11px; color: var(--muted); margin-top: 4px; }
    .gauge-foot { text-align: center; font-size: 12px; color: var(--muted); padding: 0 20px 18px; }
    .gauge-foot strong { color: var(--text); }

    .legend-list { display: flex; flex-direction: column; gap: 10px; padding: 4px 4px 0; }
    .legend-row { display: flex; align-items: center; justify-content: space-between; font-size: 12.5px; }
    .legend-key { display: flex; align-items: center; gap: 8px; color: var(--text); }
    .legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .legend-val { color: var(--muted); font-weight: 600; }

    .empty-chart-note { text-align: center; color: var(--muted); font-size: 12.5px; padding: 30px 10px; }

    @media (max-width: 900px) {
        .chart-grid.g-2-1, .chart-grid.g-1-1 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('body')

    <div class="stats-row">

        {{-- My Products --}}
        <div class="stat-card sc-indigo">
            <div class="stat-icon-wrap">
                <i class="bi bi-box-seam-fill"></i>
            </div>

            <div class="stat-num">{{ $products }}</div>
            <div class="stat-label">My Products</div>

            <div class="stat-trend up">
                <i class="bi bi-arrow-up-right"></i>
                Active listings
            </div>
        </div>


        {{-- Markets Joined --}}
        <div class="stat-card sc-emerald">
            <div class="stat-icon-wrap">
                <i class="bi bi-shop-window"></i>
            </div>

            <div class="stat-num">{{ $markets }}</div>
            <div class="stat-label">Markets Joined</div>

            <div class="stat-trend up">
                <i class="bi bi-arrow-up-right"></i>
                Approved stalls
            </div>
        </div>


        {{-- Pending Orders --}}
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap">
                <i class="bi bi-cart-check-fill"></i>
            </div>

            <div class="stat-num">{{ $pendingOrders }}</div>
            <div class="stat-label">Pending Orders</div>

            <div class="stat-trend up">
                <i class="bi bi-arrow-up-right"></i>
                Awaiting pickup
            </div>
        </div>


        {{-- Open Pickup Slots --}}
        <div class="stat-card sc-rose">
            <div class="stat-icon-wrap">
                <i class="bi bi-clock-history"></i>
            </div>

            <div class="stat-num">{{ $openSlots }}</div>
            <div class="stat-label">Open Pickup Slots</div>

            <div class="stat-trend down">
                <i class="bi bi-arrow-down-right"></i>
                This week
            </div>
        </div>


        {{-- Average Rating --}}
        <div class="stat-card sc-purple">
            <div class="stat-icon-wrap">
                <i class="bi bi-star-fill"></i>
            </div>

            <div class="stat-num">
                {{ number_format($averageRating, 1) }}
            </div>

            <div class="stat-label">Average Rating</div>

            <div class="stat-trend up">
                <i class="bi bi-arrow-up-right"></i>
                From customers
            </div>
        </div>

    </div>


    {{-- Charts row 1: Revenue trend + Order status --}}
    <div class="chart-grid g-2-1">

        <div class="chart-panel">
            <div class="chart-panel-header">
                <span class="panel-title"><i class="bi bi-graph-up-arrow"></i> Revenue &amp; Orders (Last 7 Days)</span>
                <span class="chart-panel-sub">Auto-updates daily</span>
            </div>
            <div class="chart-body">
                <div class="chart-canvas-wrap">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="chart-panel">
            <div class="chart-panel-header">
                <span class="panel-title"><i class="bi bi-pie-chart-fill"></i> Order Status</span>
            </div>
            <div class="chart-body">
                @if(array_sum($orderStatusData) > 0)
                    <div class="chart-canvas-wrap short">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="legend-list" id="orderStatusLegend"></div>
                @else
                    <div class="empty-chart-note">No orders yet — this will fill in as orders come in.</div>
                @endif
            </div>
        </div>

    </div>

    {{-- Charts row 2: Top products + Average rating gauge --}}
    <div class="chart-grid g-1-1">

        <div class="chart-panel">
            <div class="chart-panel-header">
                <span class="panel-title"><i class="bi bi-bar-chart-fill"></i> Top Products by Units Sold</span>
            </div>
            <div class="chart-body">
                @if(count($topProductData) > 0)
                    <div class="chart-canvas-wrap">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                @else
                    <div class="empty-chart-note">No sales yet — top products will show up here once orders come in.</div>
                @endif
            </div>
        </div>

        <div class="chart-panel">
            <div class="chart-panel-header">
                <span class="panel-title"><i class="bi bi-star-fill"></i> Average Rating</span>
            </div>
            <div class="chart-body">
                <div class="gauge-wrap">
                    <canvas id="ratingGaugeChart"></canvas>
                    <div class="gauge-center">
                        <div class="gauge-value">{{ number_format($averageRating, 1) }}</div>
                        <div class="gauge-caption">out of 5.0</div>
                    </div>
                </div>
            </div>
            <div class="gauge-foot">Based on <strong>{{ $totalReviews }}</strong> customer reviews</div>
        </div>

    </div>

    {{-- Recent Orders --}}
    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-cart-check-fill"></i>
                Recent Orders
            </span>

            <a class="btn-ghost" href="{{ route('orders') }}">
                View all
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>


        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Pickup Slot</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse($recentOrders as $order)

                        <tr>

                            <td>
                                <span class="id-chip">
                                    {{ $order->id }}
                                </span>
                            </td>

                            <td>
                                <strong>
                                    {{ $order->user->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $order->pickupSlot->date->format('D, d M') }},
                                {{ \Carbon\Carbon::parse($order->pickupSlot->start_time)->format('g:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($order->pickupSlot->end_time)->format('g:i A') }}
                            </td>

                            <td>
                                <span class="price-mono">
                                    PKR {{ number_format($order->total_amount, 2) }}
                                </span>
                            </td>

                            <td>
                                <span class="badge-status bs-in">
                                    <i class="bi bi-circle-fill"></i>
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" style="text-align:center;">
                                No recent orders found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var css = getComputedStyle(document.documentElement);
    var cGreen = css.getPropertyValue('--emerald').trim() || '#23764c';
    var cPrimary = css.getPropertyValue('--primary').trim() || '#073f3c';
    var cAmber = css.getPropertyValue('--warning').trim() || '#93601b';
    var cRose = css.getPropertyValue('--danger').trim() || '#b54748';
    var cInfo = css.getPropertyValue('--info').trim() || '#276b75';
    var cMuted = css.getPropertyValue('--muted').trim() || '#657970';
    var cBorder = css.getPropertyValue('--border').trim() || '#e3ebe6';
    var cText = css.getPropertyValue('--text').trim() || '#172b26';

    Chart.defaults.font.family = "'Figtree','Plus Jakarta Sans',sans-serif";
    Chart.defaults.color = cMuted;

    // Revenue & Orders trend
    var revenueEl = document.getElementById('revenueTrendChart');
    if (revenueEl) {
        new Chart(revenueEl, {
            type: 'bar',
            data: {
                labels: @json($revenueTrendLabels),
                datasets: [
                    {
                        type: 'bar',
                        label: 'Revenue (PKR)',
                        data: @json($revenueTrendData),
                        backgroundColor: cGreen,
                        borderRadius: 6,
                        maxBarThickness: 28,
                        order: 2,
                        yAxisID: 'y'
                    },
                    {
                        type: 'line',
                        label: 'Orders',
                        data: @json($ordersTrendData),
                        borderColor: cPrimary,
                        backgroundColor: cPrimary,
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: cPrimary,
                        order: 1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true } } },
                scales: {
                    x: { grid: { display: false } },
                    y: { position: 'left', grid: { color: cBorder }, ticks: { callback: v => 'PKR ' + v } },
                    y1: { position: 'right', grid: { display: false }, ticks: { precision: 0 } }
                }
            }
        });
    }

    // Order status donut
    var statusEl = document.getElementById('orderStatusChart');
    if (statusEl) {
        var statusLabels = @json($orderStatusLabels);
        var statusData = @json($orderStatusData);
        var statusColors = [cAmber, cInfo, cPrimary, cGreen, cRose];

        new Chart(statusEl, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{ data: statusData, backgroundColor: statusColors, borderWidth: 0 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: { legend: { display: false } }
            }
        });

        var legendWrap = document.getElementById('orderStatusLegend');
        if (legendWrap) {
            var total = statusData.reduce((a, b) => a + b, 0) || 1;
            legendWrap.innerHTML = statusLabels.map((label, i) => {
                var pct = Math.round((statusData[i] / total) * 100);
                return '<div class="legend-row"><span class="legend-key"><span class="legend-dot" style="background:' + statusColors[i] + '"></span>' + label + '</span><span class="legend-val">' + statusData[i] + ' (' + pct + '%)</span></div>';
            }).join('');
        }
    }

    // Top products bar
    var topProductsEl = document.getElementById('topProductsChart');
    if (topProductsEl) {
        new Chart(topProductsEl, {
            type: 'bar',
            data: {
                labels: @json($topProductLabels),
                datasets: [{
                    label: 'Units sold',
                    data: @json($topProductData),
                    backgroundColor: cGreen,
                    borderRadius: 6,
                    maxBarThickness: 26
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: cBorder }, ticks: { precision: 0 } },
                    y: { grid: { display: false } }
                }
            }
        });
    }

    // Average rating gauge
    var gaugeEl = document.getElementById('ratingGaugeChart');
    if (gaugeEl) {
        var rating = {{ (float) $averageRating }};
        var pct = Math.max(0, Math.min(5, rating)) / 5 * 100;

        new Chart(gaugeEl, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [pct, 100 - pct],
                    backgroundColor: [cGreen, cBorder],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                circumference: 270,
                rotation: 225,
                plugins: { legend: { display: false }, tooltip: { enabled: false } }
            }
        });
    }
});
</script>
@endpush

