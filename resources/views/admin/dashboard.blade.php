@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid px-3">
    <div class="d-flex align-items-center mb-3 gap-2">
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-speedometer2"></i> Dashboard Admin</h1>
        <span class="badge bg-primary">Kecamatan Taman, Madiun</span>
    </div>
    <p class="text-muted small mb-4">Ringkasan data fasilitas kesehatan dan jaringan jalan (node &amp; edge) pada sistem.</p>

    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Faskes</div>
                            <div class="display-6 fw-bold">{{ number_format($stats['total_faskes']) }}</div>
                        </div>
                        <i class="bi bi-hospital fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Puskesmas</div>
                            <div class="display-6 fw-bold">{{ number_format($stats['total_puskesmas']) }}</div>
                        </div>
                        <i class="bi bi-buildings fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Klinik</div>
                            <div class="display-6 fw-bold">{{ number_format($stats['total_klinik']) }}</div>
                        </div>
                        <i class="bi bi-heart-pulse fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Rumah Sakit</div>
                            <div class="display-6 fw-bold">{{ number_format($stats['total_rumah_sakit']) }}</div>
                        </div>
                        <i class="bi bi-hospital fs-1 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Apotek</div>
                            <div class="display-6 fw-bold">{{ number_format($stats['total_apotek']) }}</div>
                        </div>
                        <i class="bi bi-capsule fs-1 text-purple" style="color:#6f42c1;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Node</div>
                            <div class="display-6 fw-bold">{{ number_format($stats['total_node']) }}</div>
                        </div>
                        <i class="bi bi-geo-alt fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Total Edge</div>
                            <div class="display-6 fw-bold">{{ number_format($stats['total_edge']) }}</div>
                        </div>
                        <i class="bi bi-shuffle fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.map') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-map"></i> Peta Admin
        </a>
        <a href="{{ route('admin.faskes.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-hospital"></i> Kelola Faskes
        </a>
        <a href="{{ route('admin.nodes.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-geo-alt"></i> Kelola Node
        </a>
        <a href="{{ route('admin.edges.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-shuffle"></i> Kelola Edge
        </a>
    </div>
</div>
@endsection