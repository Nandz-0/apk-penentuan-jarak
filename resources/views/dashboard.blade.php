@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="bg-primary text-white py-4 mb-4">
    <div class="container-fluid px-3">
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-speedometer2"></i> Dashboard</h1>
        <p class="mb-0 opacity-75">Ringkasan data pada sistem rekomendasi faskes.</p>
    </div>
</section>

<div class="container-fluid px-3">
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm text-bg-primary">
                <div class="card-body text-center p-3">
                    <div class="fs-2 fw-bold">{{ $ticks['total_faskes'] }}</div>
                    <div class="opacity-75">Fasilitas Kesehatan</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm text-bg-success">
                <div class="card-body text-center p-3">
                    <div class="fs-2 fw-bold">{{ $ticks['total_kategori'] }}</div>
                    <div class="opacity-75">Kategori</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm text-bg-warning">
                <div class="card-body text-center p-3">
                    <div class="fs-2 fw-bold">{{ $ticks['total_titik'] }}</div>
                    <div class="opacity-75">Titik / Node</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm text-bg-danger">
                <div class="card-body text-center p-3">
                    <div class="fs-2 fw-bold">{{ $ticks['total_jalur'] }}</div>
                    <div class="opacity-75">Jalur Jalan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-pie-chart"></i> Jumlah Faskes per Kategori
        </div>
        <div class="card-body">
            @foreach($faskesPerKategori as $kategori)
                <div class="d-flex align-items-center mb-3">
                    <span class="badge me-3" style="background:{{ $kategori->warna }}; min-width:120px;">{{ $kategori->nama }}</span>
                    <div class="progress flex-grow-1" style="height:10px;">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $ticks['total_faskes'] > 0 ? ($kategori->faskes_count / $ticks['total_faskes'] * 100) : 0 }}%; background:{{ $kategori->warna }}">
                        </div>
                    </div>
                    <span class="ms-3 fw-semibold">{{ $kategori->faskes_count }}</span>
                </div>
            @endforeach

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.faskes.index') }}" class="btn btn-outline-primary btn-sm mt-2">
                    <i class="bi bi-gear"></i> Kelola Data Faskes
                </a>
            @endif
        </div>
    </div>
</div>
@endsection