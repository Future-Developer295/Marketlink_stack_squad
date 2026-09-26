@extends('Dashboard._master')
@section('nav_orders')
active
@endsection
@section('page_title', 'Orders')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-cart-check-fill"></i> Orders</span>
            <div class="panel-tools">
                <form class="search-box" method="GET" action="{{ route('orders') }}">
                    <i class="bi bi-search"></i>
                    <input
    type="text"
    name="q"
    value="{{ request('q') }}"
    placeholder="Search…"
/>
                </form>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Pickup Slot</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
@forelse($orders as $order)

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
            <span class="qty-tag">
                {{ $order->items->sum('quantity') }}
            </span>
        </td>

        <td>
            <span class="price-mono">
                PKR {{ number_format($order->total_amount, 2) }}
            </span>
        </td>

        <td>

            @if($order->status === 'pending')

                <span class="badge-status">
                    <i class="bi bi-circle-fill"></i>
                    Pending
                </span>

            @elseif($order->status === 'confirmed')

                <span class="badge-status bs-in">
                    <i class="bi bi-circle-fill"></i>
                    Confirmed
                </span>

            @elseif($order->status === 'ready')

                <span class="badge-status bs-in">
                    <i class="bi bi-circle-fill"></i>
                    Ready
                </span>

            @elseif($order->status === 'picked_up')

                <span class="badge-status bs-in">
                    <i class="bi bi-circle-fill"></i>
                    Picked Up
                </span>

            @else

                <span class="badge-status">
                    <i class="bi bi-circle-fill"></i>
                    Cancelled
                </span>

            @endif

        </td>

        <td>

            <div class="action-wrap">

                <a
                    class="btn-ghost sm"
                    href="{{ route('order_view', $order->id) }}"
                >
                    <i class="bi bi-eye"></i>
                    View
                </a>

            </div>

        </td>

    </tr>

@empty

    <tr>
        <td colspan="7" style="text-align:center;">
            No orders found.
        </td>
    </tr>

@endforelse
                    
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">Orders are created by customers at checkout — this dashboard only views them and updates status.</div>
    </div>
@endsection
