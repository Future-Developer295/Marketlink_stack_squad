@if ($paginator->total() > 0)
    <div class="hr-thin"></div>
    <div style="padding:14px 22px; display:flex; flex-wrap:wrap; gap:12px; align-items:center; justify-content:space-between;">
        <span style="color:var(--muted); font-size:13px;">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }} {{ $label ?? 'items' }}
        </span>
        @if ($paginator->hasPages())
            {{ $paginator->links() }}
        @endif
    </div>
@endif
