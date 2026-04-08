@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('stock_adjustment_active', 'active')

@section('content')
    <style>
        .report-card {
            border: none;
            border-radius: 12px;
            background: white;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background: #f8fafc;
            padding: 12px 10px;
            font-size: 13px;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody td {
            padding: 12px 10px !important;
            font-size: 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .stat-card-modern {
            border-radius: 12px;
            border: 1px solid #edf2f7;
            transition: transform 0.2s;
        }

        .stat-card-modern:hover {
            transform: translateY(-3px);
        }

        .btn-search {
            background: #0f172a;
            color: white;
            border-radius: 8px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .report-card {
                box-shadow: none;
                border: 1px solid #e2e8f0;
            }

            .content-wrapper {
                background: white !important;
            }
        }

        .clickable-row td {
            pointer-events: none;
        }

        .table thead th {
            background-color: #f8fafc !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            font-weight: 700;
            color: #64748b;
            border-top: none;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f5f9 !important;
            transform: scale(1.002);
            box-shadow: inset 4px 0 0 #2563eb;
        }

        .badge-soft {
            padding: 0.35em 0.8em;
            font-weight: 600;
            border-radius: 6px;
        }

        /* ធ្វើឱ្យ Header មើលទៅធំ និងច្បាស់ */
        .table thead th {
            vertical-align: middle;
            white-space: nowrap;
            letter-spacing: 0.5px;
            background-color: #f8fafc !important;
            /* ពណ៌ប្រផេះស្រាល */
        }

        /* ឱ្យ Row នីមួយៗមានកម្ពស់ល្មមស្រួលមើល */
        .table tbody td {
            padding: 15px 10px !important;
            font-size: 15px;
            /* បង្កើនទំហំអក្សរទិន្នន័យ */
        }
    </style>

    {{-- Page Header & Actions --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('reports.index') }}"
                        class="text-decoration-none text-muted">Reports</a></li>
                <li class="breadcrumb-item active fw-bold text-dark">Stock Adjustment</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <button class="btn btn-white border shadow-sm rounded-3 px-3" onclick="window.print()">
                <i class="bi bi-printer me-2 text-primary"></i> បោះពុម្ព
            </button>
            <button class="btn btn-success border shadow-sm rounded-3 px-3">
                <i class="bi bi-file-earmark-excel me-2"></i> Excel
            </button>
        </div>
    </div>

    {{-- Report Title Section --}}
    <div class="report-header p-4 mb-4 shadow-sm d-flex justify-content-between align-items-center"
        style="background: white; border-radius: 15px; border-left: 5px solid #2563eb;">
        <div>
            <h3 class="fw-bolder text-dark mb-1">របាយការណ៍កែសម្រួលស្តុក</h3>
            <span class="badge bg-light text-primary px-3 py-2 rounded-pill border">
                <i class="bi bi-calendar-range me-2"></i>
                @if (request('start_date') && request('end_date'))
                    {{ request('start_date') }} ដល់ {{ request('end_date') }}
                @else
                    ប្រតិបត្តិការសរុប
                @endif
            </span>
        </div>
        <div class="text-end no-print">
            <small class="text-muted d-block italic">ចេញរបាយការណ៍នៅ</small>
            <span class="fw-bold text-dark">{{ now()->format('d-M-Y H:i A') }}</span>
        </div>
    </div>

    {{-- Logic ការគណនាទុកជាមុន --}}
    @php
        $totalInQty = $adjustments->where('type', 'IN')->sum('qty');
        $totalOutQty = $adjustments->where('type', 'OUT')->sum('qty');

        $calculatedTotalValue = 0;
        foreach ($adjustments as $m) {
            $uPrice = $m->type == 'IN' ? $m->product->cost ?? 0 : $m->product->price ?? 0;
            $calculatedTotalValue += $uPrice * $m->qty;
        }
    @endphp

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm bg-white">
                <div class="icon-box bg-primary text-white me-3 d-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px; border-radius: 10px;">
                    <i class="bi bi-arrow-repeat fs-5"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Transactions</small>
                    <h4 class="fw-bolder mb-0 text-dark">{{ number_format($adjustments->count()) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm bg-white">
                <div class="icon-box bg-success text-white me-3 d-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px; border-radius: 10px;">
                    <i class="bi bi-plus-circle fs-5"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Stock In (+)</small>
                    <h4 class="fw-bolder mb-0 text-success">{{ number_format($totalInQty) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm bg-white">
                <div class="icon-box bg-danger text-white me-3 d-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px; border-radius: 10px;">
                    <i class="bi bi-dash-circle fs-5"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Stock Out (-)</small>
                    <h4 class="fw-bolder mb-0 text-danger">{{ number_format($totalOutQty) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm bg-white border-warning">
                <div class="icon-box bg-warning text-white me-3 d-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px; border-radius: 10px;">
                    <i class="bi bi-currency-dollar fs-5"></i>
                </div>
                <div>
                    <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Adj. Value</small>
                    <h4 class="fw-bolder mb-0 text-dark">${{ number_format($calculatedTotalValue, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0">
        {{-- Filter Box --}}
       <div class="filter-box shadow-sm mb-4 bg-white p-4 no-print"
     style="border-radius: 16px; border: 1px solid #eef2f7;">

    <form action="{{ route('reports.stockAdjustmentReport') }}" method="GET" class="row g-3 align-items-end">

        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-2">
                <i class="bi bi-calendar-range me-1 text-primary"></i> ចាប់ពីថ្ងៃ
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                <input type="date" name="start_date" class="form-control border-start-0 ps-0 bg-light"
                       value="{{ request('start_date') }}">
            </div>
        </div>

        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-2">
                <i class="bi bi-calendar-check me-1 text-primary"></i> ដល់ថ្ងៃ
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                <input type="date" name="end_date" class="form-control border-start-0 ps-0 bg-light"
                       value="{{ request('end_date') }}">
            </div>
        </div>

        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted mb-2">
                <i class="bi bi-arrow-down-up me-1 text-primary"></i> ប្រភេទ
            </label>
            <select name="type" id="typeFilter" class="form-select bg-light border-0 shadow-none" style="height: 41px;">
                <option value="">ទាំងអស់</option>
                <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>ចូល (+)</option>
                <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>ចេញ (-)</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted mb-2">
                <i class="bi bi-search me-1 text-primary"></i> ស្វែងរកទំនិញ
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-box-seam"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0 bg-light"
                       placeholder="វាយឈ្មោះទំនិញ..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 shadow-sm d-flex align-items-center justify-content-center"
                    style="height: 41px; border-radius: 10px; font-weight: 600;">
                <i class="bi bi-funnel-fill me-2"></i> ចម្រាញ់ទិន្នន័យ
            </button>
        </div>
    </form>
</div>

        {{-- Table Data --}}
        <div class="report-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th class="ps-4 py-3 border-0" onclick="sortTable(0)" style="cursor: pointer;">
                                <span class="d-flex align-items-center fw-bold text-dark" style="font-size: 15px;">
                                    កាលបរិច្ឆេទ <i class="bi bi-arrow-down-up ms-2 text-warning opacity-75"></i>
                                </span>
                            </th>
                            <th class="py-3 border-0" onclick="sortTable(1)" style="cursor: pointer;">
                                <span class="d-flex align-items-center fw-bold text-dark" style="font-size: 15px;">
                                    ឈ្មោះទំនិញ <i class="bi bi-arrow-down-up ms-2 text-warning opacity-75"></i>
                                </span>
                            </th>
                            <th class="text-center py-3 border-0" onclick="sortTable(2)" style="cursor: pointer;">
                                <span class="d-flex align-items-center justify-content-center fw-bold text-dark"
                                    style="font-size: 15px;">
                                    ប្រភេទ <i class="bi bi-arrow-down-up ms-2 text-warning opacity-75"></i>
                                </span>
                            </th>
                            <th class="text-end py-3 border-0" onclick="sortTable(3)" style="cursor: pointer;">
                                <span class="d-flex align-items-center justify-content-end fw-bold text-dark"
                                    style="font-size: 15px;">
                                    តម្លៃដើម <i class="bi bi-arrow-down-up ms-2 text-warning opacity-75"></i>
                                </span>
                            </th>
                            <th class="text-end py-3 border-0" onclick="sortTable(4)" style="cursor: pointer;">
                                <span class="d-flex align-items-center justify-content-end fw-bold text-dark"
                                    style="font-size: 15px;">
                                    តម្លៃលក់ <i class="bi bi-arrow-down-up ms-2 text-warning opacity-75"></i>
                                </span>
                            </th>
                            <th class="text-center py-3 border-0" onclick="sortTable(5)" style="cursor: pointer;">
                                <span class="d-flex align-items-center justify-content-center fw-bold text-dark"
                                    style="font-size: 15px;">
                                    ចំនួនកែ <i class="bi bi-arrow-down-up ms-2 text-warning opacity-75"></i>
                                </span>
                            </th>
                            <th class="text-end py-3 border-0" onclick="sortTable(6)" style="cursor: pointer;">
                                <span class="d-flex align-items-center justify-content-end fw-bold text-dark"
                                    style="font-size: 15px;">
                                    សរុបទឹកប្រាក់ <i class="bi bi-arrow-down-up ms-2 text-warning opacity-75"></i>
                                </span>
                            </th>
                            <th class="ps-4 py-3 border-0" onclick="sortTable(7)" style="cursor: pointer;">
                                <span class="d-flex align-items-center fw-bold text-dark" style="font-size: 15px;">
                                    មូលហេតុ <i class="bi bi-arrow-down-up ms-2 text-warning opacity-75"></i>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $runningTotal = 0; @endphp
                        @forelse($adjustments as $movement)
                            @php
                                $cost = $movement->product->cost ?? 0;
                                $price = $movement->product->price ?? 0;
                                $uPrice = $movement->type == 'IN' ? $cost : $price;
                                $subTotal = $uPrice * $movement->qty;
                                $runningTotal += $subTotal;
                            @endphp
                            {{-- បងត្រូវដាក់ " នៅពីក្រោយពាក្យ clickable-row --}}
                            <tr class="clickable-row" data-id="{{ $movement->id }}" style="cursor: pointer;">
                                <td class="ps-4 text-muted small">{{ $movement->created_at->format('d-M-Y H:i A') }}</td>
                                <td class="fw-bold">{{ $movement->product->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if ($movement->type == 'IN')
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">ចូល
                                            (IN)
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 rounded-pill">ចេញ
                                            (OUT)</span>
                                    @endif
                                </td>
                                <td class="text-end text-muted">${{ number_format($cost, 2) }}</td>
                                <td class="text-end text-muted">${{ number_format($price, 2) }}</td>
                                <td
                                    class="text-center fw-bold {{ $movement->type == 'IN' ? 'text-success' : 'text-danger' }}">
                                    {{ $movement->type == 'IN' ? '+' : '-' }}{{ number_format($movement->qty) }}
                                </td>
                                <td class="text-end fw-bold text-dark">${{ number_format($subTotal, 2) }}</td>
                                <td class="ps-4 text-muted small italic">{{ $movement->note ?? '---' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    មិនមានទិន្នន័យកែសម្រួលស្តុកឡើយ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($adjustments->count() > 0)
                        <tfoot class="bg-light fw-bold">
                            <tr style="border-top: 2px solid #dee2e6;">
                                <td colspan="6" class="text-end ps-4">សរុបទឹកប្រាក់ក្នុងរបាយការណ៍នេះ:</td>
                                <td class="text-end text-primary" style="font-size: 1.1rem;">
                                    ${{ number_format($runningTotal, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("%c [System] Dashboard Ready!", "color: #f59e0b; font-weight: bold;");
            // ចាប់យកគ្រប់ជួរដែលមាន class clickable-row
            const rows = document.querySelectorAll('.clickable-row');

            rows.forEach(row => {
                row.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const productName = this.cells[1].innerText.trim();
                    const totalAmount = this.cells[6].innerText.trim();

                    console.log("--------------------------------");
                    console.log("%c >>> ចុចលើ ID: " + id,
                        "background: #f59e0b; color: #fff; padding: 2px 5px; border-radius: 3px;"
                    );
                    console.log("📦 មុខទំនិញ: " + productName);
                    console.log("💰 សរុបទឹកប្រាក់: " + totalAmount);

                    // បងអាចបន្ថែមការបង្ហាញ Modal នៅទីនេះ
                });
            });
        });

        function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.querySelector(".table");
            switching = true;
            dir = "asc"; // កំណត់ឱ្យរៀបពីតូចទៅធំជាមុន

            while (switching) {
                switching = false;
                rows = table.rows;

                // រត់កាត់គ្រប់ជួរ (លើកលែងតែ Header)
                for (i = 1; i < (rows.length - 2); i++) { // -2 ដើម្បីកុំឱ្យប៉ះពាល់ Footer សរុប
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];

                    // ឆែកមើលថាតើត្រូវប្តូរវេនគ្នាឬអត់
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
            console.log("%c [System] តម្រៀបទិន្នន័យជោគជ័យ! ", "color: #10b981; font-weight: bold;");
        }
    </script>
@endpush
