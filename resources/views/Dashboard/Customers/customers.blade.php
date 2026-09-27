@extends('Dashboard._master')
@section('nav_customers')
active
@endsection
@section('page_title', 'Customers')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-lines-fill"></i> Customers</span>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Orders</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                            <td><span class='id-chip'>{{ $customer->id }}</span></td>
                            <td><strong>{{ $customer->name }}</strong></td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '—' }}</td>
                            <td><span class='qty-tag'>{{ $customer->orders_count }}</span></td>
                            <td><div class='action-wrap'>
                                <a class='btn-ghost sm' href='{{ route("customer_view", $customer->id) }}'><i class='bi bi-eye'></i></a>
                                <form action='{{ route("customer_toggle_status", $customer->id) }}' method='post'>
                                    @csrf
                                    <button class='btn-ghost sm {{ $customer->is_active ? "danger" : "" }}' type='submit' title='{{ $customer->is_active ? "Deactivate" : "Activate" }}'>
                                        <i class='bi {{ $customer->is_active ? "bi-slash-circle" : "bi-check-circle" }}'></i>
                                    </button>
                                </form>
                            </div></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:var(--muted); padding:20px;">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">Customers self-register on the storefront — admins can only view or remove accounts here.</div>
    </div>
@endsection
