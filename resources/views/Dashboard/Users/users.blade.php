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
                <form class="search-box" method="GET" action="{{ route('users') }}">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                    @if (request('q'))
                        <a href="{{ route('users') }}" class="search-clear" title="Clear search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
                <a class="btn-primary" href="{{ route('user_add') }}"><i class="bi bi-plus-lg"></i> Add User</a>
            </div>
        </div>

        <div class="tbl-wrap users-tbl-wrap">
            <table class="dtable users-table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th class="col-hide-sm">Email</th>
                        <th class="col-hide-md">Phone</th>
                        <th class="col-hide-md">Role</th>
                        <th class="col-hide-sm">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                            <td><span class='id-chip'>{{ $loop->iteration }}</span></td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td class="col-hide-sm">{{ $user->email }}</td>
                            <td class="col-hide-md">{{ $user->phone ?? '—' }}</td>
                            <td class="col-hide-md"><span class='badge-cat'>{{ $user->role }}</span></td>
                            <td class="col-hide-sm">
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
