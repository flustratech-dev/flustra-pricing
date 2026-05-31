@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-white"><i class="bi bi-tags-fill text-primary me-2"></i>Daftar Paket Berlangganan</h2>
            <p class="text-secondary mb-0" style="color: var(--text-muted);">Kelola paket, tier, harga bulanan/tahunan, serta keuntungan fitur pendukung.</p>
        </div>
        <a href="{{ route('admin.plans.create') }}" class="btn btn-neon-primary">
            <i class="bi bi-plus-circle me-2"></i>Buat Paket Baru
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

    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Paket</th>
                            <th>Kategori</th>
                            <th>Tier</th>
                            <th>Harga Bulanan</th>
                            <th>Harga Tahunan</th>
                            <th>Status</th>
                            <th>Populer</th>
                            <th class="text-end">Aksi</th>
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
                                    <span class="badge bg-secondary">{{ ucfirst($plan->category) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-dark">{{ ucfirst($plan->tier ?: '—') }}</span>
                                </td>
                                <td>{{ 'Rp ' . number_format($plan->price_monthly, 0, ',', '.') }}</td>
                                <td>{{ 'Rp ' . number_format($plan->price_yearly, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $plan->is_active ? 'success' : 'danger' }}">
                                        {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    @if($plan->is_popular)
                                        <span class="text-warning"><i class="bi bi-star-fill me-1"></i>Ya</span>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.plans.show', $plan) }}" class="btn btn-outline-info" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-outline-primary" title="Ubah">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini beserta semua fiturnya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-tags fs-1 d-block mb-3"></i>
                                    Belum ada paket yang terdaftar. Mulai dengan membuat paket baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
