@extends('Dashboard.Admin._master')
@section('nav_announcements')
active
@endsection
@section('page_title', 'Add Announcement')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-megaphone-fill"></i> Add Announcement</span>
            <a class="btn-ghost sm" href="{{ route('admin.announcements.index') }}"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <form action="{{ route('admin.announcements.store') }}" method="post">
            @csrf
            <div class="field">
              <label class="field-label">Title *</label>
              <input type="text" name="title" required class="field-input" placeholder="e.g. Holiday Market Hours" />
            </div>

            <div class="field">
              <label class="field-label">Message *</label>
              <textarea required name="message" class="field-input" placeholder="Write the announcement…"></textarea>
            </div>

          <div class="grid-2">
            <div class="field">
              <label class="field-label">Publish Date</label>
              <input type="date" name="published_at"  class="field-input" placeholder="" />
            </div>

            <div class="field">
              <label class="field-label">Status</label><br>
              <input type="checkbox" name="is_active" id="is_active" style="width:18px;height:18px;vertical-align:middle">
              <label for="is_active" style="vertical-align:middle; margin-left:6px; color:var(--muted); font-size:13px">Visible to all users</label>
            </div>
          </div>

            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Publish Announcement</button>
              <a class="btn-ghost" href="{{ route('admin.announcements.index') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
