@extends('layout.app')
@section('customer', 'active')

@section('content')
<div class="container-fluid mt-4">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0 fw-bold">កែប្រែព័ត៌មានអតិថិជន (Edit Customer)</h5>
        </div>

        <form action="{{ route('customer.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- សំខាន់បំផុតសម្រាប់មុខងារ Update --}}
            
            <div class="card-body">
                <div class="row g-3">
                    {{-- Customer Code (Readonly ប្រសិនបើមិនចង់ឱ្យកែ) --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Customer Code</label>
                        <input type="text" name="customer_code" class="form-control" 
                               value="{{ old('customer_code', $customer->customer_code) }}" readonly>
                    </div>

                    {{-- Customer Name --}}
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $customer->name) }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $customer->email) }}" required>
                    </div>

                    {{-- Zone --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Zone</label>
                        <select name="zone" class="form-select">
                            <option value="Phnom Penh" {{ $customer->zone == 'Phnom Penh' ? 'selected' : '' }}>Phnom Penh</option>
                            <option value="Kandal" {{ $customer->zone == 'Kandal' ? 'selected' : '' }}>Kandal</option>
                            <option value="Siem Reap" {{ $customer->zone == 'Siem Reap' ? 'selected' : '' }}>Siem Reap</option>
                        </select>
                    </div>
                {{-- Address --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                               value="{{ old('address', $customer->address) }}" required>
                    </div>
                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $customer->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $customer->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white text-end">
                <a href="{{ route('customer.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Update Customer</button>
            </div>
        </form>
    </div>
</div>
@endsection