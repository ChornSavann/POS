@extends('layout.app')

@section('content')
<div class="card card-info card-outline mb-4">
    <div class="card-header">
        <div class="card-title">Create New Unit</div>
    </div>
    
    <form action="{{ route('units.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="card-body">
            <div class="row g-3">
                {{-- Unit Name --}}
                <div class="col-md-6">
                    <label class="form-label">Unit Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="form-control @error('name') is-invalid @enderror" 
                           placeholder="ឧទាហរណ៍៖ កេស, កំប៉ុង, គីឡូ..." required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

               {{-- Base Unit --}}
                <div class="col-md-6">
                    <label class="form-label">Base Unit (ខ្នាតមេ)</label>
                    <select name="baseunit_id" id="baseunit_id" class="form-select">
                        <option value="">-- ជាខ្នាតមេ (None) --</option>
                        @foreach($baseUnits as $u)
                            <option value="{{ $u->id }}" {{ old('baseunit_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Operator & Value Section --}}
                {{-- ខ្ញុំប្រើ d-none class របស់ Bootstrap ដើម្បីលាក់វាពីដំបូង --}}
                <div id="operator_section" class="col-12 d-none">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Operator (ប្រមាណវិធី)</label>
                            <select name="operator" class="form-select">
                                <option value="*" {{ old('operator') == '*' ? 'selected' : '' }}>Multiply (*)</option>
                                <option value="/" {{ old('operator') == '/' ? 'selected' : '' }}>Divide (/)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Operator Value (តម្លៃបំបែក)</label>
                            <input type="number" step="0.01" name="operator_value" 
                                   value="{{ old('operator_value', 1) }}" 
                                   class="form-control @error('operator_value') is-invalid @enderror" />
                            @error('operator_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- Note --}}
                <div class="col-12">
                    <label class="form-label">Note (ចំណាំ)</label>
                    <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="2">{{ old('note') }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-info text-white" type="submit">Save Unit</button>
            <a href="{{ route('units.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

{{-- Script សម្រាប់ SweetAlert2 ទុកដដែល គ្រាន់តែដូរពាក្យ "Category" ទៅ "Unit" --}}
@push('scripts')
<script>
    $(document).on('click', '.delete-confirm', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name');
        const form = $('#delete-form-' + id);

        Swal.fire({
            title: 'Delete Unit?',
            text: `Are you sure you want to delete "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

   $(document).ready(function() {
        function checkBaseUnit() {
            var baseUnitId = $('#baseunit_id').val();
            
            if (baseUnitId == "" || baseUnitId == null) {
                // បើជាខ្នាតមេ -> លាក់ (ថែម d-none និង hide)
                $('#operator_section').addClass('d-none').hide();
            } else {
                // បើមានជ្រើសរើសខ្នាតមេ -> បង្ហាញ (ដក d-none និង show)
                $('#operator_section').removeClass('d-none').show();
            }
        }

        // ហៅឱ្យដើរភ្លាមៗពេល Page Load
        checkBaseUnit();

        // ឱ្យវាដើររាល់ពេលប្តូរ Value
        $('#baseunit_id').on('change', function() {
            checkBaseUnit();
        });
    });
</script>

@endpush