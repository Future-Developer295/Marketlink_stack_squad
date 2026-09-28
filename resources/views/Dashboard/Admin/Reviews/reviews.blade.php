@extends('Dashboard.Admin._master')
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
                        <th>Farmer</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Flagged</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td>Dummy Customer</td>
                            <td>Dummy Farmer</td>
                            <td><span class="badge-status bs-in"><i class="bi bi-star-fill"></i> 5 / 5</span></td>
                            <td>Great quality, very fresh!</td>
                            <td><span class="badge bg-danger">No</span></td>
                            <td><div class='action-wrap'><form action='{{ route("admin.reviews.flag", 1) }}' method='post'><button class='btn-ghost sm' type='submit'><i class='bi bi-flag'></i></button></form><form action='{{ route("admin.reviews.destroy", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">Admins moderate reviews by flagging inappropriate content or removing it — reviews are written by customers, not added here.</div>
    </div>
@endsection
