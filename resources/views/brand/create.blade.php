@extends('layout.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 text-dark"><i class="bi bi-award-fill text-warning"></i> Brands Management</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brand.index') }}">Brands</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card card-info card-outline shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="card-title fw-bold">
                <i class="bi bi-plus-circle-fill me-1"></i> បង្កើតម៉ាកយីហោថ្មី (Create New Brand)
            </div>
        </div>
        
        <form action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="card-body">
                <div class="row g-4">
                    {{-- Brand Name --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-tag-fill me-1 text-info"></i> Brand Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="ឧទាហរណ៍៖ Apple, Samsung..." required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-toggle-on me-1 text-success"></i> Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option selected disabled value="">ជ្រើសរើសស្ថានភាព...</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active (បើកដំណើរការ)</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive (បិទដំណើរការ)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Brand Logo --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold"><i class="bi bi-image-fill me-1 text-primary"></i> Brand Logo</label>
                        <div class="input-group">
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                                   id="brandImage" accept="image/*" onchange="previewImage(event)">
                            <label class="input-group-text" for="brandImage">Upload</label>
                        </div>
                        <div class="mt-3 p-2 border rounded bg-light d-inline-block" style="min-width: 120px; min-height: 120px; text-align: center;">
                            <img id="logo-preview" src="{{ asset('assets/img/no-image.png') }}" 
                                 alt="Preview" style="max-height: 100px; object-fit: contain;" 
                                 class="img-fluid rounded shadow-sm">
                            <p class="small text-muted mt-1 mb-0" id="preview-text">រូបភាពសាកល្បង</p>
                        </div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label fw-bold"><i class="bi bi-chat-left-text-fill me-1 text-secondary"></i> Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" placeholder="ព័ត៌មានបន្ថែមពីម៉ាកយីហោនេះ...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white py-3 text-end">
                <a href="{{ route('brand.index') }}" class="btn btn-light border me-2">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button class="btn btn-info text-white px-4" type="submit">
                    <i class="bi bi-save2-fill me-1"></i> Save Brand
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // មុខងារបង្ហាញរូបភាព Preview ភ្លាមៗ
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('logo-preview');
            var text = document.getElementById('preview-text');
            output.src = reader.result;
            output.classList.add('border-primary');
            text.innerText = "រូបភាពដែលបានរើស";
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush