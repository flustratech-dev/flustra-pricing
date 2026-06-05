@extends('layouts.admin')

@section('page_title', 'Ubah Paket')

@section('content')
<div class="row g-4">
    <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
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
