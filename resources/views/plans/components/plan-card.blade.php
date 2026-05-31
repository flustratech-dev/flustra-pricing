@php
    $isPopular = $plan->is_popular;
    $cardBorder = $isPopular ? 'border-neon-popular' : 'border-glass';
@endphp

@once
@push('styles')
<style>
    .premium-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 24px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .border-glass {
        border: 1px solid var(--border-color);
    }

    .border-neon-popular {
        border: 2px solid transparent;
        background-image: linear-gradient(var(--surface-color), var(--surface-color)), 
                          linear-gradient(135deg, var(--primary-neon) 0%, var(--secondary-neon) 100%);
        background-origin: border-box;
        background-clip: padding-box, border-box;
        box-shadow: 0 10px 40px rgba(139, 94, 60, 0.15);
    }

    .premium-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(139, 94, 60, 0.1);
        border-color: var(--primary-neon);
    }

    .border-neon-popular:hover {
        box-shadow: 0 20px 40px rgba(139, 94, 60, 0.25);
    }

    /* Ambient card backgrounds */
    .card-ambient-glow {
        position: absolute;
        top: -60px;
        left: -60px;
        width: 150px;
        height: 150px;
        border-radius: 9999px;
        background: radial-gradient(circle, rgba(139, 94, 60, 0.1) 0%, rgba(139, 94, 60, 0) 70%);
        pointer-events: none;
        z-index: 0;
    }

    .card-ambient-glow-popular {
        background: radial-gradient(circle, rgba(139, 94, 60, 0.18) 0%, rgba(139, 94, 60, 0) 70%);
    }

    .card-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 10;
    }

    .feature-item-list {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 0.8rem;
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .feature-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 9999px;
        background: var(--surface-accent);
        color: var(--primary-neon);
        flex-shrink: 0;
    }

    .feature-icon-excluded {
        background: rgba(239, 68, 68, 0.05);
        color: #ef4444;
    }

    .popular-ribbon {
        background: linear-gradient(135deg, var(--accent-neon) 0%, var(--primary-neon) 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 0.4rem 1.2rem;
        border-radius: 9999px;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
    }
</style>
@endpush
@endonce

<div 
    class="premium-card {{ $cardBorder }} p-4 pt-5" 
    data-plan-card 
    data-plan-id="{{ $plan->id }}"
    data-price-monthly="{{ $plan->price_monthly }}"
    data-price-yearly="{{ $plan->price_yearly }}"
>
    <!-- Ambient Glow Inside Card -->
    <div class="card-ambient-glow {{ $isPopular ? 'card-ambient-glow-popular' : '' }}"></div>

    <!-- Paling Populer Badge -->
    @if($isPopular)
        <div class="card-badge">
            <span class="popular-ribbon">⭐ TERPOPULER</span>
        </div>
    @endif

    <div class="z-3 position-relative d-flex flex-column h-100">
        <!-- Tier & Category -->
        <span class="text-uppercase font-semibold tracking-wider mb-2" style="font-size: 0.8rem; font-weight: 700; letter-spacing: 1px; color: var(--primary-neon) !important;">
            {{ $plan->category }} - {{ $plan->tier }}
        </span>
        
        <!-- Plan Name -->
        <h2 class="h3 fw-bold mb-2" style="color: var(--text-main);">{{ $plan->name }}</h2>
        
        <!-- Plan Description -->
        <p class="small mb-4" style="color: var(--text-muted); font-size: 0.88rem; min-height: 40px;">
            {{ $plan->description }}
        </p>

        <!-- Price Section -->
        <div class="mb-4 py-2 border-top border-bottom d-flex align-items-baseline" style="border-color: var(--border-color) !important;">
            <span class="display-6 fw-extrabold" data-price-display style="color: var(--text-main);">
                {{ $plan->getFormattedPrice('monthly') }}
            </span>
            <span class="ms-2" data-cycle-display style="color: var(--text-muted);">
                /bln
            </span>
        </div>

        <!-- Features List -->
        <div class="mb-5 flex-grow-1">
            <h6 class="font-semibold mb-3" style="font-size: 0.9rem; color: var(--text-main);">Fitur yang Anda dapatkan:</h6>
            <div class="features-wrapper">
                @forelse($plan->features as $feature)
                    <div class="feature-item-list">
                        <div class="feature-icon-wrapper {{ !$feature->is_included ? 'feature-icon-excluded' : '' }}">
                            <i class="bi {{ !$feature->is_included ? 'bi-x' : ($feature->icon_class ?: 'bi-check') }}"></i>
                        </div>
                        <span style="color: var(--text-main);" class="{{ !$feature->is_included ? 'text-decoration-line-through text-opacity-50' : '' }}">
                            {{ $feature->feature_name }}
                        </span>
                    </div>
                @empty
                    <p class="small" style="color: var(--text-muted);">Tidak ada fitur yang ditambahkan.</p>
                @endforelse
            </div>
        </div>

        <!-- Action Button -->
        <div class="mt-auto">
            @auth
                @if(auth()->user()->getCurrentPlan()?->id === $plan->id)
                    <button class="btn btn-neon-secondary w-100 py-3 rounded-4" disabled style="background: var(--surface-accent); color: var(--text-muted);">
                        <i class="bi bi-patch-check-fill text-success me-2"></i>Paket Aktif Anda
                    </button>
                @elseif(auth()->user()->hasActivePlan() && auth()->user()->canUpgrade($plan))
                    <a 
                        href="{{ route('checkout', $plan) }}" 
                        class="btn btn-neon-primary w-100 py-3 rounded-4"
                        data-checkout-link
                    >
                        <i class="bi bi-arrow-up-circle-fill me-2"></i>Upgrade Sekarang
                    </a>
                @elseif(auth()->user()->hasActivePlan() && auth()->user()->canDowngrade($plan))
                    <a 
                        href="{{ route('checkout', $plan) }}" 
                        class="btn btn-neon-secondary w-100 py-3 rounded-4"
                        data-checkout-link
                    >
                        <i class="bi bi-arrow-down-circle me-2"></i>Downgrade Paket
                    </a>
                @else
                    <a 
                        href="{{ route('checkout', $plan) }}" 
                        class="btn {{ $isPopular ? 'btn-neon-primary' : 'btn-neon-secondary' }} w-100 py-3 rounded-4"
                        data-checkout-link
                    >
                        <i class="bi bi-cart-fill me-2"></i>
                        {{ $plan->isFree() ? 'Mulai Gratis' : 'Pilih Paket' }}
                    </a>
                @endif
            @else
                <a 
                    href="{{ route('login') }}" 
                    class="btn {{ $isPopular ? 'btn-neon-primary' : 'btn-neon-secondary' }} w-100 py-3 rounded-4"
                >
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk untuk Membeli
                </a>
            @endauth
        </div>
    </div>
</div>
