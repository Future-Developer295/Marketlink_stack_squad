@extends('Dashboard.Farmer._master')
@section('nav_orders')
active
@endsection
@section('page_title', 'Order Details')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-cart-check-fill"></i> Order #1</span>
            <a class="btn-ghost sm" href="{{ route('farmer.orders.index') }}"><i class="bi bi-x"></i> Back</a>
        </div>
        <div style="padding:22px">
            <div class="detail-grid">
                <div class="detail-cell"><span class="dl">Customer</span><span class="dv">Dummy Customer</span></div>
                <div class="detail-cell"><span class="dl">Order Date</span><span class="dv">—</span></div>
                <div class="detail-cell"><span class="dl">Pickup Slot</span><span class="dv">Sat, 9:00–11:00 AM</span></div>
                <div class="detail-cell"><span class="dl">Total Amount</span><span class="dv">PKR 0.00</span></div>
                <div class="detail-cell"><span class="dl">Notes</span><span class="dv">—</span></div>
            </div>

            <div class="spacer-24"></div>
            <div class="tbl-wrap">
                <table class="dtable">
                    <thead>
                        <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                                                <tr>
                            <td><strong>Dummy Product</strong></td>
                            <td><span class="qty-tag">0</span></td>
                            <td><span class="price-mono">PKR 0.00</span></td>
                            <td><span class="price-mono">PKR 0.00</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="spacer-24"></div>
            <form action="{{ route('farmer.orders.updateStatus', 1) }}" method="post">
                @csrf
                <div class="grid-2">
                    <div class="field">
                        <label class="field-label">Update Status</label>
                        <select name="status" class="field-input">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="ready">Ready</option>
                            <option value="picked_up">Picked Up</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Update Status</button>
            </form>
        </div>
    </div>
@endsection
