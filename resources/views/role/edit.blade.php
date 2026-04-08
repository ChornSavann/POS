@extends('layout.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4">
            <h4 class="fw-bold text-dark"><i class="fas fa-edit me-2"></i>កែសម្រួលតួនាទី៖ {{ $role->label_kh }}</h4>
        </div>

        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- សំខាន់៖ សម្រាប់ការ Update ត្រូវប្រើ PUT Method --}}

            <div class="row">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ឈ្មោះជាភាសាខ្មែរ</label>
                                <input type="text" name="label_kh" class="form-control border-2"
                                    value="{{ old('label_kh', $role->label_kh) }}" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">ឈ្មោះក្នុងប្រព័ន្ធ (System Name)</label>
                                <input type="text" name="name" class="form-control border-2"
                                    value="{{ old('name', $role->name) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-light w-50 py-2 fw-bold border shadow-sm">
                            <i class="bi bi-x-circle me-1 text-danger"></i> បោះបង់
                        </a>

                        <button type="submit" class="btn btn-primary w-50 py-2 fw-bold shadow-sm">
                            <i class="fas fa-sync-alt me-1"></i> ធ្វើបច្ចុប្បន្នភាព
                        </button>
                    </div>

                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-shield-alt me-1"></i>
                                កែសម្រួលសិទ្ធិប្រើប្រាស់</h6>
                        </div>
                        <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                            @foreach ($permissions as $group => $items)
                                <div class="mb-4">
                                    <h6 class="text-muted fw-bold small text-uppercase mb-3 border-bottom pb-2">
                                        <i class="fas fa-folder-open me-1"></i> ផ្នែក៖ {{ $group }}
                                    </h6>
                                    <div class="row g-2">
                                        @foreach ($items as $permission)
                                            <div class="col-md-6 col-xl-4">
                                                <div class="form-check custom-checkbox p-2 border rounded-2">
                                                    <input class="form-check-input ms-0 me-2" type="checkbox"
                                                        name="permissions[]" value="{{ $permission->id }}"
                                                        id="perm{{ $permission->id }}"
                                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                    {{-- ឆែកមើលបើមានក្នុង DB ឱ្យវា Checked --}}
                                                    <label class="form-check-label fw-semibold small"
                                                        for="perm{{ $permission->id }}">
                                                        {{ $permission->label_kh }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .custom-checkbox:hover {
            border-color: #0d6efd !important;
            background-color: #f8f9ff !important;
            cursor: pointer;
        }
    </style>
@endsection
