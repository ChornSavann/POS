@extends('layout.app')

@section('content')
    <style>
        /* បន្ថែម CSS ដើម្បីឱ្យដូចរូបភាព */
        .table-custom-black thead th {
            background-color: #2d3436 !important;
            /* ពណ៌ខ្មៅប្រផេះដិត */
            color: #ffffff !important;
            border: 1px solid #454d55 !important;
            text-align: center;
            vertical-align: middle;
            font-weight: 500;
            font-size: 0.85rem;
            padding: 8px 10px !important;
        }

        .table-custom-black tbody td {
            border: 1px solid #dee2e6 !important;
            /* បន្ទាត់ក្រឡាប្រផេះស្រាល */
            padding: 8px 12px !important;
            color: #2d3436;
        }

        /* Effect ពេល Hover ឱ្យស្រដៀងក្នុងរូប */
        .table-custom-black tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        /* Font សម្រាប់លេខ */
        .font-number {
            font-family: 'Inter', sans-serif;
        }

        /* កែសម្រួល badge ឱ្យជ្រុង */
        .badge-square {
            border-radius: 0 !important;
            font-weight: 400;
        }

        /* CSS សម្រាប់ធ្វើឱ្យ Pagination ទៅជាជ្រុង និងស្អាត */
        .pagination-square .pagination {
            margin-bottom: 0;
            gap: 2px;
            /* ចន្លោះរវាងប៊ូតុង */
        }

        .pagination-square .page-item:first-child .page-link,
        .pagination-square .page-item:last-child .page-link,
        .pagination-square .page-link {
            border-radius: 0 !important;
            /* បង្ខំឱ្យជ្រុង ១០០% */
            border: 1px solid #dee2e6;
            color: #334155;
            padding: 6px 12px;
            transition: all 0.2s;
        }

        .pagination-square .page-item.active .page-link {
            background-color: #1e293b !important;
            /* ពណ៌ខ្មៅដូច Header Table */
            border-color: #1e293b !important;
            color: #fff !important;
        }

        .pagination-square .page-link:hover {
            background-color: #f8f9fa;
            color: #1e293b;
        }

        /* លាក់ icon ព្រួញធំៗដែលជួនកាលចេញពី Tailwind */
        .pagination svg {
            width: 20px;
            height: 20px;
        }
         .border-soft {
                border: 1px solid #e9ecef !important;
                background-color: #fcfcfc;
            }

            .border-soft:focus {
                border-color: #0d6efd !important;
                background-color: #fff;
            }

            .bg-gradient-light {
                background: linear-gradient(to right, #ffffff, #f8f9fa);
            }
    </style>
    <div class="container-fluid mt-3">
        <section class="content-header mb-4">
            {{-- Header Section --}}
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-light mb-3">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 d-flex align-items-center gap-3">
                            <div class="icon-box bg-dark text-white shadow-sm d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; border-radius: 12px;">
                                <i class="fas fa-boxes-stacked fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">របាយការណ៍ស្តុកទំនិញ</h5>
                                <p class="text-muted mb-0 small">គ្រប់គ្រង និងតាមដានវត្តមានទំនិញក្នុងឃ្លាំង</p>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-md-end mb-0 small bg-transparent p-0">
                                    <li class="breadcrumb-item text-muted"><i class="fas fa-home me-1"></i> Dashboard</li>
                                    <li class="breadcrumb-item active text-primary fw-bold">Stock Report</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('reports.stockInventory') }}" method="GET" id="searchForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">
                                    <i class="fas fa-calendar-day me-1"></i> ថ្ងៃចាប់ផ្តើម
                                </label>
                                <input type="date" name="start_date"
                                    class="form-control border-soft shadow-none rounded-3"
                                    value="{{ request('start_date', date('Y-m-01')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">
                                    <i class="fas fa-calendar-check me-1"></i> ដល់ថ្ងៃទី
                                </label>
                                <input type="date" name="end_date" class="form-control border-soft shadow-none rounded-3"
                                    value="{{ request('end_date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">
                                    <i class="fas fa-list me-1"></i> ចំនួនបង្ហាញ
                                </label>
                                <div class="d-flex gap-2">
                                    <select name="pageSize" class="form-select border-soft shadow-none rounded-3"
                                        style="width: 100px;">
                                        <option value="10" {{ request('pageSize') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('pageSize') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('pageSize') == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                    <button type="submit"
                                        class="btn btn-primary w-100 rounded-3 d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-search"></i> ស្វែងរក
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button"
                                    class="btn btn-outline-dark w-100 rounded-3 d-flex align-items-center justify-content-center gap-2"
                                    id="btnPrint">
                                    <i class="fas fa-print text-danger"></i> បោះពុម្ព
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>


        {{-- Statistics Cards --}}
        <div class="row g-3 mb-4">
            {{-- សរុបទំនិញក្នុងស្តុក --}}
            <div class="col-md-3">
                <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm bg-white">
                    <div class="icon-box bg-primary text-white me-3 d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px; border-radius: 10px;">
                        <i class="bi bi-box fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Products</small>
                        <h4 class="fw-bolder mb-0 text-dark">{{ number_format($products->total()) }}</h4>
                    </div>
                </div>
            </div>

            {{-- សរុបតម្លៃដើមក្នុងស្តុក --}}
            <div class="col-md-3">
                <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm bg-white">
                    <div class="icon-box bg-success text-white me-3 d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px; border-radius: 10px;">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Stock
                            Cost</small>
                        <h4 class="fw-bolder mb-0 text-success">
                            ${{ number_format($products->sum(fn($p) => $p->qty * $p->cost), 2) }}</h4>
                    </div>
                </div>
            </div>

            {{-- សរុបតម្លៃលក់ក្នុងស្តុក --}}
            <div class="col-md-3">
                <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm bg-white">
                    <div class="icon-box bg-info text-white me-3 d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px; border-radius: 10px;">
                        <i class="bi bi-tag fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Total Stock
                            Value</small>
                        <h4 class="fw-bolder mb-0 text-info">
                            ${{ number_format($products->sum(fn($p) => $p->qty * $p->price), 2) }}</h4>
                    </div>
                </div>
            </div>

            {{-- ទំនិញដែលជិតអស់ពីស្តុក --}}
            <div class="col-md-3">
                <div class="stat-card-modern p-3 d-flex align-items-center shadow-sm bg-white border-warning">
                    <div class="icon-box bg-danger text-white me-3 d-flex align-items-center justify-content-center"
                        style="width: 45px; height: 45px; border-radius: 10px;">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Low Stock Alert</small>
                        <h4 class="fw-bolder mb-0 text-danger">
                            {{ number_format($products->filter(fn($p) => $p->qty <= $p->alert_qty)->count()) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border shadow-sm rounded-0">
            <div class="table-responsive">
                <table class="table table-custom-black mb-0" id="stockSummaryTable">
                    <thead class="table-dark-custom">
                        <tr>
                            <th rowspan="2" style="width: 70px;">ល.រ <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th rowspan="2">កូដទំនិញ <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th rowspan="2">ឈ្មោះទំនិញ <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th rowspan="2">ប្រភេទ <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th rowspan="2">ចំនួនស្តុក <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th rowspan="2">ស្តុកប្រកាសអាសន្ន <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th colspan="2" class="border-bottom border-white border-opacity-10">តម្លៃរាយ</th>
                            <th colspan="2" class="border-bottom border-white border-opacity-10">សរុប</th>
                        </tr>
                        <tr>
                            <th style="font-size: 0.75rem;">ដើម/ឯកតា <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th style="font-size: 0.75rem;">លក់/ឯកតា <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th style="font-size: 0.75rem;">ដើមសរុប <i class="fas fa-sort ms-1 opacity-50"></i></th>
                            <th style="font-size: 0.75rem;">លក់សរុប <i class="fas fa-sort ms-1 opacity-50"></i></th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse ($products as $item)
                            @php
                                $currentQty = $item->qty ?? 0;
                                $alertQty = $item->alert_qty ?? 0;
                                $totalCost = $currentQty * ($item->cost ?? 0);
                                $totalPrice = $currentQty * ($item->price ?? 0);
                                $isLowStock = $currentQty <= $alertQty;
                            @endphp
                            <tr class="{{ $isLowStock ? 'table-danger' : '' }}">
                                <td class="text-center">{{ $loop->iteration + ($products->firstItem() - 1) }}</td>
                                <td class="text-start">{{ $item->barcode }}</td>
                                <td class="text-start">{{ $item->name }}</td>
                                <td class="text-center">{{ $item->category->name ?? 'N/A' }}</td>
                                <td class="text-center fw-bold">{{ number_format($currentQty) }}</td>
                                <td class="text-center text-muted">{{ number_format($alertQty) }}</td>
                                <td class="text-end font-number">{{ number_format($item->cost, 2) }}</td>
                                <td class="text-end font-number">{{ number_format($item->price, 2) }}</td>
                                <td class="text-end font-number fw-bold">{{ number_format($totalCost, 2) }}</td>
                                <td class="text-end font-number fw-bold">{{ number_format($totalPrice, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">មិនមានទិន្នន័យឡើយ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 no-print d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="text-muted small">
                បង្ហាញពី {{ $products->firstItem() }} ដល់ {{ $products->lastItem() }} នៃទិន្នន័យសរុប
                {{ $products->total() }}
            </div>

            <div class="pagination-square">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ១. មុខងារ Print
            const btnPrint = document.getElementById('btnPrint');
            if (btnPrint) {
                btnPrint.onclick = function() {
                    window.print();
                };
            }

            // ២. មុខងារ Sorting
            const table = document.getElementById('stockSummaryTable');
            const headers = table.querySelectorAll('thead th');

            headers.forEach((th) => {
                const icon = th.querySelector('i');
                if (icon) {
                    th.style.cursor = 'pointer';
                    th.addEventListener('click', function() {
                        const tbody = table.querySelector('tbody');
                        const rows = Array.from(tbody.querySelectorAll('tr')).filter(tr => tr.cells
                            .length > 1);

                        // កំណត់ Index ពិតប្រាកដតាមឈ្មោះ Header
                        const headerText = th.innerText.trim();
                        let colIndex = 0;

                        // រៀប Index ឱ្យត្រូវតាមជួរនីមួយៗ
                        if (headerText.includes('ល.រ')) colIndex = 0;
                        else if (headerText.includes('កូដទំនិញ')) colIndex = 1;
                        else if (headerText.includes('ឈ្មោះទំនិញ')) colIndex = 2;
                        else if (headerText.includes('ប្រភេទ')) colIndex = 3;
                        else if (headerText.includes('ចំនួនស្តុក')) colIndex = 4;
                        else if (headerText.includes('ស្តុកប្រកាសអាសន្ន')) colIndex = 5;
                        else if (headerText.includes('ដើម/ឯកតា')) colIndex = 6;
                        else if (headerText.includes('លក់/ឯកតា')) colIndex = 7;
                        else if (headerText.includes('ដើមសរុប')) colIndex = 8;
                        else if (headerText.includes('លក់សរុប')) colIndex = 9;

                        const isAsc = th.classList.contains('asc');

                        // Reset UI
                        headers.forEach(h => {
                            h.classList.remove('asc', 'desc');
                            if (h.querySelector('i')) h.querySelector('i').className =
                                'fas fa-sort ms-1 opacity-50';
                        });

                        // Sort Logic
                        rows.sort((trA, trB) => {
                            let a = trA.cells[colIndex].innerText.replace(/,/g, '').trim();
                            let b = trB.cells[colIndex].innerText.replace(/,/g, '').trim();

                            // បើជាលេខ ឱ្យ Sort តាមលេខ
                            if (!isNaN(parseFloat(a)) && isFinite(a) && !isNaN(parseFloat(
                                    b)) && isFinite(b)) {
                                return isAsc ? parseFloat(b) - parseFloat(a) : parseFloat(
                                    a) - parseFloat(b);
                            }
                            // បើជាអក្សរ ឱ្យ Sort តាមអក្សរ
                            return isAsc ? b.localeCompare(a) : a.localeCompare(b);
                        });

                        // Update UI
                        if (isAsc) {
                            th.classList.add('desc');
                            icon.className = 'fas fa-sort-down ms-1 text-white';
                        } else {
                            th.classList.add('asc');
                            icon.className = 'fas fa-sort-up ms-1 text-white';
                        }

                        rows.forEach(row => tbody.appendChild(row));
                    });
                }
            });
        });
    </script>
@endpush
