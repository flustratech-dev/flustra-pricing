@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-white"><i class="bi bi-people-fill text-info me-2"></i>Rincian Langganan Pengguna</h2>
            <p class="text-secondary mb-0" style="color: var(--text-muted);">Informasi detail masa aktif, invoices, logs, serta alat kontrol administrasi manual.</p>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-neon-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #10b981;">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 p-3" role="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2) !important; color: #ef4444;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ $message }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Subscription details & manual controls -->
    <div class="col-12 col-lg-6">
        <div class="card mb-4">
            <div class="card-header">Detail Hubungan Paket</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <li class="d-flex justify-content-between">
                        <span>Pengguna:</span>
                        <strong class="text-white">{{ $subscription->user->name }} ({{ $subscription->user->email }})</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Nama Paket:</span>
                        <strong class="text-white">{{ $subscription->plan->name }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Status:</span>
                        <span class="badge bg-{{ $subscription->getStatusBadgeColor() }}">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Siklus Penagihan:</span>
                        <strong class="text-white">{{ ucfirst($subscription->billing_cycle) }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Biaya Langganan:</span>
                        <strong class="text-white">{{ $subscription->getFormattedPrice() }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Dimulai:</span>
                        <strong class="text-white">{{ $subscription->started_at?->format('d M Y H:i') }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Berakhir:</span>
                        <strong class="text-white">{{ $subscription->ended_at?->format('d M Y H:i') ?: '—' }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Auto-Perpanjang:</span>
                        <strong class="text-white">{{ $subscription->auto_renew ? 'Ya' : 'Tidak' }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>External ID:</span>
                        <strong class="text-white text-uppercase" style="font-size: 0.85rem;">{{ $subscription->external_subscription_id ?: 'LOCAL-' . $subscription->id }}</strong>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Manual admin controllers -->
        <div class="card">
            <div class="card-header">Kontrol Manual Administrasi</div>
            <div class="card-body d-flex flex-column gap-3">
                
                <!-- Upgrade / Downgrade Plan Form -->
                <form action="{{ route('admin.subscriptions.upgrade', $subscription) }}" method="POST" class="row g-2 align-items-end p-3 rounded-4" style="background: rgba(255, 255, 255, 0.01); border: 1px solid var(--border-color);">
                    @csrf
                    <div class="col-8">
                        <label class="form-label small">Upgrade Paket</label>
                        <select name="plan_id" class="form-select select-sm" required>
                            <option value="">Pilih Paket Lebih Tinggi</option>
                            @foreach(\App\Models\Plan::active()->get() as $p)
                                @if($subscription->user->canUpgrade($p))
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->getFormattedPrice($subscription->billing_cycle) }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-neon-primary btn-sm w-100 py-2">Upgrade</button>
                    </div>
                </form>

                <form action="{{ route('admin.subscriptions.downgrade', $subscription) }}" method="POST" class="row g-2 align-items-end p-3 rounded-4" style="background: rgba(255, 255, 255, 0.01); border: 1px solid var(--border-color);">
                    @csrf
                    <div class="col-8">
                        <label class="form-label small">Downgrade Paket</label>
                        <select name="plan_id" class="form-select select-sm" required>
                            <option value="">Pilih Paket Lebih Rendah</option>
                            @foreach(\App\Models\Plan::active()->get() as $p)
                                @if($subscription->user->canDowngrade($p))
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->getFormattedPrice($subscription->billing_cycle) }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-neon-secondary btn-sm w-100 py-2 text-warning border-warning border-opacity-10">Downgrade</button>
                    </div>
                </form>

                <div class="d-flex gap-2">
                    <form action="{{ route('admin.subscriptions.renew', $subscription) }}" method="POST" class="flex-grow-1 m-0">
                        @csrf
                        <button type="submit" class="btn btn-neon-secondary w-100 py-3 text-success border-success border-opacity-20" style="background: rgba(16, 185, 129, 0.05);">
                            <i class="bi bi-arrow-clockwise me-1"></i>Perpanjang Manual
                        </button>
                    </form>

                    <form action="{{ route('admin.subscriptions.cancel', $subscription) }}" method="POST" class="flex-grow-1 m-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan langganan pengguna ini secara paksa?');">
                        @csrf
                        <input type="hidden" name="reason" value="Cancelled paksa oleh admin">
                        <button type="submit" class="btn btn-neon-secondary w-100 py-3 text-danger border-danger border-opacity-20" style="background: rgba(239, 68, 68, 0.05);">
                            <i class="bi bi-x-circle me-1"></i>Cancel Paksa
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Audits Logs & Invoices -->
    <div class="col-12 col-lg-6">
        <div class="card mb-4">
            <div class="card-header">Invoices Billing Terbit</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    @forelse($subscription->invoices as $inv)
                        <li class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color);">
                            <div>
                                <strong class="text-white text-uppercase" style="font-size: 0.85rem;">{{ $inv->invoice_number }}</strong>
                                <br>
                                <span class="text-secondary small">{{ $inv->created_at?->format('d M Y') }}</span>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $inv->status === 'paid' ? 'success' : 'warning' }} mb-1 d-inline-block">{{ ucfirst($inv->status) }}</span>
                                <br>
                                <strong class="text-white">{{ $inv->getFormattedAmount() }}</strong>
                            </div>
                        </li>
                    @empty
                        <li class="text-secondary text-center py-4">Belum ada invoice diterbitkan.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Log Aktivitas (Audit Trail)</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    @forelse($subscription->logs as $log)
                        <li class="p-2 rounded-3" style="background: rgba(255, 255, 255, 0.01); border: 1px solid var(--border-color);">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-dark">{{ strtoupper($log->event_type) }}</span>
                                <span class="text-secondary small">{{ $log->created_at?->format('d M Y H:i') }}</span>
                            </div>
                            <p class="text-white small mb-0">{{ $log->notes }}</p>
                        </li>
                    @empty
                        <li class="text-secondary text-center py-4">Belum ada log tercatat.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
