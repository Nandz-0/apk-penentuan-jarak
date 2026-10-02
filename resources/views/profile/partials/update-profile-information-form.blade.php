<section>
    <h2 class="h5 fw-bold mb-1">Informasi Profil</h2>
    <p class="text-muted small mb-4">Perbarui informasi profil dan email akun Anda.</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="small text-warning mt-1">
                    Email Anda belum terverifikasi.
                    <button form="send-verification" class="btn btn-link btn-sm p-0">Kirim ulang email verifikasi.</button>
                </div>
                @if(session('status') === 'verification-link-sent')
                    <div class="text-success small mt-1">Tautan verifikasi baru telah dikirim ke email Anda.</div>
                @endif
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
            @if(session('status') === 'profile-updated')
                <span class="text-success small fw-semibold"><i class="bi bi-check-circle"></i> Tersimpan.</span>
            @endif
        </div>
    </form>
</section>