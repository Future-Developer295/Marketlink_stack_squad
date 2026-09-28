@extends('Website._master')

@section('page_title', 'My Orders')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ route('customer_dashboard') }}">Dashboard</a> / <span class="active">My Orders</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        @include('Website.Partials.alerts')

        <div class="row g-4">
            <div class="col-lg-3">
                @include('Website.Dashboard._sidebar')
            </div>

            <div class="col-lg-9">
                <div class="ml-card mb-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('customer_orders') }}" class="ml-chip {{ request('status') ? '' : 'is-active' }}">All ({{ $statusCounts->sum() }})</a>
                        @foreach($statusCounts as $status => $count)
                        <a href="{{ route('customer_orders', ['status' => $status]) }}" class="ml-chip {{ request('status') == $status ? 'is-active' : '' }}">{{ ucfirst($status) }} ({{ $count }})</a>
                        @endforeach
                    </div>
                </div>

                <div class="ml-card">
                    @if($orders->isEmpty())
                    @include('Website.Partials.empty-state', [
                        'icon' => 'fa-bag-shopping',
                        'title' => 'No Orders Found',
                        'message' => 'You have not placed any pre-orders yet.',
                        'actionUrl' => url('/products'),
                        'actionLabel' => 'Browse Products',
                    ])
                    @else
                    <div class="table-responsive">
                        <table class="table ml-table align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Farmer</th>
                                    <th>Items</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->farmer->stall_name ?? $order->farmer->business_name ?? '' }}</td>
                                    <td>{{ $order->items->count() }}</td>
                                    <td>{{ $order->order_date?->format('d M Y') }}</td>
                                    <td>Rs. {{ number_format($order->total_amount, 0) }}</td>
                                    <td><span class="ml-badge ml-badge-mint">{{ ucfirst($order->status) }}</span></td>
                                    <td><a href="{{ route('customer_order_detail', $order->id) }}" class="ml-btn-link small">View</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                @include('Website.Partials.pagination', ['paginator' => $orders])
            </div>
        </div>
    </div>
</section>

@endsection
