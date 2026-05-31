@extends('layouts.app')

@push('styles')
<style>
    /* Styling single plan details page */
    .detail-hero-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-color);
        border-radius: 32px;
        padding: 3rem;
        box-shadow: 0 20px 50px rgba(139, 94, 60, 0.08);
        position: relative;
        overflow: hidden;
    }

    .detail-hero-card::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(139, 94, 60, 0.15) 0%, rgba(253, 251, 247, 0) 70%);
        top: -100px;
        right: -100px;
        pointer-events: none;
        z-index: 0;
    }

    .plan-badge-pill {
        background-color: var(--surface-accent);
        color: var(--text-main);
        border: 1px solid var(--border-color);
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .price-box {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.8rem;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
    }

    .pricing-toggle-container {
        background: var(--surface-accent);
        border: 1px solid var(--border-color);
        padding: 0.35rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .pricing-toggle-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.4rem 1.2rem;
        border-radius: 9999px;
        transition: all 0.3s ease;
    }

    .pricing-toggle-btn.active {
        background: linear-gradient(135deg, var(--primary-neon) 0%, var(--btn-hover) 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(139, 94, 60, 0.2);
    }

    .feature-detail-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.8rem 1rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(226, 232, 240, 0.5);
        transition: all 0.3s ease;
    }

    .feature-detail-item:hover {
        background: var(--surface-color);
        border-color: var(--primary-neon);
        transform: translateX(4px);
    }

    .feature-detail-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 9999px;
        background: var(--surface-accent);
        color: var(--primary-neon);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .feature-detail-icon-excluded {
        background: rgba(239, 68, 68, 0.05);
        color: #ef4444;
    }

    .gradient-text-detail {
        background: linear-gradient(135deg, var(--text-main) 0%, var(--primary-neon) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    
    <!-- Breadcrumbs / Back button -->
    <div class="mb-4">
        <a href="{{ route('plans.index') }}" class="btn btn-neon-secondary btn-sm rounded-pill px-3 py-2">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Semua Paket
        </a>
    </div>

    <!-- Main Detail Section -->
    <div class="detail-hero-card z-3">
        <div class="row g-5 align-items-center">
            
            <!-- Left Column: Plan Details -->
            <div class="col-12 col-lg-6 z-3 position-relative">
                <span class="badge plan-badge-pill px-3 py-2 mb-3">
                    <i class="bi bi-bookmark-star-fill me-1" style="color: var(--primary-neon);"></i>
                    {{ $plan->category }} - {{ $plan->tier }}
                </span>
                
                <h1 class="display-4 gradient-text-detail mb-3">{{ $plan->name }}</h1>
                <p class="lead mb-4" style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.7;">
                    {{ $plan->description }}
                </p>

                <!-- Pricing Box with JS Switcher -->
                <div class="price-box mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px; color: var(--text-muted);">Harga Berlangganan</span>
                        
                        <!-- Cycle Switcher -->
                        <div class="pricing-toggle-container">
                            <button type="button" class="pricing-toggle-btn active" id="btnDetailMonthly">Bulanan</button>
                            <button type="button" class="pricing-toggle-btn" id="btnDetailYearly">Tahunan</button>
                        </div>
                    </div>

                    <div class="d-flex align-items-baseline">
                        <span class="display-5 fw-extrabold" id="detailPriceDisplay" style="color: var(--text-main);">
                            {{ $plan->getFormattedPrice('monthly') }}
                        </span>
                        <span class="ms-2 fs-5" id="detailCycleDisplay" style="color: var(--text-muted);">
                            /bulan
                        </span>
                    </div>

                    <div class="mt-2 small" id="yearlySavingBadge" style="color: var(--accent-neon); display: none;">
                        <i class="bi bi-patch-check-fill me-1" style="color: var(--primary-neon);"></i>
                        Hemat hingga 20% dengan memilih opsi pembayaran tahunan!
                    </div>
                </div>

                <!-- CTA Action Buttons -->
                <div class="d-grid gap-3">
                    @auth
                        @if(auth()->user()->getCurrentPlan()?->id === $plan->id)
                            <button class="btn btn-neon-secondary btn-lg py-3 rounded-4 w-100" disabled style="background: var(--surface-accent); color: var(--text-muted); border-color: var(--border-color);">
                                <i class="bi bi-patch-check-fill text-success me-2"></i>Ini Adalah Paket Aktif Anda
                            </button>
                        @elseif(auth()->user()->hasActivePlan() && auth()->user()->canUpgrade($plan))
                            <a 
                                href="{{ route('checkout', $plan) }}?cycle=monthly" 
                                class="btn btn-neon-primary btn-lg py-3 rounded-4 w-100 text-center"
                                id="detailCheckoutBtn"
                            >
                                <i class="bi bi-arrow-up-circle-fill me-2"></i>Upgrade ke Paket Ini Sekarang
                            </a>
                        @elseif(auth()->user()->hasActivePlan() && auth()->user()->canDowngrade($plan))
                            <a 
                                href="{{ route('checkout', $plan) }}?cycle=monthly" 
                                class="btn btn-neon-secondary btn-lg py-3 rounded-4 w-100 text-center"
                                id="detailCheckoutBtn"
                            >
                                <i class="bi bi-arrow-down-circle me-2"></i>Downgrade ke Paket Ini
                            </a>
                        @else
                            <a 
                                href="{{ route('checkout', $plan) }}?cycle=monthly" 
                                class="btn btn-neon-primary btn-lg py-3 rounded-4 w-100 text-center"
                                id="detailCheckoutBtn"
                            >
                                <i class="bi bi-cart-fill me-2"></i>
                                {{ $plan->isFree() ? 'Mulai Gratis' : 'Langganan Sekarang' }}
                            </a>
                        @endif
                    @else
                        <a 
                            href="{{ route('login') }}" 
                            class="btn btn-neon-primary btn-lg py-3 rounded-4 w-100 text-center"
                        >
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk untuk Mulai Langganan
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right Column: Full Features List -->
            <div class="col-12 col-lg-6 z-3">
                <div class="p-4 rounded-4" style="background: rgba(255, 255, 255, 0.4); border: 1px solid var(--border-color);">
                    <h4 class="fw-bold mb-4" style="color: var(--text-main);">
                        <i class="bi bi-check-all me-1" style="color: var(--primary-neon);"></i>Fitur Lengkap Paket
                    </h4>

                    <div class="d-flex flex-column gap-3">
                        @forelse($plan->features as $feature)
                            <div class="feature-detail-item">
                                <div class="feature-detail-icon {{ !$feature->is_included ? 'feature-detail-icon-excluded' : '' }}">
                                    <i class="bi {{ !$feature->is_included ? 'bi-x' : ($feature->icon_class ?: 'bi-check') }}"></i>
                                </div>
                                <span style="color: var(--text-main);" class="{{ !$feature->is_included ? 'text-decoration-line-through text-opacity-50' : '' }}">
                                    <strong>{{ $feature->feature_name }}</strong>
                                </span>
                            </div>
                        @empty
                            <p class="small text-center py-4" style="color: var(--text-muted);">Tidak ada detail fitur untuk paket ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
    const btnDetailMonthly = document.getElementById('btnDetailMonthly');
    const btnDetailYearly = document.getElementById('btnDetailYearly');
    
    if (btnDetailMonthly && btnDetailYearly) {
        const monthlyPrice = {{ $plan->price_monthly }};
        const yearlyPrice = {{ $plan->price_yearly }};
        
        const priceDisplay = document.getElementById('detailPriceDisplay');
        const cycleDisplay = document.getElementById('detailCycleDisplay');
        const savingBadge = document.getElementById('yearlySavingBadge');
        const checkoutBtn = document.getElementById('detailCheckoutBtn');

        function updateDetailPricing(cycle) {
            if (cycle === 'yearly') {
                btnDetailYearly.classList.add('active');
                btnDetailMonthly.classList.remove('active');
                
                priceDisplay.textContent = formatCurrency(yearlyPrice);
                cycleDisplay.textContent = '/tahun';
                if (savingBadge) savingBadge.style.display = 'block';
                
                if (checkoutBtn) {
                    const baseUrl = checkoutBtn.href.split('?')[0];
                    checkoutBtn.href = `${baseUrl}?cycle=yearly`;
                }
            } else {
                btnDetailMonthly.classList.add('active');
                btnDetailYearly.classList.remove('active');
                
                priceDisplay.textContent = formatCurrency(monthlyPrice);
                cycleDisplay.textContent = '/bulan';
                if (savingBadge) savingBadge.style.display = 'none';
                
                if (checkoutBtn) {
                    const baseUrl = checkoutBtn.href.split('?')[0];
                    checkoutBtn.href = `${baseUrl}?cycle=monthly`;
                }
            }
        }

        btnDetailMonthly.addEventListener('click', () => updateDetailPricing('monthly'));
        btnDetailYearly.addEventListener('click', () => updateDetailPricing('yearly'));

        function formatCurrency(amount) {
            if (amount == 0) return 'Gratis';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }
    }
</script>
@endpush
@endsection
