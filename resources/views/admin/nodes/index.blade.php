@extends('layouts.app')

@section('title', 'Kelola Titik / Node')

@section('content')
<div class="container-fluid px-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-0"><i class="bi bi-geo-alt"></i> Titik / Node Jalan</h1>
            <p class="text-muted small mb-0">Simpul jaringan jalan untuk Algoritma Dijkstra (tipe jalan) dan lokasi faskes (tipe faskes).</p>
        </div>
        <a href="{{ route('admin.nodes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Titik</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="map-container">
                <div id="admin-node-map"></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Faskes</th>
                            <th>Wilayah</th>
                            <th>Koordinat</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($titik as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="font-monospace small">{{ $t->kode ?? '-' }}</td>
                                <td class="fw-semibold">{{ $t->nama }}</td>
                                <td>
                                    @if($t->tipe === 'faskes')
                                        <span class="badge text-bg-danger">Faskes</span>
                                    @else
                                        <span class="badge text-bg-primary">Jalan</span>
                                    @endif
                                </td>
                                <td class="small">{{ $t->faskesInduk?->nama ?? '-' }}</td>
                                <td class="small">{{ $t->wilayah?->nama ?? '-' }}</td>
                                <td class="small text-muted font-monospace">{{ number_format($t->latitude, 5) }}, {{ number_format($t->longitude, 5) }}</td>
                                <td>
                                    <a href="{{ route('admin.nodes.edit', $t) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal-{{ $t->id }}"><i class="bi bi-trash"></i></button>
                                    <div class="modal fade" id="deleteModal-{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-sm"><div class="modal-content">
                                            <form action="{{ route('admin.nodes.destroy', $t) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="modal-body">Hapus node <strong>{{ $t->nama }}</strong>? Edge (jalur) terkait juga akan dihapus.</div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </div>
                                            </form>
                                        </div></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data titik.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $titik->links() }}</div>
    </div>
</div>
@endsection

@push('scripts')
@php
    $nodeList = $mapNodes->map(fn ($t) => [
        'id' => $t->id,
        'nama' => $t->nama,
        'kode' => $t->kode,
        'latitude' => (float) $t->latitude,
        'longitude' => (float) $t->longitude,
        'tipe' => $t->tipe,
    ]);
@endphp
<script>
    const nodes = @json($nodeList);

    const map = L.map('admin-node-map').setView([-7.6300, 111.5160], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    nodes.forEach(function (n) {
        const color = n.tipe === 'faskes' ? '#dc3545' : '#0d6efd';
        L.circleMarker([n.latitude, n.longitude], {
            radius: n.tipe === 'faskes' ? 8 : 5,
            color: color,
            fillColor: color,
            fillOpacity: n.tipe === 'faskes' ? 1 : 0.6,
            weight: 1.5,
        }).bindTooltip('<strong>' + n.nama + '</strong><br>' + (n.kode || '') + (n.tipe === 'faskes' ? '<br><em>faskes</em>' : '<br><em>jalan</em>'), { direction: 'top' }).addTo(map);
    });

    const bounds = L.latLngBounds(nodes.map(function (n) {
        return [n.latitude, n.longitude];
    }));

    if (bounds.isValid()) {
        map.fitBounds(bounds.pad(0.15));
    }
</script>
@endpush