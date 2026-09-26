@extends('Dashboard._master')
@section('nav_announcements_list')
active
@endsection
@section('page_title', 'Edit Announcement')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-megaphone-fill"></i> Edit Announcement</span>
            <a class="btn-ghost sm" href="{{ route('announcements') }}"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <div style="color:var(--muted); font-size:13px; margin-bottom:12px"><i class="bi bi-info-circle"></i> Editing announcement #{{ $announcement->id }}.</div>
          <form action="{{ route('announcement_update', $announcement->id) }}" method="post">
            @csrf
            @if ($errors->any())
              <div style="background:#fee2e2; color:#991b1b; padding:10px 14px; border-radius:8px; margin-bottom:14px; font-size:13px">
                <ul style="margin:0; padding-left:18px">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <div class="field">
              <label class="field-label">Title *</label>
              <input type="text" name="title" required class="field-input" placeholder="e.g. Holiday Market Hours" value="{{ old('title', $announcement->title) }}" />
            </div>

            <div class="field">
              <label class="field-label">Message *</label>
              <textarea required name="message" class="field-input" placeholder="Write the announcement…">{{ old('message', $announcement->message) }}</textarea>
            </div>

          <div class="grid-2">
            <div class="field">
              <label class="field-label">Publish Date</label>
              <input type="date" name="published_at"  class="field-input" value="{{ old('published_at', $announcement->published_at?->format('Y-m-d')) }}" />
            </div>

            <div class="field">
              <label class="field-label">Status</label><br>
              <input type="checkbox" name="is_active" id="is_active" style="width:18px;height:18px;vertical-align:middle" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
              <label for="is_active" style="vertical-align:middle; margin-left:6px; color:var(--muted); font-size:13px">Visible to all users</label>
            </div>
          </div>

            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Update Announcement</button>
              <a class="btn-ghost" href="{{ route('announcements') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
