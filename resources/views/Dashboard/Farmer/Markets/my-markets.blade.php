@extends('Dashboard.Farmer._master')
@section('nav_markets')
active
@endsection
@section('page_title', 'My Markets')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-shop-window"></i> My Markets</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('farmer.markets.create') }}"><i class="bi bi-plus-lg"></i> Join a Market</a>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Market</th>
                        <th>City</th>
                        <th>Timing</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy Market</strong></td>
                            <td>Karachi</td>
                            <td>9:00 AM – 6:00 PM</td>
                            <td><span class="badge-status bs-in"><i class="bi bi-circle-fill"></i> Active</span></td>
                            <td><div class='action-wrap'><form action="" method="post"><button type='submit' class='btn-ghost sm danger'><i class='bi bi-box-arrow-left'></i> Leave</button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
