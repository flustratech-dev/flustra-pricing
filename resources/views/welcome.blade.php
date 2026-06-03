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
        background: radial-gradient(circle, rgba(139, 94, 60, 0.08) 0%, rgba(92, 82, 67, 0.05) 50%, rgba(253, 251, 247, 0) 80%);
        z-index: -1;
        pointer-events: none;
    }

    .text-gradient {
        background: linear-gradient(135deg, var(--primary-neon) 0%, var(--accent-neon) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .feature-card {
        background: rgba(249, 246, 238, 0.55);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
        text-align: left;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        background: #ffffff;
        border-color: var(--primary-neon);
        box-shadow: 0 8px 24px rgba(139, 94, 60, 0.06);
    }

    .feature-card-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .feature-card:hover .feature-card-icon-wrapper {
        transform: scale(1.15) rotate(6deg);
    }
</style>
@endpush

@section('content')
<div class="hero-section text-center">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-4 reveal-scale">
                    <img src="{{ asset('images/flustraa.png') }}" alt="Flustra Logo" style="height: 26px; width: auto; object-fit: contain;">
                    <span class="font-brand tracking-wider" style="color: var(--text-main); font-weight: 800; font-size: 0.95rem; letter-spacing: 1.2px;">FLUSTRA</span>
                </div>
                
                <h1 class="display-2 fw-extrabold mb-3 tracking-tight" style="color: var(--text-main);">
                    "Kendalikan Penuh Keuangan Anda dengan Fitur Terbaik"
                </h1>
                
                <p class="lead mb-5 mx-auto" style="max-width: 650px; color: var(--text-muted); font-size: 0.8rem; line-height: 1.5;">
                    Dari dompet keluarga hingga pembukuan bisnis enterprise. Dapatkan laporan mendalam dan portofolio otomatis dalam satu platform yang modern dan memanjakan mata. Pilih paket terbaik untuk Anda di bawah ini dan mulai langkah baru menuju kebebasan finansial.
                </p>

                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3 mt-4">
                    <a href="{{ route('plans.index') }}" class="btn btn-neon-primary px-4 py-3 rounded-pill d-inline-flex align-items-center justify-content-center" style="min-width: 200px; font-size: 1rem;">
                        <i class="bi bi-tag-fill me-2"></i>Lihat Paket Harga
                    </a>
                        @auth
                            <a href="{{ route('subscription.index') }}" class="btn btn-neon-secondary px-4 py-3 rounded-pill d-inline-flex align-items-center justify-content-center" style="min-width: 200px; font-size: 1rem;">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard Saya
                            </a>
                        @else
                    @endauth
                </div>

                <div class="row g-4 mt-5 pt-4 justify-content-center">
                    <div class="col-12 col-md-4">
                        <div class="feature-card d-flex align-items-start gap-3">
                            <div class="feature-card-icon-wrapper flex-shrink-0" style="background: rgba(139, 94, 60, 0.08); color: var(--primary-neon);">
                                <i class="bi bi-shield-fill-check"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1" style="color: var(--text-main); font-family: 'Outfit'; font-size: 1.02rem; line-height: 1.3;">Keamanan Enkripsi</h3>
                                <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.5;">Proteksi data aset ganda enkripsi end-to-end.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="feature-card d-flex align-items-start gap-3">
                            <div class="feature-card-icon-wrapper flex-shrink-0" style="background: rgba(160, 124, 95, 0.12); color: #825E43;">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1" style="color: var(--text-main); font-family: 'Outfit'; font-size: 1.02rem; line-height: 1.3;">Laporan Realtime</h3>
                                <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.5;">Grafik keuangan interaktif terupdate otomatis.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="feature-card d-flex align-items-start gap-3">
                            <div class="feature-card-icon-wrapper flex-shrink-0" style="background: rgba(94, 62, 37, 0.08); color: #5E3E25;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1" style="color: var(--text-main); font-family: 'Outfit'; font-size: 1.02rem; line-height: 1.3;">Kolaborasi Keluarga</h3>
                                <p class="text-muted mb-0" style="font-size: 0.8rem; line-height: 1.5;">Akses dompet bersama anggota dengan peran aman.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
