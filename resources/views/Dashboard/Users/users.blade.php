@extends('Dashboard._master')
@section('nav_users_list')
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
                <a class="btn-primary" href="{{ route('user_add') }}"><i class="bi bi-plus-lg"></i> Add User</a>
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
                    @forelse ($users as $user)
                    <tr>
                            <td><span class='id-chip'>{{ $loop->iteration }}</span></td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td><span class='badge-cat'>{{ $user->role }}</span></td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("user_edit", $user->id) }}'><i class='bi bi-pencil'></i></a><form action='{{ route("user_delete", $user->id) }}' method='post' onsubmit="return confirm('Delete this user?');"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:var(--muted); padding:20px">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
