
@extends('Dashboard._master')

@section('nav_categories_list')
    active
@endsection

@section('page_title', 'Edit Category')

@section('body')

    <div class="panel" style="max-width:1440px">

        <div class="panel-header">
            <span class="panel-title">
                <i class="bi bi-tags-fill"></i> Edit Category
            </span>

            <a class="btn-ghost sm" href="{{ route('categories') }}">
                <i class="bi bi-x"></i> Cancel
            </a>
        </div>

        <div style="padding:22px">

            <div style="color:var(--muted); font-size:13px; margin-bottom:12px">
                <i class="bi bi-info-circle"></i>
                Editing category #{{ $category->id }}.
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('category_update', $category->id) }}" method="POST">
                @csrf

                <div class="grid-2">

                    <div class="field">
                        <label class="field-label">
                            Category Name *
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $category->name) }}"
                            required
                            class="field-input"
                            placeholder="e.g. Vegetables"
                        >
                    </div>

                    <div class="field">
                        <label class="field-label">
                            Slug
                        </label>

                        <input
                            type="text"
                            name="slug"
                            value="{{ old('slug', $category->slug) }}"
                            class="field-input"
                            placeholder="vegetables (auto-generated if empty)"
                        >
                    </div>

                </div>

                <div class="field">
                    <label class="field-label">
                        Icon Class
                    </label>

                    <input
                        type="text"
                        name="icon"
                        value="{{ old('icon', $category->icon) }}"
                        class="field-input"
                        placeholder="bi bi-basket2-fill"
                    >
                </div>

                <div class="spacer-16"></div>

                <div style="display:flex; gap:10px">

                    <button class="btn-primary" type="submit">
                        <i class="bi bi-check2"></i>
                        Update Category
                    </button>

                    <a class="btn-ghost" href="{{ route('categories') }}">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection