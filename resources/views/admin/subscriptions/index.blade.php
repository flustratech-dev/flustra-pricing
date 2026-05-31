@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <h2 class="fw-bold text-white"><i class="bi bi-people-fill text-primary me-2"></i>Kelola Langganan Pengguna</h2>
        <p class="text-secondary" style="color: var(--text-muted);">Lihat detail berlangganan, upgrade, downgrade, batalkan, atau perbarui langganan secara manual.</p>
    </div>

    <!-- Stats summary inside subscriptions page -->
    <div class="col-12 col-md-4">
        <div class="card p-3 text-center">
            <span class="text-muted small fw-bold mb-1">TOTAL LANGGANAN AKTIF</span>
            <h3 class="fw-bold text-success">{{ \App\Models\Subscription::active()->count() }}</h3>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card p-3 text-center">
            <span class="text-muted small fw-bold mb-1">LANGGANAN PENDING</span>
            <h3 class="fw-bold text-warning">{{ \App\Models\Subscription::where('status', 'pending')->count() }}</h3>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card p-3 text-center">
            <span class="text-muted small fw-bold mb-1">SISTEM REVENUE MRR</span>
            <h3 class="fw-bold text-white" style="font-family: 'Outfit', sans-serif;">
                {{ 'Rp ' . number_format(\App\Services\BillingService::calculateMRR(), 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12">
        <div class="card p-3">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">Status Langganan</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label small">Pilih Paket</label>
                    <select name="plan_id" class="form-select">
                        <option value="">Semua Paket</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-neon-primary w-100 py-2">
                        <i class="bi bi-funnel-fill me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-neon-secondary w-100 py-2">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pengguna (User)</th>
                            <th>Nama Paket</th>
                            <th>Status</th>
                            <th>Siklus Penagihan</th>
                            <th>Biaya Bayar</th>
                            <th>Dimulai Pada</th>
                            <th>Akan Berakhir</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $sub)
                            <tr>
                                <td>
                                    <strong>{{ $sub->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $sub->user->email }}</small>
                                </td>
                                <td>{{ $sub->plan->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $sub->getStatusBadgeColor() }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst($sub->billing_cycle) }}</td>
                                <td>{{ $sub->getFormattedPrice() }}</td>
                                <td class="small text-secondary">{{ $sub->started_at?->format('d M Y') }}</td>
                                <td class="small text-secondary">{{ $sub->ended_at?->format('d M Y') ?: 'Seterusnya' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.subscriptions.show', $sub) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye-fill me-1"></i>Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-3"></i>
                                    Belum ada catatan langganan pengguna yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-3">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection
