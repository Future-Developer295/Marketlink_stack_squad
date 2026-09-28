@extends('Dashboard._master')
@section('categories')
    open
@endsection
@section('categories_fetch')
    active
@endsection
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-bookmarks-fill"></i> Categories</span>
            <div class="panel-tools">
                <a class="btn-primary " href="{{ route('category_add') }}"><i class="bi bi-plus-lg"></i> Add Category</a>
            </div>
        </div>
        <div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th width="60">#ID</th>
                        <th>Category Name</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $id = 0;
                    @endphp
                    {{-- @foreach ($categories as $category) --}}
                        <tr>

                            <td><span class='id-chip'>1</span></td>
                            <td><strong>Dummy</strong></td>
                            <td><span class='qty-tag'>1</span></td>
                            <td>
                                <div class='action-wrap'>
                                   {{-- <form action="{{route('category_edit',['id'=>$category->id])}}" method="GET"> --}}
                                     <button type="submit" class='btn-ghost sm'><i
                                            class='bi bi-pencil'></i></button>
                                   </form>
                                   {{-- <form action="{{route('category_delete',['id'=>$category->id])}}" method="Post"> --}}
                                     <button type="submit" class='btn-ghost sm'><i
                                            class='bi bi-trash'></i></button>
                                   </form>
                                </div>
                            </td>
                        </tr>
                    {{-- @endforeach --}}






                </tbody>
            </table>
        </div>


    </div>
@endsection
