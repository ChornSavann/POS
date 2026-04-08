@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('purchses', 'active')
@section('content')
<style>
    :root {
        --primary-navy: #0f172a;
        --accent-blue: #2563eb;
        --bg-light: #f8fafc;
    }

    /* បង្ខំឱ្យលាតពេញអេក្រង់ និងលុប Space ដែលមិនចាំបាច់ */
    .container-fluid {
        max-width: 100% !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Kantumruy Pro', sans-serif;
    }

    .report-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        background: white;
        overflow: hidden;
    }

    .report-header {
        background: var(--primary-navy);
        color: white;
        padding: 15px 20px;
    }

    /* កែសម្រួល Table ឱ្យ Column ស្ដើងស្អាត */
    .table thead th {
        background: #f1f5f9;
        padding: 12px 10px;
        font-size: 13px;
        color: #475569;
        border: none;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 10px 10px !important; /* បន្ថយ Padding ឱ្យតូចជាងមុន */
        font-size: 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    /* កំណត់ទំហំ Column នីមួយៗឱ្យសមស្រប */
    .col-date { width: 120px; }
    .col-ref { width: 180px; }
    .col-supplier { width: auto; } /* ទុកឱ្យវាលាតតាមឈ្មោះ */
    .col-store { width: 150px; }
    .col-amount { width: 140px; }
    .col-status { width: 110px; }
    .col-action { width: 80px; }

    .status-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .filter-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 14px;
    }

    .btn-search {
        height: 40px;
        background: var(--primary-navy);
        color: white;
        border-radius: 8px;
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-2 no-print">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('reports.index') }}" class="text-decoration-none text-muted">Reports</a></li>
            <li class="breadcrumb-item active fw-bold text-dark">Purchase Report</li>
        </ol>
    </nav>
     <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-dark"><i class="bi bi-printer"></i> បោះពុម្ព</button>
            <button class="btn btn-sm btn-success"><i class="bi bi-file-earmark-excel"></i> Excel</button>
        </div>
    </div>
</div>

<div class="report-header p-4 mb-4 shadow-sm d-flex justify-content-between align-items-center" style="background: white; border-radius: 15px; border-left: 5px solid #2563eb;">
    <div>
        <h3 class="fw-bolder text-dark mb-1">របាយការណ៍ទិញទំនិញចូល</h3>
        <span class="badge bg-light text-primary px-3 py-2 rounded-pill border">
            <i class="bi bi-calendar-range me-2"></i>
            @if(request('start_date') && request('end_date'))
                {{ request('start_date') }} ដល់ {{ request('end_date') }}
            @else
                ប្រតិបត្តិការសរុប
            @endif
        </span>
    </div>
    <div class="text-end no-print">
        <small class="text-muted d-block">Generated at</small>
        <span class="fw-bold text-dark">{{ now()->format('d-M-Y H:i A') }}</span>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm" style="background: white; border-radius: 12px; border: 1px solid #edf2f7;">
            <div class="icon-box bg-primary text-white me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 10px;">
                <i class="bi bi-bag-check fs-5"></i>
            </div>
            <div>
                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Purchases</small>
                <h4 class="fw-bolder mb-0">{{ number_format($purchases->total()) }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm" style="background: white; border-radius: 12px; border: 1px solid #edf2f7;">
            <div class="icon-box bg-info text-white me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 10px;">
                <i class="bi bi-box-seam fs-5"></i>
            </div>
            <div>
                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Items Purchased</small>
                <h4 class="fw-bolder mb-0">{{ number_format($purchases->sum(fn($p) => $p->items->count())) }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm" style="background: white; border-radius: 12px; border: 1px solid #edf2f7;">
            <div class="icon-box bg-danger text-white me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 10px;">
                <i class="bi bi-percent fs-5"></i>
            </div>
            <div>
                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Discount Recv.</small>
                <h4 class="fw-bolder mb-0 text-danger">-${{ number_format($purchases->sum('total_discount'), 2) }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card-modern p-3 d-flex align-items-center border-primary shadow-sm" style="background: #f0f7ff; border-radius: 12px; border: 1px solid #bfdbfe;">
            <div class="icon-box bg-success text-white me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; border-radius: 10px;">
                <i class="bi bi-currency-dollar fs-5"></i>
            </div>
            <div>
                <small class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;">Grand Total Expense</small>
                <h4 class="fw-bolder mb-0 text-success">${{ number_format($total_amount, 2) }}</h4>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid ">


    <div class="filter-box shadow-sm">
        <form action="{{ route('reports.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold">ចាប់ពីថ្ងៃ</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">ដល់ថ្ងៃ</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">អ្នកផ្គត់ផ្គង់</label>
                <select name="supplier_id" class="form-select">
                    <option value="">-- ទាំងអស់ --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-search w-100 mt-auto">
                    <i class="bi bi-funnel"></i> ចម្រាញ់
                </button>
            </div>
        </form>
    </div>

    <div class="report-card shadow-sm">
        <div class="report-header d-flex justify-content-between">
            <span class="small"><i class="bi bi-list-ul me-1"></i> បញ្ជីប្រតិបត្តិការ</span>
            <span class="badge bg-primary">សរុប: {{ $purchases->total() }}</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 col-date">ថ្ងៃខែទិញ</th>
                        <th class="col-ref">លេខយោង (REF NO)</th>
                        <th class="col-supplier">អ្នកផ្គត់ផ្គង់</th>
                        <th class="col-store">ឃ្លាំង/ហាង</th>
                        <th class="text-end col-amount">សរុបសាច់ប្រាក់</th>
                        <th class="text-center col-status">ស្ថានភាព</th>
                        <th class="text-center col-action">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                    <tr>
                        <td class="ps-4">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-M-Y') }}</td>
                        <td><a href="javascript:void(0)" class="fw-bold text-decoration-none" onclick="viewPurchaseDetails({{ $purchase->id }})">{{ $purchase->reference_no }}</a></td>
                        <td class="fw-bold">{{ $purchase->supplier->name ?? 'N/A' }}</td>
                        <td class="text-muted"><i class="bi bi-shop me-1"></i>{{ $purchase->store->name ?? 'KTC PC' }}</td>
                        <td class="text-end fw-bold">${{ number_format($purchase->grand_total, 2) }}</td>
                        <td class="text-center">
                            <span class="status-badge bg-success-subtle text-success border border-success-subtle">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-light text-primary rounded-circle" onclick="viewPurchaseDetails({{ $purchase->id }})">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr class="fw-bold">
                        <td colspan="4" class="text-end">សរុប (GRAND TOTAL) :</td>
                        <td class="text-end text-danger">${{ number_format($total_amount, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <span class="small text-muted">បង្ហាញ {{ $purchases->firstItem() }}-{{ $purchases->lastItem() }} នៃ {{ $purchases->total() }}</span>
            <div>{{ $purchases->links() }}</div>
        </div>
    </div>
</div>
@include('report.modal_detail')
@endsection
