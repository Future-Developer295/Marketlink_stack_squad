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

    <div class="d-flex align-items-center gap-2">

        <input type="text"
               id="permissionSearch"
               class="form-control"
               placeholder="Search permission..."
               style="width: 220px;">

        <a href="{{ route('permission_add') }}" class="btn-primary">
            <i class="bi bi-plus-lg"></i>
            Add Permission
        </a>

    </div>

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

                <tr class="permission-row">

                    <td>
                        <span class="id-chip">{{ $loop->iteration }}</span>
                    </td>

                    <td>
                        <strong class="permission-name">
                            {{ $permission->name }}
                        </strong>
                    </td>

                    <td>
                        <span class="qty-tag">
                            {{ $permission->guard_name }}
                        </span>
                    </td>

                    <td>
                        <span class="badge-cat">
                            {{ $permission->roles->count() }}
                            role{{ $permission->roles->count() == 1 ? '' : 's' }}
                        </span>
                    </td>

                    <td>
                        <div class="action-wrap">

                            <a href="{{ route('permission_view', $permission->id) }}"
                               class="btn-ghost sm">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('permission_edit', $permission->id) }}"
                               class="btn-ghost sm">
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

            <tr id="noFilterResult" style="display: none;">
                <td colspan="5">
                    <div class="empty-state">
                        <i class="bi bi-search"></i>
                        <p>No matching permissions found.</p>
                    </div>
                </td>
            </tr>

        </tbody>

    </table>

</div>


</div>

<script>

    const permissionSearch = document.getElementById('permissionSearch');

    permissionSearch.addEventListener('input', function () {

        const searchValue = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.permission-row');

        let visibleRows = 0;

        rows.forEach(row => {

            const permissionName = row
                .querySelector('.permission-name')
                .textContent
                .toLowerCase();

            if (permissionName.includes(searchValue)) {

                row.style.display = '';
                visibleRows++;

            } else {

                row.style.display = 'none';

            }

        });

        document.getElementById('noFilterResult').style.display =
            visibleRows === 0 && rows.length > 0 ? '' : 'none';

    });

</script>

@endsection
