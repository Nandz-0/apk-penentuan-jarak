@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('admin.kategori-faskes.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 fw-bold mb-0">{{ $title }}</h1>
    </div>

    <div class="card shadow-sm" style="max-width: 560px;">
        <div class="card-body">
            <form action="{{ $formAction }}" method="POST">
                @csrf
                @method($method)

                <div class="mb-3">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $kategori->nama ?? '') }}" required maxlength="100">
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                           value="{{ old('deskripsi', $kategori->deskripsi ?? '') }}" maxlength="255">
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Warna Marker (hex) <span class="text-danger">*</span></label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="color" name="warna" value="{{ old('warna', $kategori->warna ?? '#0d6efd') }}"
                               class="form-control form-control-color" style="width:60px;">
                        <input type="text" name="warna_text" class="form-control"
                               value="{{ old('warna', $kategori->warna ?? '#0d6efd') }}" maxlength="20"
                               onchange="this.form.warna.value=this.value">
                    </div>
                    @error('warna')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.kategori-faskes.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection