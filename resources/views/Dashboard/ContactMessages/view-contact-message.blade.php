@extends('Dashboard._master')
@section('nav_contact_messages')
active
@endsection
@section('page_title', 'Contact Message')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-envelope-open-fill"></i> {{ $message->subject }}</span>
            <div class="panel-tools">
                <a class="btn-ghost" href="{{ route('contact_messages') }}"><i class="bi bi-arrow-left"></i> Back</a>
                <a class="btn-primary" href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.$message->subject) }}"><i class="bi bi-reply-fill"></i> Reply by email</a>
            </div>
        </div>

        <div style="padding:22px;">
            <p style="margin:0 0 6px;"><strong>{{ $message->full_name }}</strong>
                &lt;<a href="mailto:{{ $message->email }}">{{ $message->email }}</a>&gt;</p>
            <p style="margin:0 0 18px; color:var(--muted); font-size:13px;">
                Received {{ $message->created_at?->format('D, d M Y H:i') }}
                @if ($message->user_id) · registered user #{{ $message->user_id }} @endif
            </p>
            <div style="white-space:pre-wrap; line-height:1.7;">{{ $message->message }}</div>
        </div>
    </div>
@endsection
