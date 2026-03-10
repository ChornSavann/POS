@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('supplier', 'active')

@section('content')
<div class="app-content mb-3 p-0">
    <div class="container-fluid">
        <div class="header-card d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 bg-white rounded shadow-sm">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper me-3 bg-success bg-opacity-10 p-3 rounded-circle">
                    <i class="bi bi-truck text-success fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-dark">គ្រប់គ្រងអ្នកផ្គត់ផ្គង់ (Suppliers)</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                            <li class="breadcrumb-item active fw-semibold text-success">Supplier List</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary px-3">
                    <i class="bi bi-printer me-1"></i> បោះពុម្ព
                </button>

                <a href="{{ route('suppliers.create') }}" class="btn btn-success px-4 fw-bold shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">បញ្ជីអ្នកផ្គត់ផ្គង់ (Supplier List)</h5>
                <a href="{{ route('suppliers.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Supplier
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Contact Info</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th width="120" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $supplier->name }}</td>
                            <td>
                                @if(strtolower($supplier->gender) == 'male')
                                    <span class="text-primary"><i class="bi bi-gender-male"></i> ប្រុស</span>
                                @else
                                    <span class="text-danger"><i class="bi bi-gender-female"></i> ស្រី</span>
                                @endif
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-telephone text-muted me-1"></i> {{ $supplier->phone }}</div>
                                <div class="small"><i class="bi bi-envelope text-muted me-1"></i> {{ $supplier->email ?? 'N/A' }}</div>
                            </td>
                            <td class="small text-muted">{{ Str::limit($supplier->address, 40) }}</td>
                            <td>
                                @if($supplier->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm delete-confirm"
                                            data-id="{{ $supplier->id }}"
                                            data-name="{{ $supplier->name }}"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No suppliers found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.delete-confirm', function (e) {
        e.preventDefault();
        const supplierId = $(this).data('id');
        const supplierName = $(this).data('name');
        const form = $('#delete-form-' + supplierId);

        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: `អ្នកនឹងលុបអ្នកផ្គត់ផ្គង់ "${supplierName}" ហើយមិនអាចត្រឡប់ក្រោយបានឡើយ!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'បាទ/ចាស លុបវា!',
            cancelButtonText: 'បោះបង់',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush
