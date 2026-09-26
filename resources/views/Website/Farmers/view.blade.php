@extends('Website._master')

@section('page_title', $farmer->stall_name ?? $farmer->business_name)

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb">
        <a href="{{ url('/') }}">Home</a> /
        <a href="{{ url('/farmers') }}">Farmers</a> /
        <span class="active">{{ $farmer->stall_name ?? $farmer->business_name }}</span>
    </nav>
</div>

<section class="pb-4">
    <div class="ml-container">
        <div class="ml-hero">
            <div class="row align-items-center g-4">
                <div class="col-lg-auto">
                    @if($farmer->farmer_image)
                        <img
                            src="{{ asset('farmer_images/' . $farmer->farmer_image) }}"
                            alt="{{ $farmer->stall_name ?? $farmer->business_name }}"
                            style="width:150px;height:150px;border-radius:50%;object-fit:cover;border:4px solid #dff4e9;"
                        >
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="width:150px;height:150px;border-radius:50%;border:4px solid #dff4e9;background:#fff;">
                            <i class="fa-solid fa-tractor text-success" style="font-size:55px;"></i>
                        </div>
                    @endif
                </div>

                <div class="col">
                    <div class="d-flex align-items-center gap-3 flex-wrap mb-2">
                        <h1 class="mb-0">{{ $farmer->stall_name ?? $farmer->business_name }}</h1>
                        @if($ratingCount > 0)
                            <span class="ml-badge ml-badge-mint">
                                <i class="fa-solid fa-star"></i>
                                {{ number_format($ratingAverage, 1) }} ({{ $ratingCount }} Reviews)
                            </span>
                        @endif
                    </div>

                    <p class="text-muted fs-5 mb-4">
                        {{ $farmer->description ?: 'Fresh local farm products available directly from this farmer.' }}
                    </p>

                    <div class="row g-4">
                        <div class="col-6 col-md-3">
                            <span class="text-muted d-block">Farmer</span>
                            <strong>{{ $farmer->user->name ?? 'Farmer' }}</strong>
                        </div>

                        <div class="col-6 col-md-3">
                            <span class="text-muted d-block">Schedule</span>
                            <strong>{{ $farmer->operating_days ?: 'Not added' }}</strong>
                        </div>

                        <div class="col-6 col-md-3">
                            <span class="text-muted d-block">Pickup Window</span>
                            <strong>
                                @if($farmer->start_time && $farmer->end_time)
                                    {{ \Carbon\Carbon::parse($farmer->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($farmer->end_time)->format('h:i A') }}
                                @else
                                    Not added
                                @endif
                            </strong>
                        </div>

                        <div class="col-6 col-md-3">
                            <span class="text-muted d-block">Products</span>
                            <strong>{{ $farmer->products_count }}</strong>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <a href="{{ url('/products') }}?farmer={{ $farmer->id }}" class="btn ml-btn-primary">
                            <i class="fa-solid fa-basket-shopping me-2"></i>Browse Weekly Stock
                        </a>
                        <button type="button" class="btn ml-btn-secondary">
                            <i class="fa-regular fa-heart me-2"></i>Save Farmer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-6 col-lg-3">
                <div class="ml-card h-100">
                    <span class="text-muted d-block mb-2">Business Name</span>
                    <strong class="fs-5">{{ $farmer->business_name ?: $farmer->stall_name }}</strong>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="ml-card h-100">
                    <span class="text-muted d-block mb-2">Location</span>
                    <strong class="fs-5">
                        {{ $farmer->city ?: 'Not added' }}@if($farmer->state), {{ $farmer->state }}@endif
                    </strong>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="ml-card h-100">
                    <span class="text-muted d-block mb-2">Operating Days</span>
                    <strong class="fs-5">{{ $farmer->operating_days ?: 'Not added' }}</strong>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="ml-card h-100">
                    <span class="text-muted d-block mb-2">Pickup Window</span>
                    <strong class="fs-5">
                        @if($farmer->start_time && $farmer->end_time)
                            {{ \Carbon\Carbon::parse($farmer->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($farmer->end_time)->format('h:i A') }}
                        @else
                            Not added
                        @endif
                    </strong>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
