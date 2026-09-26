@extends('Dashboard._master')

@section('nav_permissions')
    active
@endsection

@section('page_title', 'Create Permission')

@section('body')

<div class="permission-create-page">

    <div class="permission-page-header">

        <div>
            <h1>Create Permission</h1>
            <p>Define a new permission that can be assigned to roles</p>
        </div>

        <a href="{{ route('permissions') }}" class="permission-back-btn">
            Back to Permissions
        </a>

    </div>


    <form action="{{ route('permission_store') }}" method="POST">

        @csrf

        <div class="permission-create-card">

            <div class="permission-card-content">

                <h2>Permission Details</h2>

                <p>
                    This information determines how the permission appears
                    when assigning it to roles
                </p>

                <div class="permission-field">

                    <label>Permission Name</label>

                    <input
                        type="text"
                        name="name"
                        class="permission-input"
                        placeholder="e.g. view students"
                        required
                    >

                </div>

            </div>

            <div class="permission-actions">

                <a href="{{ route('permissions') }}">
                    Cancel
                </a>

                <button type="submit">
                    Save Permission
                </button>

            </div>

        </div>

    </form>

</div>

@endsection