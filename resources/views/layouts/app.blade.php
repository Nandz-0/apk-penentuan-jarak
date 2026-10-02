<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Sistem Rekomendasi Faskes'))</title>

    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/leaflet/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light-subtle">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid px-3">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="bi bi-hospital"></i> FASKES MADIUN
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}" href="{{ route('home') }}#cari">Cari Faskes</a></li>

                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.faskes.*') ? 'active' : '' }}" href="{{ route('admin.faskes.index') }}">Faskes</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.nodes.*') ? 'active' : '' }}" href="{{ route('admin.nodes.index') }}">Node</a></li>
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.edges.*') ? 'active' : '' }}" href="{{ route('admin.edges.index') }}">Edge</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                @else
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link" style="background:none;border:0;">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="container-fluid px-3 mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container-fluid px-3 mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

<main class="py-4">
    @yield('content')
</main>

<footer class="border-top bg-white py-3 mt-4">
    <div class="container-fluid px-3 text-center text-muted small">
        Aplikasi Penentuan Jarak Terdekat untuk Rekomendasi Faskes Menggunakan Algoritma Dijkstra di Wilayah Madiun Taman &mdash; Laravel 10 &amp; Leaflet.js
    </div>
</footer>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
@stack('scripts')
</body>
</html>