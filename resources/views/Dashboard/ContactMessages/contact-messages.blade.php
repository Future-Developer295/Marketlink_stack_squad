@extends('Dashboard._master')
@section('nav_contact_messages')
active
@endsection
@section('page_title', 'Contact Messages')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-envelope-fill"></i> Contact Messages</span>
            <div class="panel-tools">
                <form class="search-box" method="GET" action="{{ route('contact_messages') }}" data-ajax-filter="#contact-messages-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}" />
                    @endif
                </form>
            </div>
        </div>

        <div id="contact-messages-list" data-ajax-list>
            <div data-ajax-nav style="padding:0 22px 12px; display:flex; gap:8px;">
                <a class="btn-ghost sm {{ request('status') === 'unread' ? '' : 'active' }}" href="{{ route('contact_messages', array_filter(['q' => request('q')])) }}">All</a>
                <a class="btn-ghost sm {{ request('status') === 'unread' ? 'active' : '' }}" href="{{ route('contact_messages', array_filter(['q' => request('q'), 'status' => 'unread'])) }}">Unread</a>
            </div>

            <div class="tbl-wrap">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>From</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Received</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $message)
                            <tr>
                                <td><span class="id-chip">{{ $messages->firstItem() + $loop->index }}</span></td>
                                <td>
                                    <strong>{{ $message->full_name }}</strong><br>
                                    <a href="mailto:{{ $message->email }}" style="font-size:12px;">{{ $message->email }}</a>
                                </td>
                                <td>{{ $message->subject }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($message->message, 70) }}</td>
                                <td>{{ $message->created_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if ($message->read_at)
                                        <span class="badge bg-secondary">Read</span>
                                    @else
                                        <span class="badge bg-success">New</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-wrap">
                                        <a class="btn-ghost sm" href="{{ route('contact_message_show', $message->id) }}" title="Open"><i class="bi bi-eye"></i></a>
                                        @can('delete contact messages')
                                            <form action="{{ route('contact_message_delete', $message->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this message?">@csrf<button class="btn-ghost sm danger" type="submit"><i class="bi bi-trash"></i></button></form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; color:var(--muted); padding:20px">No messages yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('Dashboard._pager', ['paginator' => $messages, 'label' => 'messages'])
        </div>
    </div>
@endsection
