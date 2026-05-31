@extends('layouts.app')

@push('styles')
<style>
    .dashboard-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 2rem;
    }

    .table-glass {
        color: var(--text-main) !important;
    }

    .table-glass th {
        font-weight: 700;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-color) !important;
        background: transparent !important;
    }

    .table-glass td {
        border-bottom: 1px solid var(--border-color) !important;
        background: transparent !important;
        vertical-align: middle;
        color: var(--text-main) !important;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    <div class="mb-4">
        <h2 class="fw-bold" style="color: var(--text-main);"><i class="bi bi-speedometer2 me-2" style="color: var(--primary-neon);"></i>Dashboard Langganan Saya</h2>
        <p style="color: var(--text-muted);">Kelola paket aktif Anda dan pantau seluruh transaksi pembayaran di satu tempat.</p>
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
            <div class="dashboard-card h-100">
                <h4 class="fw-bold mb-4" style="color: var(--text-main);">Paket Penagihan Aktif</h4>

                @if($activeSubscription)
                    <div class="text-center py-4 mb-4 rounded-4" style="background: var(--surface-color); border: 1px solid var(--border-color);">
                        <span class="badge bg-success mb-2 px-3 py-2"><i class="bi bi-shield-check me-1"></i>Aktif</span>
                        <h2 class="fw-bold mb-1" style="color: var(--text-main);">{{ $activeSubscription->plan->name }}</h2>
                        <h4 class="mb-0 fw-bold" style="font-family: 'Outfit', sans-serif; color: var(--primary-neon);">{{ $activeSubscription->getFormattedPrice() }}</h4>
                        <span class="small" style="color: var(--text-muted);">{{ $activeSubscription->billing_cycle === 'yearly' ? 'Per Tahun' : 'Per Bulan' }}</span>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3" style="color: var(--text-main);">Rincian Paket:</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2 d-flex justify-content-between">
                                <span style="color: var(--text-muted);">Tanggal Dimulai:</span>
                                <strong style="color: var(--text-main);">{{ $activeSubscription->started_at?->format('d M Y') }}</strong>
                            </li>
                            <li class="mb-2 d-flex justify-content-between">
                                <span style="color: var(--text-muted);">Tanggal Berakhir:</span>
                                <strong style="color: var(--text-main);">{{ $activeSubscription->ended_at?->format('d M Y') }}</strong>
                            </li>
                            <li class="mb-2 d-flex justify-content-between">
                                <span style="color: var(--text-muted);">Perpanjangan Otomatis:</span>
                                <strong style="color: var(--text-main);">{{ $activeSubscription->auto_renew ? 'Aktif' : 'Tidak Aktif' }}</strong>
                            </li>
                            <li class="mb-2 d-flex justify-content-between">
                                <span style="color: var(--text-muted);">Nomor ID Berlangganan:</span>
                                <strong class="text-uppercase" style="font-size: 0.8rem; color: var(--text-main);">{{ $activeSubscription->external_subscription_id ?: 'LOC-' . $activeSubscription->id }}</strong>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-3 border-top d-grid gap-2" style="border-color: var(--border-color) !important;">
                        <a href="{{ route('plans.index') }}" class="btn btn-neon-primary py-2 rounded-3">
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
                        <h5 class="fw-bold mb-2" style="color: var(--text-main);">Anda Belum Berlangganan</h5>
                        <p class="small mb-4" style="color: var(--text-muted);">Nikmati akses tak terbatas ke semua fitur pencatatan dan analisis keuangan dengan membeli salah satu paket premium kami.</p>
                        <a href="{{ route('plans.index') }}" class="btn btn-neon-primary py-2 rounded-3">
                            <i class="bi bi-cart-fill me-2"></i>Lihat Paket Tersedia
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Invoices & Transaction History -->
        <div class="col-12 col-lg-7">
            <div class="dashboard-card h-100">
                <h4 class="fw-bold mb-4" style="color: var(--text-main);">Riwayat Transaksi</h4>

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
                                    <tr>
                                        <td>
                                            <strong class="text-uppercase" style="font-size: 0.85rem; color: var(--text-main);">{{ $invoice->invoice_number }}</strong>
                                        </td>
                                        <td>
                                            <span class="small" style="color: var(--text-main);">{{ $sub->plan->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong style="color: var(--text-main);">{{ $invoice->getFormattedAmount() }}</strong>
                                        </td>
                                        <td class="small" style="color: var(--text-muted);">
                                            {{ $invoice->created_at?->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4" style="color: var(--text-muted);">
                                        <i class="bi bi-credit-card-2-back fs-2 mb-2 d-block"></i>
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
@endsection
