@extends('Dashboard._master')

@section('nav_markets_list')
active
@endsection

@section('page_title', 'Edit Market')

@section('body')
<div class="panel" style="max-width:1440px">


  <div class="panel-header">
    <span class="panel-title">
      <i class="bi bi-shop-window"></i> Edit Market
    </span>

    <a class="btn-ghost sm" href="{{ route('markets') }}">
      <i class="bi bi-x"></i> Cancel
    </a>
  </div>

  <div style="padding:22px">

    <div style="color:var(--muted); font-size:13px; margin-bottom:12px">
      <i class="bi bi-info-circle"></i>
      Editing market #{{ $market->id }}.
    </div>

    <form action="{{ route('market_update', $market->id) }}" method="post">
      @csrf

      <div class="grid-2">

        <div class="field">
          <label class="field-label">Market Name *</label>

          <input
            type="text"
            name="name"
            value="{{ $market->name }}"
            required
            class="field-input"
            placeholder="e.g. Sunday Farmers Market" />
        </div>

        <div class="field">
          <label class="field-label">Address *</label>

          <input
            type="text"
            name="address"
            value="{{ $market->address }}"
            required
            class="field-input"
            placeholder="Street address" />
        </div>

      </div>


      <div class="grid-3">

        <div class="field">
          <label class="field-label">City *</label>

          <input
            type="text"
            name="city"
            value="{{ $market->city }}"
            required
            class="field-input" />
        </div>

        <div class="field">
          <label class="field-label">State</label>

          <input
            type="text"
            name="state"
            value="{{ $market->state }}"
            class="field-input" />
        </div>

        <div class="field">
          <label class="field-label">Country *</label>

          <input
            type="text"
            name="country"
            value="{{ $market->country }}"
            required
            class="field-input" />
        </div>

      </div>


      <div class="grid-2">

        <div class="field">
          <label class="field-label">Latitude</label>

          <input
            type="number"
            name="latitude"
            value="{{ $market->latitude }}"
            step="0.00000001"
            class="field-input"
            placeholder="24.8607" />
        </div>

        <div class="field">
          <label class="field-label">Longitude</label>

          <input
            type="number"
            name="longitude"
            value="{{ $market->longitude }}"
            step="0.00000001"
            class="field-input"
            placeholder="67.0011" />
        </div>

      </div>


      <div class="grid-3">

        <div class="field">
          <label class="field-label">Operating Days</label>

          <input
            type="text"
            name="operating_days"
            value="{{ $market->operating_days }}"
            class="field-input"
            placeholder="Sat,Sun" />
        </div>

        <div class="field">
          <label class="field-label">Start Time</label>

          <input
            type="time"
            name="start_time"
            value="{{ $market->start_time }}"
            class="field-input" />
        </div>

        <div class="field">
          <label class="field-label">End Time</label>

          <input
            type="time"
            name="end_time"
            value="{{ $market->end_time }}"
            class="field-input" />
        </div>

      </div>


      <div class="spacer-16"></div>

      <div style="display:flex; gap:10px">

        <button class="btn-primary" type="submit">
          <i class="bi bi-check2"></i> Update Market
        </button>

        <a class="btn-ghost" href="{{ route('markets') }}">
          Cancel
        </a>

      </div>

    </form>

  </div>

</div>


@endsection