@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card border-0 p-4" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(168, 85, 247, 0.1) 100%); border: 1px solid rgba(99, 102, 241, 0.15) !important;">
            <div class="d-flex align-items-center gap-4">
                <div class="fs-1 text-warning"><i class="bi bi-rocket-takeoff-fill"></i></div>
                <div>
                    <h3 class="fw-bold text-white mb-1">Selamat Datang di Flustra Admin Panel</h3>
                    <p class="text-secondary mb-0" style="color: var(--text-muted);">Di sini Anda dapat mengelola paket penawaran, langganan pengguna, serta memantau omset MRR Anda secara real-time.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small fw-bold">TOTAL PAKET AKTIF</span>
                <span class="badge bg-primary bg-opacity-10 text-primary fs-5"><i class="bi bi-tags-fill"></i></span>
            </div>
            <h2 class="fw-bold text-white mb-1">{{ \App\Models\Plan::active()->count() }}</h2>
            <span class="text-secondary small">Paket terdaftar di publik</span>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small fw-bold">USER BERLANGGANAN</span>
                <span class="badge bg-success bg-opacity-10 text-success fs-5"><i class="bi bi-people-fill"></i></span>
            </div>
            <h2 class="fw-bold text-white mb-1">{{ \App\Models\Subscription::active()->count() }}</h2>
            <span class="text-secondary small">Akun berstatus aktif</span>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small fw-bold">INVOICES TERTUNDA</span>
                <span class="badge bg-warning bg-opacity-10 text-warning fs-5"><i class="bi bi-hourglass-split"></i></span>
            </div>
            <h2 class="fw-bold text-white mb-1">{{ \App\Models\Invoice::where('status', 'pending')->count() }}</h2>
            <span class="text-secondary small">Menunggu pembayaran</span>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small fw-bold">ESTIMASI MRR</span>
                <span class="badge bg-info bg-opacity-10 text-info fs-5"><i class="bi bi-currency-dollar"></i></span>
            </div>
            <h2 class="fw-bold text-white mb-1" style="font-family: 'Outfit', sans-serif;">
                {{ 'Rp ' . number_format(\App\Services\BillingService::calculateMRR(), 0, ',', '.') }}
            </h2>
            <span class="text-secondary small">Monthly Recurring Revenue</span>
        </div>
    </div>

    <!-- Administrative Operations Guidance -->
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-gear-fill me-2 text-primary"></i>Aksi Cepat Admin</div>
            <div class="card-body d-flex flex-column gap-3">
                <a href="{{ route('admin.plans.create') }}" class="btn btn-neon-primary py-3">
                    <i class="bi bi-plus-circle me-2"></i>Buat Paket Langganan Baru
                </a>
                <a href="{{ route('admin.plans.index') }}" class="btn btn-neon-secondary py-3 text-start">
                    <i class="bi bi-tags-fill me-3 text-info"></i>Kelola Paket & Feature List
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-neon-secondary py-3 text-start">
                    <i class="bi bi-people-fill me-3 text-success"></i>Kelola Langganan Pengguna
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-activity me-2 text-info"></i>System Log Aset</div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="bi bi-database-check text-success fs-1 mb-2"></i>
                    <p class="text-secondary small mb-0">Database dan Layanan Terhubung dengan Lancar.</p>
                    <span class="badge bg-secondary mt-2 px-3 py-1">MySQL Lokal: OK</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
