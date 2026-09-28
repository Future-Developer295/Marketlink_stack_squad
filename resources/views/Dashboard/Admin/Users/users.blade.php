@extends('Dashboard.Admin._master')
@section('nav_users')
active
@endsection
@section('page_title', 'Users')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-people-fill"></i> Users</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('admin.users.create') }}"><i class="bi bi-plus-lg"></i> Add User</a>
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
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy User</strong></td>
                            <td>dummy@marketlink.test</td>
                            <td>0300-0000000</td>
                            <td><span class='badge-cat'>customer</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("admin.users.edit", 1) }}'><i class='bi bi-pencil'></i></a><form action='{{ route("admin.users.destroy", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
