@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('brand_active', 'active')

@section('content')
<style>
    /* បន្ថែម Gradient លើប៊ូតុង និងស្រមោលលើ Card */
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
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #6c757d;
        border-top: none;
    }
    /* ស្ទីលសម្រាប់ Badge បែបស្រាល (Subtle) */
    .badge-subtle {
        padding: 0.5em 0.8em;
        border-radius: 6px;
        font-weight: 600;
    }
    .bg-success-subtle { background-color: #d1e7dd; color: #0f5132; }
    .bg-danger-subtle { background-color: #f8d7da; color: #842029; }
</style>
<style>
    /* រចនាប៊ូតុងឱ្យមូល និងមាន Soft Hover */
    .btn-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
        border: none;
        text-decoration: none;
    }

    /* ពណ៌សម្រាប់ប៊ូតុង Edit */
    .btn-edit-soft {
        background-color: #eef2ff;
        color: #4f46e5;
    }
    .btn-edit-soft:hover {
        background-color: #4f46e5;
        color: white;
        transform: translateY(-2px);
    }

    /* ពណ៌សម្រាប់ប៊ូតុង Delete */
    .btn-delete-soft {
        background-color: #fff1f2;
        color: #e11d48;
    }
    .btn-delete-soft:hover {
        background-color: #e11d48;
        color: white;
        transform: translateY(-2px);
    }
</style>
<div class="app-content-header  bg-white border-bottom mb-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="mb-1 fw-bold text-dark">
                    <i class="bi bi-tag-fill me-2 text-primary"></i>គ្រប់គ្រងម៉ាកយីហោ
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active fw-bold text-primary">Brand List</li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-6">
                <div class="d-flex justify-content-end gap-2">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle shadow-sm px-3" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-1"></i> បញ្ចេញទិន្នន័យ
                        </button>
                        <ul class="dropdown-menu shadow border-0">
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-file-earmark-excel text-success me-2"></i> Export Excel</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Export PDF</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('brand.create') }}" class="btn btn-sm btn-primary-gradient px-4 text-white shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> បន្ថែមថ្មី
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card card-custom mb-4">
            <div class="card-body p-4">
                <form action="{{ route('brand.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label small fw-bold text-muted">ស្វែងរក (Search)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="ឈ្មោះម៉ាកយីហោ..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-muted">ស្ថានភាព (Status)</label>
                            <select name="status" class="form-select">
                                <option value="">ទាំងអស់ (All Status)</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-lg-5 col-md-12 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-info text-white px-4 shadow-sm">
                                <i class="bi bi-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('brand.index') }}" class="btn btn-light border px-4 shadow-sm">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class=" shadow-sm ">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 bg-dark">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 70px">#</th>
                                <th style="width: 100px">Logo</th>
                                <th>Brand Identity</th>
                                <th class="text-center" style="width: 150px">Status</th>
                                <th class="text-center" style="width: 150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            <tr>
                                <td class="text-center text-muted small">
                                    {{ ($brands->currentPage() - 1) * $brands->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="brand-logo-wrapper p-1 bg-white border rounded shadow-sm" style="width: 50px; height: 50px;">
                                        @php $imagePath = 'Image/brands/' . $brand->image; @endphp
                                        <img src="{{ ($brand->image && file_exists(public_path($imagePath))) ? asset($imagePath) : asset('assets/img/no-image.png') }}" 
                                             class="w-100 h-100 object-fit-contain rounded">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $brand->name }}</div>
                                    <div class="small text-muted"><i class="bi bi-link-45deg"></i> {{ $brand->slug }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge-subtle {{ $brand->status ? 'bg-success-subtle' : 'bg-danger-subtle' }} small">
                                        <i class="bi {{ $brand->status ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                        {{ $brand->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- ប៊ូតុងកែសម្រួល --}}
                                        <a href="{{ route('brand.edit', $brand->id) }}" 
                                        class="btn-circle btn-edit-soft shadow-sm" 
                                        title="កែសម្រួល">
                                            <i class="bi bi-pencil-fill small"></i>
                                        </a>

                                        {{-- ប៊ូតុងលុប --}}
                                        <form action="{{ route('brand.destroy', $brand->id) }}" 
                                            method="POST" 
                                            class="d-inline" 
                                            id="delete-form-{{ $brand->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" 
                                                    class="btn-circle btn-delete-soft shadow-sm delete-confirm" 
                                                    data-id="{{ $brand->id }}" 
                                                    data-name="{{ $brand->name }}" 
                                                    title="លុបចេញ">
                                                <i class="bi bi-trash-fill small"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-inbox text-muted display-4 d-block mb-3"></i>
                                    <p class="text-muted mb-0">មិនមានទិន្នន័យត្រូវបានរកឃើញឡើយក្នុងប្រព័ន្ធ។</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white py-3 border-top">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-muted small">
                            Showing <strong>{{ $brands->firstItem() ?? 0 }}</strong> to <strong>{{ $brands->lastItem() ?? 0 }}</strong> of <strong>{{ $brands->total() }}</strong> records
                        </span>
                    </div>
                    <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                        {{ $brands->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('click', function (e) {
        // ឆែកមើលថាតើអ្នកប្រើចុចចំប៊ូតុងដែលមាន class "delete-confirm" ឬទេ
        const deleteBtn = e.target.closest('.delete-confirm');
        
        if (deleteBtn) {
            e.preventDefault();
            
            const brandId = deleteBtn.getAttribute('data-id');
            const brandName = deleteBtn.getAttribute('data-name');
            const targetForm = document.getElementById('delete-form-' + brandId);

            Swal.fire({
                title: 'តើអ្នកប្រាកដទេ?',
                text: `អ្នកនឹងលុបម៉ាកយីហោ "${brandName}" នេះចេញពីប្រព័ន្ធ!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'យល់ព្រមលុប',
                cancelButtonText: 'បោះបង់',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    targetForm.submit(); // ផ្ញើទិន្នន័យទៅកាន់ Controller
                }
            });
        }
    });
</script>

<script>
    // Activate Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>