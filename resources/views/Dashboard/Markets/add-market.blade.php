@extends('Dashboard._master')

@section('nav_markets_add')
    active
@endsection

@section('page_title', 'Add Market')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('body')

    <div class="panel" style="max-width:1440px">

        <div class="panel-header">
            <span class="panel-title">
                <i class="bi bi-shop-window"></i>
                Add Market
            </span>

            <a class="btn-ghost sm" href="{{ route('markets') }}">
                <i class="bi bi-x"></i>
                Cancel
            </a>
        </div>

        <div style="padding:22px">

            <form action="{{ route('market_store') }}" method="post">
                @csrf

                <div class="grid-2">

                    <div class="field">
                        <label class="field-label">Market Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="field-input"
                            placeholder="e.g. Sunday Farmers Market">
                    </div>

                    <div class="field">
                        <label class="field-label">Address *</label>
                        <input type="text" name="address" value="{{ old('address') }}" required class="field-input"
                            placeholder="Street address">
                    </div>

                </div>

                <div class="grid-3">

                    <div class="field">
                        <label class="field-label">City *</label>
                        <input type="text" name="city" value="{{ old('city') }}" required class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">State</label>
                        <input type="text" name="state" value="{{ old('state') }}" class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">Country *</label>
                        <input type="text" name="country" value="{{ old('country') }}" required class="field-input">
                    </div>

                </div>

                <div class="grid-2">

                    <div class="field">
                        <label class="field-label">Latitude</label>
                        <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" step="0.00000001"
                            class="field-input" placeholder="Select from map" readonly>
                    </div>

                    <div class="field">
                        <label class="field-label">Longitude</label>
                        <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}"
                            step="0.00000001" class="field-input" placeholder="Select from map" readonly>
                    </div>

                </div>

                <div class="field" style="margin-top:16px">

                    <label class="field-label">
                        Market Location
                    </label>

                    <div id="marketAddMap" style="width:100%; height:420px; border-radius:14px; overflow:hidden;"></div>

                    <small style="display:block; margin-top:8px; color:var(--muted);">
                        Click on the map to select the market location.
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
                                        {{ in_array($value, old('operating_days', [])) ? 'checked' : '' }}>

                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Start Time</label>
                        <input type="time" name="start_time" value="{{ old('start_time') }}" class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">End Time</label>
                        <input type="time" name="end_time" value="{{ old('end_time') }}" class="field-input">
                    </div>

                </div>

                <div class="spacer-16"></div>

                <div style="display:flex; gap:10px">
                    <button class="btn-primary" type="submit">
                        <i class="bi bi-check2"></i>
                        Save Market
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
            const map = L.map('marketAddMap').setView([30.3753, 69.3451], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let marker = null;

            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const cityInput = document.querySelector('[name="city"]');
            const stateInput = document.querySelector('[name="state"]');
            const countryInput = document.querySelector('[name="country"]');
            const addressInput = document.querySelector('[name="address"]');

            const savedLatitude = Number(latitudeInput.value);
            const savedLongitude = Number(longitudeInput.value);

            if (savedLatitude && savedLongitude) {
                const position = [savedLatitude, savedLongitude];

                map.setView(position, 15);

                marker = L.marker(position).addTo(map);
            }

            map.on('click', async function(e) {
                const latitude = e.latlng.lat.toFixed(8);
                const longitude = e.latlng.lng.toFixed(8);

                latitudeInput.value = latitude;
                longitudeInput.value = longitude;

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                try {
                    const response = await fetch(
                        'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
                        latitude +
                        '&lon=' +
                        longitude +
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

                    const address = data.address;

                    cityInput.value =
                        address.city ||
                        address.town ||
                        address.village ||
                        address.municipality ||
                        '';

                    stateInput.value =
                        address.state ||
                        '';

                    countryInput.value =
                        address.country ||
                        '';

                    if (address.road) {
                        const road = address.road;
                        const houseNumber = address.house_number ?
                            address.house_number + ' ' :
                            '';

                        addressInput.value =
                            houseNumber + road;
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
