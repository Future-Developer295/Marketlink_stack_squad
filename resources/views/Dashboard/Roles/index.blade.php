@extends('Dashboard._master')

@section('nav_roles_list')
    active
@endsection

@section('page_title', 'Roles')

@section('body')

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-person-badge-fill"></i>
                Roles
            </span>

            <div class="panel-tools">
            <form class="search-box" method="GET" action="{{ route('roles') }}" data-ajax-filter="#roles-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                </form>
            <a href="{{ route('role_add') }}" class="btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Role
            </a>
            </div>

        </div>

        <div id="roles-list" data-ajax-list>
<div class="table-responsive">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($roles as $role)
                        <tr>

                            <td><span class="id-chip">{{ $roles->firstItem() + $loop->index }}</span></td>

                            <td><strong>{{ $role->name }}</strong></td>

                            <td>
                                <span class="badge-cat">
                                    {{ $role->permissions_count }} permission{{ $role->permissions_count == 1 ? '' : 's' }}
                                </span>
                            </td>

                            <td>
                                <div class="action-wrap">

                                    <a href="{{ route('role_view', $role->id) }}" class="btn-ghost sm">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('role_edit', $role->id) }}" class="btn-ghost sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @can('delete roles')
                                        <form action="{{ route('role_delete', $role->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this role?">
                                            @csrf
                                            <button class="btn-ghost sm danger" type="submit" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endcan

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="bi bi-person-badge"></i>
                                    <p>No roles found.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
@include('Dashboard._pager', ['paginator' => $roles, 'label' => 'roles'])
</div>

    </div>

@endsection