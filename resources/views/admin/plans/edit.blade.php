@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-white"><i class="bi bi-pencil-square text-primary me-2"></i>Ubah Paket</h2>
            <p class="text-secondary mb-0" style="color: var(--text-muted);">Ubah spesifikasi, harga, atau keuntungan paket {{ $plan->name }}.</p>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="btn btn-neon-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="col-12 col-lg-8 mx-auto">
        <form action="{{ route('admin.plans.update', $plan) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.plans.form-partial')

            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="{{ route('admin.plans.show', $plan) }}" class="btn btn-neon-secondary">Batal</a>
                <button type="submit" class="btn btn-neon-primary px-4"><i class="bi bi-save-fill me-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
