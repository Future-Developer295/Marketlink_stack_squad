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
                <div class="detail-cell"><span class="dl">Joined</span><span class="dv">{{ $customer->created_at?->format('d M Y') ?? '—' }}</span></div>
                <div class="detail-cell"><span class="dl">Total Orders</span><span class="dv">{{ $customer->orders->count() }}</span></div>
                <div class="detail-cell"><span class="dl">Status</span><span class="dv">{{ $customer->is_active ? 'Active' : 'Inactive' }}</span></div>
            </div>
        </div>
    </div>
@endsection
