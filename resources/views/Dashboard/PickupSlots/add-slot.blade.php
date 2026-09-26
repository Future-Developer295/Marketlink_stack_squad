@extends('Dashboard._master')
@section('nav_slots_add')
  active
@endsection
@section('page_title', 'Add Pickup Slot')
@section('body')
  <div class="panel" style="max-width:1440px">
    <div class="panel-header">
      <span class="panel-title"><i class="bi bi-clock-history"></i> Add Pickup Slot</span>
      <a class="btn-ghost sm" href="{{ route('slots') }}"><i class="bi bi-x"></i> Cancel</a>
    </div>
    <div style="padding:22px">
      <form action="{{ route('slot_store') }}" method="post">
        @csrf
        <div class="grid-2">
          <div class="field">
            <label class="field-label">Market *</label>

            <select required name="market_id" class="field-input">

              <option value="" selected disabled hidden>Select Market</option>

              @foreach ($markets as $market)
                <option value="{{ $market->id }}" {{ old('market_id') == $market->id ? 'selected' : '' }}>
                  {{ $market->name }}
                </option>
              @endforeach

            </select>

            @error('market_id')
              <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
              </div>
            @enderror
          </div>


          <div class="field">
            <label class="field-label">Date *</label>
            <input type="date" name="date" required class="field-input" placeholder="" />
          </div>
        </div>

        <div class="grid-2">
          <div class="field">
            <label class="field-label">Start Time *</label>
            <input type="time" name="start_time" required class="field-input" placeholder="" />
          </div>

          <div class="field">
            <label class="field-label">End Time *</label>
            <input type="time" name="end_time" required class="field-input" placeholder="" />
          </div>
        </div>

        <div class="grid-2">
          <div class="field">
            <label class="field-label">Capacity *</label>
            <input type="number" name="capacity" required min="1" class="field-input" placeholder="e.g. 20" />
          </div>
        </div>

        <div class="field">
          <label class="field-label">Status</label><br>
          <input type="checkbox" name="is_available" id="is_available"
            style="width:18px;height:18px;vertical-align:middle">
          <label for="is_available"
            style="vertical-align:middle; margin-left:6px; color:var(--muted); font-size:13px">Slot is open for
            booking</label>
        </div>

        <div class="spacer-16"></div>
        <div style="display:flex; gap:10px">
          <button class="btn-primary" type="submit"><i class="bi bi-check2"></i> Save Slot</button>
          <a class="btn-ghost" href="{{ route('slots') }}">Cancel</a>
        </div>
      </form>
    </div>
  </div>
@endsection