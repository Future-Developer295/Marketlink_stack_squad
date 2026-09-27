@extends('Dashboard._master')
@section('nav_customers')
active
@endsection
@section('page_title', 'Customer Details')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-lines-fill"></i> Customer Details</span>
            <a class="btn-ghost sm" href="{{ route('customers') }}"><i class="bi bi-x"></i> Back</a>
        </div>
        <div style="padding:22px">
            <div class="detail-grid">
                <div class="detail-cell"><span class="dl">Name</span><span class="dv">{{ $customer->name }}</span></div>
                <div class="detail-cell"><span class="dl">Email</span><span class="dv">{{ $customer->email }}</span></div>
                <div class="detail-cell"><span class="dl">Phone</span><span class="dv">{{ $customer->phone ?? '—' }}</span></div>
                <div class="detail-cell"><span class="dl">Address</span><span class="dv">{{ $customer->address ?? '—' }}</span></div>
<<<<<<< HEAD
                <div class="detail-cell"><span class="dl">Joined</span><span class="dv">{{ $customer->created_at?->format('d M Y') ?? '—' }}</span></div>
                <div class="detail-cell"><span class="dl">Total Orders</span><span class="dv">{{ $customer->orders->count() }}</span></div>
                <div class="detail-cell"><span class="dl">Status</span><span class="dv">{{ $customer->is_active ? 'Active' : 'Inactive' }}</span></div>
=======
                <div class="detail-cell"><span class="dl">Joined</span><span class="dv">{{ $customer->created_at?->format('d M, Y') ?? '—' }}</span></div>
                <div class="detail-cell"><span class="dl">Total Orders</span><span class="dv">{{ $customer->orders_count }}</span></div>
            </div>

            <div class="hr-thin" style="margin:20px 0"></div>

            <h4 style="margin-bottom:12px">Recent Orders</h4>
            <div class="tbl-wrap">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ ucfirst($order->status) }}</td>
                                <td>{{ $order->order_date?->format('d M, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; color:var(--muted)">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
>>>>>>> 281ee3e (Dashbord Improvement And New Feature Add)
            </div>
        </div>
    </div>
@endsection