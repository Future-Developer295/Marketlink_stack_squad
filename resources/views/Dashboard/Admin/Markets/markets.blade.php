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
            <form class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Search…" />
            </form>
            <a class="btn-primary" href="{{ route('market_add') }}"><i class="bi bi-plus-lg"></i> Add Market</a>
        </div>
    </div>

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
                @foreach($markets as $market)
                <tr>
                    <td>
                        <span class="id-chip">{{ $market->id }}</span>
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
                        <span class="qty-tag">0</span>
                    </td>

                    <td>
                        <div class="action-wrap">

                            <a class="btn-ghost sm"
                                href="{{ route('market_edit', $market->id) }}">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('market_delete', $market->id) }}"
                                method="post">
                                @csrf

                                <button class="btn-ghost sm danger" type="submit">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection