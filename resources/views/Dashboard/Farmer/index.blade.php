@extends('Dashboard.Farmer._master')
@section('nav_dashboard')
active
@endsection
@section('page_title', 'Farmer Dashboard')
@section('body')
    <div class="stats-row">
        <div class="stat-card sc-indigo">
            <div class="stat-icon-wrap"><i class="bi bi-box-seam-fill"></i></div>
            <div class="stat-num">{{ $products }}</div>
            <div class="stat-label">My Products</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Active listings</div>
        </div>
        <div class="stat-card sc-emerald">
            <div class="stat-icon-wrap"><i class="bi bi-shop-window"></i></div>
            <div class="stat-num">{{ $markets }}</div>
            <div class="stat-label">Markets Joined</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Approved stalls</div>
        </div>
        <div class="stat-card sc-amber">
            <div class="stat-icon-wrap"><i class="bi bi-cart-check-fill"></i></div>
            <div class="stat-num">{{ $pendingOrders }}</div>
            <div class="stat-label">Pending Orders</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Awaiting pickup</div>
        </div>
        <div class="stat-card sc-rose">
            <div class="stat-icon-wrap"><i class="bi bi-clock-history"></i></div>
            <div class="stat-num">{{ $openSlots }}</div>
            <div class="stat-label">Open Pickup Slots</div>
            <div class="stat-trend down"><i class="bi bi-arrow-down-right"></i> This week</div>
        </div>
        <div class="stat-card sc-purple">
            <div class="stat-icon-wrap"><i class="bi bi-star-fill"></i></div>
            <div class="stat-num">{{ number_format($averageRating, 1) }}</div>
            <div class="stat-label">Average Rating</div>
            <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> From customers</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-cart-check-fill"></i> Recent Orders</span>
            <a class="btn-ghost" href="{{ route('farmer.orders.index') }}">View all <i class="bi bi-arrow-right"></i></a>
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
