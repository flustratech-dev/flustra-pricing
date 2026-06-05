@extends('layouts.app')

@push('styles')
<style>
    /* Styling pricing page specific components */
    .pricing-header {
        position: relative;
        padding: 5rem 0 3rem 0;
    }

    .pricing-toggle-container {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        padding: 0.4rem 0.5rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 10px rgba(139, 94, 60, 0.05);
    }

    .pricing-toggle-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.5rem 1.5rem;
        border-radius: 9999px;
        transition: all 0.3s ease;
    }

    .pricing-toggle-btn.active {
        background: linear-gradient(135deg, #A07C5F 0%, #825E43 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(130, 94, 67, 0.15);
    }

    .category-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        background: linear-gradient(135deg, var(--text-main) 0%, var(--primary-neon) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .category-title::before {
        content: '';
        display: inline-block;
        width: 12px;
        height: 12px;
        background: var(--primary-neon);
        border-radius: 9999px;
        box-shadow: 0 0 10px var(--primary-neon);
    }
</style>
@endpush

@section('content')
<div class="container pb-5">
    
    <!-- Header Section -->
    <div class="pricing-header text-center">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-4 reveal-scale">
            <img src="{{ asset('images/flustraa.png') }}" alt="Flustra Logo" style="height: 26px; width: auto; object-fit: contain;">
            <span class="font-brand tracking-wider" style="color: var(--text-main); font-weight: 800; font-size: 0.95rem; letter-spacing: 1.2px;">FLUSTRA</span>
        </div>

        <h1 class="display-3 fw-extrabold mb-3 tracking-tight" style="color: var(--text-main);">Investasi Cerdas untuk Finansial Anda</h1>
        <p class="lead max-w-2xl mx-auto mb-4" style="max-width: 600px; margin: 0 auto; color: var(--text-muted) !important; font-size: 0.8rem; line-height: 1.5;">
            "Pilih paket yang sesuai dengan kebutuhan finansial Anda. Fleksibel—bebas ganti atau batalkan kapan saja."
        </p>

        <!-- Dynamic Billing Toggle -->
        <div class="mt-4">
            <div class="pricing-toggle-container">
                <button type="button" class="pricing-toggle-btn active" id="btnMonthly">Bulanan</button>
                <button type="button" class="pricing-toggle-btn position-relative" id="btnYearly">
                    Tahunan
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="font-size: 0.65rem; padding: 0.35em 0.65em; background-color: var(--primary-neon) !important; color: #ffffff !important;">
                        Hemat 20%
                    </span>
                </button>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-5" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important; color: #10b981;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Plans grouped by Category -->
    @foreach(['personal' => 'Paket Personal', 'family' => 'Paket Keluarga', 'business' => 'Paket Bisnis Enterprise'] as $category => $label)
        @if(isset($plans[$category]) && count($plans[$category]) > 0)
            @php
                $planCount = count($plans[$category]);
                $rowClass = $planCount === 1 ? 'justify-content-center' : 'justify-content-center justify-content-md-start';
                
                if ($planCount === 1) {
                    $columnClass = 'col-12';
                } elseif ($planCount === 4) {
                    $columnClass = 'col-12 col-md-6 col-lg-3';
                } else {
                    $columnClass = 'col-12 col-md-6 col-lg-4';
                }
            @endphp
            <div class="mb-5 py-4" id="{{ $category }}-section">
                <h3 class="category-title">{{ $label }}</h3>
                
                <div class="row g-4 {{ $rowClass }}">
                    @foreach($plans[$category] as $plan)
                        <div class="{{ $columnClass }}">
                            @include('plans.components.plan-card', ['plan' => $plan, 'horizontal' => $planCount === 1])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>

@push('scripts')
<script>
    const btnMonthly = document.getElementById('btnMonthly');
    const btnYearly = document.getElementById('btnYearly');
    let billingCycle = 'monthly';

    function updatePricing(cycle) {
        billingCycle = cycle;
        if (cycle === 'yearly') {
            btnYearly.classList.add('active');
            btnMonthly.classList.remove('active');
        } else {
            btnMonthly.classList.add('active');
            btnYearly.classList.remove('active');
        }

        const cards = document.querySelectorAll('[data-plan-card]');
        cards.forEach(card => {
            const planId = card.dataset.planId;
            const monthlyPrice = parseFloat(card.dataset.priceMonthly);
            const yearlyPrice = parseFloat(card.dataset.priceYearly);
            
            const priceElement = card.querySelector('[data-price-display]');
            const billingCycleText = card.querySelector('[data-cycle-display]');
            const checkoutLink = card.querySelector('[data-checkout-link]');
            
            // Format price to IDR
            const price = cycle === 'yearly' ? yearlyPrice : monthlyPrice;
            priceElement.textContent = formatCurrency(price);
            billingCycleText.textContent = cycle === 'yearly' ? '/tahun' : '/bln';

            // Update checkout link cycle parameter
            if (checkoutLink) {
                const baseUrl = checkoutLink.href.split('?')[0];
                checkoutLink.href = `${baseUrl}?cycle=${cycle}`;
            }
        });
    }

    btnMonthly.addEventListener('click', () => updatePricing('monthly'));
    btnYearly.addEventListener('click', () => updatePricing('yearly'));

    function formatCurrency(amount) {
        if (amount == 0) return 'Gratis';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }
</script>
@endpush
@endsection
