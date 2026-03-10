@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('expense_type_active', 'active')

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
        background: linear-gradient(135deg, #f59e0b, #d97706); /* ពណ៌ទឹកក្រូចសម្រាប់ Expense */
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
        font-size: 0.85rem;
        text-transform: uppercase;
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

    .badge-status {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        --bs-badge-color: #07f335;
        color: var(--bs-badge-color);
    }

    .badge-1 { color: #4ade80; }
    .badge-0 { color: #f87171;  }
</style>

<div class="app-content mb-2 p-0">
    <div class="container-fluid">
        <div class="header-card d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper me-3">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold text-dark">គ្រប់គ្រងប្រតិបត្តិការចំណាយ</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0">
                            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
                            <li class="breadcrumb-item active fw-semibold text-primary">Expense Transactions</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <a href="{{ route('expense_types.create') }}" class="btn btn-create px-4">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមចំណាយថ្មី
            </a>
        </div>
    </div>
</div>

<div class="app-content p-0">
    <div class="container-fluid">
        {{-- Search & Filter --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
            <div class="card-body">
                <form id="filterForm" action="{{ route('expense_types.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5">
                            <label class="form-label small fw-bold text-muted">ស្វែងរក</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="searchInput" name="search" class="form-control bg-light border-start-0"
                                       placeholder="ស្វែងរកតាមលេខកូដ..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label small fw-bold text-muted">ធនាគារ</label>
                            <select name="bank_id" class="form-select bg-light" onchange="this.form.submit()">
                                <option value="">ធនាគារទាំងអស់</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ request('bank_id') == $bank->id ? 'selected' : '' }}>{{ $bank->bank_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label small fw-bold text-muted">ស្ថានភាព</label>
                            <select id="statusFilter" name="status" class="form-select bg-light">
                                <option value="">ទាំងអស់</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>សកម្ម</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>មិនសកម្ម</option>
                            </select>
                        </div>
                        <div class="col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn btn-info text-white w-100 fw-bold">ស្វែងរក</button>
                            <a href="{{ route('expense_types.index') }}" class="btn btn-light border w-50">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive shadow-sm" style="border-radius: 12px;">
            <table class="table table-dark-soft table-hover align-middle mb-0 text-white">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px">ល.រ</th>
                        <th>លេខកូដ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                        <th>ប្រភេទចំណាយ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                        <th>ចំណាយតាមរយៈ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                        <th class="text-end">ចំនួនទឹកប្រាក់ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                        <th class="text-center">ស្ថានភាព</th>
                        <th class="text-center" style="width: 130px">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expense_types as $index => $item)
                    <tr>
                        <td class="text-center text-muted small">{{ $expense_types->firstItem() + $index }}</td>
                        <td class="text-info fw-semibold">{{ $item->code }}</td>
                        <td>
                            <div class="fw-bold">{{ $item->itemExpense->name ?? 'N/A' }}</div>
                            {{-- <div class="small text-muted">ID: #{{ $item->expens_id }}</div> --}}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-bank2 me-2 text-warning"></i>
                                <span>{{ $item->bank->bank_name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-end fw-bold text-success">
                            ${{ number_format($item->amount, 2) }}
                        </td>
                        <td class="text-center">
                            @if($item->status == 'active')
                                <span class="badge-1 badge-active">
                                    <i class="bi bi-check-circle-fill me-1"></i> សកម្ម
                                </span>
                            @else
                                <span class="badge-0 text-danger">
                                    <i class="bi bi-x-circle-fill me-1"></i> មិនសកម្ម
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('expense_types.edit', $item->id) }}" class="btn btn-sm btn-outline-light border-0 action-btn">
                                    <i class="bi bi-pencil-square text-warning fs-5"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-light border-0 delete-confirm"
                                        data-id="{{ $item->id }}" data-name="{{ $item->code }}">
                                    <i class="bi bi-trash3-fill text-danger fs-5"></i>
                                </button>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('expense_types.destroy', $item->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                            មិនមានទិន្នន័យចំណាយឡើយ
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <p class="small text-muted">បង្ហាញ {{ $expense_types->firstItem() }} ដល់ {{ $expense_types->lastItem() }} នៃទិន្នន័យសរុប {{ $expense_types->total() }}</p>
            {{ $expense_types->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // ១. សួរមុននឹងលុប (SweetAlert2 Soft Dark Theme)
    $(document).on('click', '.delete-confirm', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: `អ្នកចង់លុបប្រតិបត្តិការកូដ "${name}" នេះមែនទេ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'យល់ព្រមលុប',
            cancelButtonText: 'បោះបង់',
            background: '#1e293b',
            color: '#ffffff',
            reverseButtons: true, // ដាក់ប៊ូតុងបោះបង់នៅខាងឆ្វេង (UX ល្អជាង)
            showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' }
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete-form-${id}`).submit();
            }
        });
    });

    // ២. ចម្រោះទិន្នន័យ (Auto-submit with Safe Check)
    const filterForm = document.getElementById("filterForm");
    const statusFilter = document.getElementById("statusFilter");

    if (statusFilter && filterForm) {
        statusFilter.addEventListener("change", () => filterForm.submit());
    }

    // ៣. មុខងារ Sort តារាង (Soft Experience)
    document.querySelectorAll('.sort-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const th = this.closest('th');
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            // បញ្ឈប់ការ Sort បើគ្មានទិន្នន័យ (Empty Row)
            if (rows.length <= 1 && rows[0].innerText.includes('មិនមានទិន្នន័យ')) return;

            const index = Array.from(th.parentNode.children).indexOf(th);
            const isAscending = th.classList.contains('sort-asc');

            // ប្តូរ UI State
            document.querySelectorAll('th').forEach(header =>
                header.classList.remove('sorting-active', 'sort-asc', 'sort-desc')
            );
            th.classList.add('sorting-active', isAscending ? 'sort-desc' : 'sort-asc');

            // Effect មុននឹង Sort (Soft Fade)
            tbody.style.opacity = '0.4';
            tbody.style.transition = 'opacity 0.2s ease';

            setTimeout(() => {
                rows.sort((a, b) => {
                    const valA = a.children[index].innerText.trim();
                    const valB = b.children[index].innerText.trim();

                    return isAscending
                        ? valB.localeCompare(valA, undefined, {numeric: true, sensitivity: 'base'})
                        : valA.localeCompare(valB, undefined, {numeric: true, sensitivity: 'base'});
                });

                rows.forEach(row => tbody.appendChild(row));
                tbody.style.opacity = '1';
            }, 200);
        });
    });
});
</script>
@endpush
