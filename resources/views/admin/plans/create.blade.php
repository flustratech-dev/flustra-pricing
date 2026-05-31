@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-white"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Buat Paket Baru</h2>
            <p class="text-secondary mb-0" style="color: var(--text-muted);">Masukkan detail lengkap spesifikasi paket langganan Flustra.</p>
        </div>
        <a href="{{ route('admin.plans.index') }}" class="btn btn-neon-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="col-12 col-lg-8 mx-auto">
        <form action="{{ route('admin.plans.store') }}" method="POST">
            @csrf
            
            @include('admin.plans.form-partial')

            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="{{ route('admin.plans.index') }}" class="btn btn-neon-secondary">Batal</a>
                <button type="submit" class="btn btn-neon-primary px-4"><i class="bi bi-save me-2"></i>Simpan Paket</button>
            </div>
        </form>
    </div>
</div>
@endsection
