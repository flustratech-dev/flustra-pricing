@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold" style="color: var(--text-main);"><i class="bi bi-person-circle me-2" style="color: var(--primary-neon);"></i>Profil Saya</h2>
        <p style="color: var(--text-muted);">Perbarui informasi akun, email, dan kata sandi Anda.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #10b981;">
            <i class="bi bi-check-circle-fill me-2"></i><strong>Profil berhasil diperbarui!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #10b981;">
            <i class="bi bi-check-circle-fill me-2"></i><strong>Kata sandi berhasil diperbarui!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Update Profile Information -->
        <div class="col-12 col-lg-6">
            <div class="p-4 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <h5 class="fw-bold mb-1" style="color: var(--text-main);">Informasi Profil</h5>
                <p class="small mb-4" style="color: var(--text-muted);">Perbarui nama dan alamat email akun Anda.</p>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small" style="color: var(--text-main);">Nama</label>
                        <input type="text" class="form-control rounded-3 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                            style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-main); padding: 0.7rem 1rem;">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small" style="color: var(--text-main);">Email</label>
                        <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                            style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-main); padding: 0.7rem 1rem;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mb-3">
                            <p class="small" style="color: var(--text-muted);">
                                Email Anda belum diverifikasi.
                                <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm p-0" style="color: var(--primary-neon);">Klik di sini untuk mengirim ulang link verifikasi.</button>
                                </form>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="small text-success mt-1">Link verifikasi baru telah dikirim ke email Anda.</p>
                            @endif
                        </div>
                    @endif

                    <button type="submit" class="btn btn-neon-primary py-2 px-4 rounded-3">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-12 col-lg-6">
            <div class="p-4 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <h5 class="fw-bold mb-1" style="color: var(--text-main);">Ubah Kata Sandi</h5>
                <p class="small mb-4" style="color: var(--text-muted);">Pastikan akun Anda menggunakan kata sandi yang kuat dan unik.</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold small" style="color: var(--text-main);">Kata Sandi Saat Ini</label>
                        <input type="password" class="form-control rounded-3 @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password"
                            style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-main); padding: 0.7rem 1rem;">
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold small" style="color: var(--text-main);">Kata Sandi Baru</label>
                        <input type="password" class="form-control rounded-3 @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" autocomplete="new-password"
                            style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-main); padding: 0.7rem 1rem;">
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold small" style="color: var(--text-main);">Konfirmasi Kata Sandi</label>
                        <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                            style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-main); padding: 0.7rem 1rem;">
                    </div>

                    <button type="submit" class="btn btn-neon-primary py-2 px-4 rounded-3">
                        <i class="bi bi-shield-lock me-1"></i>Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="col-12">
            <div class="p-4 rounded-4" style="background: rgba(239, 68, 68, 0.03); border: 1px solid rgba(239, 68, 68, 0.15);">
                <h5 class="fw-bold mb-1 text-danger">Hapus Akun</h5>
                <p class="small mb-3" style="color: var(--text-muted);">Setelah akun Anda dihapus, semua data dan riwayat langganan akan dihapus secara permanen. Pastikan Anda telah menyimpan informasi penting sebelum menghapus akun.</p>

                <button type="button" class="btn btn-outline-danger py-2 px-4 rounded-3" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="bi bi-trash me-1"></i>Hapus Akun Saya
                </button>

                <!-- Delete Account Modal -->
                <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0" style="background: var(--bg-color);">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-danger" id="deleteAccountModalLabel">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Akun
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('delete')
                                <div class="modal-body">
                                    <p class="small" style="color: var(--text-muted);">Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi.</p>
                                    <div class="mb-3">
                                        <label for="delete_password" class="form-label fw-semibold small" style="color: var(--text-main);">Kata Sandi</label>
                                        <input type="password" class="form-control rounded-3 @error('password', 'userDeletion') is-invalid @enderror" id="delete_password" name="password" placeholder="Masukkan kata sandi Anda" required
                                            style="background: var(--surface-color); border: 1px solid var(--border-color); color: var(--text-main); padding: 0.7rem 1rem;">
                                        @error('password', 'userDeletion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-neon-secondary py-2 px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger py-2 px-4 rounded-3">
                                        <i class="bi bi-trash me-1"></i>Hapus Akun
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
