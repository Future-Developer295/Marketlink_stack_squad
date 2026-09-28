@extends('Website.Dashboard._layout')
@section('page_title', 'Updates')
@section('banner_title', 'A little heads-up.')
@section('banner_text', 'Pickup news, fresh arrivals, and updates from your local world.')
@section('banner_icon', 'fa-bell')
@section('account_content')
<section class="customer-panel">@forelse($notifications as $notification)<article class="account-update {{ !$notification->is_read ? 'is-unread' : '' }}"><span class="discovery-icon"><i class="fa-regular fa-bell"></i></span><div><span class="customer-eyebrow">{{ $notification->is_read ? 'READ' : 'NEW UPDATE' }}</span><h2>{{ $notification->title }}</h2><p>{{ $notification->message }}</p><time class="customer-muted">{{ $notification->created_at->diffForHumans() }}</time>@if($notification->type === 'restock')<p><a class="customer-text-link" href="{{ route('customer_favorites') }}">Explore your favorites ↗</a></p>@endif</div>@unless($notification->is_read)<form method="POST" action="{{ route('customer_notification_read',$notification) }}">@csrf<button class="customer-button customer-button-light customer-button-small">Mark as read</button></form>@endunless</article>@empty<div class="customer-empty"><i class="fa-regular fa-bell"></i><h2>You are all caught up.</h2><p>Your latest updates will appear here.</p><a href="{{ route('customer_orders') }}" class="customer-button">Check your pre-orders ↗</a></div>@endforelse</section>@include('Website.Partials.pagination',['paginator'=>$notifications])
@endsection
