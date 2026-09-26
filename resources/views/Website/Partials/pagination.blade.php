@if(($paginator ?? null) && $paginator->hasPages())
<div class="ml-pagination">
    <a href="{{ $paginator->previousPageUrl() }}" class="{{ $paginator->onFirstPage() ? 'disabled' : '' }}">
        <i class="fa-solid fa-chevron-left"></i>
    </a>

    @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="{{ $page == $paginator->currentPage() ? 'active' : '' }}">{{ $page }}</a>
    @endforeach

    <a href="{{ $paginator->nextPageUrl() }}" class="{{ $paginator->hasMorePages() ? '' : 'disabled' }}">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
</div>
@endif
