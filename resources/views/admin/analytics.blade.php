@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <h2 class="fw-bold text-white"><i class="bi bi-graph-up-arrow text-info me-2"></i>Analisis Pendapatan & Langganan</h2>
        <p class="text-secondary" style="color: var(--text-muted);">Pantau status kesehatan finansial Flustra berdasarkan data riil dari sistem.</p>
    </div>

    <!-- Revenue Cards -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3">
            <span class="text-muted small fw-bold mb-2">ESTIMASI MRR</span>
            <h2 class="fw-bold text-white" style="font-family: 'Outfit', sans-serif;">
                {{ 'Rp ' . number_format($mrr, 0, ',', '.') }}
            </h2>
            <span class="text-secondary small">Pendapatan bulanan berulang</span>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3">
            <span class="text-muted small fw-bold mb-2">TAHUNAN ARR (ESTIMASI)</span>
            <h2 class="fw-bold text-white" style="font-family: 'Outfit', sans-serif;">
                {{ 'Rp ' . number_format($mrr * 12, 0, ',', '.') }}
            </h2>
            <span class="text-secondary small">Annual Recurring Revenue</span>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3">
            <span class="text-muted small fw-bold mb-2">LANGGANAN AKTIF</span>
            <h2 class="fw-bold text-success">{{ $activeCount }}</h2>
            <span class="text-secondary small">Pengguna dengan paket berbayar</span>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card p-3">
            <span class="text-muted small fw-bold mb-2">CHURN/TERTUNDA</span>
            <h2 class="fw-bold text-warning">{{ $pendingCount }}</h2>
            <span class="text-secondary small">Akun dalam status pending</span>
        </div>
    </div>

    <!-- Grouped Plans Stat Table -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">Distribusi Langganan per Paket</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Paket</th>
                            <th>Kategori</th>
                            <th>Tier</th>
                            <th class="text-end">Jumlah Pengguna</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptionsByPlan as $sub)
                            <tr>
                                <td><strong>{{ $sub->plan->name }}</strong></td>
                                <td><span class="badge bg-secondary">{{ ucfirst($sub->plan->category) }}</span></td>
                                <td><span class="badge bg-dark">{{ ucfirst($sub->plan->tier) }}</span></td>
                                <td class="text-end"><strong>{{ $sub->total }}</strong> User</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada langganan aktif untuk memetakan distribusi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Status Lifecycle -->
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">Status Langganan Keseluruhan</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <li class="d-flex justify-content-between">
                        <span>Aktif:</span>
                        <strong class="text-success">{{ $activeCount }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Pending:</span>
                        <strong class="text-warning">{{ $pendingCount }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Dibatalkan:</span>
                        <strong class="text-danger">{{ $cancelledCount }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Expired:</span>
                        <strong class="text-secondary">{{ $expiredCount }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
