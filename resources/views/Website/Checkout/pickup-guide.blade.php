@extends('Website._master')
@section('page_title', 'Your pickup guide')
@section('body')
<div class="ml-container shop-checkout">
@include('Website.Partials.shop-banner', ['kind'=>'checkout'])
<section class="basket-grower my-4"><span class="shop-kicker">A FRESHER WAY TO SHOP</span><h1>Your market-day guide.</h1><div class="row g-4 mt-2">
@foreach([
['01', 'Build your basket', 'Choose your produce and check the grower behind each item. Stock and prices are confirmed when you add to your basket.'],
['02', 'Choose your pickup', 'At checkout, select an available pickup window for each grower. Your basket may become separate pre-orders for different stalls.'],
['03', 'Check before you travel', 'Visit My pre-orders for the latest status, pickup date, time, stall and market directions. Wait for your grower to mark your order ready.'],
['04', 'Collect and pay in person', 'Bring your pre-order number to the stall during your pickup window. Payment is made directly to your grower at collection.']
] as [$number,$title,$description])<article class="col-md-6"><span class="shop-kicker">{{ $number }}</span><h2 class="h4 mt-2">{{ $title }}</h2><p>{{ $description }}</p></article>@endforeach
</div><hr><h2 class="h4">If your plans change</h2><p>You can cancel from your account before pickup starts, unless your grower has already marked the order ready. Once cancellation closes, contact your grower for help.</p><div class="d-flex gap-3 flex-wrap"><a class="shop-pill" href="{{ route('customer_orders') }}">My pre-orders ↗</a><a class="shop-outline" href="{{ url('/products') }}">Explore the harvest ↗</a></div></section>
</div>
@endsection
