@extends('Dashboard._master')
@section('nav_announcements_list')
active
@endsection
@section('page_title', 'Announcements')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-megaphone-fill"></i> Announcements</span>
            <div class="panel-tools">
                <form class="search-box" method="GET" action="{{ route('announcements') }}" data-ajax-filter="#announcements-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('announcement_add') }}"><i class="bi bi-plus-lg"></i> Add Announcement</a>
            </div>
        </div>

        <div id="announcements-list" data-ajax-list>
<div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Published At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                    <tr>
                            <td><span class='id-chip'>{{ $announcements->firstItem() + $loop->index }}</span></td>
                            <td><strong>{{ $announcement->title }}</strong></td>
                            <td>{{ \Illuminate\Support\Str::limit($announcement->message, 60) }}</td>
                            <td>{{ $announcement->published_at?->format('Y-m-d') ?? '—' }}</td>
                            <td>
                                @if ($announcement->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td><div class='action-wrap'><a class='btn-ghost sm' href='{{ route("announcement_edit", $announcement->id) }}'><i class='bi bi-pencil'></i></a><form action="{{ route('announcement_delete', $announcement->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this announcement?">@csrf<button class='btn-ghost sm danger' type='submit'><i class='bi bi-trash'></i></button></form></div></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:var(--muted); padding:20px">No announcements yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@include('Dashboard._pager', ['paginator' => $announcements, 'label' => 'announcements'])
</div>
    </div>
@endsection
