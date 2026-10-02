@extends('layouts.app')

@section('title', 'Beranda - Rekomendasi Faskes Terdekat')

@section('content')
<section class="bg-primary text-white py-4 mb-4">
    <div class="container-fluid px-3">
        <h1 class="h3 fw-bold mb-1">
            <i class="bi bi-crosshair2"></i> Rekomendasi Fasilitas Kesehatan Terdekat
        </h1>
        <p class="mb-0 opacity-75">
            Pilih lokasi awal pada peta atau gunakan lokasi Anda, lalu sistem akan menghitung faskes terdekat
            berdasarkan <strong>Algoritma Dijkstra</strong> pada jaringan jalan Wilayah Madiun Taman.
        </p>
    </div>
</section>

<div class="container-fluid px-3">
    <div class="row g-3">
        <div class="col-lg-4">
            <div id="cari" class="card shadow-sm sticky-lg-top" style="top:1rem; z-index:1000; scroll-margin-top:5rem;">
                <div class="card-header bg-white fw-semibold" style="z-index:1001;">
                    <i class="bi bi-search"></i> Pencarian Faskes Terdekat
                </div>
                <div class="card-body">
                    <form id="form-rekomendasi">
                        @csrf
                        <div class="mb-3">
                            <label for="kategori_faskes_id" class="form-label fw-semibold">Kategori Faskes</label>
                            <select name="kategori_faskes_id" id="kategori_faskes_id" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lokasi Anda</label>
                            <div class="btn-group w-100" role="group" id="group-lokasi">
                                <button type="button" class="btn btn-outline-primary active" data-method="map">
                                    <i class="bi bi-geo-alt"></i> Peta
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-method="geo">
                                    <i class="bi bi-crosshair"></i> Gunakan Lokasi Saya
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-method="titik">
                                    <i class="bi bi-list-ol"></i> Titik
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="titik_id" class="form-label small text-muted d-none" id="label-titik">Pilih Titik Jalan</label>
                            <select name="titik_id" id="titik_id" class="form-select d-none">
                                <option value="">-- Pilih titik --</option>
                                @foreach($titik->where('tipe', 'jalan') as $t)
                                    <option value="{{ $t->id }}" data-lat="{{ $t->latitude }}" data-lng="{{ $t->longitude }}">{{ $t->nama }} ({{ $t->kode }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" name="latitude" id="latitude"
                                       class="form-control form-control-sm" placeholder="-7.6300" required>
                            </div>
                            <div class="col-6">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" name="longitude" id="longitude"
                                       class="form-control form-control-sm" placeholder="111.5160" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="btn-cari">
                            <i class="bi bi-calculator"></i> Cari Faskes Terdekat
                        </button>
                    </form>

                    <div class="mt-3 d-none" id="status-loading">
                        <div class="text-center text-muted py-3">
                            <div class="spinner-border text-primary mb-2" role="status"></div>
                            <div>Menghitung dengan Algoritma Dijkstra&hellip;</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-3" id="card-hasil">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-trophy"></i> Hasil Rekomendasi
                </div>
                <div class="card-body" id="hasil">
                    <p class="text-muted small mb-0">Belum ada perhitungan. Klik pada peta atau pilih titik untuk memulai.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="map-container">
                        <div id="recommendation-map"></div>
                    </div>
                </div>
                <div class="card-footer bg-white small text-muted">
                    <i class="bi bi-info-circle"></i> Klik pada peta untuk menempatkan titik awal, atau pilih metode
                    "Lokasi Saya" / "Titik". Garis hijau menampilkan lintasan terpendek faskes terdekat.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@php
    $appData = [
        'faskes' => $faskes->map(fn ($f) => [
            'id' => $f->id,
            'nama' => $f->nama,
            'latitude' => (float) $f->latitude,
            'longitude' => (float) $f->longitude,
            'alamat' => $f->alamat,
            'kategori' => $f->kategori->nama,
            'warna' => $f->kategori->warna,
        ]),
        'default_lat' => -7.6300,
        'default_lng' => 111.5160,
    ];
@endphp
<script>
    window.APP_DATA = @json($appData);
</script>
<script src="{{ asset('js/rekomendasi.js') }}"></script>
@endpush