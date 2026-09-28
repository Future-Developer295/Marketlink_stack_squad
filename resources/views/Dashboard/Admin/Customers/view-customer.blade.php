@extends('Dashboard.Admin._master')
@section('nav_customers')
active
@endsection
@section('page_title', 'Customer Details')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-lines-fill"></i> Customer Details</span>
            <a class="btn-ghost sm" href="{{ route('admin.customers.index') }}"><i class="bi bi-x"></i> Back</a>
        </div>
        <div style="padding:22px">
            <div class="detail-grid">
                <div class="detail-cell"><span class="dl">Name</span><span class="dv">Dummy Customer</span></div>
                <div class="detail-cell"><span class="dl">Email</span><span class="dv">customer@marketlink.test</span></div>
                <div class="detail-cell"><span class="dl">Phone</span><span class="dv">0300-0000000</span></div>
                <div class="detail-cell"><span class="dl">Address</span><span class="dv">—</span></div>
                <div class="detail-cell"><span class="dl">Joined</span><span class="dv">—</span></div>
                <div class="detail-cell"><span class="dl">Total Orders</span><span class="dv">0</span></div>
            </div>
        </div>
    </div>
@endsection
