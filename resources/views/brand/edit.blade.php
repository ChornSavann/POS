@extends('layout.app')
@section('brand_active', 'active')

@section('content')
<div class="card card-info card-outline mb-4">
    <div class="card-header">
        <div class="card-title">កែប្រែម៉ាកយីហោ (Edit Brand: {{ $brand->name }})</div>
    </div>
    
    <form action="{{ route('brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                {{-- Brand Name --}}
                <div class="col-md-6">
                    <label class="form-label">Brand Name</label>
                    <input type="text" name="name" value="{{ old('name', $brand->name) }}" 
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="បញ្ចូលឈ្មោះម៉ាក..." required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="1" {{ old('status', $brand->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $brand->status) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Brand Logo --}}
                <div class="col-md-12">
                    <label class="form-label">Brand Logo (រូបភាព)</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                           accept="image/*" onchange="previewImage(event)">
                    
                    <div class="mt-2 d-flex gap-3">
                        {{-- បង្ហាញរូបភាពចាស់ --}}
                        <div>
                            <small class="text-muted d-block">រូបភាពបច្ចុប្បន្ន:</small>
                            @if($brand->image && file_exists(public_path('Image/brands/' . $brand->image)))
                                <img src="{{ asset('Image/brands/' . $brand->image) }}" class="img-thumbnail" style="max-height: 100px;">
                            @else
                                <img src="{{ asset('assets/img/no-image.png') }}" class="img-thumbnail" style="max-height: 100px;">
                            @endif
                        </div>
                        
                        {{-- កន្លែងបង្ហាញ Preview រូបភាពថ្មី --}}
                        <div id="preview-container" style="display: none;">
                            <small class="text-success d-block">រូបភាពថ្មី (Preview):</small>
                            <img id="logo-preview" src="#" alt="Preview" style="max-height: 100px;" class="img-thumbnail">
                        </div>
                    </div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label">Description (ការពិពណ៌នា)</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="3" placeholder="ព័ត៌មានបន្ថែម...">{{ old('description', $brand->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('brand.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-info text-white" type="submit">
                <i class="bi bi-save"></i> Update Brand
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('logo-preview');
            var container = document.getElementById('preview-container');
            output.src = reader.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush