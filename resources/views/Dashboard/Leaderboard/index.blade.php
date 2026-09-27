@extends('Dashboard._master')

@section('nav_farmer_leaderboard')
    active
@endsection

@section('page_title', 'Farmer Leaderboard')

@push('styles')
<style>
    .rank-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-weight: 700;
        font-size: 13px;
        background: var(--surface-2, #f1f5f3);
        color: var(--muted);
    }
    .rank-badge.gold { background: #fff3cd; color: #93601b; }
    .rank-badge.silver { background: #e9ecef; color: #495057; }
    .rank-badge.bronze { background: #f3d9c4; color: #8a4b1f; }

    .leader-row-1 td, .leader-row-2 td, .leader-row-3 td {
        background: rgba(35, 118, 76, 0.03);
    }
</style>
@endpush

@section('body')

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-trophy-fill"></i>
                Farmer Performance Leaderboard
            </span>

            <div class="panel-tools">

                <a class="btn-ghost {{ $period === 'all' ? 'active' : '' }}"
                    href="{{ route('farmer_leaderboard', ['sort' => $sort, 'period' => 'all']) }}">All Time</a>

                <a class="btn-ghost {{ $period === 'month' ? 'active' : '' }}"
                    href="{{ route('farmer_leaderboard', ['sort' => $sort, 'period' => 'month']) }}">This Month</a>

                <a class="btn-ghost {{ $period === '30days' ? 'active' : '' }}"
                    href="{{ route('farmer_leaderboard', ['sort' => $sort, 'period' => '30days']) }}">Last 30 Days</a>

                <a class="btn-ghost {{ $period === '7days' ? 'active' : '' }}"
                    href="{{ route('farmer_leaderboard', ['sort' => $sort, 'period' => '7days']) }}">Last 7 Days</a>

            </div>

        </div>

        <div style="display:flex; gap:10px; padding:12px 22px; border-bottom:1px solid var(--border);">
            <span style="font-size:13px; color:var(--muted); align-self:center;">Sort by:</span>

            <a class="btn-ghost {{ $sort === 'revenue' ? 'active' : '' }}"
                href="{{ route('farmer_leaderboard', ['sort' => 'revenue', 'period' => $period]) }}">
                <i class="bi bi-cash-stack"></i> Revenue
            </a>

            <a class="btn-ghost {{ $sort === 'orders' ? 'active' : '' }}"
                href="{{ route('farmer_leaderboard', ['sort' => 'orders', 'period' => $period]) }}">
                <i class="bi bi-bag-check-fill"></i> Orders
            </a>
        </div>

        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Farmer</th>
                        <th>Total Orders</th>
                        <th>Total Revenue</th>
                        <th>Products Listed</th>
                        <th>Avg Rating</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($farmers as $farmer)

                        <tr class="{{ $loop->iteration <= 3 ? 'leader-row-'.$loop->iteration : '' }}">

                            <td>
                                @php
                                    $rankClass = match ($loop->iteration) {
                                        1 => 'gold',
                                        2 => 'silver',
                                        3 => 'bronze',
                                        default => '',
                                    };
                                @endphp
                                <span class="rank-badge {{ $rankClass }}">
                                    @if($loop->iteration <= 3)
                                        <i class="bi bi-trophy-fill"></i>
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </span>
                            </td>

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
                                {{ $farmer->products_count }}
                            </td>

                            <td>
                                @if($farmer->avg_rating > 0)
                                    <i class="bi bi-star-fill" style="color:#f0b429;"></i>
                                    {{ $farmer->avg_rating }} / 5
                                @else
                                    <span style="color:var(--muted);">No reviews yet</span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" style="text-align:center; padding:40px;">
                                <i class="bi bi-trophy" style="font-size:35px;"></i>
                                <br>
                                <strong>No approved farmers with sales data yet.</strong>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="hr-thin"></div>

        <div style="padding:14px 22px; color:var(--muted); font-size:13px">
            Cancelled orders are excluded from revenue and order counts.
        </div>

    </div>

@endsection
