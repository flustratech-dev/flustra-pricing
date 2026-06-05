@extends('layouts.app')

@push('styles')
<style>
    .dashboard-header {
        position: relative;
        padding: 5rem 0 3rem 0;
    }

    .premium-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .premium-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 30px rgba(139, 94, 60, 0.08);
        border-color: var(--primary-neon);
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

    .action-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(139, 94, 60, 0.1);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .premium-card:hover .action-icon-wrapper {
        transform: scale(1.15) rotate(6deg);
        background: rgba(139, 94, 60, 0.15);
    }
</style>
@endpush

@section('content')
<div class="container pb-5">

    <!-- Header Section -->
    <div class="dashboard-header text-center">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-4 reveal-scale">
            <img src="{{ asset('images/flustraa.png') }}" alt="Flustra Logo" style="height: 26px; width: auto; object-fit: contain;">
            <span class="font-brand tracking-wider" style="color: var(--text-main); font-weight: 800; font-size: 0.95rem; letter-spacing: 1.2px;">FLUSTRA</span>
        </div>

        <h1 class="display-3 fw-extrabold mb-3 tracking-tight" style="color: var(--text-main);">Dashboard Akun Anda</h1>
        <p class="lead max-w-2xl mx-auto mb-4" style="max-width: 600px; margin: 0 auto; color: var(--text-muted) !important; font-size: 0.8rem; line-height: 1.5;">
            "Selamat datang kembali, {{ auth()->user()->name }}! Kelola akun, pantau status langganan, dan perbarui profil Anda dengan mudah di satu tempat."
        </p>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-5" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #10b981;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Welcome Card -->
        <div class="col-12">
            <div class="premium-card p-4">
                <div class="card-ambient-glow"></div>
                <div class="d-flex align-items-center gap-4 z-3 position-relative">
                    <div class="fs-1" style="color: var(--primary-neon);"><i class="bi bi-emoji-smile-fill"></i></div>
                    <div>
                        <h4 class="fw-semibold mb-1" style="color: var(--text-main);">Selamat Datang di Flustra!</h4>
                        <p class="mb-0 small" style="color: var(--text-muted); line-height: 1.5;">Anda berhasil masuk ke akun Anda. Gunakan menu navigasi untuk mengelola langganan dan melihat paket yang tersedia.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="premium-card p-4 text-center">
                <div class="card-ambient-glow"></div>
                <div class="z-3 position-relative d-flex flex-column h-100 justify-content-between">
                    <div>
                        <div class="mb-3">
                            <span class="action-icon-wrapper">
                                <i class="bi bi-credit-card-2-front fs-4" style="color: var(--primary-neon);"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-2" style="color: var(--text-main);">Langganan Saya</h5>
                        <p class="small mb-4" style="color: var(--text-muted); line-height: 1.5;">Lihat status paket aktif dan riwayat transaksi pembayaran Anda.</p>
                    </div>
                    <div>
                        <a href="{{ route('subscription.index') }}" class="btn btn-neon-primary py-2 px-4 rounded-3 w-100">
                            <i class="bi bi-arrow-right me-1"></i>Kelola Langganan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="premium-card p-4 text-center">
                <div class="card-ambient-glow"></div>
                <div class="z-3 position-relative d-flex flex-column h-100 justify-content-between">
                    <div>
                        <div class="mb-3">
                            <span class="action-icon-wrapper">
                                <i class="bi bi-tags fs-4" style="color: var(--primary-neon);"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-2" style="color: var(--text-main);">Lihat Paket</h5>
                        <p class="small mb-4" style="color: var(--text-muted); line-height: 1.5;">Jelajahi berbagai paket langganan premium yang tersedia untuk Anda.</p>
                    </div>
                    <div>
                        <a href="{{ route('plans.index') }}" class="btn btn-neon-secondary py-2 px-4 rounded-3 w-100">
                            <i class="bi bi-eye me-1"></i>Lihat Paket
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="premium-card p-4 text-center">
                <div class="card-ambient-glow"></div>
                <div class="z-3 position-relative d-flex flex-column h-100 justify-content-between">
                    <div>
                        <div class="mb-3">
                            <span class="action-icon-wrapper">
                                <i class="bi bi-person-circle fs-4" style="color: var(--primary-neon);"></i>
                            </span>
                        </div>
                        <h5 class="fw-semibold mb-2" style="color: var(--text-main);">Profil Saya</h5>
                        <p class="small mb-4" style="color: var(--text-muted); line-height: 1.5;">Perbarui informasi akun, email, dan kata sandi Anda.</p>
                    </div>
                    <div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-neon-secondary py-2 px-4 rounded-3 w-100">
                            <i class="bi bi-pencil-square me-1"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

