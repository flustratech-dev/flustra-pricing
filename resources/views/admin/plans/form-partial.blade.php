<!-- Plan Basic Info -->
<div class="card mb-4">
    <div class="card-header">Dasar Rincian Paket</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
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
            
            <div class="col-md-6">
                <label class="form-label">Slug *</label>
                <input 
                    type="text" 
                    class="form-control @error('slug') is-invalid @enderror" 
                    name="slug" 
                    value="{{ old('slug', $plan->slug ?? '') }}" 
                    placeholder="Contoh: personal-mid"
                    required
                >
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Kategori *</label>
                <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ (old('category', $plan->category ?? '') === $cat) ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Tier Tingkat *</label>
                <select class="form-select @error('tier') is-invalid @enderror" name="tier" required>
                    <option value="">Pilih Tier</option>
                    @foreach($tiers as $tr)
                        <option value="{{ $tr }}" {{ (old('tier', $plan->tier ?? '') === $tr) ? 'selected' : '' }}>
                            {{ ucfirst($tr) }}
                        </option>
                    @endforeach
                </select>
                @error('tier')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
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

            <div class="col-12">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Jelaskan secara ringkas peruntukan paket ini...">{{ old('description', $plan->description ?? '') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

<!-- Pricing Settings -->
<div class="card mb-4">
    <div class="card-header">Pengaturan Harga (IDR)</div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Harga Bulanan *</label>
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

            <div class="col-md-6">
                <label class="form-label">Harga Tahunan *</label>
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
            </div>

            <div class="col-md-6 d-flex align-items-center gap-4 pt-3">
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        name="is_popular" 
                        value="1" 
                        id="chkPopular"
                        {{ old('is_popular', $plan->is_popular ?? false) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="chkPopular">Tandai sebagai Paling Populer</label>
                </div>

                <div class="form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        name="is_active" 
                        value="1" 
                        id="chkActive"
                        {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="chkActive">Aktifkan Paket Publik</label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Plan Features -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Fitur & Keuntungan</span>
        <button type="button" class="btn btn-sm btn-outline-info" onclick="addFeatureRow()">
            <i class="bi bi-plus-circle me-1"></i>Tambah Baris Fitur
        </button>
    </div>
    <div class="card-body">
        <div id="featuresContainer">
            @php
                $features = old('features', isset($plan) ? $plan->features : []);
            @endphp

            @forelse($features as $index => $feat)
                <div class="row g-2 feature-row mb-3 pb-3 border-bottom border-secondary border-opacity-10 align-items-center" data-index="{{ $index }}">
                    <div class="col-md-7">
                        <label class="form-label small">Nama Fitur</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="features[{{ $index }}][name]" 
                            value="{{ $feat->feature_name ?? $feat['name'] ?? '' }}" 
                            placeholder="Contoh: Dompet Bersama (Joint Account)"
                            required
                        >
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Icon Class (Bootstrap Icons)</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="features[{{ $index }}][icon]" 
                            value="{{ $feat->icon_class ?? $feat['icon'] ?? 'bi-check' }}" 
                            placeholder="bi-check"
                        >
                    </div>
                    <div class="col-md-2 d-flex justify-content-end pt-4">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFeatureRow(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-secondary small" id="noFeaturesPlaceholder">
                    Belum ada keuntungan fitur ditambahkan. Klik 'Tambah Baris Fitur' untuk mengisi list.
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    let featureIndex = {{ count($features) }};

    function addFeatureRow() {
        const container = document.getElementById('featuresContainer');
        const placeholder = document.getElementById('noFeaturesPlaceholder');
        if (placeholder) {
            placeholder.remove();
        }

        const html = `
            <div class="row g-2 feature-row mb-3 pb-3 border-bottom border-secondary border-opacity-10 align-items-center" data-index="${featureIndex}">
                <div class="col-md-7">
                    <label class="form-label small">Nama Fitur</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="features[${featureIndex}][name]" 
                        placeholder="Contoh: Pencatatan Pemasukan"
                        required
                    >
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Icon Class (Bootstrap Icons)</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="features[${featureIndex}][icon]" 
                        value="bi-check" 
                        placeholder="bi-check"
                    >
                </div>
                <div class="col-md-2 d-flex justify-content-end pt-4">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFeatureRow(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        featureIndex++;
    }

    function removeFeatureRow(button) {
        button.closest('.feature-row').remove();
    }
</script>
@endpush
