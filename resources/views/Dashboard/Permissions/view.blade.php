@extends('Dashboard._master')

@section('nav_permissions')
    active
@endsection

@section('page_title', 'View Permission')

@section('body')

    <div class="permission-create-page">

        <div class="permission-page-header">

            <div>
                <h1>{{ $permission->name }}</h1>
                <p>Permission details and assigned roles</p>
            </div>

            <a href="{{ route('permissions') }}" class="permission-back-btn">
                Back to Permissions
            </a>

        </div>

        <div class="permission-create-card">

            <div class="permission-card-content">

                <h2>Permission Details</h2>

                <div class="permission-view-detail">

                    <span>Permission Name</span>

                    <strong>{{ $permission->name }}</strong>

                </div>

                <div class="permission-view-detail">

                    <span>Guard Name</span>

                    <strong>{{ $permission->guard_name }}</strong>

                </div>

                <div class="permission-view-detail">

                    <span>Assigned Roles</span>

                    @if ($permission->roles->count())

                        <div class="permission-role-list">

                            @foreach ($permission->roles as $role)
                                <span class="permission-role-badge">
                                    {{ $role->name }}
                                </span>
                            @endforeach

                        </div>
                    @else
                        <strong>No roles assigned</strong>

                    @endif

                </div>

            </div>

            <div class="permission-actions">

                <a href="{{ route('permissions') }}">
                    Back
                </a>

                <a href="{{ route('permission_edit', $permission->id) }}" class="permission-edit-btn">
                    Edit Permission
                </a>

            </div>

        </div>

    </div>

@endsection
