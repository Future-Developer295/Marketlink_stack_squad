@extends('Dashboard.Admin._master')
@section('nav_categories')
active
@endsection
@section('page_title', 'Add Category')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-tags-fill"></i> Add Category</span>
            <a class="btn-ghost sm" href="{{ route('admin.categories.index') }}"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <form action="{{ route('admin.categories.store') }}" method="post">
            @csrf
          <div class="grid-2">
            <div class="field">
              <label class="field-label">Category Name *</label>
              <input type="text" name="name" required class="field-input" placeholder="e.g. Vegetables" />
            </div>

            <div class="field">
              <label class="field-label">Slug</label>
              <input type="text" name="slug"  class="field-input" placeholder="vegetables (auto-generated if empty)" />
            </div>
          </div>

            <div class="field">
              <label class="field-label">Icon Class</label>
              <input type="text" name="icon"  class="field-input" placeholder="bi bi-basket2-fill" />
            </div>

            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Save Category</button>
              <a class="btn-ghost" href="{{ route('admin.categories.index') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
