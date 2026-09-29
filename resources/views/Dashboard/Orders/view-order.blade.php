@extends('Dashboard._master')

@section('nav_orders')
active
@endsection

@section('page_title', 'Order Details')

@section('body')

<div class="panel" style="max-width:1440px">

    <div class="panel-header">

        <span class="panel-title">
            <i class="bi bi-cart-check-fill"></i>
            Order #{{ $order->id }}
        </span>

        <a class="btn-ghost sm" href="{{ route('orders') }}">
            <i class="bi bi-x"></i>
            Back
        </a>

    </div>

    <div id="order-details" style="padding:22px">

        <div class="detail-grid">

            <div class="detail-cell">
                <span class="dl">Customer</span>
                <span class="dv">
                    {{ $order->user->name }}
                </span>
            </div>

            <div class="detail-cell">
                <span class="dl">Order Date</span>
                <span class="dv">
                    {{ $order->order_date->format('d M Y, h:i A') }}
                </span>
            </div>

            <div class="detail-cell">
                <span class="dl">Pickup Slot</span>
                <span class="dv">
                    {{ $order->pickupSlot->date->format('D, d M Y') }},
                    {{ \Carbon\Carbon::parse($order->pickupSlot->start_time)->format('g:i A') }}
                    –
                    {{ \Carbon\Carbon::parse($order->pickupSlot->end_time)->format('g:i A') }}
                </span>
            </div>

            <div class="detail-cell">
                <span class="dl">Total Amount</span>
                <span class="dv">
                    PKR {{ number_format($order->total_amount, 2) }}
                </span>
            </div>

            <div class="detail-cell">
                <span class="dl">Notes</span>
                <span class="dv">
                    {{ $order->notes ?? '—' }}
                </span>
            </div>

        </div>

        <div class="spacer-24"></div>

        <div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($order->items as $item)

                        <tr>

                            <td>
                                <strong>
                                    {{ $item->product->name }}
                                </strong>
                            </td>

                            <td>
                                <span class="qty-tag">
                                    {{ $item->quantity }}
                                </span>
                            </td>

                            <td>
                                <span class="price-mono">
                                    PKR {{ number_format($item->price, 2) }}
                                </span>
                            </td>

                            <td>
                                <span class="price-mono">
                                    PKR {{ number_format($item->subtotal, 2) }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" style="text-align:center;">
                                No items found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="spacer-24"></div>

        <form
            action="{{ route('order_status_update', $order->id) }}"
            method="post"
            data-ajax
            data-ajax-refresh="#order-details"
        >

            @csrf

            <div class="grid-2">

                <div class="field">

                    <label class="field-label">
                        Update Status
                    </label>

                    <select name="status" class="field-input">

                        <option value="pending"
                            {{ $order->status === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="confirmed"
                            {{ $order->status === 'confirmed' ? 'selected' : '' }}>
                            Confirmed
                        </option>

                        <option value="ready"
                            {{ $order->status === 'ready' ? 'selected' : '' }}>
                            Ready
                        </option>

                        <option value="picked_up"
                            {{ $order->status === 'picked_up' ? 'selected' : '' }}>
                            Picked Up
                        </option>

                        <option value="cancelled"
                            {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>

                    </select>

                </div>

            </div>

            <button class="btn-primary" type="submit">
                <i class="bi bi-check2"></i>
                Update Status
            </button>

        </form>

    </div>

</div>

@endsection