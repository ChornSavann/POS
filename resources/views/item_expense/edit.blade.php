@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('item_expense_active', 'active')

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
        background: linear-gradient(135deg, #f59e0b, #fbbf24); /* ពណ៌ទឹកក្រូចសម្រាប់ Edit */
        color: white;
        border-radius: 12px;
        font-size: 22px;
    }

    /* Form Card Style */
    .expense-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .expense-card .card-header {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        padding: 15px 25px;
        border: none;
    }

    /* Inputs Style */
    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        border-color: #f59e0b;
    }

    .btn-update {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 600;
        transition: transform 0.2s;
    }

    .btn-update:hover {
        transform: translateY(-1px);
        color: white;
        opacity: 0.95;
    }
</style>

<div class="container-fluid">
    {{-- HEADER --}}
    <div class="header-card d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper me-3">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <h4 class="mb-1 fw-bold text-dark">គ្រប់គ្រងប្រភេទចំណាយ</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">ទំព័រដើម</a></li>
                        <li class="breadcrumb-item active fw-semibold text-warning">កែប្រែប្រភេទចំណាយ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="{{ route('item_expense.index') }}" class="btn btn-light px-4 border shadow-sm" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="card expense-card">
        <div class="card-header text-white">
            <h5 class="mb-0 fw-medium">
                <i class="bi bi-pencil-square me-2"></i> កែប្រែព័ត៌មាន៖ {{ $itemExpense->name }}
            </h5>
        </div>

        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('item_expense.update', $itemExpense->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- លេខកូដ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">លេខកូដសម្គាល់ <span class="text-danger">*</span></label>
                        <input type="text" name="code"
                               class="form-control bg-light @error('code') is-invalid @enderror"
                               value="{{ old('code', $itemExpense->code) }}" readonly>
                        @error('code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ឈ្មោះប្រភេទចំណាយ --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ឈ្មោះប្រភេទចំណាយ <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $itemExpense->name) }}"
                               placeholder="ឧទាហរណ៍៖ ចំណាយលើសម្ភារៈការិយាល័យ">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ស្ថានភាព --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">ស្ថានភាព</label>
                        <select name="status" class="form-select">
                            {{-- ឬប្រសិនបើក្នុង DB ជាអក្សរ active/inactive សូមប្រើខាងក្រោមនេះជំនួសវិញ --}}
                            <option value="active" {{ old('status', $itemExpense->status) == 'active' ? 'selected' : '' }}>សកម្ម (Active)</option>
                            <option value="inactive" {{ old('status', $itemExpense->status) == 'inactive' ? 'selected' : '' }}>មិនសកម្ម (Inactive)</option>

                        </select>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('item_expense.index') }}" class="btn btn-light px-4 border" style="border-radius: 10px;">បោះបង់</a>
                    <button type="submit" class="btn btn-update shadow text-white">
                        <i class="bi bi-save me-1"></i> រក្សាទុកការផ្លាស់ប្តូរ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
