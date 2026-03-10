@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('purchases', 'active')
@section('content')
<style>
    .header-glass {
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(12px);
}

.text-primary-gradient {
    background: linear-gradient(45deg,#4e73df,#224abe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.uppercase-tracking {
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-glass-primary {
    background: linear-gradient(45deg,#4e73df,#224abe);
    color: #fff;
    border: none;
}

.btn-glass-success {
    background: linear-gradient(45deg,#1cc88a,#17a673);
    color: #fff;
    border: none;
}

.btn-glass-secondary {
    background: rgba(108,117,125,0.1);
    border: 1px solid rgba(108,117,125,0.3);
}
    /* Custom Background for Badges */
    .bg-success-light { background-color: rgba(25, 135, 84, 0.12); color: #198754; }
    .bg-warning-light { background-color: rgba(255, 193, 7, 0.15); color: #997404; }
    .bg-info-light { background-color: rgba(13, 202, 240, 0.12); color: #0dcaf0; }
    .bg-danger-light { background-color: rgba(220, 53, 69, 0.12); color: #dc3545; }

    /* Table Styling */
    .custom-table thead th {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        border-top: none;
    }
    .custom-table tbody tr:hover {
        background-color: #f8f9fa;
        transition: 0.2s;
    }

    /* Avatar Styling */
    .avatar-sm {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
    }

    /* Card Border logic */
    .card { border-radius: 12px; }
</style>
<div class="container-fluid ">
 <div class="d-flex align-items-center justify-content-between mb-4 header-glass p-4 rounded-4 shadow-sm border-0">

    <!-- LEFT -->
    <div>
        <h3 class="mb-1 fw-bolder text-dark">
            <span class="text-primary-gradient">
                <i class="bi bi-cart-check me-2"></i>
            </span>
            បញ្ជីទិញចូល
        </h3>
        <p class="text-muted mb-0 small uppercase-tracking">
            Purchase Management & Supplier Transactions
        </p>
    </div>

    <!-- RIGHT ACTION BUTTONS -->
    <div class="action-buttons d-flex gap-3">

        <button class="btn btn-glass-secondary shadow-sm" onclick="location.reload()">
            <i class="bi bi-arrow-counterclockwise me-2"></i>Refresh
        </button>

        <button class="btn btn-glass-success shadow-sm">
            <i class="bi bi-file-earmark-excel me-2"></i>Export
        </button>

        <a href="{{ route('purchases.create') }}"
           class="btn btn-glass-primary shadow-sm">
            <i class="bi bi-plus-circle me-2"></i>បង្កើតការទិញថ្មី
        </a>

    </div>
</div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-primary border-5">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small text-uppercase fw-bold">សរុបការទិញ</div>
                        <div class="h4 fw-bold mb-0 mt-1">{{ $purchases->total() }}</div>
                    </div>
                    <div class="ms-3 text-primary opacity-50"><i class="fas fa-shopping-cart fa-2x"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-success border-5">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="text-muted small text-uppercase fw-bold">ទទួលបានរួច (Received)</div>
                        <div class="h4 fw-bold mb-0 text-success mt-1">
                            {{ $purchases->where('status', 'Received')->count() }}
                        </div>
                    </div>
                    <div class="ms-3 text-success opacity-50"><i class="fas fa-check-circle fa-2x"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-list-ul me-2 text-primary"></i>ទិន្នន័យលម្អិត</h6>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0" placeholder="ស្វែងរកវិក្កយបត្រ...">
                </div>
                <button class="btn btn-outline-secondary btn-sm rounded-2"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-table">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">កាលបរិច្ឆេទ</th>
                        <th>Reference No</th>
                        <th>អ្នកផ្គត់ផ្គង់</th>
                        <th>សាខា/ឃ្លាំង</th>
                        <th>ស្ថានភាព</th>
                        <th class="text-end">សរុបទឹកប្រាក់</th>
                        <th class="text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                    <tr>
                        <td class="ps-4">
                            <div class="small fw-bold text-dark">{{ date('d-M-Y', strtotime($purchase->purchase_date)) }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">{{ $purchase->created_at->format('h:i A') }}</div>
                        </td>
                        <td><span class="fw-bold text-primary">{{ $purchase->reference_no }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2 bg-info-light text-info rounded-circle shadow-sm" style="width:32px; height:32px;">
                                    {{ substr($purchase->supplier->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="fw-medium">{{ $purchase->supplier->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td><span class="text-muted"><i class="fas fa-store me-1 small"></i>{{ $purchase->store->name ?? 'N/A' }}</span></td>
                        <td>
                            @php
                                $statusClass = [
                                    'Received' => 'bg-success-light text-success',
                                    'Pending'  => 'bg-warning-light text-warning',
                                    'Ordered'  => 'bg-info-light text-info'
                                ][$purchase->status] ?? 'bg-light text-muted';
                            @endphp
                            <span class="badge rounded-pill {{ $statusClass }} px-3">
                                <i class="fas fa-circle me-1 small" style="font-size: 6px;"></i>{{ $purchase->status }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark">${{ number_format($purchase->grand_total, 2) }}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle shadow-none" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow border">
                                    <li><a class="dropdown-item py-2" href="#"><i class="fas fa-eye me-2 text-info"></i>មើលលម្អិត</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ route('purchases.edit', $purchase->id) }}"><i class="fas fa-edit me-2 text-warning"></i>កែប្រែទិន្នន័យ</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('purchase.delete', $purchase->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item py-2 text-danger btn-delete">
                                                <i class="fas fa-trash me-2"></i> លុបចេញ
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">មិនមានទិន្នន័យទិញចូលក្នុងប្រព័ន្ធឡើយ។</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white py-3 border-top-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="small text-muted">បង្ហាញ {{ $purchases->firstItem() }} ដល់ {{ $purchases->lastItem() }} នៃ {{ $purchases->total() }} ទិន្នន័យ</div>
                <div>{{ $purchases->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Your Script LAST -->
<script>
$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    let form = $(this).closest('form');

    Swal.fire({
        title: 'តើអ្នកប្រាកដទេ?',
        text: "ទិន្នន័យនេះនឹងត្រូវលុបចេញពីប្រព័ន្ធ!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'បាទ/ចាស លុបវាចុះ!',
        cancelButtonText: 'បោះបង់'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
