@extends('Website._master')
@section('body')
<div class="customer-shell">
@include('Website.Dashboard._sidebar')
<div class="customer-content">
<header class="customer-topbar"><div><span class="customer-eyebrow">MY MARKETLINK</span><span class="customer-page-label">@yield('page_title')</span></div><div class="customer-top-actions"><a href="{{ route('customer_dashboard') }}" class="customer-text-link">Overview ↗</a><a href="{{ url('/cart') }}" class="customer-button customer-button-small"><i class="fa-solid fa-basket-shopping"></i> Basket <span>{{ collect(session('cart', []))->sum() }}</span></a></div></header>
@include('Website.Partials.alerts')
<section class="account-banner"><div><span class="customer-eyebrow">YOUR LITTLE LOCAL WORLD</span><h1>@yield('banner_title')</h1><p>@yield('banner_text')</p></div><span class="account-banner-art" aria-hidden="true"><i class="fa-solid @yield('banner_icon', 'fa-seedling')"></i><b>✳</b></span></section>
@yield('account_content')
<footer class="customer-bottom-note"><span><i class="fa-solid fa-leaf"></i> A little closer to the source.</span><a href="{{ url('/products') }}">Explore the harvest ↗</a></footer>
</div></div>
<dialog class="customer-confirm-dialog" id="cancel-order-dialog"><form method="dialog"><h2>Plans changed?</h2><p>Cancel this pre-order? Your grower will see its updated status.</p><div><button value="keep" class="customer-button customer-button-light">Keep my pre-order</button><button value="cancel" class="customer-button">Cancel pre-order</button></div></form></dialog>
@endsection
