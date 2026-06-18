@extends('layouts.app')

@push('styles')
<style>
    .checkout-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 2rem;
    }

    .form-control, .form-select {
        background-color: var(--surface-color) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-main) !important;
        border-radius: 12px !important;
        padding: 0.75rem 1rem !important;
        transition: all 0.3s ease !important;
    }

    .form-control:focus, .form-select:focus {
        background-color: var(--bg-color) !important;
        border-color: var(--primary-neon) !important;
        box-shadow: 0 0 10px rgba(139, 94, 60, 0.2) !important;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row g-4">
        
        <!-- Order Summary & Checkout Form -->
        <div class="col-12 col-lg-8">
            <div class="checkout-card mb-4">
                <h4 class="fw-bold mb-4" style="color: var(--text-main);"><i class="bi bi-cart-check-fill me-2" style="color: var(--primary-neon);"></i>Konfirmasi Pemesanan</h4>
                
                <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded-4" style="background: var(--surface-color); border: 1px solid var(--border-color);">
                    <div>
                        <h6 class="fw-bold mb-1" style="color: var(--text-main);">{{ $plan->name }}</h6>
                        <small style="color: var(--text-muted);">{{ $plan->description }}</small>
                    </div>
                    <span class="fw-bold fs-5" style="color: var(--text-main);">{{ $plan->getFormattedPrice($billingCycle) }}</span>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold mb-3" style="color: var(--text-main);">Keuntungan Paket:</h6>
                    <div class="row g-2">
                        @foreach($plan->features as $feature)
                            @if($feature->is_included)
                                <div class="col-12 col-md-6">
                                    <div class="d-flex align-items-center gap-2 small">
                                        <i class="bi bi-check-circle-fill" style="color: var(--primary-neon);"></i>
                                        <span style="color: var(--text-main);">{{ $feature->feature_name }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="pt-3 border-top" style="border-color: var(--border-color) !important;">
                    <div class="row g-3">
                        <div class="col-6">
                            <span class="d-block" style="color: var(--text-muted);">Siklus Penagihan</span>
                            <strong style="color: var(--text-main);">{{ $billingCycle === 'yearly' ? 'Tahunan (Hemat 20%)' : 'Bulanan' }}</strong>
                        </div>
                        <div class="col-6 text-end">
                            <span class="d-block" style="color: var(--text-muted);">Periode Langganan</span>
                            <strong style="color: var(--text-main);">
                                {{ now()->format('d M Y') }} - 
                                {{ $billingCycle === 'yearly' ? now()->addYear()->format('d M Y') : now()->addMonth()->format('d M Y') }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Form -->
            <div class="checkout-card">
                <h4 class="fw-bold mb-4" style="color: var(--text-main);"><i class="bi bi-person-bounding-box me-2" style="color: var(--primary-neon);"></i>Informasi Penagihan</h4>
                
                <form id="checkoutForm">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">

                    <div class="mb-3">
                        <label class="form-label">Alamat Email</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            name="email"
                            value="{{ auth()->user()->email }}"
                            readonly
                            style="opacity: 0.6; cursor: not-allowed;"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input 
                            type="text" 
                            class="form-control @error('full_name') is-invalid @enderror" 
                            name="full_name"
                            value="{{ auth()->user()->name }}"
                            required
                        >
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check mb-4 mt-4">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="agree_terms"
                            id="agreeTerms"
                            required
                        >
                        <label class="form-check-label small" for="agreeTerms" style="color: var(--text-muted);">
                            Saya menyetujui semua <a href="#" class="text-decoration-none" style="color: var(--primary-neon);">Syarat & Ketentuan</a> serta kebijakan perpanjangan otomatis di Flustra.
                        </label>
                    </div>

                    <button type="submit" id="payButton" class="btn btn-neon-primary btn-lg w-100 py-3">
                        <i class="bi bi-shield-fill-check me-2"></i>Selesaikan Pembayaran
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar Total Calculation -->
        <div class="col-12 col-lg-4">
            <div class="checkout-card sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4" style="color: var(--text-main);">Rincian Biaya</h5>
                
                @php
                    $basePrice = $billingCycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;
                    $taxAmount = $basePrice * 0.11; // 11% PPN Ind
                    $totalPrice = $basePrice + $taxAmount;
                @endphp

                <div class="d-flex justify-content-between mb-3">
                    <span style="color: var(--text-muted);">Harga Paket:</span>
                    <span style="color: var(--text-main);">{{ 'Rp ' . number_format($basePrice, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span style="color: var(--text-muted);">PPN (11%):</span>
                    <span style="color: var(--text-main);">{{ 'Rp ' . number_format($taxAmount, 0, ',', '.') }}</span>
                </div>
                
                <hr class="my-4" style="border-color: var(--border-color) !important;">
                
                <div class="d-flex justify-content-between align-items-baseline mb-4">
                    <span class="fw-bold fs-5" style="color: var(--text-main);">Total Bayar:</span>
                    <span class="fw-extrabold fs-3" style="font-family: 'Outfit', sans-serif; color: var(--text-main);">
                        {{ 'Rp ' . number_format($totalPrice, 0, ',', '.') }}
                    </span>
                </div>

                @if($billingCycle === 'yearly')
                    <div class="alert border-0 rounded-4 p-3 mb-0" role="alert" style="background: rgba(139, 94, 60, 0.1); border: 1px solid rgba(139, 94, 60, 0.2) !important; color: var(--text-main);">
                        <i class="bi bi-gift-fill me-2 fs-5" style="color: var(--primary-neon);"></i>
                        <strong>Diskon 20% Terpasang!</strong>
                        <p class="small mb-0 mt-1">Anda menghemat {{ 'Rp ' . number_format(($plan->price_monthly * 12) - $plan->price_yearly, 0, ',', '.') }} dengan memilih paket tahunan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const payButton = document.getElementById('payButton');
    payButton.disabled = true;
    payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';

    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route('checkout.snap-token') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                plan_id: formData.get('plan_id'),
                billing_cycle: formData.get('billing_cycle'),
                full_name: formData.get('full_name')
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Gagal membuat transaksi.');
        }

        window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
                window.location.href = '/checkout/callback?status=success&plan_id=' + formData.get('plan_id') + '&cycle=' + formData.get('billing_cycle');
            },
            onPending: function(result) {
                alert('Pembayaran tertunda. Silakan selesaikan pembayaran Anda.');
                payButton.disabled = false;
                payButton.innerHTML = '<i class="bi bi-shield-fill-check me-2"></i>Selesaikan Pembayaran';
            },
            onError: function(result) {
                alert('Pembayaran gagal. Silakan coba metode pembayaran lain.');
                payButton.disabled = false;
                payButton.innerHTML = '<i class="bi bi-shield-fill-check me-2"></i>Selesaikan Pembayaran';
            },
            onClose: function() {
                payButton.disabled = false;
                payButton.innerHTML = '<i class="bi bi-shield-fill-check me-2"></i>Selesaikan Pembayaran';
            }
        });
    } catch (error) {
        alert(error.message);
        payButton.disabled = false;
        payButton.innerHTML = '<i class="bi bi-shield-fill-check me-2"></i>Selesaikan Pembayaran';
    }
});
</script>
@endsection
