@extends('Dashboard.Farmer._master')
@section('nav_reviews')
active
@endsection
@section('page_title', 'Reviews')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-star-fill"></i> Reviews</span>
            <div class="panel-tools">
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy Customer</strong></td>
                            <td>Dummy Product</td>
                            <td><span class="badge-status bs-in"><i class="bi bi-star-fill"></i> 5 / 5</span></td>
                            <td>Great quality, very fresh!</td>
                            <td>2026-09-20</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">Reviews are read-only here — farmers can view feedback but cannot edit, reply, or delete reviews.</div>
    </div>
@endsection
