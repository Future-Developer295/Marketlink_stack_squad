@extends('Dashboard._master')

@section('nav_my_markets_add')
active
@endsection

@section('page_title', 'Join a Market')

@section('body')

<div class="panel" style="max-width:1440px">

    <div class="panel-header">

        <span class="panel-title">
            <i class="bi bi-shop-window"></i> Join a Market
        </span>

        <a class="btn-ghost sm" href="{{ route('my_markets') }}">
            <i class="bi bi-x"></i> Cancel
        </a>

    </div>


    <div style="padding:22px">

        <div style="color:var(--muted); font-size:13px; margin-bottom:12px">
            <i class="bi bi-info-circle"></i>
            Select a market you want to join.
        </div>


        <form action="{{ route('my_markets_store') }}" method="POST">

            @csrf

            <div class="grid-2">

                <div class="field">

                    <label class="field-label">
                        Market *
                    </label>

                    <select required name="market_id" class="field-input">

                        <option value="" disabled selected>
                            Select Market
                        </option>

                        @foreach($markets as $market)

                            <option value="{{ $market->id }}">
                                {{ $market->name }} — {{ $market->city }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            <div class="spacer-16"></div>


            <div style="display:flex; gap:10px">

                <button class="btn-primary" type="submit">
                    <i class="bi bi-check2"></i>
                    Request to Join
                </button>

                <a class="btn-ghost" href="{{ route('my_markets') }}">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection