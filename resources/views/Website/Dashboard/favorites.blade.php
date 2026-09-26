@extends('Website._master')

@section('page_title', 'Favorites')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ url('/dashboard') }}">Dashboard</a> / <span class="active">Favorites</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        @include('Website.Partials.alerts')

        <div class="row g-4">
            <div class="col-lg-3">
                @include('Website.Dashboard._sidebar')
            </div>

            <div class="col-lg-9">
                @if($favorites->isEmpty())
                @include('Website.Partials.empty-state', [
                    'icon' => 'fa-heart',
                    'title' => 'No Favorites Yet',
                    'message' => 'Farmers and products you save will appear here.',
                    'actionUrl' => url('/products'),
                    'actionLabel' => 'Browse Products',
                ])
                @else
                <div class="ml-card">
                    <div class="table-responsive">
                        <table class="table ml-table align-middle">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Details</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($favorites as $favorite)
                                <tr>
                                    <td>{{ $favorite->product_id ? 'Product' : 'Farmer' }}</td>
                                    <td>
                                        @if($favorite->product)
                                            {{ $favorite->product->name }}
                                        @elseif($favorite->farmer)
                                            {{ $favorite->farmer->stall_name ?? $favorite->farmer->business_name }}
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        @if($favorite->product)
                                            Rs. {{ number_format($favorite->product->price, 0) }} / {{ $favorite->product->unit }}
                                        @elseif($favorite->farmer)
                                            {{ $favorite->farmer->city }}, {{ $favorite->farmer->state }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($favorite->product)
                                        <a href="{{ url('/products/'.$favorite->product_id) }}" class="ml-btn-link small">View</a>
                                        @elseif($favorite->farmer)
                                        <a href="{{ url('/farmers/'.$favorite->farmer_id) }}" class="ml-btn-link small">View</a>
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ url('/dashboard/favorites/'.$favorite->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-btn-link small text-danger"><i class="fa-solid fa-trash"></i> Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
