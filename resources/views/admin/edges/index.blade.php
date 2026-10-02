@extends('layouts.app')

@section('title', 'Kelola Edge')

@section('content')
<div class="container-fluid px-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-0"><i class="bi bi-shuffle"></i> Edge (Jalur)</h1>
            <p class="text-muted small mb-0">Hubungan (edge) antar node; jarak dalam meter menjadi bobot Algoritma Dijkstra.</p>
        </div>
        <a href="{{ route('admin.edges.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Edge</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Nama Jalan</th>
                            <th>Jarak (m)</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jalur as $j)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $j->nodeAwal->nama }}
                                    <div class="small text-muted font-monospace">{{ $j->nodeAwal->kode }}</div>
                                </td>
                                <td>{{ $j->nodeTujuan->nama }}
                                    <div class="small text-muted font-monospace">{{ $j->nodeTujuan->kode }}</div>
                                </td>
                                <td class="small">{{ $j->nama_jalan ?? '-' }}</td>
                                <td class="fw-semibold">{{ number_format($j->jarak) }}</td>
                                <td>
                                    <a href="{{ route('admin.edges.edit', $j) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal-{{ $j->id }}"><i class="bi bi-trash"></i></button>
                                    <div class="modal fade" id="deleteModal-{{ $j->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-sm"><div class="modal-content">
                                            <form action="{{ route('admin.edges.destroy', $j) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <div class="modal-body">Hapus edge ini? (edge arah sebaliknya juga akan dihapus)</div>
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
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data edge.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $jalur->links() }}</div>
    </div>
</div>
@endsection