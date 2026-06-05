@extends('layouts.admin')

@section('page_title', 'Buat Paket Baru')

@section('content')
<div class="row g-4">
    <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="col-12 col-lg-8 mx-auto">
        <form action="{{ route('admin.plans.store') }}" method="POST">
            @csrf
            
            @include('admin.plans.form-partial')

            <div class="d-flex gap-3 justify-content-end mt-4">
                <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Simpan Paket</button>
            </div>
        </form>
    </div>
</div>
@endsection
