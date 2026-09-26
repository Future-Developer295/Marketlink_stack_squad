@extends('Dashboard._master')

@section('nav_permissions')
    active
@endsection

@section('page_title', 'Edit Permission')

@section('body')

<div class="permission-create-page">

    <div class="permission-page-header">

        <div>
            <h1>Edit Permission</h1>
            <p>Update permission information</p>
        </div>

        <a href="{{ route('permissions') }}" class="permission-back-btn">
            Back to Permissions
        </a>

    </div>

    <form action="{{ route('permission_update', $permission->id) }}" method="POST">

        @csrf

        <div class="permission-create-card">

            <div class="permission-card-content">

                <h2>Permission Details</h2>

                <p>
                    Update the permission name
                </p>

                <div class="permission-field">

                    <label>Permission Name</label>

                    <input
                        type="text"
                        name="name"
                        class="permission-input"
                        value="{{ $permission->name }}"
                        required
                    >

                </div>

            </div>

            <div class="permission-actions">

                <a href="{{ route('permissions') }}">
                    Cancel
                </a>

                <button type="submit">
                    Update Permission
                </button>

            </div>

        </div>

    </form>

</div>

@endsection