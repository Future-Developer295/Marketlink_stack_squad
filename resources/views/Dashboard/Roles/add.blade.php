@extends('Dashboard._master')

@section('nav_roles')
    active
@endsection

@section('page_title', 'Create Role')

@section('body')

<div class="role-create-page">

    <div class="role-page-header">
        <div>
            <h1>Create Role</h1>
            <p>Select the modules and actions this role is allowed to perform</p>
        </div>

        <a href="{{ route('roles') }}" class="role-back-btn">
            Back to Roles
        </a>
    </div>

    <form action="{{ route('role_store') }}" method="POST">
        @csrf

        <div class="role-create-layout">

            <div class="permissions-panel">

                <div class="role-panel-title">
                    <h2>Roles</h2>
                    <p>Select the modules and actions this role is allowed to perform</p>
                </div>

                @foreach($permissionGroups as $module => $permissions)

                    <div class="permission-module">

                        <div class="permission-module-header">

                            <div>
                                <h3>{{ $module }}</h3>

                                <span>
                                    {{ count($permissions) }}
                                    {{ count($permissions) == 1 ? 'permission' : 'permissions' }}
                                </span>
                            </div>

                            <label class="select-all-box">
                                <input type="checkbox" class="module-select-all">
                                <span>Select All</span>
                            </label>

                        </div>

                        <div class="permission-list">

                            @foreach($permissions as $permission)

                                <label class="permission-item">

                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        class="permission-checkbox"
                                    >

                                    <span>{{ $permission->name }}</span>

                                </label>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>


            <div class="role-side-panel">

                <div class="role-details-card">

                    <h2>Role Details</h2>

                    <p>Basic information about this role</p>

                    <div class="role-field">

                        <label>Role Name *</label>

                        <input
                            type="text"
                            name="name"
                            class="role-input"
                            placeholder="e.g. Content Reviewer"
                            required
                        >

                    </div>

                </div>


                <div class="role-info-card">

                    <p>
                        Permissions are grouped by module. Use
                        "Select All" to quickly grant every action
                        within a module.
                    </p>

                </div>

            </div>

        </div>


        <div class="role-form-actions">

            <a href="{{ route('roles') }}" class="role-cancel">
                Cancel
            </a>

            <button type="submit" class="role-save-btn">
                Save Role
            </button>

        </div>

    </form>

</div>

@endsection
<script>
    document.querySelectorAll('.permission-module').forEach(function(module) {

        const selectAll = module.querySelector('.module-select-all');
        const permissions = module.querySelectorAll('.permission-checkbox');

        selectAll.addEventListener('change', function() {

            permissions.forEach(function(permission) {
                permission.checked = selectAll.checked;
            });

        });

        permissions.forEach(function(permission) {

            permission.addEventListener('change', function() {

                const checked = module.querySelectorAll(
                    '.permission-checkbox:checked'
                ).length;

                selectAll.checked = checked === permissions.length;

            });

        });

    });
</script>