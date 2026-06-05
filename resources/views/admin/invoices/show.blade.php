@extends('layouts.admin')

@section('page_title', 'Detail Invoice')

@section('content')
<div class="row g-4">
    <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
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

    <div class="col-12 col-lg-8 mx-auto">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center border-bottom border-secondary border-opacity-10 pb-3 mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-1">INVOICE: {{ $invoice->invoice_number }}</h5>
                    <span class="text-secondary small">Diterbitkan: {{ $invoice->created_at?->format('d M Y H:i') }}</span>
                </div>
                <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'pending' ? 'warning' : 'danger') }} px-3 py-2 fs-6">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <span class="text-muted small d-block mb-1">DITAGIHKAN KEPADA:</span>
                    <strong class="text-dark d-block">{{ $invoice->subscription->user->name }}</strong>
                    <span class="text-secondary small">{{ $invoice->subscription->user->email }}</span>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted small d-block mb-1">MASA PENAGIHAN:</span>
                    <strong class="text-dark d-block">{{ $invoice->billing_period_start?->format('d M Y') }} - {{ $invoice->billing_period_end?->format('d M Y') }}</strong>
                    <span class="text-secondary small">Jatuh Tempo: {{ $invoice->due_date?->format('d M Y') }}</span>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Item Pembelian</th>
                            <th class="text-end">Nominal Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Langganan {{ $invoice->subscription->plan->name }}</strong>
                                <br>
                                <small class="text-muted">Siklus: {{ ucfirst($invoice->subscription->billing_cycle) }}</small>
                            </td>
                            <td class="text-end"><strong>{{ $invoice->getFormattedAmount() }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row g-3 justify-content-end mb-4 border-top border-secondary border-opacity-10 pt-3">
                <div class="col-md-5 text-end">
                    <div class="d-flex justify-content-between mb-2 text-secondary">
                        <span>Subtotal:</span>
                        <span class="text-dark">{{ $invoice->getFormattedAmount() }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-secondary">
                        <span>Total Bayar:</span>
                        <h4 class="fw-bold text-info" style="font-family: 'Outfit', sans-serif;">{{ $invoice->getFormattedAmount() }}</h4>
                    </div>
                </div>
            </div>

            <div class="border-top border-secondary border-opacity-10 pt-4 d-flex gap-3 justify-content-end">
                <form action="{{ route('admin.invoices.send', $invoice) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-envelope-fill me-2"></i>Kirim Email Pengingat
                    </button>
                </form>
                @if($invoice->status === 'paid')
                    <button class="btn btn-primary" disabled>
                        <i class="bi bi-patch-check-fill me-2"></i>Invoice Lunas
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
