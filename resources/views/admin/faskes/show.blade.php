@extends('layouts.app')

@section('title', $faskes->nama.' - Detail Faskes')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-0"><i class="bi bi-hospital"></i> {{ $faskes->nama }}</h1>
            <p class="text-muted small mb-0">
                <span class="badge" style="background:{{ $faskes->kategori->warna }}">{{ $faskes->kategori->nama }}</span>
                @if($faskes->wilayah)
                    &nbsp;{{ $faskes->wilayah->nama }}
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.faskes.edit', $faskes) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-info-circle"></i> Informasi Faskes</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Jenis</dt>
                        <dd class="col-sm-8">
                            <span class="badge" style="background:{{ $faskes->kategori->warna }}">{{ $faskes->kategori->nama }}</span>
                        </dd>

                        <dt class="col-sm-4">Alamat</dt>
                        <dd class="col-sm-8">{{ $faskes->alamat }}</dd>

                        @if($faskes->telepon)
                            <dt class="col-sm-4">Nomor Telepon</dt>
                            <dd class="col-sm-8"><i class="bi bi-telephone"></i> {{ $faskes->telepon }}</dd>
                        @endif

                        @if($faskes->jam_operasional)
                            <dt class="col-sm-4">Jam Operasional</dt>
                            <dd class="col-sm-8"><i class="bi bi-clock"></i> {{ $faskes->jam_operasional }}</dd>
                        @endif

                        <dt class="col-sm-4">Latitude</dt>
                        <dd class="col-sm-8 font-monospace small">{{ number_format($faskes->latitude, 6) }}</dd>

                        <dt class="col-sm-4">Longitude</dt>
                        <dd class="col-sm-8 font-monospace small">{{ number_format($faskes->longitude, 6) }}</dd>

                        <dt class="col-sm-4">Node Terdekat</dt>
                        <dd class="col-sm-8">
                            @if($faskes->node)
                                <span class="font-monospace">{{ $faskes->node->kode ?? $faskes->node->id }}</span>
                                <span class="text-muted small">({{ $faskes->node->nama }})</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </dd>
                    </dl>

                    @if($faskes->deskripsi)
                        <hr>
                        <h6 class="fw-semibold">Deskripsi</h6>
                        <p class="mb-0 text-muted">{{ $faskes->deskripsi }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-geo-alt"></i> Peta Lokasi</div>
                <div class="card-body p-0">
                    <div class="map-container">
                        <div id="faskes-detail-map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.faskes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form action="{{ route('admin.faskes.destroy', $faskes) }}" method="POST">
                    @csrf @method('DELETE')
                    <div class="modal-body">
                        <p>Hapus <strong>{{ $faskes->nama }}</strong>? Data titik terkait juga akan dihapus.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    $detailLat = (float) $faskes->latitude;
    $detailLng = (float) $faskes->longitude;
@endphp

@push('scripts')
<script>
    const info = {
        nama: @json($faskes->nama),
        alamat: @json($faskes->alamat),
        kategori: @json($faskes->kategori->nama),
        warna: @json($faskes->kategori->warna),
        lat: {{ $detailLat }},
        lng: {{ $detailLng }},
    };

    const map = L.map('faskes-detail-map').setView([info.lat, info.lng], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const icon = L.divIcon({
        html: '<div style="background:' + info.warna + ';border-radius:50%;width:18px;height:18px;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4);"></div>',
        className: '',
        iconSize: [18, 18],
        iconAnchor: [9, 9],
    });

    L.marker([info.lat, info.lng], { icon: icon })
        .bindPopup('<strong>' + info.nama + '</strong><br><small>' + info.alamat + '</small>')
        .addTo(map)
        .openPopup();
</script>
@endpush