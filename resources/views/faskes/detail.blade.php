@extends('layouts.app')

@section('title', $faskes->nama.' - Detail Faskes')

@section('content')
<section class="bg-primary text-white py-4 mb-4">
    <div class="container">
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-hospital"></i> {{ $faskes->nama }}</h1>
        <p class="mb-0 opacity-75">
            <span class="badge" style="background:{{ $faskes->kategori->warna }}">{{ $faskes->kategori->nama }}</span>
            @if($faskes->wilayah)
                &nbsp;{{ $faskes->wilayah->nama }} {{ $faskes->wilayah->kota ? '('.$faskes->wilayah->kota.')' : '' }}
            @endif
        </p>
    </div>
</section>

<div class="container">
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

                        @if($jalan_terdekat)
                            <dt class="col-sm-4">Titik jalan terdekat</dt>
                            <dd class="col-sm-8">{{ $jalan_terdekat->nama }} ({{ $jalan_terdekat->kode }})</dd>
                        @endif
                    </dl>

                    @if($faskes->deskripsi)
                        <hr>
                        <h6 class="fw-semibold">Deskripsi</h6>
                        <p class="mb-0 text-muted">{{ $faskes->deskripsi }}</p>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('peta') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Peta</a>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm"><i class="bi bi-calculator"></i> Hitung Jarak Terdekat</a>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="map-container">
                        <div id="detail-map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const faskes = {
        nama: {{ Js::from($faskes->nama) }},
        kategori: {{ Js::from($faskes->kategori->nama) }},
        warna: {{ Js::from($faskes->kategori->warna) }},
        alamat: {{ Js::from($faskes->alamat) }},
        lat: {{ $faskes->latitude }},
        lng: {{ $faskes->longitude }}
    };

    const map = L.map('detail-map').setView([faskes.lat, faskes.lng], 15);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const icon = L.divIcon({
        html: '<div style="background:' + faskes.warna + ';border-radius:50%;width:18px;height:18px;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4);"></div>',
        className: '',
        iconSize: [18, 18],
        iconAnchor: [9, 9],
    });

    L.marker([faskes.lat, faskes.lng], { icon: icon })
        .bindPopup(
            '<strong>' + faskes.nama + '</strong><br>' +
            '<span class="badge" style="background:' + faskes.warna + '">' + faskes.kategori + '</span>' +
            '<br><small>' + faskes.alamat + '</small>'
        )
        .addTo(map)
        .openPopup();
</script>
@endpush