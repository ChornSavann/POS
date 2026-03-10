@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('item_expense_active', 'active') {{-- កែឈ្មោះតាម Sidebar active របស់អ្នក --}}

@section('content')
<style>
    /* រចនាបថ Header & Buttons */
    .header-card {
        background: #ffffff;
        padding: 18px 24px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #f1f1f1;
    }

    .icon-wrapper {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        border-radius: 10px;
        font-size: 20px;
    }

    .btn-create {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 18px;
        transition: 0.3s;
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
        color: white;
    }

    /* រចនាបថតារាង Dark Soft */
    .table-dark-soft {
        background-color: #1e293b !important;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-dark-soft thead th {
        background-color: #334155 !important;
        color: #f1f5f9 !important;
        font-weight: 500;
        border: none;
        padding: 15px;
        font-size: 0.9rem;
    }

    .table-dark-soft tbody tr {
        border-bottom: 1px solid #334155;
    }

    .table-dark-soft.table-hover tbody tr:hover {
        background-color: #334155 !important;
    }

    .sort-icon {
        font-size: 0.75rem;
        margin-left: 5px;
        color: #64748b;
        cursor: pointer;
    }

    .badge-active {
        background-color: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
        padding: 5px 10px;
        border-radius: 6px;
    }
</style>

<div class="app-content mb-3 p-1">
    <div class="container-fluid">
        <div class="header-card d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper me-3">
                    <i class="bi bi-cart-dash"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-dark">គ្រប់គ្រងប្រភេទចំណាយ</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0">
                            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
                            <li class="breadcrumb-item active fw-semibold text-primary">Expense Types</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <a href="{{ route('item_expense.create') }}" class="btn btn-create px-4">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមប្រភេទចំណាយ
            </a>
        </div>
    </div>
</div>

<div class="app-content p-0">
    <div class="container-fluid">
        {{-- Search & Filter --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
            <div class="card-body">
                <form id="filterForm" action="{{ route('item_expense.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-6">
                            <label class="form-label small fw-bold text-muted">ស្វែងរក</label>
                            <input type="text" id="searchInput" name="search" class="form-control"
                                   placeholder="ស្វែងរកតាមឈ្មោះ ឬលេខកូដ..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label small fw-bold text-muted">ស្ថានភាព</label>
                            <select id="statusFilter" name="status" class="form-select">
                                <option value="">ទាំងអស់</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>សកម្ម</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>មិនសកម្ម</option>
                            </select>
                        </div>
                        <div class="col-lg-3 d-flex gap-2">
                            <button type="submit" class="btn btn-info text-white w-100">ស្វែងរក</button>
                            <a href="{{ route('item_expense.index') }}" class="btn btn-light border w-50">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-dark-soft table-hover align-middle mb-0 text-white">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px">ល.រ</th>
                        <th>លេខកូដ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                        <th>ឈ្មោះប្រភេទចំណាយ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                        <th class="text-center">ស្ថានភាព</th>
                        <th class="text-center" style="width: 150px">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($itemExpenses as $index => $item)
                    <tr>
                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                        <td class="text-info fw-semibold">{{ $item->code }}</td>
                        <td class="fw-bold">{{ $item->name }}</td>
                        <td class="text-center">
                            @if($item->status == 'active')
                                <span class="badge badge-active">សកម្ម</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger">មិនសកម្ម</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('item_expense.edit', $item->id) }}" class="btn btn-sm btn-outline-light border-0">
                                    <i class="bi bi-pencil-fill text-warning"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-light border-0 delete-confirm"
                                        data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                    <i class="bi bi-trash-fill text-danger"></i>
                                </button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('item_expense.destroy', $item->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">មិនមានទិន្នន័យឡើយ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // សួរមុននឹងលុប (SweetAlert2)
    $(document).on('click', '.delete-confirm', function(){
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: `អ្នកចង់លុបប្រភេទចំណាយ "${name}" នេះមែនទេ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'យល់ព្រមលុប',
            cancelButtonText: 'បោះបង់'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete-form-${id}`).submit();
            }
        });
    });

    // ចម្រោះទិន្នន័យ (Filter & Search)
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("filterForm");
        const searchInput = document.getElementById("searchInput");
        const statusFilter = document.getElementById("statusFilter");

        let delayTimer;
        searchInput.addEventListener("keyup", function () {
            clearTimeout(delayTimer);
            delayTimer = setTimeout(() => form.submit(), 600);
        });

        statusFilter.addEventListener("change", () => form.submit());
    });

    // មុខងារ Sort តារាង
    document.querySelectorAll('.sort-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const th = this.closest('th');
            const index = Array.from(th.parentNode.children).indexOf(th);
            const asc = this.asc = !this.asc;

            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.sort((a, b) => {
                const valA = a.children[index].innerText;
                const valB = b.children[index].innerText;
                return asc ? valA.localeCompare(valB) : valB.localeCompare(valA);
            }).forEach(row => tbody.appendChild(row));
        });
    });
</script>
@endpush
