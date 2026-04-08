@extends('layout.app') {{-- ប្រាកដថា layouts.app ឬ layouts.admin --}}

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ isset($role) ? 'កែសម្រួលតួនាទី' : 'បង្កើតតួនាទីថ្មី' }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">កំណត់ត្រា</a></li>
                        <li class="breadcrumb-item active">{{ isset($role) ? 'កែសម្រួល' : 'បង្កើតថ្មី' }}</li>
                    </ol>
                </nav>
            </div>
            <button type="button" class="btn btn-outline-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createPermissionModal">
                <i class="fas fa-plus-circle me-1"></i> បង្កើតសិទ្ធិថ្មី
            </button>
        </div>

        <form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST">
            @csrf
            @if (isset($role))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-info-circle me-1 text-primary"></i> ព័ត៌មានតួនាទី</h6>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ឈ្មោះជាភាសាខ្មែរ</label>
                                <input type="text" name="label_kh" class="form-control border-2 @error('label_kh') is-invalid @enderror"
                                    value="{{ $role->label_kh ?? old('label_kh') }}" placeholder="ឧ៖ បុគ្គលិកលក់" required>
                                @error('label_kh') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">ឈ្មោះក្នុងប្រព័ន្ធ (System Name)</label>
                                <input type="text" name="name" class="form-control border-2 @error('name') is-invalid @enderror"
                                    value="{{ $role->name ?? old('name') }}" placeholder="ឧ៖ cashier" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 shadow-sm py-2 fw-bold rounded-3">
                        <i class="fas fa-save me-1"></i> រក្សាទុកទិន្នន័យ
                    </button>
                    <a href="{{ route('roles.index') }}" class="btn btn-light w-100 mt-2 rounded-3 text-muted">បោះបង់</a>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-shield-alt me-1"></i> កំណត់សិទ្ធិប្រើប្រាស់</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label small fw-bold" for="checkAll">ជ្រើសរើសទាំងអស់</label>
                            </div>
                        </div>
                        <div class="card-body overflow-auto" style="max-height: 700px;">
                            @foreach ($permissions as $group => $items)
                                <div class="mb-4 group-container">
                                    <h6 class="text-primary fw-bold small text-uppercase mb-3 border-bottom pb-2 d-flex align-items-center">
                                        <i class="fas fa-folder-open me-2"></i> ផ្នែក៖ {{ $group }}
                                        <span class="badge bg-light text-primary ms-2 border">{{ count($items) }} សិទ្ធិ</span>
                                    </h6>
                                    <div class="row g-2">
                                        @foreach ($items as $permission)
                                            <div class="col-md-6 col-xl-4">
                                                <div class="custom-checkbox-wrapper p-2 border rounded-2 bg-light-subtle h-100">
                                                    <div class="form-check mb-0">
                                                        <input class="form-check-input permission-item" type="checkbox"
                                                            name="permissions[]" value="{{ $permission->id }}"
                                                            id="perm{{ $permission->id }}"
                                                            {{ (isset($rolePermissions) && in_array($permission->id, $rolePermissions)) || (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold small text-dark d-block cursor-pointer" for="perm{{ $permission->id }}">
                                                            {{ $permission->label_kh }}
                                                        </label>
                                                    </div>
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

    <div class="modal fade" id="createPermissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold"><i class="fas fa-key me-2"></i>បង្កើតសិទ្ធិថ្មី</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ឈ្មោះសិទ្ធិ (System Name)</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="user_view" value="{{ old('name') }}" required>
                            <small class="text-muted">អក្សរតូច គ្មានដកឃ្លា (ឧ៖ user_delete)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ឈ្មោះបង្ហាញ (Label KH)</label>
                            <input type="text" name="label_kh" class="form-control @error('label_kh') is-invalid @enderror" placeholder="មើលបញ្ជីអ្នកប្រើប្រាស់" value="{{ old('label_kh') }}" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">ក្រុម (Group Name)</label>
                            <select name="group_name" class="form-select @error('group_name') is-invalid @enderror" required>
                                <option value="">--- ជ្រើសរើសក្រុម ---</option>
                                <option value="User Management">ការគ្រប់គ្រងអ្នកប្រើប្រាស់</option>
                                <option value="Product Management">ការគ្រប់គ្រងទំនិញ</option>
                                <option value="Sale Management">ការគ្រប់គ្រងការលក់</option>
                                <option value="Report Management">ការគ្រប់គ្រងរបាយការណ៍</option>
                                <option value="Setting">ការកំណត់</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">បិទ</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">រក្សាទុក</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .custom-checkbox-wrapper { transition: all 0.2s ease-in-out; border: 1px solid #e9ecef !important; }
        .custom-checkbox-wrapper:hover { border-color: #0d6efd !important; background-color: #f8f9ff !important; transform: translateY(-2px); }
        .cursor-pointer { cursor: pointer; }
        .bg-light-subtle { background-color: #fbfbfb; }
        .form-check-input { width: 1.2em; height: 1.2em; }
    </style>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ១. មុខងារ Check All
        const checkAll = document.getElementById('checkAll');
        const items = document.querySelectorAll('.permission-item');

        if(checkAll) {
            checkAll.addEventListener('change', function() {
                items.forEach(item => item.checked = this.checked);
            });
        }

        // ២. បើក Modal វិញបើមាន Error មកពី Validation
        @if ($errors->has('name') || $errors->has('label_kh') || $errors->has('group_name'))
            var myModal = new bootstrap.Modal(document.getElementById('createPermissionModal'));
            myModal.show();

            Swal.fire({
                icon: 'warning',
                title: 'ព័ត៌មានមិនត្រឹមត្រូវ',
                text: 'សូមពិនិត្យមើលទិន្នន័យក្នុង Modal ម្តងទៀត!',
                confirmButtonText: 'យល់ព្រម'
            });
        @endif
    });
</script>
@endpush
