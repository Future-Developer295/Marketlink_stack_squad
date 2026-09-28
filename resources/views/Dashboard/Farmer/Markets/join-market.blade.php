@extends('Dashboard.Farmer._master')
@section('nav_markets')
active
@endsection
@section('page_title', 'Join a Market')
@section('body')
    <div class="panel" style="max-width:1440px">
        <div class="panel-header">
            <span class="panel-title"><i class="bi bi-shop-window"></i> Join a Market</span>
            <a class="btn-ghost sm" href="{{ route('farmer.markets.index') }}"><i class="bi bi-x"></i> Cancel</a>
        </div>
        <div style="padding:22px">
          <div style="color:var(--muted); font-size:13px; margin-bottom:12px"><i class="bi bi-info-circle"></i> Your request will appear as inactive until approved by the market admin.</div>
          <form action="{{ route('farmer.markets.store') }}" method="post">
            @csrf
          <div class="grid-2">
            <div class="field">
              <label class="field-label">Market *</label>
              <select required name="market_id" class="field-input">
                <option disabled selected hidden>Select Market</option>
                <option value="1">Dummy Farmers Market — Karachi</option>
              </select>
            </div>
          </div>

            <div class="spacer-16"></div>
            <div style="display:flex; gap:10px">
              <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Request to Join</button>
              <a class="btn-ghost" href="{{ route('farmer.markets.index') }}">Cancel</a>
            </div>
          </form>
        </div>
    </div>
@endsection
