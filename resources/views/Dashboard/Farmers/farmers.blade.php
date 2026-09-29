@extends('Dashboard._master')
@section('nav_farmers')
    active
@endsection
@section('page_title', 'Farmers')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-person-workspace"></i> Farmers</span>
            <div class="panel-tools">
                <form class="search-box" method="GET" action="{{ route('farmers') }}" data-ajax-filter="#farmers-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                </form>
            </div>
        </div>

        <div id="farmers-list" data-ajax-list>
<div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Market Name</th>
                        <th>Farmer Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($farmers as $farmer)
                        <tr>
                            <td>
                                <span class="id-chip">{{ $farmers->firstItem() + $loop->index }}</span>
                            </td>

                            <td>
                                {{ $farmer->market?->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $farmer->farmer?->name ?? 'N/A' }}
                            </td>

                            <td>
                                @if ($farmer->is_active)
                                    <span class="badge-status bs-active">
                                        <i class="bi bi-circle-fill"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge-status bs-inactive">
                                        <i class="bi bi-circle-fill"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="action-wrap">
                                    <a href="{{ route('farmer_edit', $farmer->id) }}" class="btn-ghost sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px;">No farmers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@include('Dashboard._pager', ['paginator' => $farmers, 'label' => 'farmers'])
</div>
    </div>
@endsection
