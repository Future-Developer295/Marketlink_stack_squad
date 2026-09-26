
@extends('Dashboard._master')

@section('nav_categories_add')
    active
@endsection

@section('page_title', 'Add Category')

@section('body')

    <div class="panel" style="max-width:1440px">

        <div class="panel-header">
            <span class="panel-title">
                <i class="bi bi-tags-fill"></i> Add Category
            </span>

            <a class="btn-ghost sm" href="{{ route('categories') }}">
                <i class="bi bi-x"></i> Cancel
            </a>
        </div>

        <div style="padding:22px">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('category_store') }}" method="POST">
                @csrf

                <div class="grid-2">

                    <div class="field">
                        <label class="field-label">
                            Category Name *
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
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
                            value="{{ old('slug') }}"
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
                        value="{{ old('icon') }}"
                        class="field-input"
                        placeholder="bi bi-basket2-fill"
                    >
                </div>

                <div class="spacer-16"></div>

                <div style="display:flex; gap:10px">

                    <button class="btn-primary" type="submit">
                        <i class="bi bi-check2"></i>
                        Save Category
                    </button>

                    <a class="btn-ghost" href="{{ route('categories') }}">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection
