@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold" style="color: var(--text-main);"><i class="bi bi-speedometer2 me-2" style="color: var(--primary-neon);"></i>Dashboard</h2>
        <p style="color: var(--text-muted);">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>! Kelola akun dan langganan Anda dari sini.</p>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #10b981;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Welcome Card -->
        <div class="col-12">
            <div class="p-4 rounded-4" style="background: linear-gradient(135deg, rgba(139, 94, 60, 0.08) 0%, rgba(92, 82, 67, 0.05) 100%); border: 1px solid var(--border-color);">
                <div class="d-flex align-items-center gap-4">
                    <div class="fs-1" style="color: var(--primary-neon);"><i class="bi bi-emoji-smile-fill"></i></div>
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--text-main);">Selamat Datang di Flustra!</h4>
                        <p class="mb-0" style="color: var(--text-muted);">Anda berhasil masuk ke akun Anda. Gunakan menu navigasi untuk mengelola langganan dan melihat paket yang tersedia.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="p-4 rounded-4 h-100" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-center">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: rgba(139, 94, 60, 0.1);">
                            <i class="bi bi-credit-card-2-front fs-4" style="color: var(--primary-neon);"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: var(--text-main);">Langganan Saya</h5>
                    <p class="small mb-3" style="color: var(--text-muted);">Lihat status paket aktif dan riwayat transaksi pembayaran Anda.</p>
                    <a href="{{ route('subscription.index') }}" class="btn btn-neon-primary py-2 px-4 rounded-3">
                        <i class="bi bi-arrow-right me-1"></i>Kelola Langganan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="p-4 rounded-4 h-100" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-center">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: rgba(139, 94, 60, 0.1);">
                            <i class="bi bi-tags fs-4" style="color: var(--primary-neon);"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: var(--text-main);">Lihat Paket</h5>
                    <p class="small mb-3" style="color: var(--text-muted);">Jelajahi berbagai paket langganan premium yang tersedia untuk Anda.</p>
                    <a href="{{ route('plans.index') }}" class="btn btn-neon-secondary py-2 px-4 rounded-3">
                        <i class="bi bi-eye me-1"></i>Lihat Paket
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="p-4 rounded-4 h-100" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-center">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: rgba(139, 94, 60, 0.1);">
                            <i class="bi bi-person-circle fs-4" style="color: var(--primary-neon);"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: var(--text-main);">Profil Saya</h5>
                    <p class="small mb-3" style="color: var(--text-muted);">Perbarui informasi akun, email, dan kata sandi Anda.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-neon-secondary py-2 px-4 rounded-3">
                        <i class="bi bi-pencil-square me-1"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
