@extends('Dashboard._master')

@section('nav_permissions_list')
    active
@endsection

@section('page_title', 'Permissions')

@section('body')

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-key-fill"></i>
                Permissions
            </span>

            <div class="panel-tools">
            <form class="search-box" method="GET" action="{{ route('permissions') }}" data-ajax-filter="#permissions-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                </form>
            <a href="{{ route('permission_add') }}" class="btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Permission
            </a>
            </div>

        </div>

        <div id="permissions-list" data-ajax-list>
<div class="table-responsive">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Permission Name</th>
                        <th>Guard</th>
                        <th>Roles</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($permissions as $permission)
                        <tr>

                            <td><span class="id-chip">{{ $permissions->firstItem() + $loop->index }}</span></td>

                            <td><strong>{{ $permission->name }}</strong></td>

                            <td>
                                <span class="qty-tag">{{ $permission->guard_name }}</span>
                            </td>

                            <td>
                                <span class="badge-cat">
                                    {{ $permission->roles_count }} role{{ $permission->roles_count == 1 ? '' : 's' }}
                                </span>
                            </td>

                            <td>
                                <div class="action-wrap">

                                    <a href="{{ route('permission_view', $permission->id) }}" class="btn-ghost sm">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('permission_edit', $permission->id) }}" class="btn-ghost sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @can('delete permissions')
                                        <form action="{{ route('permission_delete', $permission->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this permission?">
                                            @csrf
                                            <button class="btn-ghost sm danger" type="submit" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endcan

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-key"></i>
                                    <p>No permissions found.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
@include('Dashboard._pager', ['paginator' => $permissions, 'label' => 'permissions'])
</div>

    </div>

@endsection