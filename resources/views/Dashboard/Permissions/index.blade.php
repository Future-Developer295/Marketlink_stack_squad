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

            <a href="{{ route('permission_add') }}" class="btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Permission
            </a>

        </div>

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

                            <td><span class="id-chip">{{ $loop->iteration }}</span></td>

                            <td><strong>{{ $permission->name }}</strong></td>

                            <td>
                                <span class="qty-tag">{{ $permission->guard_name }}</span>
                            </td>

                            <td>
                                <span class="badge-cat">
                                    {{ $permission->roles->count() }} role{{ $permission->roles->count() == 1 ? '' : 's' }}
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

    </div>

@endsection