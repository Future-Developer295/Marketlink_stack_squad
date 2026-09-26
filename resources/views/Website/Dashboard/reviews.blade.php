@extends('Website._master')

@section('page_title', 'My Reviews')

@section('body')

<div class="ml-container">
    <nav class="ml-breadcrumb"><a href="{{ url('/') }}">Home</a> / <a href="{{ route('customer_dashboard') }}">Dashboard</a> / <span class="active">My Reviews</span></nav>
</div>

<section class="ml-section pt-2">
    <div class="ml-container">
        @include('Website.Partials.alerts')

        <div class="row g-4">
            <div class="col-lg-3">
                @include('Website.Dashboard._sidebar')
            </div>

            <div class="col-lg-9">
                @if($reviews->isEmpty())
                @include('Website.Partials.empty-state', [
                    'icon' => 'fa-comment-slash',
                    'title' => 'No Reviews Yet',
                    'message' => 'Reviews you submit for farmers and products will appear here.',
                ])
                @else
                @foreach($reviews as $review)
                <div class="ml-card mb-3">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <span class="ml-badge ml-badge-mint">{{ $review->product ? 'Product Review' : 'Farmer Review' }}</span>
                            <strong class="d-block mt-1">{{ $review->product->name ?? ($review->farmer->stall_name ?? $review->farmer->business_name ?? '') }}</strong>
                            <span class="small text-muted">{{ $review->farmer->stall_name ?? $review->farmer->business_name ?? '' }}</span>
                        </div>
                        <div class="text-end">
                            <div class="ml-rating small">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i > $review->rating ? 'text-muted' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="small text-muted">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    <p class="small text-muted mt-2 mb-0">{{ $review->comment }}</p>
                    @if($review->reply)
                    <div class="mt-2 pt-2" style="border-top:1px solid var(--ml-border);">
                        <span class="small text-success d-block"><i class="fa-solid fa-reply"></i> Farmer replied</span>
                        <span class="small text-muted">{{ $review->reply->response }}</span>
                    </div>
                    @endif
                </div>
                @endforeach
                @include('Website.Partials.pagination', ['paginator' => $reviews])
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
