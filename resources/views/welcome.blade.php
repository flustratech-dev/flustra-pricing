@extends('layouts.app')

@push('styles')
<style>
    .hero-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .hero-glow {
        position: absolute;
        top: 20%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 800px;
        height: 400px;
        background: radial-gradient(circle, rgba(196, 168, 124, 0.08) 0%, rgba(139, 115, 85, 0.05) 50%, rgba(251, 248, 243, 0) 80%);
        z-index: -1;
        pointer-events: none;
    }

    .text-gradient {
        background: linear-gradient(135deg, var(--text-main) 0%, var(--primary-neon) 50%, var(--btn-hover) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="hero-section text-center">
    <div class="hero-glow"></div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <span class="badge px-3 py-2 rounded-pill mb-4" style="font-size: 0.85rem; background-color: var(--surface-accent); color: var(--text-main); border: 1px solid var(--border-color);">
                    <i class="bi bi-rocket-takeoff-fill me-2 animate-bounce" style="color: var(--primary-neon);"></i>Sistem Berlangganan Flustra Baru
                </span>
                
                <h1 class="display-2 fw-extrabold mb-3 tracking-tight" style="color: var(--text-main);">
                    Kelola Aset & Finansial dengan <span class="text-gradient">Fitur Premium</span>
                </h1>
                
                <p class="lead mb-5 mx-auto" style="max-width: 650px; color: var(--text-muted); font-size: 1.15rem; line-height: 1.75;">
                    Selamat datang di Flustra Pricing Suite. Akses laporan keuangan mendalam, portofolio otomatis, dompet bersama keluarga, hingga pembukuan kas enterprise dalam platform yang modern dan indah.
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('plans.index') }}" class="btn btn-neon-primary btn-lg px-5 py-3 rounded-4">
                        <i class="bi bi-tag-fill me-2"></i>Lihat Paket Harga
                    </a>
                    @auth
                        <a href="{{ route('subscription.index') }}" class="btn btn-neon-secondary btn-lg px-4 py-3 rounded-4">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-neon-secondary btn-lg px-4 py-3 rounded-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Akun
                        </a>
                    @endauth
                </div>

                <div class="row g-4 mt-5 pt-5 justify-content-center">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-4" style="background: var(--surface-color); border: 1px solid var(--border-color);">
                            <i class="bi bi-shield-fill-check text-success fs-3 mb-2 d-block"></i>
                            <span class="fw-bold d-block small mb-1" style="color: var(--text-main);">Keamanan Enkripsi</span>
                            <span class="text-muted small" style="font-size: 0.75rem;">Proteksi data aset ganda</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-4" style="background: var(--surface-color); border: 1px solid var(--border-color);">
                            <i class="bi bi-graph-up-arrow fs-3 mb-2 d-block" style="color: var(--primary-neon);"></i>
                            <span class="fw-bold d-block small mb-1" style="color: var(--text-main);">Laporan Realtime</span>
                            <span class="text-muted small" style="font-size: 0.75rem;">Visualisasi grafik interaktif</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-4" style="background: var(--surface-color); border: 1px solid var(--border-color);">
                            <i class="bi bi-people-fill fs-3 mb-2 d-block" style="color: var(--accent-neon);"></i>
                            <span class="fw-bold d-block small mb-1" style="color: var(--text-main);">Kolaborasi Keluarga</span>
                            <span class="text-muted small" style="font-size: 0.75rem;">Akses dompet bersama</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
