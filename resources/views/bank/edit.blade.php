@extends('layout.app')

@section('setting_menu_open','menu-open')
@section('setting_active','active')
@section('bank_active','active')

@section('content')

<style>
    /* Header Custom Style */
    .header-card {
        background: #fff;
        padding: 16px 16px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #f1f1f1;
        margin-bottom: 20px;
    }

    .icon-wrapper {
        width: 47px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        border-radius: 10px;
        font-size: 20px;
    }

    /* Form Card Style */
    .bank-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .bank-card .card-header {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border-radius: 14px 14px 0 0;
        border: none;
    }

    /* Inputs Style */
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #e6e6e6;
    }

    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.15rem rgba(79, 70, 229, 0.2);
        border-color: #6366f1;
    }

    /* Buttons Style */
    .btn-save {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border: none;
        color: white;
        padding: 8px 18px;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-save:hover {
        opacity: 0.9;
        color: white;
    }
</style>

{{-- ផ្នែកក្បាលទំព័រ (HEADER) --}}
<div class="app-content mb-3">
    <div class="container-fluid">
        <div class="header-card d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper me-3">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-dark">កែសម្រួលធនាគារ</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-decoration-none text-muted">
                                    <i class="bi bi-house-door"></i> ទំព័រដើម
                                </a>
                            </li>
                            <li class="breadcrumb-item active fw-semibold text-primary">Edit Bank</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <a href="{{ route('bank.index') }}" class="btn btn-light shadow-sm">
                <i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ
            </a>
        </div>
    </div>
</div>

{{-- ផ្នែកខ្លឹមសារ (CONTENT) --}}
<div class="container-fluid pb-4">
    <div class="card bank-card">
        <div class="card-header text-white">
            <h5 class="mb-0">
                <i class="bi bi-bank2 me-2"></i> បច្ចុប្បន្នភាពព័ត៌មានធនាគារ
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('bank.update', $bank->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- ឈ្មោះធនាគារ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ឈ្មោះធនាគារ (Bank Name) <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name"
                               class="form-control @error('bank_name') is-invalid @enderror"
                               value="{{ old('bank_name', $bank->bank_name) }}"
                               placeholder="ABA Bank">
                        @error('bank_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ឈ្មោះម្ចាស់គណនី --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ឈ្មោះម្ចាស់គណនី (Account Holder) <span class="text-danger">*</span></label>
                        <input type="text" name="account_name"
                               class="form-control @error('account_name') is-invalid @enderror"
                               value="{{ old('account_name', $bank->account_name) }}"
                               placeholder="John Doe">
                        @error('account_name')
                            <small class="text-danger">{{ $message }}</small>
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
                        <label class="form-label fw-semibold">រូបិយប័ណ្ណ (Currency) <span class="text-danger">*</span></label>
                        <select name="currency" class="form-select @error('currency') is-invalid @enderror">
                            <option value="USD" {{ old('currency', $bank->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="KHR" {{ old('currency', $bank->currency) == 'KHR' ? 'selected' : '' }}>KHR - Khmer Riel</option>
                        </select>
                        @error('currency')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- សមតុល្យដើមគ្រា --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">សមតុល្យដើមគ្រា (Opening Balance)</label>
                        <input type="number" step="0.01" min="0" name="opening_balance"
                               class="form-control @error('opening_balance') is-invalid @enderror"
                               value="{{ old('opening_balance', $bank->opening_balance) }}">
                        @error('opening_balance')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ស្ថានភាព --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ស្ថានភាព (Status)</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $bank->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $bank->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                {{-- ប៊ូតុងសកម្មភាព --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('bank.index') }}" class="btn btn-light">បោះបង់</a>
                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check-circle me-1"></i> រក្សាទុកការកែប្រែ
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
