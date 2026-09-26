@extends('Dashboard._master')

@section('nav_slots_list')
    active
@endsection

@section('page_title', 'Edit Pickup Slot')

@section('body')

<div class="panel" style="max-width:1440px">

    <div class="panel-header">
        <span class="panel-title">
            <i class="bi bi-clock-history"></i> Edit Pickup Slot
        </span>

        <a class="btn-ghost sm" href="{{ route('slots') }}">
            <i class="bi bi-x"></i> Cancel
        </a>
    </div>

    <div style="padding:22px">

        <div style="color:var(--muted); font-size:13px; margin-bottom:12px">
            <i class="bi bi-info-circle"></i>
            Editing slot #{{ $slot->id }}.
        </div>

   <form action="{{ route('slot_update', $slot->id) }}" method="POST">
    @csrf
            <div class="grid-2">

  <div class="field">
    <label class="field-label">Market *</label>

    <select required name="market_id" class="field-input">
        <option value="" disabled hidden>Select Market</option>
        
        @foreach ($markets as $market)
            <option value="{{ $market->id }}"
                {{ $market->id == $slot->market_id ? 'selected' : '' }}>
                {{ $market->name }}
            </option>
        @endforeach
    </select>
</div>


                <div class="field">
                    <label class="field-label">Date *</label>

                    <input type="date"
                           name="date"
                   value="{{ $slot->date ? \Carbon\Carbon::parse($slot->date)->format('Y-m-d') : '' }}"
                           required
                           class="field-input">
                </div>

            </div>

            <div class="grid-2">

                <div class="field">
                    <label class="field-label">Start Time *</label>

                    <input type="time"
                           name="start_time"
                           value="{{ $slot->start_time }}"
                           required
                           class="field-input">
                </div>

                <div class="field">
                    <label class="field-label">End Time *</label>

                    <input type="time"
                           name="end_time"
                           value="{{ $slot->end_time }}"
                           required
                           class="field-input">
                </div>

            </div>

            <div class="grid-2">

                <div class="field">
                    <label class="field-label">Capacity *</label>

                    <input type="number"
                           name="capacity"
                           value="{{ $slot->capacity }}"
                           required
                           min="1"
                           class="field-input">
                </div>

            </div>

            <div class="field">

                <label class="field-label">Status</label>
                <br>

                <input type="checkbox"
                       name="is_available"
                       id="is_available"
                       value="1"
                       {{ $slot->is_available ? 'checked' : '' }}
                       style="width:18px;height:18px;vertical-align:middle">

                <label for="is_available"
                       style="vertical-align:middle; margin-left:6px; color:var(--muted); font-size:13px">
                    Slot is open for booking
                </label>

            </div>

            <div class="spacer-16"></div>

         <div style="display:flex; gap:10px">

    <button class="btn-primary" type="submit">
        <i class="bi bi-check2"></i> Update Slot
    </button>

    <a class="btn-ghost" href="{{ route('slots') }}">
        Cancel
    </a>

</div>

        </form>

    </div>

</div>

@endsection