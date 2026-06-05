@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<!-- Metric Cards -->
<div class="row g-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="metric-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-tags"></i>
            </div>
            <div>
                <div class="text-muted small">Paket Aktif</div>
                <div class="fs-4 fw-bold lh-1 mt-1">{{ \App\Models\Plan::active()->count() ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="metric-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="text-muted small">Langganan Aktif</div>
                <div class="fs-4 fw-bold lh-1 mt-1">{{ \App\Models\Subscription::active()->count() ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="metric-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="text-muted small">Tagihan Tertunda</div>
                <div class="fs-4 fw-bold lh-1 mt-1">{{ \App\Models\Invoice::where('status', 'pending')->count() ?? 0 }}</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="metric-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div>
                <div class="text-muted small">Estimasi MRR</div>
                <div class="fs-4 fw-bold lh-1 mt-1 text-truncate" style="max-width: 120px;">
                    {{ 'Rp ' . number_format(\App\Services\BillingService::calculateMRR(), 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="row mt-4">
    <!-- Kolom Kiri: Tabel Terbaru -->
    <div class="col-12 col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="text-primary fw-bold" style="font-size: 0.9rem; color: var(--primary-neon) !important;">Langganan Terbaru</span>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary btn-sm" style="font-size: 0.75rem;">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Paket</th>
                                <th>Status</th>
                                <th>Bergabung Pada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentSubs = \App\Models\Subscription::with(['user', 'plan'])->latest()->take(5)->get();
                            @endphp
                            
                            @forelse($recentSubs as $sub)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $sub->user->name ?? '-' }}</div>
                                        <div class="text-muted small">{{ $sub->user->email ?? '-' }}</div>
                                    </td>
                                    <td>{{ $sub->plan->name ?? '-' }}</td>
                                    <td>
                                        @if($sub->status === 'active')
                                            <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                                        @elseif($sub->status === 'canceled')
                                            <span class="badge bg-danger bg-opacity-10 text-danger">Dibatalkan</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ ucfirst($sub->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $sub->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Belum ada langganan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Akses Cepat & Akun -->
    <div class="col-12 col-lg-4 mt-4 mt-lg-0 d-flex flex-column gap-4">
        <!-- Akses Cepat -->
        <div class="card">
            <div class="card-header">
                <span class="text-primary fw-bold" style="font-size: 0.9rem; color: var(--primary-neon) !important;">Akses Cepat</span>
            </div>
            <div class="card-body p-3 d-flex flex-column gap-2">
                <a href="{{ route('admin.plans.create') }}" class="btn btn-primary w-100 text-center d-flex justify-content-center align-items-center gap-2 mb-1">
                    <i class="bi bi-plus-circle"></i> Buat Paket Baru
                </a>
                <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary w-100 text-start d-flex align-items-center bg-transparent">
                    <i class="bi bi-tags text-muted me-3"></i> 
                    <span class="text-dark" style="font-size: 0.85rem;">Kelola Paket</span>
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary w-100 text-start d-flex align-items-center bg-transparent">
                    <i class="bi bi-people text-muted me-3"></i> 
                    <span class="text-dark" style="font-size: 0.85rem;">Pengguna Langganan</span>
                </a>
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary w-100 text-start d-flex align-items-center bg-transparent">
                    <i class="bi bi-shield-lock text-muted me-3"></i> 
                    <span class="text-dark" style="font-size: 0.85rem;">Tagihan & Pembayaran</span>
                </a>
            </div>
        </div>

        <!-- Akun Anda -->
        <div class="card">
            <div class="card-header">
                <span class="text-primary fw-bold" style="font-size: 0.9rem; color: var(--primary-neon) !important;">Akun Anda</span>
            </div>
            <div class="card-body p-3">
                <div class="text-muted small mb-2">{{ auth()->user()->email ?? 'admin@gmail.com' }}</div>
                <div class="badge bg-danger bg-opacity-10 text-danger mb-4" style="letter-spacing: 0.5px;">ADMINISTRATOR</div>
                <div class="d-flex align-items-center text-success small fw-medium">
                    <i class="bi bi-check-circle me-2"></i> Akses penuh ke seluruh modul
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
