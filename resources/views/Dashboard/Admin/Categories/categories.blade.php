@extends('Dashboard.Admin._master')
@section('nav_categories')
active
@endsection
@section('page_title', 'Categories')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-tags-fill"></i> Categories</span>
            <div class="panel-tools">
                <form class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('admin.categories.create') }}"><i class="bi bi-plus-lg"></i> Add Category</a>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        <tr>
                            <td><span class='id-chip'>1</span></td>
                            <td><i class='bi bi-basket2-fill'></i></td>
                            <td><strong>Vegetables</strong></td>
                            <td>vegetables</td>
                            <td><span class='qty-tag'>0</span></td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("admin.categories.edit", 1) }}'><i class='bi bi-pencil'></i></a><form action='{{ route("admin.categories.destroy", 1) }}' method='post'><button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
