@extends('layout.app')

{{-- បើក Menu Setting និងឱ្យពណ៌លើ Category --}}
@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('category_active', 'active')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark"><i class="bi bi-tags-fill text-primary"></i> Category Management</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card card-primary card-outline shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="card-title fw-bold">
                <i class="bi bi-plus-circle-fill me-1 text-primary"></i> បង្កើតប្រភេទថ្មី (Create New Category)
            </div>
        </div>
        
        {{-- កែ Route ទៅជា category.store --}}
        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="card-body">
                <div class="row g-4">
                    {{-- Category Name --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Category Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="បញ្ចូលឈ្មោះប្រភេទ (ឧទាហរណ៍៖ ភេសជ្ជៈ...)" required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option selected disabled value="">ជ្រើសរើស...</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                   
                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Description (ការពិពណ៌នា)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="ព័ត៌មានបន្ថែម...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white text-end">
                <a href="{{ route('category.index') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-arrow-left-circle me-1"></i> Cancel
                </a>
                <button class="btn btn-primary px-4" type="submit">
                    <i class="bi bi-save-fill me-1"></i> Save Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('logo-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush