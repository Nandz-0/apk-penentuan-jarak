@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('admin.nodes.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 fw-bold mb-0">{{ $title }}</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ $formAction }}" method="POST">
                @csrf
                @method($method)

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode Titik</label>
                        <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                               value="{{ old('kode', $titik->kode ?? '') }}" maxlength="20"
                               placeholder="cth: J21 / F3">
                        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Nama Titik <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $titik->nama ?? '') }}" required maxlength="150">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 pt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe" id="tipe_jalan" value="jalan"
                                       @checked(old('tipe', $titik->tipe ?? 'jalan') === 'jalan')>
                                <label class="form-check-label" for="tipe_jalan">Jalan (node jaringan)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipe" id="tipe_faskes" value="faskes"
                                       @checked(old('tipe', $titik->tipe ?? null) === 'faskes')>
                                <label class="form-check-label" for="tipe_faskes">Faskes</label>
                            </div>
                        </div>
                        @error('tipe')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Terkait Faskes (jika tipe = faskes)</label>
                        <select name="faskes_id" class="form-select @error('faskes_id') is-invalid @enderror">
                            <option value="">-- Tidak ada --</option>
                            @foreach($faskesList as $f)
                                <option value="{{ $f->id }}" @selected(old('faskes_id', $titik->faskes_id ?? null) == $f->id)>{{ $f->nama }}</option>
                            @endforeach
                        </select>
                        @error('faskes_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Wilayah</label>
                        <select name="wilayah_id" class="form-select @error('wilayah_id') is-invalid @enderror">
                            <option value="">-- Pilih wilayah --</option>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}" @selected(old('wilayah_id', $titik->wilayah_id ?? null) == $w->id)>{{ $w->nama }} {{ $w->kota ? '('.$w->kota.')' : '' }}</option>
                            @endforeach
                        </select>
                        @error('wilayah_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @include('admin._coordinate-picker', [
                        'latValue' => old('latitude', $titik->latitude ?? -7.6300),
                        'lngValue' => old('longitude', $titik->longitude ?? 111.5160),
                    ])

                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.nodes.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection