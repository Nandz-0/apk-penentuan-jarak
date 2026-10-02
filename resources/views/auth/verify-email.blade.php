@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
    <h5 class="mb-3 fw-bold">Verifikasi Email Anda</h5>
    <p class="text-muted small mb-3">
        Terima kasih telah mendaftar. Sebelum memulai, harap verifikasi email Anda dengan mengklik tautan yang kami kirim.
        Jika tidak menerima email, klik tombol di bawah ini.
    </p>

    @if(session('status') == 'verification-link-sent')
        <div class="alert alert-success py-2">
            <i class="bi bi-check-circle"></i> Tautan verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Kirim Ulang Email Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Keluar</button>
        </form>
    </div>
@endsection