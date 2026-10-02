<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Sistem Rekomendasi Faskes'))</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-auth">

<div class="d-flex align-items-center min-vh-100 py-4">
    <div class="container" style="max-width: 480px;">
        <div class="text-center mb-4">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:64px;height:64px;">
                <i class="bi bi-hospital" style="font-size: 2rem;"></i>
            </div>
            <h1 class="h4 fw-bold mb-1">Sistem Rekomendasi Faskes</h1>
            <p class="text-muted small mb-0">Algoritma Dijkstra &mdash; Wilayah Madiun Taman</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>