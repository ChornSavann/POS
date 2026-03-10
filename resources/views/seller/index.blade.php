@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('seller_active', 'active') {{-- ប្តូរឈ្មោះ Section active ឱ្យត្រូវតាម menu របស់បង --}}

@section('content')
<div class="app-content mb-3 p-0">
    <div class="container-fluid">
        <div class="header-card d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 bg-white rounded shadow-sm"
             style="border-left: 5px solid #0d6efd;">

            <div class="d-flex align-items-center">
                <div class="icon-wrapper me-3 bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="bi bi-person-badge text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-dark">គ្រប់គ្រងបុគ្គលិកលក់ (Sellers)</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                            <li class="breadcrumb-item active fw-semibold text-primary">Seller List</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary px-3">
                    <i class="bi bi-download me-1"></i> Export
                </button>

                <a href="{{ route('seller.create') }}" class="btn btn-primary px-4 fw-bold shadow-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> បន្ថែមអ្នកលក់ថ្មី
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card card-outline card-primary shadow-sm"> {{-- ប្តូរពណ៌មកខៀវវិញឱ្យប្លែកពី Supplier --}}
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">បញ្ជីអ្នកលក់ (Seller List)</h5>
                <a href="{{ route('seller.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Seller
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
                        @forelse($sellers as $seller)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $seller->name }}</td>
                            <td>
                                @if($seller->gender == 'ប្រុស')
                                    <span class="text-primary"><i class="bi bi-gender-male"></i> ប្រុស</span>
                                @elseif($seller->gender == 'ស្រី')
                                    <span class="text-danger"><i class="bi bi-gender-female"></i> ស្រី</span>
                                @else
                                    <span class="text-muted"><i class="bi bi-gender-ambiguous"></i> ផ្សេងៗ</span>
                                @endif
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-telephone text-muted me-1"></i> {{ $seller->phone }}</div>
                                <div class="small"><i class="bi bi-envelope text-muted me-1"></i> {{ $seller->email }}</div>
                            </td>
                            <td class="small text-muted">{{ Str::limit($seller->address, 40) }}</td>
                            <td>
                                @if($seller->status == 1)
                                    <span class="badge rounded-pill bg-success-light text-success px-3 border border-success" style="background-color: #e8f5e9;">Active</span>
                                @else
                                    <span class="badge rounded-pill bg-danger-light text-danger px-3 border border-danger" style="background-color: #ffebee;">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('seller.edit', $seller->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm delete-confirm"
                                            data-id="{{ $seller->id }}"
                                            data-name="{{ $seller->name }}"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $seller->id }}" action="{{ route('seller.destroy', $seller->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No sellers found.
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
        const sellerId = $(this).data('id');
        const sellerName = $(this).data('name');
        const form = $('#delete-form-' + sellerId);

        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: `អ្នកនឹងលុបអ្នកលក់ "${sellerName}" ហើយមិនអាចត្រឡប់ក្រោយបានឡើយ!`,
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
