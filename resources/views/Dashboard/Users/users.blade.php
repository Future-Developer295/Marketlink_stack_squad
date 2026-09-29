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
                <form class="search-box" method="GET" action="{{ route('users') }}" data-ajax-filter="#users-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('user_add') }}"><i class="bi bi-plus-lg"></i> Add User</a>
            </div>
        </div>

        <div id="users-list" data-ajax-list>
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
                            <td><span class='id-chip'>{{ $users->firstItem() + $loop->index }}</span></td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '—' }}</td>
                            <td><span class='badge-cat'>{{ $user->role }}</span></td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @elseif ($user->role === 'farmer' && $user->approval_status === 'rejected')
                                    <span class="badge bg-danger" title="{{ $user->rejection_reason }}">Rejected</span>
                                @elseif ($user->role === 'farmer')
                                    <span class="badge bg-warning text-dark">Pending approval</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("user_edit", $user->id) }}'><i class='bi bi-pencil'></i></a>@if ($user->role === 'farmer' && ! $user->is_active)@can('edit users')<form action="{{ route('user_approve', $user->id) }}" method="POST" style="display:inline" data-ajax data-ajax-refresh="#users-list" data-confirm="Approve this farmer? They will be emailed."><input type="hidden" name="_token" value="{{ csrf_token() }}"><button class='btn-ghost sm' type='submit' title='Approve farmer'><i class='bi bi-patch-check'></i></button></form>@endcan @endif@if ($user->role === 'farmer' && ! $user->is_active && $user->approval_status !== 'rejected')@can('edit users')<details style="display:inline-block; position:relative;"><summary class="btn-ghost sm danger" title="Reject farmer"><i class="bi bi-x-lg"></i></summary><div style="position:absolute; right:0; top:42px; z-index:50; width:260px; background:#fff; border:1px solid #ddd; border-radius:10px; padding:12px; box-shadow:0 8px 24px rgba(0,0,0,0.12);"><form action="{{ route('user_reject', $user->id) }}" method="POST" data-ajax data-ajax-refresh="#users-list" data-confirm="Reject this farmer? They will be emailed your comment."><input type="hidden" name="_token" value="{{ csrf_token() }}"><textarea name="rejection_reason" rows="3" class="form-control" placeholder="Why is this farmer rejected?" required></textarea><button type="submit" class="btn btn-danger btn-sm mt-2" style="width:100%;"><i class="bi bi-x-circle"></i> Reject</button></form></div></details>@endcan @endif<form action="{{ route('user_delete', $user->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this user?"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:var(--muted); padding:20px">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@include('Dashboard._pager', ['paginator' => $users, 'label' => 'users'])
</div>
    </div>
@endsection
