@extends('layouts.app')

@section('title', 'Kelola Faskes')

@section('content')
<div class="container-fluid px-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-hospital"></i> Data Fasilitas Kesehatan</h1>
        <a href="{{ route('admin.faskes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Faskes
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Wilayah</th>
                            <th>Alamat</th>
                            <th>Koordinat</th>
                            <th>Node Terdekat</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faskes as $f)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $f->nama }}
                                    @if($f->jam_operasional)
                                        <div class="small text-muted"><i class="bi bi-clock"></i> {{ $f->jam_operasional }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background:{{ $f->kategori->warna }}">{{ $f->kategori->nama }}</span>
                                </td>
                                <td class="small">{{ $f->wilayah?->nama ?? '-' }}</td>
                                <td class="small">{{ $f->alamat }}</td>
                                <td class="small text-muted font-monospace">{{ number_format($f->latitude, 5) }}, {{ number_format($f->longitude, 5) }}</td>
                                <td class="small">
                                    @if($f->node)
                                        <span class="font-monospace">{{ $f->node->kode ?? $f->node->id }}</span>
                                        <div class="text-muted">{{ $f->node->tipe }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.faskes.show', $f) }}" class="btn btn-sm btn-outline-secondary"
                                       title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.faskes.edit', $f) }}" class="btn btn-sm btn-outline-primary"
                                       title="Ubah"><i class="bi bi-pencil"></i></a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $f->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <div class="modal fade" id="deleteModal-{{ $f->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.faskes.destroy', $f) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>Hapus <strong>{{ $f->nama }}</strong>? Data titik terkait juga akan dihapus.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data faskes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $faskes->links() }}
        </div>
    </div>
</div>
@endsection