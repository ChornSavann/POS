@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('tables_active', 'active')
@section('content')
<div class="container-fluid">
   <div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3">
        <div class="row align-items-center">

            {{-- Title Section --}}
            <div class="col-md-4 mb-3 mb-md-0">
                <h4 class="m-0 fw-bold text-dark d-flex align-items-center">
                    <span class="bg-primary bg-gradient text-white rounded-3 p-2 me-2 shadow-sm">
                        <i class="fas fa-th-large"></i>
                    </span>
                    ប្លង់តុអាហារ
                </h4>
                <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Table Management System</small>
            </div>

            {{-- Action Buttons --}}
            <div class="col-md-8 text-md-end">

                {{-- Status Badges (បន្ថែមក្នុង Header ឱ្យមើលទៅតូចស្អាត) --}}
                <div class="d-inline-flex me-3">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill me-2 shadow-xs">
                        <i class="fas fa-circle text-success me-1 small"></i> ទំនេរ: {{ $tables->where('status', 'free')->count() }}
                    </span>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-xs">
                        <i class="fas fa-circle text-danger me-1 small"></i> ជាប់រវល់: {{ $tables->where('status', 'busy')->count() }}
                    </span>
                </div>

                {{-- Export Excel --}}
                <button onclick="exportExcel()" class="btn btn-outline-success px-3 me-2 shadow-sm border-0">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Excel
                </button>

                {{-- Export PDF --}}
                <button onclick="exportPDF()" class="btn btn-outline-danger px-3 me-2 shadow-sm border-0">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                </button>

                {{-- Add Table Button --}}
                <button class="btn btn-primary px-4 shadow-sm rounded-3"
                        onclick="openAddModal()"
                        data-bs-toggle="modal"
                        data-bs-target="#tableModal">
                    <i class="bi bi-plus-circle me-1"></i> បន្ថែមតុថ្មី
                </button>
            </div>

        </div>
    </div>
</div>


<div class="row g-3"> @foreach($tables as $table)
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <div class="card h-100 border-0 shadow-sm table-card {{ $table->status == 'busy' ? 'bg-busy-light' : 'bg-white' }}">
            <div class="card-body text-center pt-3 pb-2"> <div class="table-icon-circle mb-2 {{ $table->status == 'free' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-danger bg-opacity-10 text-danger' }}"
                     style="width: 45px; height: 45px; font-size: 1.2rem; margin: 0 auto;">
                    <i class="fas fa-utensils"></i>
                </div>

                <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $table->name }}</h6>

                <p class="text-muted mb-2 text-truncate px-1" style="font-size: 0.7rem;" title="{{ $table->note }}">
                    {{ $table->note ?? '---' }}
                </p>

                @if($table->status == 'free')
                    <span class="badge bg-success status-badge px-2 py-1" style="font-size: 0.65rem;">
                        <i class="fas fa-check-circle"></i> ទំនេរ
                    </span>
                @else
                    <span class="badge bg-danger status-badge px-2 py-1" style="font-size: 0.65rem;">
                        <i class="fas fa-user-clock"></i> ជាប់រវល់
                    </span>
                @endif
            </div>

            <div class="card-footer bg-transparent border-top-0 pb-3 pt-0 text-center">
                <div class="btn-group w-100 px-1">
                    <button class="btn btn-xs btn-light border shadow-sm rounded-start-pill py-1"
                            onclick="openEditModal('{{ $table->id }}', '{{ $table->name }}', '{{ $table->status }}', '{{ $table->note }}')"
                            style="font-size: 0.8rem;">
                        <i class="fas fa-edit text-muted"></i>
                    </button>

                    @if($table->status == 'free')
                        <button class="btn btn-xs btn-primary shadow-sm rounded-end-pill flex-grow-1 py-1" style="font-size: 0.75rem;">
                            បើកតុ
                        </button>
                    @else
                        <button class="btn btn-xs btn-outline-danger shadow-sm rounded-end-pill flex-grow-1 py-1" style="font-size: 0.75rem;">
                            ទូទាត់
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>


@endsection
<style>
    /* ១. បន្ថែមពណ៌ផ្ទៃក្រោយស្រាលៗសម្រាប់តុដែលជាប់រវល់ */
    .bg-busy-light {
        background-color: #fff8f8 !important; /* ក្រហមផ្កាឈូកស្រាលបំផុត */
    }

    /* ២. រចនា Circle សម្រាប់ដាក់ Icon តុ (ទំហំតូចល្មម ៤៥ភីកសែល) */
    .table-icon-circle {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    /* ៣. រចនា Card តុ (រាងតូចល្មម និងមាន Shadow ស្រាល) */
    .table-card {
        border-radius: 15px !important;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid rgba(0,0,0,.05) !important;
    }

    /* Effect ពេលដាក់ Mouse ពីលើ Card */
    .table-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.1) !important;
    }

    .table-card:hover .table-icon-circle {
        transform: scale(1.1);
    }

    /* ៤. កែសម្រួល Badge ស្ថានភាពឱ្យតូច និងមូលស្អាត */
    .status-badge {
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.65rem;
        padding: 4px 10px;
    }

    /* ៥. កំណត់សម្រាប់ប៊ូតុងតូចៗ (Extra Small Buttons) */
    .btn-xs {
        padding: 0.25rem 0.4rem;
        font-size: 0.75rem;
        line-height: 1.5;
    }

    /* ៦. បន្ថែមការ truncate អក្សរវែងពេកកុំឱ្យខូចប្លង់ */
    .text-truncate {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
     /* ចលនាពេល Modal បើកមក */
    .modal.fade .modal-dialog {
        transform: scale(0.8); /* ចាប់ផ្ដើមដោយទំហំតូចបន្តិច */
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: scale(1); /* រីកមកទំហំធម្មតាវិញ */
    }

    /* បន្ថែមចលនាឲ្យ Input បន្តិចពេល Modal បើក (ស្រេចចិត្ត) */
    .modal-body .mb-3 {
        animation: fadeInUp 0.5s ease backfill;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* រចនាប៊ូតុង Close ឱ្យស្អាតពេល Hover */
    .btn-close-white {
        transition: transform 0.2s;
    }
    .btn-close-white:hover {
        transform: rotate(90deg);
    }
    // ដាក់ក្នុង CSS
    .shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }

    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }
</style>

<div class="modal fade" id="tableModal" tabindex="-1" aria-labelledby="tableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white border-0 py-3" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold" id="tableModalLabel">
                    <i class="fas fa-plus-circle me-2"></i><span id="modalTitle">បន្ថែមតុថ្មី</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tableForm">
                @csrf {{-- ត្រូវប្រាកដថាមាន @csrf ដើម្បីឱ្យ axios ចាប់បាន --}}
                <div class="modal-body p-4">
                    {{-- id សម្រាប់ Edit --}}
                    <input type="hidden" name="id" id="tableId">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">ឈ្មោះតុ (Table Name) *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-tag text-primary"></i></span>
                            {{-- កែពី tableName មកជា name ឱ្យត្រូវតាម Request --}}
                            <input type="text" name="name" id="tableName" class="form-control border-start-0 bg-light" placeholder="ឧទាហរណ៍៖ តុលេខ ០១" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">ស្ថានភាព (Status)</label>
                        {{-- ត្រូវបន្ថែម name="status" --}}
                        <select name="status" id="tableStatus" class="form-select bg-light">
                            <option value="free">✅ ទំនេរ (Free)</option>
                            <option value="busy">⏳ ជាប់រវល់ (Busy)</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted">កំណត់ចំណាំ (Note)</label>
                        {{-- ត្រូវបន្ថែម name="note" --}}
                        <textarea name="note" id="tableNote" class="form-control bg-light" rows="3" placeholder="បញ្ចូលព័ត៌មានបន្ថែម..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">
                        <i class="fas fa-save me-2"></i>រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /**
     * ២. ការកំណត់ Header សម្រាប់ Axios (ការពារបញ្ហា 419 - CSRF Token)
     */
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    /**
     * ៣. មុខងារបើក Modal (Add & Edit)
     */
    function openAddModal() {
        const form = document.getElementById('tableForm');
        form.reset(); // លុបទិន្នន័យចាស់
        document.getElementById('tableId').value = '';
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle me-2"></i>បន្ថែមតុថ្មី';
        $('#tableModal').modal('show');
    }

    function openEditModal(id, name, status, note) {
        document.getElementById('tableId').value = id;
        document.getElementById('tableName').value = name;
        document.getElementById('tableStatus').value = status;
        document.getElementById('tableNote').value = note;
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>កែប្រែព័ត៌មានតុ';
        $('#tableModal').modal('show');
    }

    /**
     * ៤. មុខងារលុបតុ (Delete) ជាមួយ SweetAlert សួរបញ្ជាក់
     */
    function deleteTable(id) {
        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: "ទិន្នន័យតុនេះនឹងត្រូវលុបចេញពីប្រព័ន្ធ!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'បាទ/ចាស, លុបវា!',
            cancelButtonText: 'បោះបង់'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/tables/${id}`)
                    .then(res => {
                        Swal.fire('លុបជោគជ័យ!', res.data.message, 'success')
                            .then(() => location.reload());
                    })
                    .catch(err => alert('មិនអាចលុបបានទេ!'));
            }
        })
    }

    /**
     * ៥. Submit Form តាមរយៈ AJAX (Store & Update)
     */
    document.getElementById('tableForm').onsubmit = function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        // បង្ហាញ Loading State
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>កំពុងរក្សាទុក...';

        // ប្រើ FormData ដើម្បីធានាថាចាប់បានគ្រប់ Field ដែលមាន name="..."
        const formData = new FormData(this);
        const tableId = document.getElementById('tableId').value;

        // កំណត់ URL (ប្រើ /tables ឱ្យត្រូវតាម Resource Route)
        const url = tableId ? `/table/${tableId}` : "{{ route('table.store') }}";

        // បើកែប្រែ (Update) ត្រូវបន្ថែម _method: PUT ក្នុង FormData
        if(tableId) {
            formData.append('_method', 'PUT');
        }

        // ផ្ញើទៅកាន់ Server
        axios.post(url, formData)
            .then(response => {
                $('#tableModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'ជោគជ័យ!',
                    text: response.data.message || 'រក្សាទុកបានជោគជ័យ',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload()); // Refresh ដើម្បីបច្ចុប្បន្នភាពទិន្នន័យ
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                if (error.response && error.response.status === 422) {
                    // បង្ហាញ Error លម្អិតពី Laravel Validation
                    const errors = error.response.data.errors;
                    const errorMsg = Object.values(errors).flat().join('\n');

                    Swal.fire({
                        icon: 'error',
                        title: 'ទិន្នន័យមិនត្រឹមត្រូវ!',
                        text: errorMsg
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'បរាជ័យ!',
                        text: 'មានបញ្ហាម៉ាស៊ីនបម្រើ ឬ Route មិនត្រឹមត្រូវ (500/404)'
                    });
                }
            });
    };
</script>
@endpush

