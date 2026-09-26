<div class="ml-empty-state">
    <div class="ml-empty-state__icon">
        <i class="fa-solid {{ $icon ?? 'fa-seedling' }}"></i>
    </div>
    <h4>{{ $title ?? 'Nothing Found' }}</h4>
    <p class="text-muted mt-2 mb-4">{{ $message ?? 'Try adjusting your search or filters to find what you are looking for.' }}</p>
    @if(($actionUrl ?? null) && ($actionLabel ?? null))
        <a href="{{ $actionUrl }}" class="btn ml-btn-primary">
            <i class="fa-solid fa-rotate-right"></i> {{ $actionLabel }}
        </a>
    @endif
</div>
