@extends('Dashboard.Admin._master')
@section('nav_announcements')
active
@endsection
@section('page_title', 'Announcements')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-megaphone-fill"></i> Announcements</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('admin.announcements.create') }}"><i class="bi bi-plus-lg"></i> Add Announcement</a>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Published At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy Announcement</strong></td>
                            <td>Markets closed on public holiday…</td>
                            <td>2026-09-20</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("admin.announcements.edit", 1) }}'><i class='bi bi-pencil'></i></a><form action='{{ route("admin.announcements.destroy", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
