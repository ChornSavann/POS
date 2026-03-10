@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('expense_type_active', 'active')

@section('content')

{{-- រក្សារចនាបថ CSS ដដែលដូចទំព័រ Create --}}
<style>
    .header-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #f1f1f1; margin-bottom: 25px; }
    .icon-wrapper { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; border-radius: 12px; font-size: 22px; }
    .expense-card { border: none; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
    .expense-card .card-header { background: linear-gradient(135deg, #4f46e5, #6366f1); padding: 15px 25px; border: none; }
    .form-control, .form-select { border-radius: 10px; padding: 12px 15px; border: 1px solid #e2e8f0; transition: all 0.3s; }
    .form-control:focus, .form-select:focus { box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); border-color: #6366f1; }
    .btn-save { background: linear-gradient(135deg, #4f46e5, #6366f1); border: none; color: white; padding: 10px 25px; border-radius: 10px; font-weight: 600; transition: transform 0.2s; }
</style>

<div class="container-fluid px-4">
    {{-- HEADER --}}
    <div class="header-card d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper me-3">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold text-dark">កែសម្រួលប្រតិបត្តិការចំណាយ</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">ទំព័រដើម</a></li>
                        <li class="breadcrumb-item active fw-semibold text-primary">កែសម្រួលទិន្នន័យ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="{{ route('expense_types.index') }}" class="btn btn-light px-4 border shadow-sm" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="card expense-card mb-5">
        <div class="card-header text-white">
            <h5 class="mb-0 fw-medium">
                <i class="bi bi-info-circle me-2"></i> ព័ត៌មានលម្អិតនៃប្រតិបត្តិការ
            </h5>
        </div>

        <div class="card-body p-4 p-lg-5">
            {{-- ប្តូរមកប្រើ Update Route និង Method PUT --}}
            <form action="{{ route('expense_types.update', $expenseType->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- លេខកូដ (Readonly ក្នុងពេល Edit ដើម្បីការពារកំហុស Row Data) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">លេខកូដសម្គាល់ <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control bg-light"
                               value="{{ old('code', $expenseType->code) }}" readonly>
                    </div>

                    {{-- ចំនួនទឹកប្រាក់ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ចំនួនទឹកប្រាក់ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-currency-dollar text-success"></i></span>
                            <input type="number" step="0.01" name="amount"
                                   class="form-control border-start-0 @error('amount') is-invalid @enderror"
                                   value="{{ old('amount', $expenseType->amount) }}">
                        </div>
                        @error('amount') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                    </div>

                    {{-- ប្រភេទចំណាយ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ប្រភេទចំណាយមេ <span class="text-danger">*</span></label>
                        <select name="expens_id" class="form-select @error('expens_id') is-invalid @enderror">
                            @foreach($itemExpenses as $item)
                                <option value="{{ $item->id }}" {{ old('expens_id', $expenseType->expens_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} ({{ $item->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('expens_id') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                    </div>

                    {{-- ធនាគារ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">បង់តាមរយៈធនាគារ <span class="text-danger">*</span></label>
                        <select name="bank_id" class="form-select @error('bank_id') is-invalid @enderror">
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}" {{ old('bank_id', $expenseType->bank_id) == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank_id') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                    </div>

                    {{-- ស្ថានភាព --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ស្ថានភាព</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $expenseType->status) == 'active' ? 'selected' : '' }}>សកម្ម (Active)</option>
                            <option value="inactive" {{ old('status', $expenseType->status) == 'inactive' ? 'selected' : '' }}>មិនសកម្ម (Inactive)</option>
                        </select>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('expense_types.index') }}" class="btn btn-light px-4 border" style="border-radius: 10px;">បោះបង់</a>
                    <button type="submit" class="btn btn-save shadow px-5">
                        <i class="bi bi-cloud-check me-1"></i> រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
