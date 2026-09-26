@extends('Website._master')

@section('page_title', 'Notifications')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ route('customer_dashboard') }}">Dashboard</a> / <span class="active">Notifications</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        @include('Website.Partials.alerts')

        <div class="row g-4">
            <div class="col-lg-3">
                @include('Website.Dashboard._sidebar')
            </div>

            <div class="col-lg-9">
                @if($notifications->isEmpty())
                @include('Website.Partials.empty-state', [
                    'icon' => 'fa-bell',
                    'title' => 'No Notifications',
                    'message' => 'Updates about your orders and account will appear here.',
                ])
                @else
                <div class="ml-card">
                    @foreach($notifications as $notification)
                    <div class="d-flex justify-content-between align-items-start gap-3 py-3" style="border-top:1px solid var(--ml-border);">
                        <div class="d-flex gap-3">
                            <i class="fa-solid {{ $notification->is_read ? 'fa-bell text-muted' : 'fa-bell text-success' }} mt-1"></i>
                            <div>
                                <strong>{{ $notification->title }}</strong>
                                <p class="small text-muted mb-1">{{ $notification->message }}</p>
                                <span class="small text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @unless($notification->is_read)
                        <form method="POST" action="{{ route('customer_notification_read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="ml-btn-link small">Mark as Read</button>
                        </form>
                        @endunless
                    </div>
                    @endforeach
                </div>
                @include('Website.Partials.pagination', ['paginator' => $notifications])
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
