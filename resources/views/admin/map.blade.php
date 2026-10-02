@extends('layouts.app')

@section('title', 'Peta Admin')

@section('content')
<div class="container-fluid px-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-0"><i class="bi bi-map"></i> Peta Admin</h1>
            <p class="text-muted small mb-0">Visualisasi node jalan, faskes, dan edge jaringan untuk Algoritma Dijkstra.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.nodes.index') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-geo-alt"></i> Kelola Node</a>
            <a href="{{ route('admin.edges.index') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-shuffle"></i> Kelola Edge</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0 position-relative">
            <div id="admin-map" style="height: 72vh; min-height: 480px;"></div>

            <div class="p-3 bg-white border-top small" id="map-legend">
                <span class="me-3"><span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:#0d6efd;vertical-align:middle;"></span> Node Jalan</span>
                <span class="me-3"><span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:#dc3545;vertical-align:middle;"></span> Faskes</span>
                <span class="me-3"><span style="display:inline-block;width:18px;height:0;border-top:3px solid #6c757d;vertical-align:middle;"></span> Edge (jalur)</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body py-3">
                <div class="text-muted small">Node Jalan</div>
                <div class="h4 fw-bold mb-0">{{ $nodes->where('tipe', 'jalan')->count() }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body py-3">
                <div class="text-muted small">Faskes</div>
                <div class="h4 fw-bold mb-0">{{ $nodes->where('tipe', 'faskes')->count() }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body py-3">
                <div class="text-muted small">Edge</div>
                <div class="h4 fw-bold mb-0">{{ $edges->count() }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body py-3">
                <div class="text-muted small">Data Faskes</div>
                <div class="h4 fw-bold mb-0">{{ $faskesCount }}</div>
            </div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@php
    $nodeMarkers = $nodes->map(fn ($t) => [
        'id' => $t->id,
        'nama' => $t->nama,
        'kode' => $t->kode,
        'lat' => (float) $t->latitude,
        'lng' => (float) $t->longitude,
        'tipe' => $t->tipe,
        'faskes' => $t->faskesInduk?->nama,
    ]);

    $edgePairs = [];
    foreach ($edges as $e) {
        if (! $e->nodeAwal || ! $e->nodeTujuan) {
            continue;
        }

        $a = $e->nodeAwal;
        $b = $e->nodeTujuan;
        $key = min($a->id, $b->id).'-'.max($a->id, $b->id);

        if (! isset($edgePairs[$key])) {
            $edgePairs[$key] = [
                'a' => [(float) $a->latitude, (float) $a->longitude],
                'b' => [(float) $b->latitude, (float) $b->longitude],
                'jarak' => (int) $e->jarak,
                'nama' => $e->nama_jalan,
            ];
        }
    }
@endphp
<script>
    const nodes = @json($nodeMarkers);
    const edges = @json(array_values($edgePairs));

    const map = L.map('admin-map').setView([-7.6300, 111.5160], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const edgeLayer = L.layerGroup().addTo(map);
    const nodeLayer = L.layerGroup().addTo(map);
    const faskesLayer = L.layerGroup().addTo(map);

    edges.forEach(function (e) {
        L.polyline([e.a, e.b], { color: '#6c757d', weight: 2, opacity: 0.7 })
            .bindTooltip('Edge ' + e.jarak + ' m' + (e.nama ? ' - ' + e.nama : ''))
            .addTo(edgeLayer);
    });

    nodes.forEach(function (n) {
        if (n.tipe === 'faskes') {
            const icon = L.divIcon({
                html: '<div title="Faskes" style="background:#dc3545;border:2px solid #fff;border-radius:4px;width:16px;height:16px;box-shadow:0 1px 4px rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:bold;">+</div>',
                className: '',
                iconSize: [16, 16],
                iconAnchor: [8, 8],
            });

            L.marker([n.lat, n.lng], { icon: icon })
                .bindPopup('<strong>' + (n.faskes || n.nama) + '</strong><br><small>Faskes - ' + (n.kode || '') + '</small>')
                .bindTooltip(n.faskes || n.nama, { direction: 'top' })
                .addTo(faskesLayer);
        } else {
            L.circleMarker([n.lat, n.lng], {
                radius: 5,
                color: '#0d6efd',
                fillColor: '#0d6efd',
                fillOpacity: 0.85,
                weight: 1.5,
            })
                .bindTooltip((n.kode ? n.kode + ' - ' : '') + n.nama, { direction: 'top' })
                .addTo(nodeLayer);
        }
    });

    L.control.layers(null, {
        'Edge (jalur)': edgeLayer,
        'Node Jalan': nodeLayer,
        'Faskes': faskesLayer,
    }, { collapsed: false }).addTo(map);

    const bounds = L.latLngBounds(nodes.map(function (n) { return [n.lat, n.lng]; }));
    if (bounds.isValid()) {
        map.fitBounds(bounds.pad(0.15));
    }
</script>
@endpush