@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('admin.faskes.index') }}" class="btn btn-outline-secondary me-2">
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
                        <label class="form-label">Nama Faskes <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $faskes->nama ?? '') }}" required maxlength="150">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Faskes <span class="text-danger">*</span></label>
                        <select name="kategori_faskes_id" class="form-select @error('kategori_faskes_id') is-invalid @enderror" required>
                            <option value="">-- Pilih jenis faskes --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" @selected(old('kategori_faskes_id', $faskes->kategori_faskes_id ?? null) == $k->id)>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_faskes_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Wilayah</label>
                        <select name="wilayah_id" class="form-select @error('wilayah_id') is-invalid @enderror">
                            <option value="">-- Pilih wilayah --</option>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}" @selected(old('wilayah_id', $faskes->wilayah_id ?? null) == $w->id)>{{ $w->nama }} {{ $w->kota ? '('.$w->kota.')' : '' }}</option>
                            @endforeach
                        </select>
                        @error('wilayah_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" required>{{ old('alamat', $faskes->alamat ?? '') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                               value="{{ old('telepon', $faskes->telepon ?? '') }}" maxlength="30">
                        @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Jam Operasional</label>
                        <input type="text" name="jam_operasional" class="form-control @error('jam_operasional') is-invalid @enderror"
                               value="{{ old('jam_operasional', $faskes->jam_operasional ?? '') }}">
                        @error('jam_operasional')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $faskes->deskripsi ?? '') }}</textarea>
                    </div>

@include('admin._coordinate-picker', [
                        'latValue' => old('latitude', $faskes->latitude ?? -7.6300),
                        'lngValue' => old('longitude', $faskes->longitude ?? 111.5160),
                    ])

                    <div class="col-12">
                        <label class="form-label">Node Terdekat</label>
                        <select name="node_id" id="node_id" class="form-select @error('node_id') is-invalid @enderror">
                            <option value="">-- Pilih node manual --</option>
                            @foreach($nodeList as $n)
                                <option value="{{ $n->id }}"
                                        data-lat="{{ $n->latitude }}" data-lng="{{ $n->longitude }}"
                                        data-tipe="{{ $n->tipe }}"
                                        @selected(old('node_id', $faskes->node_id ?? null) == $n->id)>
                                    {{ $n->nama }} ({{ $n->kode ?? $n->id }}){{ $n->tipe === 'faskes' ? ' - faskes' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text" id="node-hint">Saran dihitung otomatis dari koordinat faskes; kamu tetap bisa memilih node lain secara manual.</div>
                        @error('node_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.faskes.index') }}" class="btn btn-outline-secondary">Batal</a>
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
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const nodeSelect = document.getElementById('node_id');
        const hint = document.getElementById('node-hint');

        if (!latInput || !lngInput || !nodeSelect) {
            return;
        }

        const options = Array.from(nodeSelect.options).filter(function (o) {
            return o.value && o.dataset.lat && o.dataset.lng;
        });
        let userPicked = false;

        function haversine(lat1, lng1, lat2, lng2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function suggest() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);

            if (isNaN(lat) || isNaN(lng) || options.length === 0) {
                return;
            }

            let best = null;
            let bestDistance = Infinity;

            options.forEach(function (o) {
                const d = haversine(lat, lng, parseFloat(o.dataset.lat), parseFloat(o.dataset.lng));
                if (d < bestDistance) {
                    bestDistance = d;
                    best = o;
                }
            });

            if (hint) {
                hint.textContent = 'Saran otomatis: ' + best.textContent.trim() + ' (±' + Math.round(bestDistance / 10) * 10 + ' m). Pilih node lain secara manual jika perlu.';
            }

            if (!userPicked) {
                nodeSelect.value = best.value;
            }
        }

        nodeSelect.addEventListener('change', function () {
            userPicked = true;
        });

        latInput.addEventListener('change', suggest);
        lngInput.addEventListener('change', suggest);

        suggest();
    })();
</script>
@endpush