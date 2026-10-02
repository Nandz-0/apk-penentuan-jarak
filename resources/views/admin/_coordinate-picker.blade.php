<div class="col-6">
    <label class="form-label">Latitude</label>
    <input type="number" step="any" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror"
           value="{{ $latValue }}" required>
    @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-6">
    <label class="form-label">Longitude</label>
    <input type="number" step="any" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror"
           value="{{ $lngValue }}" required>
    @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="col-12">
    <label class="form-label text-muted small">Atur titik pada peta (klik untuk memindahkan)</label>
    <div class="coordinate-picker" data-lat="{{ $latValue }}" data-lng="{{ $lngValue }}"
         style="height:300px; border:1px solid #dee2e6; border-radius:.375rem;"></div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.coordinate-picker').forEach(function (elPicker) {
        const picker = elPicker;
        const pickerForm = picker.closest('form');
        const latInput = pickerForm.querySelector('input[name="latitude"]');
        const lngInput = pickerForm.querySelector('input[name="longitude"]');

        const map = L.map(picker).setView([
            parseFloat(picker.dataset.lat),
            parseFloat(picker.dataset.lng),
        ], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        const marker = L.marker([parseFloat(picker.dataset.lat), parseFloat(picker.dataset.lng)], { draggable: true }).addTo(map);

        function sync(lat, lng) {
            latInput.value = parseFloat(lat).toFixed(7);
            lngInput.value = parseFloat(lng).toFixed(7);
            latInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        sync(picker.dataset.lat, picker.dataset.lng);

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            sync(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function () {
            const p = marker.getLatLng();
            sync(p.lat, p.lng);
        });

        latInput.addEventListener('change', function () {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]).update();
                map.setView([lat, lng]);
            }
        });
        lngInput.addEventListener('change', function () {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]).update();
                map.setView([lat, lng]);
            }
        });
    });
</script>
@endpush