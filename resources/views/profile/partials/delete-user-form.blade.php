<section>
    <h2 class="h5 fw-bold mb-1 text-danger">Hapus Akun</h2>
    <p class="text-muted small mb-4">
        Setelah akun dihapus, seluruh data Anda akan terhapus permanen. Sebelum menghapus, simpan data yang ingin Anda pertahankan.
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        <i class="bi bi-trash"></i> Hapus Akun
    </button>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title">Yakin ingin menghapus akun?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">
                            Setelah akun dihapus, seluruh data Anda akan dihapus permanen. Masukkan password untuk konfirmasi.
                        </p>
                        <input id="password" name="password" type="password" class="form-control"
                               placeholder="Password" autocomplete="current-password">
                        @error('password', 'userDeletion')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>