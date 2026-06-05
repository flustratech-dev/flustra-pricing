@extends('layouts.admin')

@section('page_title', 'Tagihan & Invoices')

@section('content')
<div class="row g-4">
    <div class="col-12 mb-2">
        <p class="text-muted mb-0 small">Lihat seluruh transaksi pembayaran, verifikasi status, dan kirim pengingat manual.</p>
    </div>

    <!-- Filters -->
    <div class="col-12">
        <div class="card p-3">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small">Status Pembayaran</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-funnel-fill me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary w-100 py-2">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Invoice</th>
                            <th>Pengguna</th>
                            <th>Paket Langganan</th>
                            <th>Status</th>
                            <th>Nominal Tagihan</th>
                            <th>Dibayar Pada</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td>
                                    <strong class="text-dark text-uppercase" style="font-size: 0.85rem;">{{ $inv->invoice_number }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $inv->subscription->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $inv->subscription->user->email }}</small>
                                </td>
                                <td>{{ $inv->subscription->plan->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </td>
                                <td><strong>{{ $inv->getFormattedAmount() }}</strong></td>
                                <td class="small text-secondary">{{ $inv->paid_at?->format('d M Y H:i') ?: '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.invoices.show', $inv) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye-fill me-1"></i>Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-file-earmark-text fs-1 d-block mb-3"></i>
                                    Belum ada tagihan invoices diterbitkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection
