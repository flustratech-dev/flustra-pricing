# VIEWS & TEMPLATES - SUBSCRIPTION SYSTEM FLUSTRA

---

## 1️⃣ PUBLIC VIEWS

### resources/views/plans/index.blade.php
```blade
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-2">Paket & Langganan</h1>
        <p class="lead text-muted">Buka potensi penuh pengelolaan aset Anda dengan paket premium.</p>
    </div>

    <!-- Billing Cycle Toggle -->
    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-end align-items-center gap-3">
            <span class="text-muted">Bulanan</span>
            
            <div class="form-check form-switch">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="billingToggle"
                    data-bs-toggle="tooltip"
                    title="Dapatkan diskon 20% untuk paket tahunan"
                >
                <label class="form-check-label" for="billingToggle"></label>
            </div>

            <span class="text-muted">
                Tahunan 
                <span class="badge bg-warning text-dark ms-2" id="discountBadge" style="display: none;">
                    -20%
                </span>
            </span>
        </div>
    </div>

    <!-- Plans by Category -->
    @foreach(['personal' => 'Paket Personal', 'family' => 'Paket Keluarga', 'business' => 'Paket Bisnis'] as $category => $label)
        @if(isset($plans[$category]))
            <div class="mb-5 pb-5 border-bottom" id="{{ $category }}-section">
                <h3 class="mb-4">{{ $label }}</h3>
                
                <div class="row g-4">
                    @foreach($plans[$category] as $plan)
                        <div class="col-md-6 col-lg-4">
                            @include('plans.components.plan-card', ['plan' => $plan])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>

@push('scripts')
<script>
    const billingToggle = document.getElementById('billingToggle');
    const discountBadge = document.getElementById('discountBadge');

    billingToggle.addEventListener('change', function() {
        const isYearly = this.checked;
        const cards = document.querySelectorAll('[data-plan-id]');

        cards.forEach(card => {
            const planId = card.dataset.planId;
            const monthlyPrice = card.dataset.priceMonthly;
            const yearlyPrice = card.dataset.priceYearly;
            const priceElement = card.querySelector('[data-price]');
            const billCycleElement = card.querySelector('[data-billing-cycle]');

            if (isYearly) {
                priceElement.textContent = formatCurrency(yearlyPrice);
                billCycleElement.textContent = '/thn';
            } else {
                priceElement.textContent = formatCurrency(monthlyPrice);
                billCycleElement.textContent = '/bln';
            }
        });

        discountBadge.style.display = isYearly ? 'inline-block' : 'none';
    });

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }
</script>
@endpush

@endsection
```

### resources/views/plans/components/plan-card.blade.php
```blade
@php
    $isPopular = $plan->is_popular;
    $cardClass = $isPopular ? 'border-warning border-2' : 'border-1';
    $buttonClass = $isPopular 
        ? 'btn btn-warning btn-lg' 
        : 'btn btn-outline-secondary btn-lg';
@endphp

<div 
    class="card h-100 {{ $cardClass }} plan-card position-relative" 
    data-plan-id="{{ $plan->id }}"
    data-price-monthly="{{ $plan->price_monthly }}"
    data-price-yearly="{{ $plan->price_yearly }}"
>
    <!-- Popular Badge -->
    @if($isPopular)
        <div class="position-absolute top-0 start-50 translate-middle">
            <span class="badge bg-warning text-dark px-3 py-2">
                PALING POPULER
            </span>
        </div>
    @endif

    <div class="card-body pt-5">
        <!-- Plan Title -->
        <h5 class="card-title fw-bold mb-2">{{ $plan->name }}</h5>
        <p class="card-text text-muted small mb-4">{{ $plan->description }}</p>

        <!-- Price Section -->
        <div class="mb-4">
            <div class="d-flex align-items-baseline">
                <span 
                    class="display-5 fw-bold" 
                    data-price
                >
                    {{ $plan->getFormattedPrice('monthly') }}
                </span>
                <span 
                    class="text-muted ms-2" 
                    data-billing-cycle
                >
                    /bln
                </span>
            </div>
            @if(!$plan->isFree() && $plan->price_yearly)
                <small class="text-muted">
                    atau {{ $plan->getFormattedPrice('yearly') }}/tahun
                </small>
            @endif
        </div>

        <!-- Features List -->
        <ul class="list-unstyled mb-4">
            @forelse($plan->features as $feature)
                <li class="mb-2 d-flex align-items-start">
                    <i class="bi bi-check text-success me-3 mt-1 flex-shrink-0"></i>
                    <span class="small">{{ $feature->feature_name }}</span>
                </li>
            @empty
                <li class="text-muted small">Tidak ada fitur</li>
            @endforelse
        </ul>

        <!-- CTA Button -->
        <div class="d-grid gap-2">
            @auth
                @if(auth()->user()->getCurrentPlan()?->id === $plan->id)
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="bi bi-check-circle me-2"></i> Paket Aktif
                    </button>
                @elseif(auth()->user()->hasActivePlan() && auth()->user()->canUpgrade($plan))
                    <a 
                        href="{{ route('checkout', $plan) }}" 
                        class="{{ $buttonClass }}"
                    >
                        <i class="bi bi-arrow-up-right me-2"></i> Upgrade
                    </a>
                @elseif(auth()->user()->hasActivePlan() && auth()->user()->canDowngrade($plan))
                    <a 
                        href="{{ route('checkout', $plan) }}" 
                        class="{{ $buttonClass }}"
                    >
                        <i class="bi bi-arrow-down-right me-2"></i> Downgrade
                    </a>
                @else
                    <a 
                        href="{{ route('checkout', $plan) }}" 
                        class="{{ $buttonClass }}"
                    >
                        <i class="bi bi-cart-plus me-2"></i>
                        {{ $plan->isFree() ? 'Mulai Gratis' : 'Pilih Paket' }}
                    </a>
                @endif
            @else
                <a 
                    href="{{ route('login') }}" 
                    class="{{ $buttonClass }}"
                >
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </a>
            @endauth
        </div>
    </div>

    @if($isPopular)
        <div class="card-footer bg-light border-top-0 text-center py-3">
            <small class="text-muted">⭐ Pilihan terpopuler bulan ini</small>
        </div>
    @endif
</div>
```

### resources/views/checkout/show.blade.php
```blade
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold">{{ $plan->name }}</h6>
                            <small class="text-muted">{{ $plan->description }}</small>
                        </div>
                        <span class="fw-bold">{{ $plan->getFormattedPrice($billingCycle) }}</span>
                    </div>

                    <ul class="list-unstyled mb-4">
                        @foreach($plan->features as $feature)
                            <li class="mb-2">
                                <i class="bi bi-check text-success me-2"></i>
                                {{ $feature->feature_name }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between">
                            <span>Siklus Penagihan:</span>
                            <span class="fw-bold">
                                {{ $billingCycle === 'yearly' ? 'Per Tahun' : 'Per Bulan' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Periode:</span>
                            <span class="fw-bold">
                                {{ now()->format('d M Y') }} - 
                                {{ $billingCycle === 'yearly' ? now()->addYear() : now()->addMonth() }}->(format)
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Form -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Penagihan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('process-payment') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email"
                                value="{{ auth()->user()->email }}"
                                readonly
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
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method">
                                <option value="credit_card">Kartu Kredit / Debit</option>
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="e_wallet">E-Wallet (GCash, OVO, Dana)</option>
                            </select>
                        </div>

                        <div class="form-check mb-3">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="agree_terms"
                                id="agreeTerms"
                                required
                            >
                            <label class="form-check-label" for="agreeTerms">
                                Saya setuju dengan 
                                <a href="#" target="_blank">Syarat & Ketentuan</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-lock-fill me-2"></i>
                            Lanjutkan ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Total Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Harga Paket:</span>
                        <span>{{ $plan->getFormattedPrice($billingCycle) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>PPN (10%):</span>
                        <span>Rp {{ number_format(($plan->price_monthly * 0.1), 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total:</span>
                        <span>Rp {{ number_format(($plan->price_monthly * 1.1), 0, ',', '.') }}</span>
                    </div>

                    @if($billingCycle === 'yearly')
                        <div class="alert alert-success mt-3" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Hemat 20%!</strong>
                            Dengan memilih paket tahunan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## 2️⃣ ADMIN VIEWS

### resources/views/admin/plans/index.blade.php
```blade
@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">Kelola Paket</h2>
            <p class="text-muted">Atur paket berlangganan untuk pengguna</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Buat Paket Baru
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Plans Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Paket</th>
                        <th>Kategori</th>
                        <th>Harga (Bulanan)</th>
                        <th>Harga (Tahunan)</th>
                        <th>Status</th>
                        <th>Populer</th>
                        <th>Fitur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        <tr>
                            <td>
                                <strong>{{ $plan->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $plan->slug }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($plan->category) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($plan->price_monthly, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($plan->price_yearly, 0, ',', '.') }}</td>
                            <td>
                                @if($plan->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                @if($plan->is_popular)
                                    <i class="bi bi-star-fill text-warning"></i> Ya
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $plan->features->count() }} fitur</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a 
                                        href="{{ route('admin.plans.edit', $plan) }}" 
                                        class="btn btn-outline-primary"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a 
                                        href="{{ route('admin.plans.show', $plan) }}" 
                                        class="btn btn-outline-info"
                                        title="Lihat"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button 
                                        class="btn btn-outline-danger"
                                        onclick="deleteConfirm({{ $plan->id }})"
                                        title="Hapus"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox"></i> Belum ada paket
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($plans->hasPages())
        <div class="mt-4">
            {{ $plans->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function deleteConfirm(planId) {
        if (confirm('Apakah Anda yakin ingin menghapus paket ini?')) {
            document.getElementById('deleteForm-' + planId).submit();
        }
    }
</script>
@endpush
@endsection
```

### resources/views/admin/plans/form-partial.blade.php
```blade
<!-- Plan Information -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Informasi Paket</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama Paket *</label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name', $plan->name ?? '') }}"
                        placeholder="Contoh: Personal Mid"
                        required
                    >
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Slug *</label>
                    <input 
                        type="text" 
                        class="form-control @error('slug') is-invalid @enderror"
                        name="slug"
                        value="{{ old('slug', $plan->slug ?? '') }}"
                        placeholder="personal-mid"
                        required
                    >
                    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Kategori *</label>
                    <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option value="personal" {{ old('category', $plan->category ?? '') === 'personal' ? 'selected' : '' }}>Personal</option>
                        <option value="family" {{ old('category', $plan->category ?? '') === 'family' ? 'selected' : '' }}>Family</option>
                        <option value="business" {{ old('category', $plan->category ?? '') === 'business' ? 'selected' : '' }}>Business</option>
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Tier *</label>
                    <select class="form-select @error('tier') is-invalid @enderror" name="tier" required>
                        <option value="">Pilih Tier</option>
                        <option value="free" {{ old('tier', $plan->tier ?? '') === 'free' ? 'selected' : '' }}>Free</option>
                        <option value="low" {{ old('tier', $plan->tier ?? '') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="mid" {{ old('tier', $plan->tier ?? '') === 'mid' ? 'selected' : '' }}>Mid</option>
                        <option value="high" {{ old('tier', $plan->tier ?? '') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('tier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Urutan Tampil *</label>
                    <input 
                        type="number" 
                        class="form-control @error('display_order') is-invalid @enderror"
                        name="display_order"
                        value="{{ old('display_order', $plan->display_order ?? 0) }}"
                        min="0"
                        required
                    >
                    @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea 
                class="form-control @error('description') is-invalid @enderror"
                name="description"
                rows="3"
                placeholder="Deskripsi singkat tentang paket"
            >{{ old('description', $plan->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<!-- Pricing -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Harga</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Harga Bulanan (Rp) *</label>
                    <input 
                        type="number" 
                        class="form-control @error('price_monthly') is-invalid @enderror"
                        name="price_monthly"
                        value="{{ old('price_monthly', $plan->price_monthly ?? 0) }}"
                        step="1000"
                        min="0"
                        required
                    >
                    @error('price_monthly')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Harga Tahunan (Rp) *</label>
                    <input 
                        type="number" 
                        class="form-control @error('price_yearly') is-invalid @enderror"
                        name="price_yearly"
                        value="{{ old('price_yearly', $plan->price_yearly ?? 0) }}"
                        step="1000"
                        min="0"
                        required
                    >
                    @error('price_yearly')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted d-block mt-2">
                        💡 Diskon: <span id="discountPercentage">0</span>%
                    </small>
                </div>
            </div>
        </div>

        <div class="form-check mb-3">
            <input 
                class="form-check-input" 
                type="checkbox" 
                name="is_popular"
                id="isPopular"
                {{ old('is_popular', $plan->is_popular ?? false) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="isPopular">
                Tandai sebagai paket paling populer
            </label>
        </div>

        <div class="form-check">
            <input 
                class="form-check-input" 
                type="checkbox" 
                name="is_active"
                id="isActive"
                {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="isActive">
                Paket aktif
            </label>
        </div>
    </div>
</div>

<!-- Features -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Fitur Paket</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFeature()">
            <i class="bi bi-plus-circle me-1"></i> Tambah Fitur
        </button>
    </div>
    <div class="card-body">
        <div id="featuresContainer">
            @forelse(old('features', $plan->features ?? []) as $index => $feature)
                <div class="feature-item mb-3 pb-3 border-bottom" data-index="{{ $index }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nama Fitur</label>
                            <input 
                                type="text" 
                                class="form-control"
                                name="features[{{ $index }}][name]"
                                value="{{ old("features.$index.name", $feature['name'] ?? $feature->feature_name ?? '') }}"
                                placeholder="Contoh: Pencatatan Pemasukan"
                                required
                            >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon Class</label>
                            <input 
                                type="text" 
                                class="form-control"
                                name="features[{{ $index }}][icon]"
                                value="{{ old("features.$index.icon", $feature['icon'] ?? '') }}"
                                placeholder="bi-check"
                            >
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <label class="form-label">Deskripsi (Opsional)</label>
                            <textarea 
                                class="form-control"
                                name="features[{{ $index }}][description]"
                                rows="2"
                                placeholder="Deskripsi fitur"
                            >{{ old("features.$index.description", $feature['description'] ?? $feature->feature_description ?? '') }}</textarea>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeFeature(this)">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            @empty
                <div class="text-muted text-center py-4">
                    <p>Belum ada fitur. Klik tombol "Tambah Fitur" untuk menambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    let featureIndex = {{ old('features') ? count(old('features')) : (isset($plan) ? $plan->features->count() : 0) }};

    function addFeature() {
        const html = `
            <div class="feature-item mb-3 pb-3 border-bottom" data-index="${featureIndex}">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Nama Fitur</label>
                        <input 
                            type="text" 
                            class="form-control"
                            name="features[${featureIndex}][name]"
                            placeholder="Contoh: Pencatatan Pemasukan"
                            required
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Icon Class</label>
                        <input 
                            type="text" 
                            class="form-control"
                            name="features[${featureIndex}][icon]"
                            placeholder="bi-check"
                        >
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <label class="form-label">Deskripsi (Opsional)</label>
                        <textarea 
                            class="form-control"
                            name="features[${featureIndex}][description]"
                            rows="2"
                            placeholder="Deskripsi fitur"
                        ></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeFeature(this)">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </div>
        `;
        document.getElementById('featuresContainer').innerHTML += html;
        featureIndex++;
    }

    function removeFeature(button) {
        button.closest('.feature-item').remove();
    }

    // Calculate discount percentage
    const priceMonthlyInput = document.querySelector('input[name="price_monthly"]');
    const priceYearlyInput = document.querySelector('input[name="price_yearly"]');

    function calculateDiscount() {
        const monthly = parseFloat(priceMonthlyInput.value) || 0;
        const yearly = parseFloat(priceYearlyInput.value) || 0;
        const monthlyTotal = monthly * 12;
        
        if (monthlyTotal > 0) {
            const discount = ((monthlyTotal - yearly) / monthlyTotal * 100).toFixed(0);
            document.getElementById('discountPercentage').textContent = discount;
        }
    }

    priceMonthlyInput?.addEventListener('change', calculateDiscount);
    priceYearlyInput?.addEventListener('change', calculateDiscount);
    calculateDiscount();
</script>
@endpush
```

---

## 3️⃣ ADMIN SUBSCRIPTIONS VIEWS

### resources/views/admin/subscriptions/index.blade.php
```blade
@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">Kelola Langganan</h2>
            <p class="text-muted">Lihat dan kelola langganan pengguna</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Tertunda</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Paket</label>
                    <select name="plan_id" class="form-select">
                        <option value="">Semua Paket</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-2"></i> Filter
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Aktif</h6>
                    <h3 class="fw-bold text-success">
                        {{ \App\Models\Subscription::active()->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Tertunda</h6>
                    <h3 class="fw-bold text-warning">
                        {{ \App\Models\Subscription::where('status', 'pending')->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Dibatalkan</h6>
                    <h3 class="fw-bold text-danger">
                        {{ \App\Models\Subscription::where('status', 'cancelled')->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">MRR</h6>
                    <h3 class="fw-bold">
                        Rp {{ number_format(\App\Services\BillingService::calculateMRR(), 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Siklus</th>
                        <th>Harga</th>
                        <th>Dimulai</th>
                        <th>Berakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td>
                                <strong>{{ $subscription->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $subscription->user->email }}</small>
                            </td>
                            <td>{{ $subscription->plan->name }}</td>
                            <td>
                                <span class="badge bg-{{ $subscription->getStatusBadgeColor() }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($subscription->billing_cycle) }}</td>
                            <td>{{ $subscription->getFormattedPrice() }}</td>
                            <td>{{ $subscription->started_at?->format('d M Y') }}</td>
                            <td>{{ $subscription->ended_at?->format('d M Y') }}</td>
                            <td>
                                <a 
                                    href="{{ route('admin.subscriptions.show', $subscription) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-eye me-1"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox"></i> Belum ada data langganan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($subscriptions->hasPages())
        <div class="mt-4">
            {{ $subscriptions->links() }}
        </div>
    @endif
</div>
@endsection
```

---

Lanjutan di file routes.php...
