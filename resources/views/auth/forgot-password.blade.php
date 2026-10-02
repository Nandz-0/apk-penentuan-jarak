@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
    <h5 class="mb-3 fw-bold">Lupa Password</h5>
    <p class="text-muted small mb-3">
        Masukkan email Anda. Kami akan mengirimkan tautan reset password.
    </p>

    @if(session('status'))
        <div class="alert alert-success py-2">
            <i class="bi bi-check-circle"></i> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Kirim Tautan Reset</button>
    </form>
@endsection