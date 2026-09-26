@extends('Dashboard._master')

@section('nav_roles')
    active
@endsection

@section('page_title', 'View Role')

@section('body')

<div class="role-create-page">

    <div class="role-page-header">

        <div>
            <h1>{{ $role->name }}</h1>
            <p>Role details and assigned permissions</p>
        </div>

        <a href="{{ route('roles') }}" class="role-back-btn">
            Back to Roles
        </a>

    </div>

    <div class="role-view-card">

        <div class="role-view-header">

            <div>
                <span class="role-view-label">Role Name</span>
                <h2>{{ $role->name }}</h2>
            </div>

            <a
                href="{{ route('role_edit', $role->id) }}"
                class="role-save-btn"
            >
                Edit Role
            </a>

        </div>

        <div class="role-view-body">

            <h3>Assigned Permissions</h3>

            @if($role->permissions->count())

                <div class="role-permission-grid">

                    @foreach($role->permissions as $permission)

                        <div class="role-permission-box">
                            {{ $permission->name }}
                        </div>

                    @endforeach

                </div>

            @else

                <p class="role-empty">
                    No permissions assigned to this role.
                </p>

            @endif

        </div>

    </div>

</div>

@endsection