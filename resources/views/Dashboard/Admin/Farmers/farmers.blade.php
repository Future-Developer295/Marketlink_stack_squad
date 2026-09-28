@extends('Dashboard.Admin._master')
@section('nav_farmers')
active
@endsection
@section('page_title', 'Farmers')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-workspace"></i> Farmers</span>
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
                        <th>Stall</th>
                        <th>Owner</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy Stall</strong></td>
                            <td>Dummy Farmer</td>
                            <td>Karachi</td>
                            <td><span class="badge-status bs-in"><i class="bi bi-circle-fill"></i> Pending</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("admin.farmers.edit", 1) }}'><i class='bi bi-pencil'></i></a><form action='{{ route("admin.farmers.destroy", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="hr-thin"></div>
        <div style="padding:14px 22px; color:var(--muted); font-size:13px">Admins review, approve/reject and edit farmer profiles. Farmers register themselves, so no Add here.</div>
    </div>
@endsection
