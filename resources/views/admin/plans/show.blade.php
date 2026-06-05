@extends('layouts.admin')

@section('page_title', 'Rincian Paket')

@section('content')
<div class="row g-4">
    <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-fill me-1"></i> Ubah Paket
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

    <!-- Plan details info -->
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header">Spesifikasi Detail</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <li class="d-flex justify-content-between">
                        <span>Nama Paket:</span>
                        <strong class="text-dark">{{ $plan->name }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Slug:</span>
                        <strong class="text-dark">{{ $plan->slug }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Kategori:</span>
                        <span class="badge bg-secondary">{{ ucfirst($plan->category) }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Tier Tingkat:</span>
                        <span class="badge bg-dark">{{ ucfirst($plan->tier ?: '—') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Harga Bulanan:</span>
                        <strong class="text-dark">{{ 'Rp ' . number_format($plan->price_monthly, 0, ',', '.') }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Harga Tahunan:</span>
                        <strong class="text-dark">{{ 'Rp ' . number_format($plan->price_yearly, 0, ',', '.') }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Diskon Tahunan:</span>
                        <strong class="text-warning">{{ $plan->getYearlyDiscountPercentage() }}%</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Status Publik:</span>
                        <span class="badge bg-{{ $plan->is_active ? 'success' : 'danger' }}">
                            {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Tanda Populer:</span>
                        <strong class="text-dark">{{ $plan->is_popular ? 'Ya' : 'Tidak' }}</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Urutan Tampil:</span>
                        <strong class="text-dark">Ke-{{ $plan->display_order }}</strong>
                    </li>
                    <li class="d-flex flex-column border-top border-secondary border-opacity-10 pt-3">
                        <span class="text-muted small mb-2">Deskripsi Paket:</span>
                        <p class="text-dark small mb-0">{{ $plan->description ?: 'Tidak ada deskripsi.' }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Features List -->
    <div class="col-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header">Keuntungan Fitur Terdaftar</div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    @forelse($plan->features as $feat)
                        <div class="d-flex align-items-center gap-3 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color);">
                            <div class="fs-5 text-success"><i class="bi {{ $feat->icon_class ?: 'bi-check-circle-fill' }}"></i></div>
                            <div>
                                <h6 class="mb-0 text-dark font-semibold" style="font-size: 0.95rem;">{{ $feat->feature_name }}</h6>
                                @if($feat->feature_description)
                                    <small class="text-secondary">{{ $feat->feature_description }}</small>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-secondary text-center py-5">Belum ada fitur/keuntungan yang didaftarkan untuk paket ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
