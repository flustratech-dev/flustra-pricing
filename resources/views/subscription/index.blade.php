@extends('layouts.app')

@push('styles')
<style>
    .subscription-header {
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

    .table-glass {
        color: var(--text-main) !important;
    }

    .table-glass th {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-color) !important;
        background: transparent !important;
        padding: 1rem;
    }

    .table-glass td {
        border-bottom: 1px solid var(--border-color) !important;
        background: transparent !important;
        vertical-align: middle;
        color: var(--text-main) !important;
        padding: 1rem;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container pb-5">
    
    <!-- Header Section -->
    <div class="subscription-header text-center">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-4 reveal-scale">
            <img src="{{ asset('images/flustraa.png') }}" alt="Flustra Logo" style="height: 26px; width: auto; object-fit: contain;">
            <span class="font-brand tracking-wider" style="color: var(--text-main); font-weight: 800; font-size: 0.95rem; letter-spacing: 1.2px;">FLUSTRA</span>
        </div>

        <h1 class="display-3 fw-extrabold mb-3 tracking-tight" style="color: var(--text-main);">Kelola Langganan Anda</h1>
        <p class="lead max-w-2xl mx-auto mb-4" style="max-width: 600px; margin: 0 auto; color: var(--text-muted) !important; font-size: 0.8rem; line-height: 1.5;">
            "Pantau detail paket aktif Anda, lihat riwayat penagihan transaksi, serta kelola masa aktif berlangganan Anda dalam satu ruang personal."
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
        <!-- Active Subscription Info -->
        <div class="col-12 col-lg-5">
            <div class="premium-card p-4">
                <div class="card-ambient-glow"></div>
                <div class="z-3 position-relative d-flex flex-column h-100 justify-content-between">
                    <div>
                        <h4 class="fw-semibold mb-4" style="color: var(--text-main); font-family: 'Outfit', sans-serif;">Paket Penagihan Aktif</h4>

                        @if($activeSubscription)
                            <div class="text-center py-4 mb-4 rounded-4" style="background: var(--surface-color); border: 1px solid var(--border-color); box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);">
                                <span class="badge mb-3 px-3 py-2" style="background: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; border: 1px solid rgba(16, 185, 129, 0.2); font-weight: 600;"><i class="bi bi-shield-check me-1"></i>Aktif</span>
                                <h2 class="fw-bold mb-1" style="color: var(--text-main); font-family: 'Outfit', sans-serif;">{{ $activeSubscription->plan->name }}</h2>
                                <h3 class="mb-0 fw-semibold" style="font-family: 'Outfit', sans-serif; color: var(--primary-neon);">{{ $activeSubscription->getFormattedPrice() }}</h3>
                                <span class="small" style="color: var(--text-muted);">{{ $activeSubscription->billing_cycle === 'yearly' ? 'Per Tahun' : 'Per Bulan' }}</span>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3" style="color: var(--text-main);">Rincian Paket:</h6>
                                <ul class="list-unstyled small">
                                    <li class="mb-2 d-flex justify-content-between">
                                        <span style="color: var(--text-muted);">Tanggal Dimulai:</span>
                                        <span class="fw-medium" style="color: var(--text-main);">{{ $activeSubscription->started_at?->format('d M Y') }}</span>
                                    </li>
                                    <li class="mb-2 d-flex justify-content-between">
                                        <span style="color: var(--text-muted);">Tanggal Berakhir:</span>
                                        <span class="fw-medium" style="color: var(--text-main);">{{ $activeSubscription->ended_at?->format('d M Y') }}</span>
                                    </li>
                                    <li class="mb-2 d-flex justify-content-between">
                                        <span style="color: var(--text-muted);">Perpanjangan Otomatis:</span>
                                        <span class="fw-medium" style="color: var(--text-main);">{{ $activeSubscription->auto_renew ? 'Aktif' : 'Tidak Aktif' }}</span>
                                    </li>
                                    <li class="mb-2 d-flex justify-content-between">
                                        <span style="color: var(--text-muted);">Nomor ID Berlangganan:</span>
                                        <span class="text-uppercase fw-medium" style="font-size: 0.8rem; color: var(--text-main);">{{ $activeSubscription->external_subscription_id ?: 'LOC-' . $activeSubscription->id }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="pt-3 border-top d-grid gap-2" style="border-color: var(--border-color) !important;">
                                <a href="{{ route('plans.index') }}" class="btn btn-neon-primary py-2 rounded-3 text-center">
                                    <i class="bi bi-arrow-left-right me-2"></i>Upgrade / Downgrade Paket
                                </a>
                                <form method="POST" action="{{ route('subscription.cancel', $activeSubscription) }}" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan langganan Anda? Akses ke fitur premium akan terputus.');">
                                    @csrf
                                    <button type="submit" class="btn btn-neon-secondary w-100 py-2 rounded-3 text-danger border-danger border-opacity-20" style="background: rgba(239, 68, 68, 0.05);">
                                        <i class="bi bi-x-circle me-2"></i>Batalkan Langganan
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-info-circle fs-1 mb-3 d-block" style="color: var(--text-muted);"></i>
                                <h5 class="fw-semibold mb-2" style="color: var(--text-main);">Anda Belum Berlangganan</h5>
                                <p class="small mb-4" style="color: var(--text-muted); line-height: 1.5;">Nikmati akses tak terbatas ke semua fitur pencatatan dan analisis keuangan dengan membeli salah satu paket premium kami.</p>
                                <a href="{{ route('plans.index') }}" class="btn btn-neon-primary py-2 rounded-3">
                                    <i class="bi bi-cart-fill me-2"></i>Lihat Paket Tersedia
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices & Transaction History -->
        <div class="col-12 col-lg-7">
            <div class="premium-card p-4">
                <div class="card-ambient-glow"></div>
                <div class="z-3 position-relative">
                    <h4 class="fw-semibold mb-4" style="color: var(--text-main); font-family: 'Outfit', sans-serif;">Riwayat Transaksi</h4>

                    <div class="table-responsive">
                        <table class="table table-glass mb-0">
                            <thead>
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Paket</th>
                                    <th>Status</th>
                                    <th>Total Biaya</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $sub)
                                    @foreach($sub->invoices as $invoice)
                                        @php
                                            $badgeStyle = '';
                                            if ($invoice->status === 'paid') {
                                                $badgeStyle = 'background: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; border: 1px solid rgba(16, 185, 129, 0.2);';
                                            } elseif ($invoice->status === 'pending') {
                                                $badgeStyle = 'background: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; border: 1px solid rgba(245, 158, 11, 0.2);';
                                            } else {
                                                $badgeStyle = 'background: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; border: 1px solid rgba(239, 68, 68, 0.2);';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="text-uppercase fw-medium" style="font-size: 0.85rem; color: var(--text-main);">{{ $invoice->invoice_number }}</span>
                                            </td>
                                            <td>
                                                <span class="small" style="color: var(--text-main);">{{ $sub->plan->name }}</span>
                                            </td>
                                            <td>
                                                <span class="badge px-2 py-1.5" style="{{ $badgeStyle }} font-weight: 600; font-size: 0.75rem;">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-medium" style="color: var(--text-main);">{{ $invoice->getFormattedAmount() }}</span>
                                            </td>
                                            <td class="small" style="color: var(--text-muted);">
                                                {{ $invoice->created_at?->format('d M Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4" style="color: var(--text-muted);">
                                            <i class="bi bi-credit-card-2-back fs-2 mb-2 d-block" style="color: var(--text-muted);"></i>
                                            Belum ada transaksi pembayaran yang tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $subscriptions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

