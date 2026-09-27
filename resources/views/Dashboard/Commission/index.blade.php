@extends('Dashboard._master')

@section('nav_commission_tracking')
    active
@endsection

@section('page_title', 'Commission Tracking')

@push('styles')
<style>
    .rate-form {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .rate-form input[type="number"] {
        width: 78px;
        padding: 4px 8px;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 13px;
    }
    .rate-pill {
        display: inline-block;
        padding: 2px 9px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(35, 118, 76, 0.08);
        color: var(--brand, #23764c);
    }
    .rate-pill.is-default {
        background: var(--surface-2, #f1f5f3);
        color: var(--muted);
    }
</style>
@endpush

@section('body')

    <div class="stats-row">
        <div class="stat-card sc-emerald">
            <div class="stat-icon-wrap"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-num">PKR {{ number_format($totals['revenue'], 2) }}</div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> {{ $totals['orders'] }} orders</div>
        </div>
        <div class="stat-card sc-indigo">
            <div class="stat-icon-wrap"><i class="bi bi-percent"></i></div>
            <div class="stat-num">PKR {{ number_format($totals['commission'], 2) }}</div>
            <div class="stat-label">Platform Commission</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Platform's cut</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap"><i class="bi bi-wallet2"></i></div>
            <div class="stat-num">PKR {{ number_format($totals['payout'], 2) }}</div>
            <div class="stat-label">Owed to Farmers</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> Net payout</div>
        </div>
    </div>

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-percent"></i>
                Commission &amp; Fee Tracking
            </span>

            <div class="panel-tools">

                <a class="btn-ghost {{ $period === 'all' ? 'active' : '' }}"
                    href="{{ route('commission_tracking', ['period' => 'all', 'farmer_id' => $farmerId]) }}">All Time</a>

                <a class="btn-ghost {{ $period === 'month' ? 'active' : '' }}"
                    href="{{ route('commission_tracking', ['period' => 'month', 'farmer_id' => $farmerId]) }}">This Month</a>

                <a class="btn-ghost {{ $period === '30days' ? 'active' : '' }}"
                    href="{{ route('commission_tracking', ['period' => '30days', 'farmer_id' => $farmerId]) }}">Last 30 Days</a>

                <a class="btn-ghost {{ $period === '7days' ? 'active' : '' }}"
                    href="{{ route('commission_tracking', ['period' => '7days', 'farmer_id' => $farmerId]) }}">Last 7 Days</a>

            </div>

        </div>

        <form method="GET" action="{{ route('commission_tracking') }}"
            style="display:flex; gap:10px; padding:12px 22px; border-bottom:1px solid var(--border); align-items:center;">
            <input type="hidden" name="period" value="{{ $period }}">
            <span style="font-size:13px; color:var(--muted);">Farmer:</span>
            <select name="farmer_id" onchange="this.form.submit()"
                style="padding:5px 10px; border:1px solid var(--border); border-radius:6px; font-size:13px;">
                <option value="">All Farmers</option>
                @foreach($allFarmers as $f)
                    <option value="{{ $f->id }}" {{ (string) $farmerId === (string) $f->id ? 'selected' : '' }}>
                        {{ $f->stall_name ?? $f->business_name }}
                    </option>
                @endforeach
            </select>
            @if($farmerId)
                <a href="{{ route('commission_tracking', ['period' => $period]) }}" class="btn-ghost">Clear</a>
            @endif
        </form>

        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                        <th>Rate</th>
                        <th>Commission</th>
                        <th>Payout Owed</th>
                        @can('edit commission rate')
                            <th>Set Rate</th>
                        @endcan
                    </tr>
                </thead>

                <tbody>
                    @forelse($farmers as $farmer)

                        <tr>
                            <td>
                                <strong>{{ $farmer->stall_name ?? $farmer->business_name ?? 'N/A' }}</strong>
                                <br>
                                <span style="color:var(--muted); font-size:12px;">
                                    {{ $farmer->user->name ?? '' }}
                                </span>
                            </td>

                            <td>
                                <span class="qty-tag">{{ $farmer->orders_count }}</span>
                            </td>

                            <td>
                                <span class="price-mono">PKR {{ number_format($farmer->total_revenue, 2) }}</span>
                            </td>

                            <td>
                                <span class="rate-pill {{ $farmer->commission_rate === null ? 'is-default' : '' }}">
                                    {{ number_format($farmer->rate, 2) }}%
                                    {{ $farmer->commission_rate === null ? '(default)' : '' }}
                                </span>
                            </td>

                            <td>
                                <span class="price-mono">PKR {{ number_format($farmer->commission_amount, 2) }}</span>
                            </td>

                            <td>
                                <span class="price-mono">PKR {{ number_format($farmer->payout_amount, 2) }}</span>
                            </td>

                            @can('edit commission rate')
                                <td>
                                    <form class="rate-form" method="POST"
                                        action="{{ route('commission_rate_update', $farmer->id) }}">
                                        @csrf
                                        <input type="number" step="0.01" min="0" max="100"
                                            name="commission_rate"
                                            placeholder="{{ number_format($farmer->rate, 2) }}"
                                            value="{{ $farmer->commission_rate }}">
                                        <button type="submit" class="btn-ghost" title="Save rate">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" style="text-align:center; padding:40px;">
                                <i class="bi bi-percent" style="font-size:35px;"></i>
                                <br>
                                <strong>No order revenue in this range yet.</strong>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="hr-thin"></div>

        <div style="padding:14px 22px; color:var(--muted); font-size:13px">
            Commission = Revenue &times; Rate. Cancelled orders are excluded. Leave a farmer's rate box empty and save to reset them to the platform default ({{ number_format(config('marketlink.default_commission_rate', 10), 2) }}%).
        </div>

    </div>

@endsection
