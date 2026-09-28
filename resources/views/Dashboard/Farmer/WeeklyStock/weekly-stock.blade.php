@extends('Dashboard.Farmer._master')
@section('nav_stock')
active
@endsection
@section('page_title', 'Weekly Stock')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-calendar2-week-fill"></i> Weekly Stock</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('farmer.stock.create') }}"><i class="bi bi-plus-lg"></i> Add Schedule</a>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Product</th>
                        <th>Day</th>
                        <th>Quantity</th>
                        <th>Valid Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy Product</strong></td>
                            <td><span class='badge-cat'>Mon</span></td>
                            <td><span class='qty-tag'>0 kg</span></td>
                            <td>01 Jan – 31 Dec</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("farmer.stock.edit", 1) }}'><i class='bi bi-pencil'></i></a><form action='{{ route("farmer.stock.destroy", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
