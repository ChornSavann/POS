@extends('layout.app')

@section('content')
<div class="card card-info card-outline mb-4">
    <div class="card-header">
        <div class="card-title">Edit Unit: {{ $unit->name }}</div>
    </div>
    
    <form action="{{ route('units.update', $unit->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                {{-- Unit Name --}}
                <div class="col-md-6">
                    <label class="form-label">Unit Name</label>
                    <input type="text" name="name" value="{{ old('name', $unit->name) }}" 
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
                            {{-- ការពារកុំឱ្យយកខ្លួនឯងធ្វើជា Base Unit ឱ្យខ្លួនឯង --}}
                            @if($u->id != $unit->id)
                                <option value="{{ $u->id }}" {{ old('baseunit_id', $unit->baseunit_id) == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- Operator & Value Section --}}
                <div id="operator_section" class="col-12 d-none">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Operator (ប្រមាណវិធី)</label>
                            <select name="operator" class="form-select">
                                <option value="*" {{ old('operator', $unit->operator) == '*' ? 'selected' : '' }}>Multiply (*)</option>
                                <option value="/" {{ old('operator', $unit->operator) == '/' ? 'selected' : '' }}>Divide (/)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Operator Value (តម្លៃបំបែក)</label>
                            <input type="number" step="0.01" name="operator_value" 
                                   value="{{ old('operator_value', $unit->operator_value) }}" 
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
                    {{-- បានបន្ថែម $unit->note ចូលក្នុង Textarea --}}
                    <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="2">{{ old('note', $unit->note) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-info text-white" type="submit">Update Unit</button>
            <a href="{{ route('units.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function checkBaseUnit() {
            var baseUnitId = $('#baseunit_id').val();
            
            if (baseUnitId == "" || baseUnitId == null) {
                $('#operator_section').addClass('d-none').hide();
            } else {
                $('#operator_section').removeClass('d-none').show();
            }
        }

        checkBaseUnit();

        $('#baseunit_id').on('change', function() {
            checkBaseUnit();
        });
    });
</script>
@endpush