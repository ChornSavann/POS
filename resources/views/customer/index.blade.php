
@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('customer', 'active')

@push('styles')
<style>
    /* Custom CSS ដើម្បីបង្កើនសម្រស់ */
    .table-custom thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #495057;
        border-top: none !important;
    }
    .customer-name {
        font-size: 0.95rem;
        color: #2d3748;
        transition: all 0.2s;
    }
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .badge-soft-primary {
        background-color: #e0e7ff;
        color: #4338ca;
    }
    .card-custom {
        border-radius: 15px;
        overflow: hidden;
    }
    .search-wrapper .input-group-text {
        background: transparent;
        border-right: none;
    }
    .search-wrapper .form-control {
        border-left: none;
    }
    .search-wrapper .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="app-content mb-3 p-0">
    <div class="container-fluid">
        <div class="header-card d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 bg-white rounded shadow-sm"
             style="border-left: 5px solid #6366f1;">

            <div class="d-flex align-items-center">
                <div class="icon-wrapper me-3 bg-indigo bg-opacity-10 p-3 rounded-circle" style="background-color: rgba(99, 102, 241, 0.1);">
                    <i class="bi bi-people-fill text-indigo fs-4" style="color: #6366f1;"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-dark">គ្រប់គ្រងអតិថិជន (Customers)</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0 p-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                            <li class="breadcrumb-item active fw-semibold" style="color: #6366f1;">បញ្ជីអតិថិជន</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('customer.create') }}" class="btn px-4 fw-bold shadow-sm py-2 text-white"
                   style="border-radius: 10px; background-color: #6366f1;">
                    <i class="bi bi-person-plus-fill me-1"></i> បន្ថែមអតិថិជនថ្មី
                </a>
            </div>
        </div>
    </div>
</div>

        <div class="card card-custom border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4 px-4">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="mb-0 fw-bold">គ្រប់គ្រងអតិថិជន</h5>
                    </div>
                   <div class="col-md-8">
                        <div class="d-flex justify-content-md-end search-wrapper">
                            <form action="{{ route('customer.index') }}" method="GET" id="searchForm" class="d-flex gap-2">
                                <div class="input-group input-group-merge border rounded-3 overflow-hidden shadow-sm" style="width: 380px;">
                                    <span class="input-group-text border-0 bg-white py-2 pe-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>

                                    <input type="text" name="search" id="searchInput" class="form-control border-0 py-2"
                                        placeholder="ស្វែងរកតាមឈ្មោះ ឬកូដ..." value="{{ request('search') }}" autocomplete="off">

                                    @if(request('search'))
                                        <a href="{{ route('customer.index') }}" class="btn border-0 py-2 text-muted bg-white">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    @endif

                                    <button type="submit" class="btn btn-primary border-0 px-3">
                                        ស្វែងរក
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">លេខរៀង</th>
                            <th>ព័ត៌មានអតិថិជន</th>
                            <th>តំបន់</th>
                            <th>ព័ត៌មានទំនាក់ទំនង</th>
                            <th>អាសយដ្ឋាន</th>
                            <th class="text-center pe-4">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td class="ps-4">
                                <span class="text-muted fw-medium">{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; background: #eef2ff;">
                                        {{ Str::upper(Str::substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="customer-name fw-bold">{{ $customer->name }}</div>
                                        <span class="badge badge-soft-primary text-danger px-2 py-1 small">{{ $customer->customer_code }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-dark">
                                    <i class="bi bi-geo-alt-fill text-danger me-2 small"></i>
                                    {{ $customer->zone ?? 'មិនទាន់កំណត់' }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="small fw-medium"><i class="bi bi-phone me-2 text-muted"></i>{{ $customer->phone ?? '---' }}</span>
                                    <span class="small text-muted"><i class="bi bi-envelope me-2 text-muted"></i>{{ $customer->email }}</span>
                                </div>
                            </td>
                            <td>
                                <p class="text-muted mb-0 small" style="max-width: 200px;">
                                    {{ Str::limit($customer->address, 40) }}
                                </p>
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-action btn-light text-primary border" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-action btn-light text-danger border delete-confirm"
                                            data-id="{{ $customer->id }}" data-name="{{ $customer->name }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $customer->id }}" action="{{ route('customer.destroy', $customer->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-25">
                                <h6 class="text-muted fw-light">មិនមានទិន្នន័យអតិថិជនស្វែងរកឡើយ</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
            <div class="card-footer bg-white border-0 py-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted small mb-0">បង្ហាញ {{ $customers->firstItem() }} ដល់ {{ $customers->lastItem() }} នៃទិន្នន័យសរុប {{ $customers->total() }}</p>
                    {{ $customers->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
$(document).ready(function() {
    // Real-time search with visual feedback
    let timeout = null;
    $('#searchInput').on('input', function() {
        clearTimeout(timeout);
        $(this).parent().addClass('shadow-sm');
        timeout = setTimeout(() => {
            $('#searchForm').submit();
        }, 1000);
    });

    // Custom SweetAlert2 Style
    $(document).on('click', '.delete-confirm', function(e) {
        e.preventDefault();
        const name = $(this).data('name');
        const formId = '#delete-form-' + $(this).data('id');

        Swal.fire({
            title: 'លុបទិន្នន័យ?',
            html: `តើអ្នកចង់លុបអតិថិជន <b>"${name}"</b> ឬ?<br>ទិន្នន័យនឹងបាត់បង់ពីប្រព័ន្ធ!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'បាទ លុបចេញ',
            cancelButtonText: 'បោះបង់',
            buttonsStyling: true,
            customClass: {
                confirmButton: 'btn btn-danger px-4 py-2 mx-2',
                cancelButton: 'btn btn-light px-4 py-2 mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $(formId).submit();
            }
        });
    });
});
</script>
@endpush
