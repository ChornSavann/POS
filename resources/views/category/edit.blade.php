@extends('layout.app')

@section('content')
<div class="card card-info card-outline mb-4">
    <div class="card-header">
        <div class="card-title">Create New Category</div>
    </div>
    
    <form action="{{ route('category.update', $category->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                {{-- Category Name --}}
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" 
                           class="form-control @error('name') is-invalid @enderror" required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $category->description ) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-info text-white" type="submit">Save Category</button>
            <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
