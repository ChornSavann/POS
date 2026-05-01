@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('banks_active', 'active')
@section('content')
<style>
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

<style>
        .header-card{
        background:#ffffff;
        padding:18px 24px;
        border-radius:12px;
        box-shadow:0 4px 15px rgba(0,0,0,0.05);
        border:1px solid #f1f1f1;
    }

    .icon-wrapper{
        width:45px;
        height:45px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:linear-gradient(135deg,#4f46e5,#6366f1);
        color:white;
        border-radius:10px;
        font-size:20px;
    }

    .btn-create{
        background:linear-gradient(135deg,#4f46e5,#6366f1);
        border:none;
        color:white;
        font-weight:600;
        border-radius:8px;
        padding:8px 18px;
        transition:0.3s;
        box-shadow:0 3px 10px rgba(0,0,0,0.1);
    }

    .btn-create:hover{
        transform:translateY(-2px);
        box-shadow:0 6px 15px rgba(0,0,0,0.15);
        color:white;
    }
     /* រចនាបថបន្ថែមសម្រាប់ក្បាលតារាង */
    .table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-transform: none;
        font-size: 0.9rem;
        border-top: none;
        padding: 12px 15px;
        vertical-align: middle;
        white-space: nowrap;
    }

    /* Icon តម្រៀប (Sorting Icon) ស្ទីលស្រទន់ */
    .sort-icon {
        font-size: 0.75rem;
        margin-left: 5px;
        color: #cbd5e1; /* ពណ៌ប្រផេះស្រាល */
        transition: color 0.2s;
        cursor: pointer;
    }

    .sort-icon:hover {
        color: #6366f1; /* ប្តូរពណ៌ពេល Hover */
    }
     /* រចនាបថតារាង Dark ឱ្យមានភាពស្រទន់ */
    .table-dark-soft {
        background-color: #1e293b !important; /* ពណ៌ខៀវក្រម៉ៅស្រទន់ */
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-dark-soft thead th {
        background-color: #334155 !important; /* ពណ៌ក្បាលតារាង */
        color: #f1f5f9 !important;
        font-weight: 500;
        border: none;
        padding: 15px;
    }

    .table-dark-soft tbody tr {
        border-bottom: 1px solid #334155;
        transition: all 0.2s ease;
    }

    /* Hover effect ឱ្យមានពន្លឺតិចៗ */
    .table-dark-soft.table-hover tbody tr:hover {
        background-color: #334155 !important;
        color: #fff !important;
    }

    /* ស្ទីលសម្រាប់ Badge ស្ថានភាព */
    .badge-active {
        background-color: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
</style>

<div class="app-content mb-4">
    <div class="container-fluid">
        <div class="header-card d-flex justify-content-between align-items-center flex-wrap">

            <!-- Left Side -->
            <div class="d-flex align-items-center">
                <div class="icon-wrapper me-3">
                    <i class="bi bi-bank"></i>
                </div>

                <div>
                    <h4 class="mb-1 fw-bold text-dark">
                        គ្រប់គ្រងធនាគារ (Banks)
                    </h4>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-decoration-none text-muted">
                                    <i class="bi bi-house-door"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active fw-semibold text-primary">
                                Bank List
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Right Side -->
            <div>
                <a href="{{ route('bank.create') }}" class="btn btn-create px-4">
                    <i class="bi bi-plus-lg me-1"></i>
                    បន្ថែមធនាគារថ្មី
                </a>
            </div>

        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        {{-- Search & Filter --}}
        <div class="card card-custom mb-4">
            <div class="card-body ">
               <form id="bankFilterForm" action="{{ route('bank.index') }}" method="GET">
                    <div class="row g-3 align-items-end">

                        <div class="col-lg-6 col-md-6">
                            <label class="form-label small fw-bold text-muted">ស្វែងរកធនាគារ</label>
                            <input
                                type="text"
                                id="searchInput"
                                name="search"
                                class="form-control"
                                placeholder="ស្វែងរកតាមឈ្មោះ, លេខគណនី..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-muted">ស្ថានភាព</label>
                            <select id="statusFilter" name="status" class="form-select">
                                <option value="">ស្ថានភាពទាំងអស់</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-12 d-flex gap-2">

                            <button type="submit" class="btn btn-info text-white w-100">
                                <i class="bi bi-filter me-1"></i> ស្វែងរក
                            </button>

                            <a href="{{ route('bank.index') }}" class="btn btn-light border w-100">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>

                        </div>

                    </div>
                </form>
            </div>
        </div>

       <div class="table-responsive">
    <table class="table table-dark-soft table-hover align-middle mb-0 text-white">
        <thead>
            <tr>
                <th class="text-center" style="width: 60px">ល.រ</th>
                <th>ឈ្មោះធនាគារ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                <th>ឈ្មោះម្ចាស់គណនី <i class="bi bi-arrow-down-up sort-icon"></i></th>
                <th>លេខគណនី <i class="bi bi-arrow-down-up sort-icon"></i></th>
                <th>សមតុល្យ <i class="bi bi-arrow-down-up sort-icon"></i></th>
                <th class="text-center">រូបិយប័ណ្ណ</th>
                <th class="text-center">ស្ថានភាព</th>
                <th class="text-center" style="width: 150px">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banks as $index => $bank)
            <tr>
                <td class="text-center text-muted">{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $bank->bank_name }}</td>
                <td>{{ $bank->account_name }}</td>
                <td class="text-info">{{ $bank->account_number }}</td>
                <td>{{ number_format($bank->opening_balance, 2) }}</td>
                <td class="text-center">
                    <span class="badge bg-secondary">{{ $bank->currency }}</span>
                </td>
                <td class="text-center">
                    @if($bank->is_active)
                        <span class="badge badge-active">សកម្ម</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger">មិនសកម្ម</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="btn-group">
                        <a href="{{ route('bank.edit', $bank->id) }}" class="btn btn-sm btn-outline-light border-0">
                            <i class="bi bi-pencil-fill text-warning"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-light border-0 delete-confirm" data-id="{{ $bank->id }}" data-name="{{ $bank->bank_name }}">
                             <form id="delete-form-{{ $bank->id }}" action="{{ route('bank.destroy', $bank->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                            <i class="bi bi-trash-fill text-danger"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.delete-confirm', function(e){
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name');
        const form = $('#delete-form-' + id);

        Swal.fire({
            title: 'Delete Bank?',
            text: `តើអ្នកប្រាកដថាចង់លុប "${name}" មែនទេ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete'
        }).then((result) => {
            if(result.isConfirmed){
                form.submit();
            }
        });
    });

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("bankFilterForm");
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");

    let delayTimer;

    // Live search delay
    searchInput.addEventListener("keyup", function () {

        clearTimeout(delayTimer);

        delayTimer = setTimeout(function () {
            form.submit();
        }, 500);

    });

    // Auto submit when filter change
    statusFilter.addEventListener("change", function () {
        form.submit();
    });

});
document.addEventListener('DOMContentLoaded', function () {
    const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

    const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
        v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

    // ចាប់យកគ្រប់ក្បាលតារាងដែលមាន icon sort
    document.querySelectorAll('th').forEach(th => th.addEventListener('click', function() {
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const icon = th.querySelector('.sort-icon');

        // បើគ្មាន icon ក្នុង th នោះទេ មិនបាច់ធ្វើការ sort ឡើយ
        if (!icon) return;

        // តម្រៀបទិន្នន័យ
        Array.from(tbody.querySelectorAll('tr'))
            .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
            .forEach(tr => tbody.appendChild(tr));

        // ផ្លាស់ប្តូររូបរាង Icon ឱ្យមើលទៅ Soft និងដឹងថាវាឡើងលើឬចុះក្រោម
        resetSortIcons();
        if (this.asc) {
            icon.classList.replace('bi-arrow-down-up', 'bi-sort-up');
            icon.style.color = '#6366f1'; // ពណ៌ Indigo ពេល active
        } else {
            icon.classList.replace('bi-arrow-down-up', 'bi-sort-down');
            icon.style.color = '#6366f1';
        }
    }));

    // មុខងារកំណត់ icon ទៅដើមវិញ
    function resetSortIcons() {
        document.querySelectorAll('.sort-icon').forEach(icon => {
            icon.classList.remove('bi-sort-up', 'bi-sort-down');
            icon.classList.add('bi-arrow-down-up');
            icon.style.color = '#cbd5e1'; // ត្រឡប់ទៅពណ៌ប្រផេះស្រាលវិញ
        });
    }
});

</script>
@endpush

