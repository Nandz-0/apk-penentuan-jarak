@extends('layouts.guest')

@section('title', 'Konfirmasi Password')

@section('content')
    <h5 class="mb-3 fw-bold">Konfirmasi Password</h5>
    <p class="text-muted small mb-3">
        Ini adalah area aman. Harap konfirmasi password Anda sebelum melanjutkan.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                   required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Konfirmasi</button>
    </form>
@endsection