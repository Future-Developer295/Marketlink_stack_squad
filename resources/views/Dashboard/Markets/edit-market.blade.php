@extends('Dashboard._master')

@section('nav_markets_list')
    active
@endsection

@section('page_title', 'Edit Market')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <style>
        .days-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .day-option {
            position: relative;
            cursor: pointer;
        }

        .day-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .day-option span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 78px;
            padding: 10px 14px;
            border: 1px solid #d8e5df;
            border-radius: 10px;
            background: #fff;
            color: #173b32;
            font-size: 13px;
            transition: all .2s ease;
        }

        .day-option input:checked+span {
            border-color: #31a46f;
            background: #eefaf4;
            color: #0f6b45;
        }

        .day-option span:hover {
            border-color: #31a46f;
        }
    </style>
@endpush

@section('body')

    @php
        $selectedDays = collect(explode(',', $market->operating_days ?? ''))
            ->map(fn($day) => strtolower(substr(trim($day), 0, 3)))
            ->filter()
            ->values()
            ->toArray();

        $startTime = $market->start_time ? \Illuminate\Support\Carbon::parse($market->start_time)->format('H:i') : '';

        $endTime = $market->end_time ? \Illuminate\Support\Carbon::parse($market->end_time)->format('H:i') : '';
    @endphp

    <div class="panel" style="max-width:1440px">

        <div class="panel-header">
            <span class="panel-title">
                <i class="bi bi-shop-window"></i>
                Edit Market
            </span>

            <a class="btn-ghost sm" href="{{ route('markets') }}">
                <i class="bi bi-x"></i>
                Cancel
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

                        <input type="text" name="name" value="{{ old('name', $market->name) }}" required
                            class="field-input" placeholder="e.g. Sunday Farmers Market">
                    </div>

                    <div class="field">
                        <label class="field-label">Address *</label>

                        <input type="text" id="address" name="address" value="{{ old('address', $market->address) }}"
                            required class="field-input" placeholder="Street address">
                    </div>

                </div>

                <div class="grid-3">

                    <div class="field">
                        <label class="field-label">City *</label>

                        <input type="text" id="city" name="city" value="{{ old('city', $market->city) }}"
                            required class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">State</label>

                        <input type="text" id="state" name="state" value="{{ old('state', $market->state) }}"
                            class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">Country *</label>

                        <input type="text" id="country" name="country" value="{{ old('country', $market->country) }}"
                            required class="field-input">
                    </div>

                </div>

                <div class="grid-2">

                    <div class="field">
                        <label class="field-label">Latitude</label>

                        <input type="number" id="latitude" name="latitude"
                            value="{{ old('latitude', $market->latitude) }}" step="0.00000001" class="field-input"
                            placeholder="Select from map" readonly>
                    </div>

                    <div class="field">
                        <label class="field-label">Longitude</label>

                        <input type="number" id="longitude" name="longitude"
                            value="{{ old('longitude', $market->longitude) }}" step="0.00000001" class="field-input"
                            placeholder="Select from map" readonly>
                    </div>

                </div>

                <div class="field" style="margin-top:16px">

                    <label class="field-label">
                        Market Location
                    </label>

                    <div id="marketEditMap" style="width:100%; height:420px; border-radius:14px; overflow:hidden;"></div>

                    <small style="display:block; margin-top:8px; color:var(--muted);">
                        Click on the map to change the market location.
                    </small>

                </div>

                <div class="grid-3">

                    <div class="field">
                        <label class="field-label">Operating Days</label>

                        <div class="days-picker">

                            @foreach ([
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday',
        ] as $value => $label)
                                <label class="day-option">

                                    <input type="checkbox" name="operating_days[]" value="{{ $value }}"
                                        {{ in_array(strtolower($value), old('operating_days', $selectedDays), true) ? 'checked' : '' }}>

                                    <span>{{ $label }}</span>

                                </label>
                            @endforeach

                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Start Time</label>

                        <input type="time" name="start_time" value="{{ old('start_time', $startTime) }}"
                            class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">End Time</label>

                        <input type="time" name="end_time" value="{{ old('end_time', $endTime) }}" class="field-input">
                    </div>

                </div>

                <div class="spacer-16"></div>

                <div style="display:flex; gap:10px">

                    <button class="btn-primary" type="submit">
                        <i class="bi bi-check2"></i>
                        Update Market
                    </button>

                    <a class="btn-ghost" href="{{ route('markets') }}">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const cityInput = document.getElementById('city');
            const stateInput = document.getElementById('state');
            const countryInput = document.getElementById('country');
            const addressInput = document.getElementById('address');

            const latitude = Number(latitudeInput.value);
            const longitude = Number(longitudeInput.value);

            const defaultLocation = latitude && longitude ?
                [latitude, longitude] :
                [30.3753, 69.3451];

            const defaultZoom = latitude && longitude ? 15 : 5;

            const map = L.map('marketEditMap').setView(
                defaultLocation,
                defaultZoom
            );

            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }
            ).addTo(map);

            let marker = null;

            if (latitude && longitude) {
                marker = L.marker([
                    latitude,
                    longitude
                ]).addTo(map);
            }

            map.on('click', async function(e) {

                const selectedLatitude = e.latlng.lat.toFixed(8);
                const selectedLongitude = e.latlng.lng.toFixed(8);

                latitudeInput.value = selectedLatitude;
                longitudeInput.value = selectedLongitude;

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                try {

                    const response = await fetch(
                        'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
                        selectedLatitude +
                        '&lon=' +
                        selectedLongitude +
                        '&zoom=18&addressdetails=1', {
                            headers: {
                                'Accept-Language': 'en'
                            }
                        }
                    );

                    const data = await response.json();

                    if (!data.address) {
                        return;
                    }

                    const location = data.address;

                    cityInput.value =
                        location.city ||
                        location.town ||
                        location.village ||
                        location.municipality ||
                        '';

                    stateInput.value =
                        location.state ||
                        '';

                    countryInput.value =
                        location.country ||
                        '';

                    if (location.road) {

                        const houseNumber = location.house_number ?
                            location.house_number + ' ' :
                            '';

                        addressInput.value =
                            houseNumber + location.road;

                    } else if (data.display_name) {

                        addressInput.value = data.display_name;
                    }

                } catch (error) {
                    console.error(error);
                }
            });

        });
    </script>
@endpush
