@extends('layout.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark"><i class="bi bi-plus-circle-fill text-success"></i> Add New Supplier</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card card-outline card-success shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">ព័ត៌មានអ្នកផ្គត់ផ្គង់ (Supplier Information)</h5>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="card-body">
                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="បញ្ចូលឈ្មោះអ្នកផ្គត់ផ្គង់" value="{{ old('name') }}" required>
                        </div>
                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Gender Section --}}
<div class="col-md-4">
    <label class="form-label fw-bold">Gender (ភេទ) <span class="text-danger">*</span></label>
    <div class="d-flex mt-2">
        {{-- Male Radio --}}
        <div class="form-check me-3">
            <input class="form-check-input @error('gender') is-invalid @enderror" 
                   type="radio" name="gender" id="male" value="male" 
                   {{ old('gender') == 'male' ? 'checked' : '' }}>
            <label class="form-check-label" for="male">
                <i class="bi bi-gender-male text-primary"></i> ប្រុស (Male)
            </label>
        </div>

        {{-- Female Radio --}}
        <div class="form-check">
            <input class="form-check-input @error('gender') is-invalid @enderror" 
                   type="radio" name="gender" id="female" value="female" 
                   {{ old('gender') == 'female' ? 'checked' : '' }}>
            <label class="form-check-label" for="female">
                <i class="bi bi-gender-female text-danger"></i> ស្រី (Female)
            </label>
        </div>
    </div>
    
    {{-- បង្ហាញ Error Message ប្រសិនបើមាន --}}
    @error('gender')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   placeholder="012 345 678" value="{{ old('phone') }}" required>
                        </div>
                        @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="example@mail.com" value="{{ old('email') }}">
                        </div>
                        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active (ដំណើរការ)</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive (ផ្អាក)</option>
                        </select>
                    </div>

                    {{-- Address --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                  rows="3" placeholder="អាសយដ្ឋានបច្ចុប្បន្ន...">{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white py-3 text-end">
                <a href="{{ route('suppliers.index') }}" class="btn btn-light border px-4 me-2">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save2"></i> Save Supplier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection