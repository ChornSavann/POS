@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('store_active', 'active')
@section('content')
<style>
    /* បន្ថែម Gradient និង Soft Shadow */
    .btn-primary-gradient {
        background: linear-gradient(45deg, #0d6efd, #004dc7);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
    .card-custom {
        border-radius: 12px;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    /* រចនាប៊ូតុងមូល */
    .btn-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }
    .btn-edit-soft { background-color: #eef2ff; color: #4f46e5; }
    .btn-edit-soft:hover { background-color: #4f46e5; color: white; transform: translateY(-2px); }
    
    .btn-delete-soft { background-color: #fff1f2; color: #e11d48; }
    .btn-delete-soft:hover { background-color: #e11d48; color: white; transform: translateY(-2px); }

    /* ប៊ូតុង Detail សម្រាប់មើលព័ត៌មានហាង */
    .btn-view-soft { background-color: #f0fdf4; color: #16a34a; }
    .btn-view-soft:hover { background-color: #16a34a; color: white; transform: translateY(-2px); }

    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #6c757d;
    }
    .badge-subtle {
        padding: 0.5em 0.8em;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.7rem;
    }
    .bg-success-subtle { background-color: #d1e7dd; color: #0f5132; border: 1px solid #a3cfbb; }
    .bg-danger-subtle { background-color: #f8d7da; color: #842029; border: 1px solid #f1aeb5; }


</style>

<div class="app-content-header bg-white border-bottom mb-3">
    <div class="container-fluid py-3">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-shop me-2 text-primary"></i>គ្រប់គ្រងហាង (Stores)
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active fw-bold text-primary">Store List</li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('store.create') }}" class="btn btn-sm btn-primary-gradient px-4 text-white shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> បន្ថែមហាងថ្មី
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        {{-- Search & Filter Section --}}
        <div class="card card-custom mb-4">
            <div class="card-body p-4">
                <form action="{{ route('store.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5 col-md-6">
                            <label class="form-label small fw-bold text-muted">ស្វែងរកហាង</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="ស្វែងរកតាមឈ្មោះ, លេខទូរស័ព្ទ ឬអ៊ីមែល..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-muted">ស្ថានភាព</label>
                            <select name="status" class="form-select">
                                <option value="">ស្ថានភាពទាំងអស់</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>បើកដំណើរការ (Active)</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>បិទបណ្ដោះអាសន្ន (Inactive)</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-info text-white px-4 shadow-sm w-100">
                                <i class="bi bi-filter me-1"></i> ស្វែងរក
                            </button>
                            <a href="{{ route('store.index') }}" class="btn btn-light border px-4 shadow-sm">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="card card-custom">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px">#</th>
                                <th style="width: 80px">Logo</th>
                                <th>Store Information</th>
                                <th>Contact Details</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stores as $store)
                            <tr>
                                <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="p-1 bg-white border rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; overflow: hidden;">
                                        @php $logoPath = 'Image/stores/' . $store->logo; @endphp
                                        <img src="{{ ($store->logo && file_exists(public_path($logoPath))) ? asset($logoPath) : asset('assets/img/no-image.png') }}" 
                                             class="w-100 h-100 object-fit-cover">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $store->name }}</div>
                                    <div class="small text-muted text-truncate" style="max-width: 200px;">
                                        <i class="bi bi-geo-alt-fill me-1"></i> {{ $store->address ?: 'មិនទាន់មានអាសយដ្ឋាន' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><i class="bi bi-telephone-fill me-2 text-primary"></i>{{ $store->phone ?: 'N/A' }}</div>
                                    <div class="small text-muted"><i class="bi bi-envelope-fill me-2 text-info"></i>{{ $store->email ?: 'N/A' }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge-subtle {{ $store->status ? 'bg-success-subtle' : 'bg-danger-subtle' }}">
                                        <i class="bi {{ $store->status ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                        {{ $store->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                       <button type="button" 
                                                class="btn btn-sm btn-info view-store-btn text-white​​ rounded-circle" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewStoreModal"
                                                data-name="{{ $store->name }}"
                                                data-phone="{{ $store->phone ?? 'N/A' }}"
                                                data-email="{{ $store->email ?? 'N/A' }}"
                                                data-address="{{ $store->address ?? 'N/A' }}"
                                                data-status="{{ $store->status }}"
                                                data-logo="{{ $store->logo ? asset('Image/stores/'.$store->logo) : asset('assets/img/no-image.png') }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        {{-- Edit --}}
                                        <a href="{{ route('store.edit', $store->id) }}" class="btn-circle btn-edit-soft shadow-sm" title="កែសម្រួល">
                                            <i class="bi bi-pencil-fill small"></i>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('store.destroy', $store->id) }}" method="POST" id="delete-form-{{ $store->id }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-circle btn-delete-soft shadow-sm delete-confirm" 
                                                    data-id="{{ $store->id }}" data-name="{{ $store->name }}" title="លុបហាង">
                                                <i class="bi bi-trash-fill small"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-shop text-muted display-4 d-block mb-3"></i>
                                    <p class="text-muted">មិនទាន់មានទិន្នន័យហាងក្នុងប្រព័ន្ធនៅឡើយទេ។</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Pagination --}}
            <div class="card-footer bg-white py-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">Showing {{ $stores->firstItem() }} to {{ $stores->lastItem() }} of {{ $stores->total() }} stores</span>
                    <div>{{ $stores->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('store.show') {{-- Modal សម្រាប់បង្ហាញព័ត៌មានលម្អិតរបស់ហាង --}}

{{-- Scripts លោកអ្នកអាចប្រើ scripts ដែលមានពីមុនបាន --}}
@endsection
@push('scripts')
<script>
    // មុខងារបង្ហាញរូបភាព Preview ភ្លាមៗពេលរើស File
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('logo-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // SweetAlert សម្រាប់ Delete (រក្សាទុកក្នុង Layout ឬទំព័រ Index)
    $(document).on('click', '.delete-confirm', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name');
        const form = $('#delete-form-' + id);

        Swal.fire({
            title: 'Delete Store?',
            text: `តើអ្នកប្រាកដថាចង់លុបហាង "${name}" មែនទេ?`,
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

    // បង្ហាញព័ត៌មានលម្អិតរបស់ហាងនៅក្នុង Modal
    $(document).ready(function() {
        $('.view-store-btn').on('click', function() {
            // ១. ចាប់យកទិន្នន័យពី data-attributes
            const store = $(this).data();

            // ២. បញ្ចូលទៅក្នុង Modal Elements
            $('#view-name').text(store.name);
            $('#view-slug').text('@' + store.slug);
            $('#view-phone').text(store.phone);
            $('#view-email').text(store.email);
            $('#view-address').text(store.address);
            $('#view-logo').attr('src', store.logo);

        
            // ក្នុង function Click របស់អ្នក
            const status = $(this).data('status');
            const statusDot = $('#view-status-dot');
            const statusText = $('#view-status-text');

            if (status == 1) {
                statusText.text('Active Now').addClass('bg-success text-white').removeClass('bg-danger');
                statusDot.addClass('bg-success').removeClass('bg-danger');
            } else {
                statusText.text('Inactive').addClass('bg-danger text-white').removeClass('bg-success');
                statusDot.addClass('bg-danger').removeClass('bg-success');
            }
                });
    });

</script>
@endpush