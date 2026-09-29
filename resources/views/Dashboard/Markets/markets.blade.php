@extends('Dashboard._master')
@section('nav_markets_list')
active
@endsection
@section('page_title', 'Markets')
@section('body')
<div class="panel">
    <div class="panel-header">
        <span class="panel-title"><i class="bi bi-shop-window"></i> Markets</span>
        <div class="panel-tools">
            <form class="search-box" method="GET" action="{{ route('markets') }}" data-ajax-filter="#markets-list">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search…" />
                </form>
            <a class="btn-primary" href="{{ route('market_add') }}"><i class="bi bi-plus-lg"></i> Add Market</a>
        </div>
    </div>

    <div id="markets-list" data-ajax-list>
<div class="tbl-wrap">
        <table class="dtable">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>Operating Days</th>
                    <th>Timing</th>
                    <th>Farmers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
@forelse($markets as $market)
                <tr>
                    <td>
                        <span class="id-chip">{{ $markets->firstItem() + $loop->index }}</span>
                    </td>

                    <td>
                        <strong>{{ $market->name }}</strong>
                    </td>

                    <td>{{ $market->city }}</td>

                    <td>{{ $market->operating_days }}</td>

                    <td>
                        {{ $market->start_time }} – {{ $market->end_time }}
                    </td>

                    <td>
                        <span class="qty-tag">{{ $market->market_farmers_count }}</span>
                    </td>

                    <td>
                        <div class="action-wrap">

                            <a class="btn-ghost sm"
                                href="{{ route('market_edit', $market->id) }}">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('market_delete', $market->id) }}" method="POST" style="display:inline" data-ajax data-ajax-remove="tbody tr" data-confirm="Delete this market?">
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
                    <td colspan="7" style="text-align:center; padding:30px;">No markets found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@include('Dashboard._pager', ['paginator' => $markets, 'label' => 'markets'])
</div>
</div>
@endsection