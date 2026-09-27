

<?php $__env->startSection('nav_markets_add'); ?>
    active
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_title', 'Add Market'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('body'); ?>

    <div class="panel" style="max-width:1440px">

        <div class="panel-header">
            <span class="panel-title">
                <i class="bi bi-shop-window"></i>
                Add Market
            </span>

            <a class="btn-ghost sm" href="<?php echo e(route('markets')); ?>">
                <i class="bi bi-x"></i>
                Cancel
            </a>
        </div>

        <div style="padding:22px">

            <form action="<?php echo e(route('market_store')); ?>" method="post">
                <?php echo csrf_field(); ?>

                <div class="grid-2">

                    <div class="field">
                        <label class="field-label">Market Name *</label>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="field-input"
                            placeholder="e.g. Sunday Farmers Market">
                    </div>

                    <div class="field">
                        <label class="field-label">Address *</label>
                        <input type="text" name="address" value="<?php echo e(old('address')); ?>" required class="field-input"
                            placeholder="Street address">
                    </div>

                </div>

                <div class="grid-3">

                    <div class="field">
                        <label class="field-label">City *</label>
                        <input type="text" name="city" value="<?php echo e(old('city')); ?>" required class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">State</label>
                        <input type="text" name="state" value="<?php echo e(old('state')); ?>" class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">Country *</label>
                        <input type="text" name="country" value="<?php echo e(old('country')); ?>" required class="field-input">
                    </div>

                </div>

                <div class="grid-2">

                    <div class="field">
                        <label class="field-label">Latitude</label>
                        <input type="number" id="latitude" name="latitude" value="<?php echo e(old('latitude')); ?>" step="0.00000001"
                            class="field-input" placeholder="Select from map" readonly>
                    </div>

                    <div class="field">
                        <label class="field-label">Longitude</label>
                        <input type="number" id="longitude" name="longitude" value="<?php echo e(old('longitude')); ?>"
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday',
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="day-option">
                                    <input type="checkbox" name="operating_days[]" value="<?php echo e($value); ?>"
                                        <?php echo e(in_array($value, old('operating_days', [])) ? 'checked' : ''); ?>>

                                    <span><?php echo e($label); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Start Time</label>
                        <input type="time" name="start_time" value="<?php echo e(old('start_time')); ?>" class="field-input">
                    </div>

                    <div class="field">
                        <label class="field-label">End Time</label>
                        <input type="time" name="end_time" value="<?php echo e(old('end_time')); ?>" class="field-input">
                    </div>

                </div>

                <div class="spacer-16"></div>

                <div style="display:flex; gap:10px">
                    <button class="btn-primary" type="submit">
                        <i class="bi bi-check2"></i>
                        Save Market
                    </button>

                    <a class="btn-ghost" href="<?php echo e(route('markets')); ?>">
                        Cancel
                    </a>
                </div>

            </form>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('Dashboard._master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Marketlink\resources\views/Dashboard/Markets/add-market.blade.php ENDPATH**/ ?>