@extends('Dashboard._master')

@section('nav_categories_list')
    active
@endsection

@section('page_title', 'Categories')

@section('body')

    <div class="panel">

        <div class="panel-header">

            <span class="panel-title">
                <i class="bi bi-tags-fill"></i> Categories
            </span>

            <div class="panel-tools">

                <form class="search-box" method="GET" action="{{ route('categories') }}" data-ajax-filter="#categories-list">
                    <i class="bi bi-search"></i>

                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..." />

                </form>

                <a class="btn-primary" href="{{ route('category_add') }}">
                    <i class="bi bi-plus-lg"></i> Add Category
                </a>

            </div>

        </div>

        <div id="categories-list" data-ajax-list>
<div class="tbl-wrap">

            <table class="dtable">

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($categories as $category)

                        <tr>

                            <td>
                                <span class="id-chip">
                                    {{ $categories->firstItem() + $loop->index }}
                                </span>
                            </td>

                            <td>
                                @if($category->icon)
                                    <i class="{{ $category->icon }}"></i>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <strong>
                                    {{ $category->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $category->slug }}
                            </td>

                            <td>
                                <div class="action-wrap">

                                    <a class="btn-ghost sm" href="{{ route('category_edit', $category->id) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('category_delete', $category->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this category?">
                                        @csrf

                                        <button class="btn-ghost sm danger" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px;">
                                No categories found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
@include('Dashboard._pager', ['paginator' => $categories, 'label' => 'categories'])
</div>

    </div>

@endsection