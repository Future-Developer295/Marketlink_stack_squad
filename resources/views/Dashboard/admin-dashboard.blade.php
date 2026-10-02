@extends('Dashboard._master')
@section('nav_dashboard_admin')
active
@endsection
@section('page_title', 'Admin Dashboard')

@push('styles')
<style>
    .chart-grid { display: grid; gap: 16px; margin-bottom: 16px; }
    .chart-grid.g-2-1 { grid-template-columns: 2fr 1fr; }
    .chart-grid.g-1-1-1 { grid-template-columns: 1fr 1fr 1fr; }
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
    .chart-canvas-wrap.short { height: 180px; }

    .gauge-wrap { position: relative; display: flex; align-items: center; justify-content: center; height: 170px; }
    .gauge-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -46%); text-align: center; pointer-events: none; }
    .gauge-value { font-size: 26px; font-weight: 800; color: var(--text); letter-spacing: -1px; line-height: 1; }
    .gauge-caption { font-size: 10.5px; color: var(--muted); margin-top: 4px; }
    .gauge-foot { text-align: center; font-size: 12px; color: var(--muted); padding: 0 16px 18px; }
    .gauge-foot strong { color: var(--text); }

    .legend-list { display: flex; flex-direction: column; gap: 10px; padding: 4px 4px 0; }
    .legend-row { display: flex; align-items: center; justify-content: space-between; font-size: 12.5px; }
    .legend-key { display: flex; align-items: center; gap: 8px; color: var(--text); }
    .legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .legend-val { color: var(--muted); font-weight: 600; }

    .empty-chart-note { text-align: center; color: var(--muted); font-size: 12.5px; padding: 30px 10px; }

    @media (max-width: 1100px) {
        .chart-grid.g-1-1-1 { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 900px) {
        .chart-grid.g-2-1, .chart-grid.g-1-1-1 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('body')

    <div class="stats-row">
        <div class="stat-card sc-indigo">
            <div class="stat-icon-wrap"><i class="bi bi-people-fill"></i></div>
            <div class="stat-num">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Customers + Farmers</div>
        </div>
        <div class="stat-card sc-emerald">
            <div class="stat-icon-wrap"><i class="bi bi-person-workspace"></i></div>
            <div class="stat-num">{{ $pendingFarmers }}</div>
            <div class="stat-label">Pending Farmers</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Awaiting approval</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap"><i class="bi bi-shop-window"></i></div>
            <div class="stat-num">{{ $activeMarkets }}</div>
            <div class="stat-label">Active Markets</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> All regions</div>
        </div>
        <div class="stat-card sc-rose">
            <div class="stat-icon-wrap"><i class="bi bi-box-seam-fill"></i></div>
            <div class="stat-num">{{ $totalProducts }}</div>
            <div class="stat-label">Total Products</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> Across all farmers</div>
        </div>
        <div class="stat-card sc-purple">
            <div class="stat-icon-wrap"><i class="bi bi-flag-fill"></i></div>
            <div class="stat-num">{{ $flaggedReviews }}</div>
            <div class="stat-label">Flagged Reviews</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Needs moderation</div>
        </div>
    </div>

    <div class="chart-grid g-2-1">

        <div class="chart-panel">
            <div class="chart-panel-header">
                <span class="panel-title"><i class="bi bi-graph-up-arrow"></i> Platform Revenue &amp; Orders (Last 7 Days)</span>
                <span class="chart-panel-sub">All farmers combined</span>
            </div>
            <div class="chart-body">
                <div class="chart-canvas-wrap">
                    <canvas id="adminRevenueTrendChart"></canvas>
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
                        <canvas id="adminOrderStatusChart"></canvas>
                    </div>
                    <div class="legend-list" id="adminOrderStatusLegend"></div>
                @else
                    <div class="empty-chart-note">No orders on the platform yet.</div>
                @endif
            </div>
        </div>

    </div>

    <div class="chart-grid g-1-1-1">

        <div class="chart-panel">
            <div class="chart-panel-header">
                <span class="panel-title"><i class="bi bi-bar-chart-fill"></i> Top Markets by Farmers</span>
            </div>
            <div class="chart-body">
                @if(count($topMarketData) > 0)
                    <div class="chart-canvas-wrap short">
                        <canvas id="topMarketsChart"></canvas>
                    </div>
                @else
                    <div class="empty-chart-note">No active farmer-market links yet.</div>
                @endif
            </div>
        </div>

        <div class="chart-panel">
            <div class="chart-panel-header">
                <span class="panel-title"><i class="bi bi-people-fill"></i> Users by Role</span>
            </div>
            <div class="chart-body">
                <div class="chart-canvas-wrap short">
                    <canvas id="usersByRoleChart"></canvas>
                </div>
                <div class="legend-list" id="usersByRoleLegend"></div>
            </div>
        </div>

        <div class="chart-panel">
            <div class="chart-panel-header">
                <span class="panel-title"><i class="bi bi-patch-check-fill"></i> Farmer Approval Rate</span>
            </div>
            <div class="chart-body">
                <div class="gauge-wrap">
                    <canvas id="approvalGaugeChart"></canvas>
                    <div class="gauge-center">
                        <div class="gauge-value">{{ $approvalRate }}%</div>
                        <div class="gauge-caption">approved</div>
                    </div>
                </div>
            </div>
            <div class="gauge-foot"><strong>{{ $pendingFarmers }}</strong> pending review</div>
        </div>

    </div>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-workspace"></i> Pending Farmer Approvals</span>
            <a class="btn-ghost" href="{{ route('farmers') }}">View all <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr><th>#ID</th><th>Stall Name</th><th>Owner</th><th>Submitted</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($pendingFarmerList as $fp)
                    <tr>
                        <td><span class="id-chip">{{ $loop->iteration }}</span></td>
                        <td><strong>{{ $fp->stall_name }}</strong></td>
                        <td>{{ $fp->user?->name ?? 'N/A' }}</td>
                        <td>{{ $fp->created_at?->format('Y-m-d') }}</td>
                        <td><span class="badge-status bs-in"><i class="bi bi-circle-fill"></i> Pending</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:var(--muted); padding:20px">No pending farmer approvals.</td>
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

    Chart.defaults.font.family = "'Figtree','Plus Jakarta Sans',sans-serif";
    Chart.defaults.color = cMuted;

    var revenueEl = document.getElementById('adminRevenueTrendChart');
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

    var statusEl = document.getElementById('adminOrderStatusChart');
    if (statusEl) {
        var statusLabels = @json($orderStatusLabels);
        var statusData = @json($orderStatusData);
        var statusColors = [cAmber, cInfo, cPrimary, cGreen, cRose];

        new Chart(statusEl, {
            type: 'doughnut',
            data: { labels: statusLabels, datasets: [{ data: statusData, backgroundColor: statusColors, borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '68%', plugins: { legend: { display: false } } }
        });

        var legendWrap = document.getElementById('adminOrderStatusLegend');
        if (legendWrap) {
            var total = statusData.reduce((a, b) => a + b, 0) || 1;
            legendWrap.innerHTML = statusLabels.map((label, i) => {
                var pct = Math.round((statusData[i] / total) * 100);
                return '<div class="legend-row"><span class="legend-key"><span class="legend-dot" style="background:' + statusColors[i] + '"></span>' + label + '</span><span class="legend-val">' + statusData[i] + ' (' + pct + '%)</span></div>';
            }).join('');
        }
    }

    var topMarketsEl = document.getElementById('topMarketsChart');
    if (topMarketsEl) {
        new Chart(topMarketsEl, {
            type: 'bar',
            data: {
                labels: @json($topMarketLabels),
                datasets: [{
                    label: 'Farmers',
                    data: @json($topMarketData),
                    backgroundColor: cPrimary,
                    borderRadius: 6,
                    maxBarThickness: 24
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

    var roleEl = document.getElementById('usersByRoleChart');
    if (roleEl) {
        var roleLabels = @json($userRoleLabels);
        var roleData = @json($userRoleData);
        var roleColors = [cRose, cGreen, cInfo];

        new Chart(roleEl, {
            type: 'doughnut',
            data: { labels: roleLabels, datasets: [{ data: roleData, backgroundColor: roleColors, borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '68%', plugins: { legend: { display: false } } }
        });

        var roleLegendWrap = document.getElementById('usersByRoleLegend');
        if (roleLegendWrap) {
            var totalUsers = roleData.reduce((a, b) => a + b, 0) || 1;
            roleLegendWrap.innerHTML = roleLabels.map((label, i) => {
                var pct = Math.round((roleData[i] / totalUsers) * 100);
                return '<div class="legend-row"><span class="legend-key"><span class="legend-dot" style="background:' + roleColors[i] + '"></span>' + label + '</span><span class="legend-val">' + roleData[i] + ' (' + pct + '%)</span></div>';
            }).join('');
        }
    }

    var approvalEl = document.getElementById('approvalGaugeChart');
    if (approvalEl) {
        var rate = {{ (float) $approvalRate }};

        new Chart(approvalEl, {
            type: 'doughnut',
            data: { datasets: [{ data: [rate, 100 - rate], backgroundColor: [cGreen, cBorder], borderWidth: 0 }] },
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
