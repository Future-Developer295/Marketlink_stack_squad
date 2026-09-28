@extends('Dashboard._master')

@section('nav_my_markets_list')
active
@endsection

@section('page_title', 'My Markets')

@section('body')

<div class="panel">

    <div class="panel-header">

        <span class="panel-title">
            <i class="bi bi-shop-window"></i> My Markets
        </span>

        <div class="panel-tools">

            <form class="search-box" method="GET" action="{{ route('my_markets') }}">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search…"
                />
            </form>

            <a class="btn-primary" href="{{ route('my_markets_join') }}">
                <i class="bi bi-plus-lg"></i> Join a Market
            </a>

        </div>

    </div>


    <div class="tbl-wrap">

        <table class="dtable">

            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Market</th>
                    <th>City</th>
                    <th>Timing</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>


            <tbody>
                @forelse($myMarkets as $item)

                    <tr>

                        <td>
                            <span class="id-chip">
                                {{ $item->market->id }}
                            </span>
                        </td>


                        <td>
                            <strong>
                                {{ $item->market->name }}
                            </strong>
                        </td>


                        <td>
                            {{ $item->market->city }}
                        </td>


                        <td>
                            {{ \Carbon\Carbon::parse($item->market->start_time)->format('g:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($item->market->end_time)->format('g:i A') }}
                        </td>


                        <td>
    @if($item->is_active)
        <span class="badge-status bs-in">
            <i class="bi bi-circle-fill"></i>
            Active
        </span>
    @else
        <span class="badge-status">
            <i class="bi bi-circle-fill"></i>
            Pending
        </span>
    @endif
</td>

                        <td>

                            <div class="action-wrap">

                                <form
                                    action="{{ route('my_markets_leave', $item->market->id) }}"
                                    method="POST"
                                >

                                    @csrf

                                    <button
                                        type="submit"
                                        class="btn-ghost sm danger"
                                    >
                                        <i class="bi bi-box-arrow-left"></i>
                                        Leave
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align: center;">
                            No markets found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection