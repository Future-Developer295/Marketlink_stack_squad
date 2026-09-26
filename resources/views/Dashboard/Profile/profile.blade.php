@extends('Dashboard._master')

@section('nav_profile')
    active
@endsection

@section('page_title', 'My Profile')

@section('body')

    @php
        $operatingDays = collect(explode(',', $farmer?->operating_days ?? ''))
            ->map(fn($day) => strtolower(substr(trim($day), 0, 3)))
            ->filter()
            ->toArray();

        $startTime = $farmer?->start_time ? $farmer->start_time->format('H:i') : '';

        $endTime = $farmer?->end_time ? $farmer->end_time->format('H:i') : '';
    @endphp

    <div class="panel" style="max-width:1440px">

        <div class="panel-header">
            <span class="panel-title">
                <i class="bi bi-person-badge-fill"></i> Farmer Profile
            </span>
        </div>

        <div style="padding:22px">

            <form action="{{ route('my_profile_update') }}" method="post" enctype="multipart/form-data">

                @csrf

                <div class="farmer-image-wrapper">

                    <label for="farmer_image" class="farmer-image-label">

                        <div class="farmer-image-box">

                            @if ($farmer?->farmer_image)
                                <img id="farmerImagePreview" src="{{ asset('farmer_images/' . $farmer->farmer_image) }}"
                                    alt="Farmer Image">
                            @else
                                <div id="farmerImagePreview" class="farmer-image-placeholder">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif

                        </div>

                    </label>

                    <input type="file" name="farmer_image" id="farmer_image" class="farmer-image-input" accept="image/*">

                    <div class="farmer-image-text">
                        Click image to change
                    </div>

                </div>

                <div class="grid-2">

                    <div class="field">

                        <label class="field-label">
                            Stall Name *
                        </label>

                        <input type="text" name="stall_name" required class="field-input"
                            placeholder="e.g. Green Valley Stall" value="{{ old('stall_name', $farmer?->stall_name) }}">

                    </div>

                    <div class="field">

                        <label class="field-label">
                            Business Name
                        </label>

                        <input type="text" name="business_name" class="field-input"
                            placeholder="Registered business name"
                            value="{{ old('business_name', $farmer?->business_name) }}">

                    </div>

                </div>

                <div class="field">

                    <label class="field-label">
                        Description
                    </label>

                    <textarea name="description" class="field-input" placeholder="Tell customers about your farm…">{{ old('description', $farmer?->description) }}</textarea>

                </div>

                <div class="field">

                    <label class="field-label">
                        Address
                    </label>

                    <input type="text" name="address" class="field-input" placeholder="Street address"
                        value="{{ old('address', $farmer?->address) }}">

                </div>

                <div class="grid-3">

                    <div class="field">

                        <label class="field-label">
                            Country
                        </label>

                        <select name="country" id="country" class="field-input">
                            <option value="{{ $farmer?->country }}" selected>
                                {{ $farmer?->country ?? 'Select Country' }}
                            </option>
                        </select>

                    </div>

                    <div class="field">

                        <label class="field-label">
                            State
                        </label>

                        <select name="state" id="state" class="field-input">
                            <option value="{{ $farmer?->state }}" selected>
                                {{ $farmer?->state ?? 'Select State' }}
                            </option>
                        </select>

                    </div>

                    <div class="field">

                        <label class="field-label">
                            City
                        </label>

                        <select name="city" id="city" class="field-input">
                            <option value="{{ $farmer?->city }}" selected>
                                {{ $farmer?->city ?? 'Select City' }}
                            </option>
                        </select>

                    </div>

                </div>

                <div class="field">

                    <label class="field-label">
                        Select Location
                    </label>

                    <div id="map" style="height:400px; width:100%; border-radius:10px;"></div>

                </div>

                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $farmer?->latitude) }}">

                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $farmer?->longitude) }}">

                <div class="grid-3">

                    <div class="field">

                        <label class="field-label">
                            Operating Days
                        </label>

                        <div class="operating-days">

                            @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                @php
                                    $shortDay = strtolower(substr($day, 0, 3));
                                @endphp

                                <label class="operating-day">

                                    <input type="checkbox" name="operating_days[]" value="{{ $day }}"
                                        {{ in_array($shortDay, old('operating_days', $operatingDays)) ? 'checked' : '' }}>

                                    <span>
                                        {{ $day }}
                                    </span>

                                </label>
                            @endforeach

                        </div>

                    </div>

                    <div class="field">
                        <label class="field-label">Start Time</label>

                        <input type="time" name="start_time" class="field-input"
                            value="{{ old('start_time', $startTime) }}">
                    </div>

                    <div class="field">
                        <label class="field-label">End Time</label>

                        <input type="time" name="end_time" class="field-input"
                            value="{{ old('end_time', $endTime) }}">
                    </div>

                </div>

                <div class="spacer-16"></div>

                <button class="btn-primary" type="submit">
                    <i class="bi bi-check2"></i>
                    Save Profile
                </button>

            </form>

        </div>

    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const savedLatitude = {{ $farmer?->latitude ?? 24.8607 }};
        const savedLongitude = {{ $farmer?->longitude ?? 67.0011 }};

        const hasSavedLocation =
            {{ $farmer?->latitude && $farmer?->longitude ? 'true' : 'false' }};

        const map = L.map('map').setView(
            [savedLatitude, savedLongitude],
            hasSavedLocation ? 15 : 11
        );

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }
        ).addTo(map);

        let marker = null;

        if (hasSavedLocation) {
            marker = L.marker([
                savedLatitude,
                savedLongitude
            ]).addTo(map);
        }

        map.on('click', async function(e) {

            const latitude = e.latlng.lat.toFixed(8);
            const longitude = e.latlng.lng.toFixed(8);

            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            try {

                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`
                );

                const data = await response.json();

                const address = data.address;

                const country = address.country ?? '';
                const state = address.state ?? '';
                const city =
                    address.city ??
                    address.town ??
                    address.village ??
                    '';

                document.getElementById('country').innerHTML =
                    `<option value="${country}" selected>${country}</option>`;

                document.getElementById('state').innerHTML =
                    `<option value="${state}" selected>${state}</option>`;

                document.getElementById('city').innerHTML =
                    `<option value="${city}" selected>${city}</option>`;

            } catch (error) {

                console.error('Location fetch error:', error);

            }
        });

        document.getElementById('farmer_image').addEventListener(
            'change',
            function(event) {

                const file = event.target.files[0];

                if (!file) {
                    return;
                }

                const imageUrl = URL.createObjectURL(file);
                const preview =
                    document.getElementById('farmerImagePreview');

                if (preview.tagName === 'IMG') {

                    preview.src = imageUrl;

                } else {

                    preview.outerHTML = `
                        <img
                            id="farmerImagePreview"
                            src="${imageUrl}"
                            alt="Farmer Image"
                        >
                    `;

                }
            }
        );
    </script>

@endsection
