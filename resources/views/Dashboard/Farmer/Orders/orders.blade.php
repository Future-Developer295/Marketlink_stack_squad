@extends('Dashboard.Farmer._master')
@section('nav_orders')
active
@endsection
@section('page_title', 'Orders')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-cart-check-fill"></i> Orders</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
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
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy Customer</strong></td>
                            <td>Sat, 9:00–11:00 AM</td>
                            <td><span class='qty-tag'>0</span></td>
                            <td><span class='price-mono'>PKR 0.00</span></td>
                            <td><span class="badge-status bs-in"><i class="bi bi-circle-fill"></i> Pending</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("farmer.orders.show", 1) }}'><i class='bi bi-eye'></i> View</a></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">Farmers can view orders and update their status (pending → confirmed → ready → picked up). Orders cannot be added or deleted here.</div>
    </div>
@endsection
