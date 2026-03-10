@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('customer', 'active')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark"><i class="bi bi-person-plus-fill text-primary"></i> Add New Customer</h4>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb float-sm-end small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">ព័ត៌មានអតិថិជន (Customer Information)</h5>
        </div>

        <form action="{{ route('customer.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row g-3">
                    {{-- Customer Code --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Customer Code <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-qr-code"></i></span>
                            <input type="text" name="customer_code" class="form-control @error('customer_code') is-invalid @enderror" 
                                   placeholder="ឧទាហរណ៍: CUS-001" value="{{ old('customer_code') }}" required>
                        </div>
                        @error('customer_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Customer Name --}}
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="ឈ្មោះអតិថិជន" value="{{ old('name') }}" required>
                        </div>
                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="example@mail.com" value="{{ old('email') }}" required>
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   placeholder="012 345 678" value="{{ old('phone') }}">
                        </div>
                        @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Zone --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Zone / Region</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                            <select name="zone" class="form-select @error('zone') is-invalid @enderror">
                                <option value="">-- ជ្រើសរើសតំបន់ --</option>
                                <option value="Phnom Penh" {{ old('zone') == 'Phnom Penh' ? 'selected' : '' }}>Phnom Penh</option>
                                <option value="Kandal" {{ old('zone') == 'Kandal' ? 'selected' : '' }}>Kandal</option>
                                <option value="Siem Reap" {{ old('zone') == 'Siem Reap' ? 'selected' : '' }}>Siem Reap</option>
                                <option value="Other" {{ old('zone') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        @error('zone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active (ដំណើរការ)</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive (ផ្អាក)</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Address --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Full Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                  rows="3" placeholder="អាសយដ្ឋានបច្ចុប្បន្ន...">{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white py-3 text-end">
                <a href="{{ route('customer.index') }}" class="btn btn-light border px-4 me-2">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save2"></i> Save Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection