@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    @if(session('status'))
        <div class="alert alert-success py-2">
            <i class="bi bi-check-circle"></i> {{ session('status') }}
        </div>
    @endif

    <h5 class="mb-3 fw-bold">Masuk ke Akun</h5>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                   value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                   required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
            <label class="form-check-label small" for="remember_me">Ingat saya</label>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            @if(Route::has('password.request'))
                <a class="small text-decoration-none" href="{{ route('password.request') }}">Lupa password?</a>
            @endif
            <button type="submit" class="btn btn-primary ms-3">Masuk  <i class="bi bi-box-arrow-in-right"></i></button>
        </div>
    </form>

    <hr class="my-3">
    <p class="text-center small mb-0 text-muted">
        Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
    </p>
@endsection