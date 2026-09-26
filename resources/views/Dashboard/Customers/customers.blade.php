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

                    <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy Customer</strong></td>
                            <td>customer@marketlink.test</td>
                            <td>0300-0000000</td>
                            <td><span class='qty-tag'>0</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("customer_view", 1) }}'><i class='bi bi-eye'></i></a><form action='{{ route("customer_delete", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">Customers self-register on the storefront — admins can only view or remove accounts here.</div>
    </div>
@endsection
