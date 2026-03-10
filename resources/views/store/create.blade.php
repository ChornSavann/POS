@extends('layout.app')

@section('content')
<div class="app-content-header bg-white border-bottom mb-4">
    <div class="container-fluid py-3">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-plus-circle-fill me-2 text-primary"></i>ចុះឈ្មោះហាងថ្មី
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('store.index') }}" class="text-decoration-none text-muted">Store List</a></li>
                        <li class="breadcrumb-item active fw-bold text-primary">Create Store</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm mb-5" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title mb-0 fw-bold text-muted small text-uppercase">ព័ត៌មានលម្អិតរបស់ហាង (Store Details)</h5>
                </div>
                
                <form action="{{ route('store.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <div class="card-body p-4">
                        <div class="row g-4">
                            {{-- Store Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">ឈ្មោះហាង <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-shop"></i></span>
                                    <input type="text" name="name" value="{{ old('name') }}" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="ឧទាហរណ៍៖ ហាងលក់ទំនិញទំនើប..." required>
                                </div>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">ស្ថានភាពហាង <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>បើកដំណើរការ (Active)</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>បិទបណ្ដោះអាសន្ន (Inactive)</option>
                                </select>
                                @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Phone Number --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">លេខទូរស័ព្ទ</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" value="{{ old('phone') }}" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           placeholder="012 345 678">
                                </div>
                                @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Email Address --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">អ៊ីមែលហាង</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" value="{{ old('email') }}" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="store@example.com">
                                </div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Store Logo --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Logo របស់ហាង</label>
                                <div class="d-flex align-items-center gap-3 p-3 border rounded bg-light">
                                    <div class="preview-box border rounded bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px; overflow: hidden;">
                                        <img id="logo-preview" src="{{ asset('assets/img/no-image.png') }}" class="w-100 h-100 object-fit-cover" alt="Preview">
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" 
                                               accept="image/*" onchange="previewImage(event)">
                                        <div class="form-text mt-1">ទំហំដែលណែនាំ៖ 500x500px (JPG, PNG, WebP)</div>
                                    </div>
                                </div>
                                @error('logo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Address --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">អាសយដ្ឋានហាង</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                          rows="3" placeholder="បញ្ចូលទីតាំងហាងជាក់ស្ដែង...">{{ old('address') }}</textarea>
                                @error('address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light py-4 text-end border-top-0" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                        <a href="{{ route('store.index') }}" class="btn btn-outline-secondary px-4 me-2">បោះបង់</a>
                        <button class="btn btn-primary px-5 shadow-sm fw-bold" type="submit">
                            <i class="bi bi-save me-1"></i> រក្សាទុកទិន្នន័យ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    // Bootstrap Validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endpush