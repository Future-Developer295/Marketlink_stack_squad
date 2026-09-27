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
        </div>
    </div>

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
                    <th>Farmer Reply</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($reviews as $review)
                <tr>
                    <td>
                        <span class="id-chip">{{ $loop->iteration }}</span>
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
                        @if ($review->reply)
                            <p class="small mb-1">{{ $review->reply->response }}</p>
                        @endif

                        @if (auth()->user()->hasRole('farmer'))
                            <form action="{{ route('review_reply_store', $review->id) }}" method="post" class="d-flex gap-1">
                                @csrf
                                <textarea name="response" rows="2" class="form-control form-control-sm" placeholder="Write a reply...">{{ old('response', optional($review->reply)->response) }}</textarea>
                                <button class="btn-ghost sm" type="submit" title="{{ $review->reply ? 'Update reply' : 'Add reply' }}">
                                    <i class="bi bi-reply-fill"></i>
                                </button>
                            </form>
                        @elseif (! $review->reply)
                            <span class="small text-muted">No reply yet.</span>
                        @endif
                    </td>

                    <td>
                        <div class="action-wrap">
                            <form action="{{ route('review_flag', $review->id) }}" method="post">
                                @csrf
                                <button class="btn-ghost sm" type="submit" title="Toggle flag">
                                    <i class="bi bi-flag"></i>
                                </button>
                            </form>

                            <form action="{{ route('review_delete', $review->id) }}" method="post" onsubmit="return confirm('Delete this review?');">
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
                    <td colspan="9" style="text-align:center; color:var(--muted); padding:20px">
                        No reviews yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="hr-thin"></div>

    <div style="padding:14px 22px; color:var(--muted); font-size:13px">
        Reviews are written by customers, not added here — this page is for viewing and moderating (flag/remove).
    </div>
</div>
@endsection

