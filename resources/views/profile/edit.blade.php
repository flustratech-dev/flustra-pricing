@extends('layouts.app')

@push('styles')
<style>
    .profile-header {
        position: relative;
        padding: 5rem 0 3rem 0;
    }

    .premium-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .card-ambient-glow {
        position: absolute;
        top: -60px;
        left: -60px;
        width: 130px;
        height: 130px;
        border-radius: 9999px;
        background: radial-gradient(circle, rgba(139, 94, 60, 0.08) 0%, rgba(139, 94, 60, 0) 70%);
        pointer-events: none;
        z-index: 0;
    }

    .form-control-premium {
        background: var(--bg-color) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-main) !important;
        padding: 0.7rem 1rem !important;
        border-radius: 12px !important;
        transition: all 0.3s ease !important;
    }

    .form-control-premium:focus {
        background: #ffffff !important;
        border-color: var(--primary-neon) !important;
        box-shadow: 0 0 0 4px rgba(139, 94, 60, 0.1) !important;
    }
</style>
@endpush

@section('content')
<div class="container pb-5">

    <!-- Header Section -->
    <div class="profile-header text-center">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-4 reveal-scale">
            <img src="{{ asset('images/flustraa.png') }}" alt="Flustra Logo" style="height: 26px; width: auto; object-fit: contain;">
            <span class="font-brand tracking-wider" style="color: var(--text-main); font-weight: 800; font-size: 0.95rem; letter-spacing: 1.2px;">FLUSTRA</span>
        </div>

        <h1 class="display-3 fw-extrabold mb-3 tracking-tight" style="color: var(--text-main);">Pengaturan Profil</h1>
        <p class="lead max-w-2xl mx-auto mb-4" style="max-width: 600px; margin: 0 auto; color: var(--text-muted) !important; font-size: 0.8rem; line-height: 1.5;">
            "Perbarui informasi personal Anda, atur preferensi keamanan kata sandi, atau kelola opsi kepemilikan akun Anda secara aman."
        </p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #10b981;">
            <i class="bi bi-check-circle-fill me-2"></i><span class="fw-medium">Profil berhasil diperbarui!</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #10b981;">
            <i class="bi bi-check-circle-fill me-2"></i><span class="fw-medium">Kata sandi berhasil diperbarui!</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Update Profile Information -->
        <div class="col-12 col-lg-6">
            <div class="premium-card p-4">
                <div class="card-ambient-glow"></div>
                <div class="z-3 position-relative">
                    <h5 class="fw-semibold mb-1" style="color: var(--text-main); font-family: 'Outfit', sans-serif;">Informasi Profil</h5>
                    <p class="small mb-4" style="color: var(--text-muted);">Perbarui nama dan alamat email akun Anda.</p>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-medium small" style="color: var(--text-main);">Nama</label>
                                <input type="text" class="form-control form-control-premium @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="username" class="form-label fw-medium small" style="color: var(--text-main);">Username</label>
                                <input type="text" class="form-control form-control-premium @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-medium small" style="color: var(--text-main);">Email</label>
                                <input type="email" class="form-control form-control-premium @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-medium small" style="color: var(--text-main);">No. Telepon <span style="opacity: 0.75;">(Opsional)</span></label>
                                <input type="tel" class="form-control form-control-premium @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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

                        <button type="submit" class="btn btn-neon-primary py-2 px-4 rounded-3 mt-2">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Password -->
        <div class="col-12 col-lg-6">
            <div class="premium-card p-4">
                <div class="card-ambient-glow"></div>
                <div class="z-3 position-relative">
                    <h5 class="fw-semibold mb-1" style="color: var(--text-main); font-family: 'Outfit', sans-serif;">Ubah Kata Sandi</h5>
                    <p class="small mb-4" style="color: var(--text-muted);">Pastikan akun Anda menggunakan kata sandi yang kuat dan unik.</p>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-medium small" style="color: var(--text-main);">Kata Sandi Saat Ini</label>
                            <input type="password" class="form-control form-control-premium @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium small" style="color: var(--text-main);">Kata Sandi Baru</label>
                            <input type="password" class="form-control form-control-premium @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-medium small" style="color: var(--text-main);">Konfirmasi Kata Sandi</label>
                            <input type="password" class="form-control form-control-premium" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-neon-primary py-2 px-4 rounded-3 mt-2">
                            <i class="bi bi-shield-lock me-1"></i>Perbarui Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="col-12">
            <div class="premium-card p-4" style="background: rgba(239, 68, 68, 0.03); border: 1px solid rgba(239, 68, 68, 0.15);">
                <div class="card-ambient-glow" style="background: radial-gradient(circle, rgba(239, 68, 68, 0.08) 0%, rgba(239, 68, 68, 0) 70%);"></div>
                <div class="z-3 position-relative">
                    <h5 class="fw-semibold mb-1 text-danger" style="font-family: 'Outfit', sans-serif;">Hapus Akun</h5>
                    <p class="small mb-3" style="color: var(--text-muted); line-height: 1.5;">Setelah akun Anda dihapus, semua data dan riwayat langganan akan dihapus secara permanen. Pastikan Anda telah menyimpan informasi penting sebelum menghapus akun.</p>

                    <button type="button" class="btn btn-outline-danger py-2 px-4 rounded-3" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash me-1"></i>Hapus Akun Saya
                    </button>

                    <!-- Delete Account Modal -->
                    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0" style="background: var(--bg-color);">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-semibold text-danger" id="deleteAccountModalLabel" style="font-family: 'Outfit', sans-serif;">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Akun
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('profile.destroy') }}">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">
                                        <p class="small" style="color: var(--text-muted); line-height: 1.5;">Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi.</p>
                                        <div class="mb-3">
                                            <label for="delete_password" class="form-label fw-medium small" style="color: var(--text-main);">Kata Sandi</label>
                                            <input type="password" class="form-control form-control-premium @error('password', 'userDeletion') is-invalid @enderror" id="delete_password" name="password" placeholder="Masukkan kata sandi Anda" required>
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

</div>
@endsection

