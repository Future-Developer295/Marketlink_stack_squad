@extends('Website._master')

@section('page_title', 'Dashboard')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <span class="active">Dashboard</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        @include('Website.Partials.alerts')

        <div class="row g-4">
            <div class="col-lg-3">
                @include('Website.Dashboard._sidebar')
            </div>

            <div class="col-lg-9">
                <div class="ml-card mb-4">
                    <h4 class="mb-1">Welcome back, {{ $user->name }}</h4>
                    <p class="text-muted mb-0 small">Here is an overview of your pre-orders and account activity.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3"><div class="ml-card text-center"><strong class="d-block fs-4">{{ $stats['total_orders'] }}</strong><span class="small text-muted">Total Orders</span></div></div>
                    <div class="col-6 col-lg-3"><div class="ml-card text-center"><strong class="d-block fs-4">{{ $stats['active_orders'] }}</strong><span class="small text-muted">Active Orders</span></div></div>
                    <div class="col-6 col-lg-3"><div class="ml-card text-center"><strong class="d-block fs-4">{{ $stats['completed_orders'] }}</strong><span class="small text-muted">Completed Orders</span></div></div>
                    <div class="col-6 col-lg-3"><div class="ml-card text-center"><strong class="d-block fs-4">{{ $stats['favorites'] }}</strong><span class="small text-muted">Favorites</span></div></div>
                </div>

                <div class="ml-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Recent Orders</strong>
                        <a href="{{ url('/dashboard/orders') }}" class="ml-btn-link small">View All <i class="fa-solid fa-arrow-right"></i></a>
                    </div>

                    @forelse($recentOrders as $order)
                    <div class="d-flex justify-content-between align-items-center py-3" style="border-top:1px solid var(--ml-border);">
                        <div>
                            <strong>#{{ $order->id }}</strong>
                            <span class="small text-muted d-block">{{ $order->farmer->stall_name ?? $order->farmer->business_name ?? '' }} &middot; {{ $order->items->count() }} Items</span>
                            <span class="small text-muted">{{ $order->order_date?->format('d M Y') }}</span>
                        </div>
                        <div class="text-end">
                            <span class="ml-badge ml-badge-mint">{{ ucfirst($order->status) }}</span>
                            <strong class="d-block mt-1">Rs. {{ number_format($order->total_amount, 0) }}</strong>
                        </div>
                        <a href="{{ url('/dashboard/orders/'.$order->id) }}" class="ml-btn-link small">Details</a>
                    </div>
                    @empty
                    @include('Website.Partials.empty-state', [
                        'icon' => 'fa-bag-shopping',
                        'title' => 'No Orders Yet',
                        'message' => 'Your placed pre-orders will show up here.',
                        'actionUrl' => url('/products'),
                        'actionLabel' => 'Browse Products',
                    ])
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
