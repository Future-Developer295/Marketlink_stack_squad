@extends('Dashboard._master')
@section('nav_slots_list')
    active
@endsection
@section('page_title', 'Pickup Slots')
@section('body')
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-clock-history"></i> Pickup Slots</span>
            <div class="panel-tools">
                <form class="search-box" method="GET" action="{{ route('slots') }}" data-ajax-filter="#slots-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                </form>
                <a class="btn-primary" href="{{ route('slot_add') }}"><i class="bi bi-plus-lg"></i> Add Slot</a>
            </div>
        </div>

        <div id="slots-list" data-ajax-list>
<div class="tbl-wrap">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Market</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($slots as $slot)

                        <tr>
                            <td>
                                <span class="id-chip">{{ $slots->firstItem() + $loop->index }}</span>
                            </td>

                            <td>
                                <strong>{{ $slot->market?->name ?? 'N/A' }}</strong>
                            </td>

                            <td>
                                {{ $slot->date }}
                            </td>

                            <td>
                                {{ date('g:i A', strtotime($slot->start_time)) }}
                                -
                                {{ date('g:i A', strtotime($slot->end_time)) }}
                            </td>

                            <td>
                                <span class="qty-tag">{{ $slot->capacity }}</span>
                            </td>

                            <td>
                                @if($slot->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </td>

                            <td>
                                <div class="action-wrap">

                                    <a class="btn-ghost sm" href="{{ route('slot_edit', $slot->id) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('slot_delete', $slot->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this pickup slot?">
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
                            <td colspan="7" style="text-align:center; padding:30px;">No pickup slots found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
@include('Dashboard._pager', ['paginator' => $slots, 'label' => 'slots'])
</div>
    </div>
@endsection