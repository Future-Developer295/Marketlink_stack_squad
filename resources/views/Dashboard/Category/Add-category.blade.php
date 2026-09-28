@extends('Dashboard._master')
@section('categories')
open
@endsection
@section('category_add')
active
@endsection

@section('body')
      <div class="panel" style="max-width:1440px">
        <div class="panel-header">
          <span class="panel-title"><i class="bi bi-bookmarks-fill"></i> Add Category</span>
          <a class="btn-ghost sm" href="categories.html"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <form action="{{route('category_store')}}" method="post">
            <div class="field">
              <label class="field-label">Category Name</label>
              <input type="text" name="category_name" required class="field-input" placeholder="e.g. Electronics"/>
            </div>
            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Save Category</button>
              <a class="btn-ghost" href="{{route('categories')}}">Cancel</a>
            </div>
          </form>
        </div>
      </div>
@endsection
 