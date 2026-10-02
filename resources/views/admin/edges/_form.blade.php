@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('admin.edges.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 fw-bold mb-0">{{ $title }}</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ $formAction }}" method="POST">
                @csrf
                @method($method)

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Node Awal <span class="text-danger">*</span></label>
                        <select name="node_awal" id="node_awal" class="form-select @error('node_awal') is-invalid @enderror" required>
                            <option value="">-- Pilih node awal --</option>
                            @foreach($titikList as $t)
                                <option value="{{ $t->id }}"
                                        data-lat="{{ $t->latitude }}" data-lng="{{ $t->longitude }}"
                                        @selected(old('node_awal', $jalur->node_awal ?? null) == $t->id)>
                                    {{ $t->nama }} ({{ $t->kode ?? $t->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('node_awal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Node Tujuan <span class="text-danger">*</span></label>
                        <select name="node_tujuan" id="node_tujuan" class="form-select @error('node_tujuan') is-invalid @enderror" required>
                            <option value="">-- Pilih node tujuan --</option>
                            @foreach($titikList as $t)
                                <option value="{{ $t->id }}"
                                        data-lat="{{ $t->latitude }}" data-lng="{{ $t->longitude }}"
                                        @selected(old('node_tujuan', $jalur->node_tujuan ?? null) == $t->id)>
                                    {{ $t->nama }} ({{ $t->kode ?? $t->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('node_tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jarak (meter) <span class="text-danger">*</span></label>
                        <input type="number" name="jarak" id="jarak" min="1" step="1" required
                               class="form-control @error('jarak') is-invalid @enderror"
                               value="{{ old('jarak', $jalur->jarak ?? '') }}">
                        <div class="form-text">Terisi otomatis dari koordinat node; dapat diubah manual.</div>
                        @error('jarak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama Jalan</label>
                        <input type="text" name="nama_jalan" class="form-control @error('nama_jalan') is-invalid @enderror"
                               value="{{ old('nama_jalan', $jalur->nama_jalan ?? '') }}" maxlength="150">
                        @error('nama_jalan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label text-muted small">Pratinjau jalur pada peta</label>
                        <div id="jalur-preview" style="height:300px; border:1px solid #dee2e6; border-radius:.375rem;"></div>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.edges.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const asal = document.getElementById('node_awal');
        const tujuan = document.getElementById('node_tujuan');
        const jarak = document.getElementById('jarak');

        const map = L.map('jalur-preview').setView([-7.6300, 111.5160], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        let ma = null, mb = null, line = null;

        function haversine(lat1, lng1, lat2, lng2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function draw() {
            if (line) { map.removeLayer(line); }
            if (ma) { map.removeLayer(ma); }
            if (mb) { map.removeLayer(mb); }

            const oA = asal.selectedOptions[0];
            const oB = tujuan.selectedOptions[0];
            if (!oA || !oA.value || !oB || !oB.value) return;

            const a = [parseFloat(oA.dataset.lat), parseFloat(oA.dataset.lng)];
            const b = [parseFloat(oB.dataset.lat), parseFloat(oB.dataset.lng)];

            ma = L.marker(a).addTo(map);
            mb = L.marker(b).addTo(map);
            line = L.polyline([a, b], { color: '#198754', weight: 4 }).addTo(map);
            map.fitBounds(L.latLngBounds([a, b]).pad(0.3));

            if (!jarak.value || parseInt(jarak.value) <= 0) {
                jarak.value = Math.max(1, Math.round(haversine(a[0], a[1], b[0], b[1])));
            }
        }

        asal.addEventListener('change', draw);
        tujuan.addEventListener('change', draw);
        draw();
    })();
</script>
@endpush