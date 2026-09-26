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

            <a href="{{ route('role_add') }}" class="btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Role
            </a>

        </div>

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

                            <td><span class="id-chip">{{ $loop->iteration }}</span></td>

                            <td><strong>{{ $role->name }}</strong></td>

                            <td>
                                <span class="badge-cat">
                                    {{ $role->permissions->count() }} permission{{ $role->permissions->count() == 1 ? '' : 's' }}
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

    </div>

@endsection