@extends('layouts.app')

@section('title', 'Kelola Kategori Faskes')

@section('content')
<div class="container-fluid px-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-tags"></i> Kategori Faskes</h1>
        <a href="{{ route('admin.kategori-faskes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
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
                            <th>Deskripsi</th>
                            <th>Warna Marker</th>
                            <th>Jumlah Faskes</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoris as $k)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $k->nama }}</td>
                                <td class="small">{{ $k->deskripsi ?? '-' }}</td>
                                <td><span class="badge" style="background:{{ $k->warna }}">{{ $k->warna }}</span></td>
                                <td>{{ $k->faskes_count }}</td>
                                <td>
                                    <a href="{{ route('admin.kategori-faskes.edit', $k) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    @if($k->faskes_count === 0)
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal-{{ $k->id }}"><i class="bi bi-trash"></i></button>
                                        <div class="modal fade" id="deleteModal-{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-sm"><div class="modal-content">
                                                <form action="{{ route('admin.kategori-faskes.destroy', $k) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <div class="modal-body">Hapus kategori <strong>{{ $k->nama }}</strong>?</div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </div>
                                                </form>
                                            </div></div>
                                        </div>
                                    @else
                                        <span class="text-muted small" title="Tidak dapat dihapus karena masih dipakai">terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection