@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('bank_active', 'active')

@section('content')

<style>
    /* Header Custom Style */
    .header-card {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        border-radius: 12px;
        font-size: 22px;
    }

    /* Form Card Style */
    .bank-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .bank-card .card-header {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        padding: 15px 25px;
        border: none;
    }

    /* Inputs Style */
    .form-label {
        color: #444;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        border-color: #6366f1;
    }

    /* Buttons Style */
    .btn-save {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 600;
        transition: transform 0.2s;
    }

    .btn-save:hover {
        transform: translateY(-1px);
        color: white;
        opacity: 0.95;
    }

    .btn-back {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 500;
    }


    /* ចលនាវិលត្រឡប់នៃ Icon */
    @keyframes spin-soft {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .btn-generate-active i {
        animation: spin-soft 0.6s ease-in-out;
    }

    /* ចលនាចុចប៊ូតុង (Pulse Effect) */
    .btn-generate {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-generate:active {
        transform: scale(0.95); /* រួញចូលបន្តិចពេលចុច */
    }

    /* Effect ពេលបញ្ចូលលេខជោគជ័យ */
    .input-flash {
        animation: flash-blue 0.8s;
    }

    @keyframes flash-blue {
        0% { background-color: #fff; }
        50% { background-color: #e0e7ff; border-color: #6366f1; }
        100% { background-color: #fff; }
    }
</style>

<div class="container-fluid ">
    {{-- ប្លុកចំណងជើង (HEADER) --}}
    <div class="header-card d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper me-3">
                <i class="bi bi-bank"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold text-dark">គ្រប់គ្រងធនាគារ</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="bi bi-house-door"></i> ទំព័រដើម
                            </a>
                        </li>
                        <li class="breadcrumb-item active fw-semibold text-primary">
                            {{ isset($bank) ? 'កែប្រែព័ត៌មានធនាគារ' : 'បន្ថែមធនាគារថ្មី' }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="{{ route('bank.index') }}" class="btn btn-light btn-back shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    {{-- ប្លុកផ្នែកបញ្ចូលទិន្នន័យ (FORM CARD) --}}
    <div class="card bank-card">
        <div class="card-header text-white">
            <h5 class="mb-0 fw-medium">
                <i class="bi bi-plus-circle me-2"></i>
                {{ isset($bank) ? 'កែប្រែធនាគារ' : 'បញ្ចូលព័ត៌មានធនាគារ' }}
            </h5>
        </div>

        <div class="card-body p-4 p-lg-5">
            <form action="{{ isset($bank) ? route('bank.update', $bank->id) : route('bank.store') }}" method="POST">
                @csrf
                @if(isset($bank)) @method('PUT') @endif

                <div class="row g-4">
                    {{-- ឈ្មោះធនាគារ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ឈ្មោះធនាគារ <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name"
                               class="form-control @error('bank_name') is-invalid @enderror"
                               value="{{ old('bank_name', $bank->bank_name ?? '') }}"
                               placeholder="ឧទាហរណ៍៖ ABA Bank">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ឈ្មោះម្ចាស់គណនី --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ឈ្មោះម្ចាស់គណនី <span class="text-danger">*</span></label>
                        <input type="text" name="account_name"
                               class="form-control @error('account_name') is-invalid @enderror"
                               value="{{ old('account_name', $bank->account_name ?? '') }}"
                               placeholder="ឧទាហរណ៍៖ CHHEANG SOKHEANG">
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                   {{-- លេខគណនី --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">លេខគណនី (Account Number) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="account_number" id="account_number"
                                class="form-control @error('account_number') is-invalid @enderror"
                                value="{{ old('account_number', $bank->account_number ?? '') }}"
                                placeholder="000 123 456">

                            <button class="btn btn-save btn-generate" type="button" id="btnGenerate">
                                <i class="fa-solid fa-shuffle"></i>
                            </button>
                        </div>
                    </div>

                    {{-- រូបិយប័ណ្ណ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">រូបិយប័ណ្ណ <span class="text-danger">*</span></label>
                        <select name="currency" class="form-select @error('currency') is-invalid @enderror">
                            <option value="USD" {{ old('currency', $bank->currency ?? '') == 'USD' ? 'selected' : '' }}>
                                USD - ដុល្លារអាមេរិក
                            </option>
                            <option value="KHR" {{ old('currency', $bank->currency ?? '') == 'KHR' ? 'selected' : '' }}>
                                KHR - រៀលខ្មែរ
                            </option>
                        </select>
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- សមតុល្យដើមគ្រា --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">សមតុល្យដើមគ្រា</label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" name="opening_balance"
                                   class="form-control @error('opening_balance') is-invalid @enderror"
                                   value="{{ old('current_balance', $bank->current_balance ?? '0.00') }}">
                        </div>
                        @error('opening_balance')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ស្ថានភាព --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ស្ថានភាព</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $bank->is_active ?? 1) == 1 ? 'selected' : '' }}>សកម្ម (Active)</option>
                            <option value="0" {{ old('is_active', $bank->is_active ?? 1) == 0 ? 'selected' : '' }}>មិនសកម្ម (Inactive)</option>
                        </select>
                    </div>
                </div>

                {{-- ប៊ូតុងសកម្មភាព --}}
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('bank.index') }}" class="btn btn-light px-4 border shadow-sm" style="border-radius: 10px;">
                        បោះបង់
                    </a>
                    <button type="submit" class="btn btn-save shadow">
                        <i class="bi bi-check2-circle me-1"></i> រក្សាទុកទិន្នន័យ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    document.getElementById('btnGenerate').addEventListener('click', function() {
    const btn = this;
    const inputField = document.getElementById('account_number');

    // ១. បន្ថែមចលនាវិលទៅលើ Icon
    btn.classList.add('btn-generate-active');

    // ២. បង្កើតលេខចៃដន្យចន្លោះពី 1 ដល់ 99999999
    const randomNum = Math.floor(Math.random() * 99999999) + 1;

    // ៣. បំប្លែងទៅជាទម្រង់ P-00000000 (ប្រើ padStart ដើម្បីថែមលេខ ០ ឱ្យគ្រប់ ៨ ខ្ទង់)
    const formattedCode = 'P-' + randomNum.toString().padStart(8, '0');

    // បញ្ចូលតម្លៃទៅក្នុង Input
    inputField.value = formattedCode;

    // ៤. បន្ថែមចលនា Flash ទៅលើ Input
    inputField.classList.remove('input-flash');
    void inputField.offsetWidth;
    inputField.classList.add('input-flash');

    setTimeout(() => {
        btn.classList.remove('btn-generate-active');
    }, 600);
});
</script>
@endpush
