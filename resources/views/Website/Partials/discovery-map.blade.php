@php
    $markets = $markets ?? collect();

    $points = collect();

    if (is_numeric($place->latitude) && is_numeric($place->longitude) && abs((float) $place->latitude) <= 90 && abs((float) $place->longitude) <= 180) {
        $points->push([
            'lat' => (float) $place->latitude,
            'lng' => (float) $place->longitude,
            'label' => $place->name ?? $place->stall_name,
        ]);
    }

    foreach ($markets as $market) {
        if (is_numeric($market->latitude) && is_numeric($market->longitude) && abs((float) $market->latitude) <= 90 && abs((float) $market->longitude) <= 180) {
            $lat = (float) $market->latitude;
            $lng = (float) $market->longitude;

            $alreadyThere = $points->contains(fn ($p) => abs($p['lat'] - $lat) < 0.0001 && abs($p['lng'] - $lng) < 0.0001);

            if (! $alreadyThere) {
                $points->push([
                    'lat' => $lat,
                    'lng' => $lng,
                    'label' => $market->name,
                ]);
            }
        }
    }

    $points = $points->values();
    $mapId = 'discovery-map-' . uniqid();
@endphp
<section class="discovery-panel location-panel" id="directions">
    <span class="shop-kicker">YOUR NEXT LOCAL STOP</span>
    <h2>Find your way here.</h2>
    <p>{{ $place->address }}, {{ $place->city }}, {{ $place->state }}</p>

    @if($points->count() > 1)

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
        <div id="{{ $mapId }}" class="discovery-map"></div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            (function () {
                var points = @json($points);
                var map = L.map('{{ $mapId }}', { scrollWheelZoom: false });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                var bounds = [];

                points.forEach(function (point) {
                    L.marker([point.lat, point.lng]).addTo(map).bindPopup(point.label);
                    bounds.push([point.lat, point.lng]);
                });

                map.fitBounds(bounds, { padding: [30, 30] });
            })();
        </script>

    @elseif($points->count() === 1)

        <iframe
            class="discovery-map"
            title="{{ $points->first()['label'] }} on OpenStreetMap"
            loading="lazy"
            referrerpolicy="no-referrer"
            src="https://www.openstreetmap.org/export/embed.html?{{ http_build_query(['bbox' => implode(',', [$points->first()['lng'] - .018, $points->first()['lat'] - .012, $points->first()['lng'] + .018, $points->first()['lat'] + .012]), 'layer' => 'mapnik', 'marker' => $points->first()['lat'] . ',' . $points->first()['lng']]) }}"
        ></iframe>

    @endif

    <a class="shop-pill" href="https://www.google.com/maps/dir/?api=1&amp;destination={{ urlencode(is_numeric($place->latitude) && is_numeric($place->longitude) ? $place->latitude . ',' . $place->longitude : $place->address . ', ' . $place->city) }}" target="_blank" rel="noopener noreferrer">Get directions <i class="fa-solid fa-location-arrow"></i></a>
    <small>Choose your starting point in Maps.</small>
</section>
