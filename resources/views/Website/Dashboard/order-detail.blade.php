@extends('Website._master')

@section('page_title', 'Order #'.$order->id)

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ route('customer_orders') }}">My Orders</a> / <span class="active">#{{ $order->id }}</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        <div class="row g-4">
            <div class="col-lg-3">
                @include('Website.Dashboard._sidebar')
            </div>

            <div class="col-lg-9">
                <div class="ml-card mb-4 d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <strong class="fs-5">Order #{{ $order->id }}</strong>
                        <span class="small text-muted d-block">Placed {{ $order->order_date?->format('d M Y, h:i A') }}</span>
                    </div>
                    <span class="ml-badge ml-badge-mint">{{ ucfirst($order->status) }}</span>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="ml-card">
                            <strong>Items</strong>
                            <div class="table-responsive mt-2">
                                <table class="table ml-table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? '' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rs. {{ number_format($item->price, 0) }}</td>
                                            <td>Rs. {{ number_format($item->subtotal, 0) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total</th>
                                            <th>Rs. {{ number_format($order->total_amount, 0) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        @if($order->notes)
                        <div class="ml-card mt-3">
                            <strong>Note</strong>
                            <p class="small text-muted mb-0 mt-1">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-5">
                        <div class="ml-card mb-3">
                            <strong>Farmer</strong>
                            <p class="mb-0 mt-1">{{ $order->farmer->stall_name ?? $order->farmer->business_name ?? '' }}</p>
                            <span class="small text-muted d-block">{{ $order->farmer->user->name ?? '' }}</span>
                            <span class="small text-muted d-block">{{ $order->farmer->city ?? '' }}, {{ $order->farmer->state ?? '' }}</span>
                            <a href="{{ url('/farmers/'.$order->farmer_id) }}" class="ml-btn-link small mt-2">View Farmer</a>
                        </div>

                        @if($order->pickupSlot)
                        <div class="ml-card">
                            <strong>Pickup Slot</strong>
                            <p class="mb-0 mt-1">{{ \Illuminate\Support\Carbon::parse($order->pickupSlot->date)->format('D, d M Y') }}</p>
                            <span class="small text-muted d-block">{{ $order->pickupSlot->start_time }} - {{ $order->pickupSlot->end_time }}</span>
                            @if($order->pickupSlot->market)
                            <span class="small text-muted d-block">{{ $order->pickupSlot->market->name }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
