@extends('layouts.app')

@section('title', 'Peta Faskes & Jaringan Jalan')

@section('content')
<section class="bg-primary text-white py-4 mb-4">
    <div class="container-fluid px-3">
        <h1 class="h3 fw-bold mb-1">
            <i class="bi bi-map"></i> Peta Faskes &amp; Jaringan Jalan
        </h1>
        <p class="mb-0 opacity-75">
            Visualisasi seluruh fasilitas kesehatan beserta titik dan jalur jaringan jalan yang menjadi input
            Algoritma Dijkstra di Wilayah Madiun Taman.
        </p>
    </div>
</section>

<div class="container-fluid px-3">
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="map-container">
                <div id="peta-map"></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-hospital"></i> Daftar Fasilitas Kesehatan
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Faskes</th>
                        <th>Kategori</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faskes as $f)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold"><a href="{{ route('faskes.detail', $f) }}" class="text-decoration-none">{{ $f->nama }}</a></td>
                            <td><span class="badge" style="background:{{ $f->kategori->warna }}">{{ $f->kategori->nama }}</span></td>
                            <td>{{ $f->alamat }}</td>
                            <td>{{ $f->telepon ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data faskes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@php
    $petaData = [
        'faskes' => $faskes->map(fn ($f) => [
            'id' => $f->id,
            'nama' => $f->nama,
            'latitude' => (float) $f->latitude,
            'longitude' => (float) $f->longitude,
            'alamat' => $f->alamat,
            'telepon' => $f->telepon,
            'kategori' => $f->kategori->nama,
            'warna' => $f->kategori->warna,
        ]),
        'titik' => $titik->map(fn ($t) => [
            'id' => $t->id,
            'nama' => $t->nama,
            'latitude' => (float) $t->latitude,
            'longitude' => (float) $t->longitude,
            'tipe' => $t->tipe,
            'kode' => $t->kode,
        ]),
        'jalur' => $jalur->map(fn ($j) => [
            'asal_lat' => (float) $j->nodeAwal->latitude,
            'asal_lng' => (float) $j->nodeAwal->longitude,
            'tujuan_lat' => (float) $j->nodeTujuan->latitude,
            'tujuan_lng' => (float) $j->nodeTujuan->longitude,
            'nama_jalan' => $j->nama_jalan,
            'jarak' => (int) $j->jarak,
        ]),
    ];
@endphp
<script>
    const PETA_DATA = @json($petaData);
</script>
<script src="{{ asset('js/peta.js') }}"></script>
@endpush