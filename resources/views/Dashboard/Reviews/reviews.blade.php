@extends('Dashboard._master')

@section('nav_reviews')
active
@endsection

@section('page_title', 'Reviews')

@section('body')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title"><i class="bi bi-star-fill"></i> Reviews</span>
        <div class="panel-tools">
            <form class="search-box" method="GET" action="{{ route('reviews') }}" data-ajax-filter="#reviews-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                </form>
        </div>
    </div>

    <div id="reviews-list" data-ajax-list>
<div class="tbl-wrap">
        <table class="dtable">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Customer</th>
                    <th>Farmer</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Flagged</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($reviews as $review)
                <tr>
                    <td>
                        <span class="id-chip">{{ $reviews->firstItem() + $loop->index }}</span>
                    </td>

                    <td>{{ $review->user?->name ?? 'N/A' }}</td>

                    <td>{{ $review->farmer?->user?->name ?? 'N/A' }}</td>

                    <td>{{ $review->product?->name ?? 'N/A' }}</td>

                    <td>
                        <span class="badge-status bs-in">
                            <i class="bi bi-star-fill"></i>
                            {{ $review->rating }} / 5
                        </span>
                    </td>

                    <td>{{ $review->comment }}</td>

                    <td>
                        @if ($review->is_flagged)
                            <span class="badge bg-danger">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>

                    <td>
                        <div class="action-wrap">
                            <form action="{{ route('review_flag', $review->id) }}" method="post" data-ajax data-ajax-refresh="#reviews-list">
                                @csrf
                                <button class="btn-ghost sm" type="submit" title="Toggle flag">
                                    <i class="bi bi-flag"></i>
                                </button>
                            </form>

                            <form action="{{ route('review_delete', $review->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this review?">
                                @csrf
                                <button class="btn-ghost sm danger" type="submit">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:var(--muted); padding:20px">
                        No reviews yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@include('Dashboard._pager', ['paginator' => $reviews, 'label' => 'reviews'])
</div>

    <div class="hr-thin"></div>

    <div style="padding:14px 22px; color:var(--muted); font-size:13px">
        Reviews are written by customers, not added here — this page is for viewing and moderating (flag/remove).
    </div>
</div>
@endsection

