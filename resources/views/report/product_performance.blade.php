@extends('layout.app')

@section('content')
    <div class="content-wrapper ">
        {{-- Header របាយការណ៍ --}}
        <section class="content-header py-3">
            <div class="container-fluid">
                <div
                    class="d-flex align-items-center justify-content-between bg-white p-3 shadow-sm rounded-3 border-start border-primary border-5">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white p-3 rounded-3 me-3">
                            <i class="fas fa-chart-line fs-4"></i>
                        </div>
                        <div>
                            <h1 class="h4 mb-0 fw-bold text-dark">របាយការណ៍វិភាគការលក់ទំនិញ</h1>
                            <p class="text-muted mb-0 small">Top Selling & Slow Moving Overview</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                {{-- ផ្នែក Cards បង្ហាញសរុប (បន្ថែមឱ្យដូច Template មុនរបស់បង) --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm custom-card-std">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="std-icon-box bg-light-blue text-primary">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-label-std mb-0">មុខទំនិញសរុប</p>
                                        <h4 class="text-value-std mb-0">{{ number_format($totalItems) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm custom-card-std">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="std-icon-box bg-light-orange text-warning">
                                        <i class="fas fa-truck-loading"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-label-std mb-0">បរិមាណលក់សរុប</p>
                                        <h4 class="text-value-std mb-0">{{ number_format($totalQty) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm custom-card-std border-bottom-std-success">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="std-icon-box bg-light-green text-success">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <div class="ms-3">
                                        <p class="text-label-std mb-0">ទឹកប្រាក់លក់សរុប</p>
                                        <h4 class="text-value-std mb-0 text-success">${{ number_format($totalSales, 2) }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ផ្នែក Filter --}}
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-header bg-dark text-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i> ចម្រាញ់ទិន្នន័យ (Filter Report)</h6>
                    </div>
                    <div class="card-body bg-light-gray">
                        <form method="GET" action="{{ route('reports.product_performance') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">ថ្ងៃចាប់ផ្ដើម (Start Date)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-calendar-alt text-muted"></i></span>
                                    <input type="date" name="startDate" class="form-control border-start-0 ps-0"
                                        value="{{ $startDate }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">ថ្ងៃបញ្ចប់ (End Date)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-calendar-check text-muted"></i></span>
                                    <input type="date" name="endDate" class="form-control border-start-0 ps-0"
                                        value="{{ $endDate }}" />
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                                    <i class="fas fa-sync-alt me-2"></i> ទាញយករបាយការណ៍
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ផ្នែកតារាងបង្ហាញទិន្នន័យ --}}
                <div class="row g-4">
                    {{-- តារាងលក់ដាច់បំផុត (Top Selling) --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden">
                            <div
                                class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-bold text-success"><i class="fas fa-arrow-trend-up me-2"></i>
                                    លក់ដាច់បំផុត (Top 10)</h5>
                                <button class="btn btn-sm btn-outline-success border-0"><i
                                        class="fas fa-file-excel"></i></button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">ទំនិញ</th>
                                                <th class="text-center py-3 text-uppercase small fw-bold text-secondary">
                                                    ចំនួន</th>
                                                <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-secondary">
                                                    សរុប</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($topSelling as $item)
                                                <tr>
                                                    <td class="ps-4 fw-bold text-dark">{{ $item->name }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-primary rounded-pill px-3 py-2">{{ number_format($item->total_qty) }}</span>
                                                    </td>
                                                    <td class="text-end pe-4 fw-bold text-success">
                                                        ${{ number_format($item->total_revenue, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted small">
                                                        មិនមានទិន្នន័យលក់</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- តារាងលក់មិនដាច់ (Slow Moving) --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden">
                            <div
                                class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                                <h5 class="mb-0 fw-bold text-danger"><i class="fas fa-arrow-trend-down me-2"></i> លក់មិនដាច់
                                    (Slow Moving)</h5>
                                <button class="btn btn-sm btn-outline-danger border-0"><i
                                        class="fas fa-file-excel"></i></button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">ទំនិញ
                                                </th>
                                                <th class="text-center py-3 text-uppercase small fw-bold text-secondary">
                                                    ចំនួន</th>
                                                <th class="text-end pe-4 py-3 text-uppercase small fw-bold text-secondary">
                                                    សរុប</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($slowMoving as $item)
                                                <tr>
                                                    <td class="ps-4 fw-bold text-dark">{{ $item->name }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-secondary rounded-pill px-3 py-2">{{ number_format($item->total_qty) }}</span>
                                                    </td>
                                                    <td class="text-end pe-4 fw-bold text-danger">
                                                        ${{ number_format($item->total_revenue, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted small">
                                                        មិនមានទិន្នន័យ</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0 py-4 text-center text-muted small">
                    <i class="fas fa-clock me-1"></i> របាយការណ៍គិតចាប់ពីថ្ងៃទី
                    <strong>{{ date('d-M-Y', strtotime($startDate)) }}</strong> ដល់
                    <strong>{{ date('d-M-Y', strtotime($endDate)) }}</strong>
                </div>
            </div>
        </section>
    </div>

    <style>
        .bg-light-gray {
            background-color: #f8f9fc;
        }

        .bg-soft-success {
            background-color: #e8f5e9;
        }

        .bg-soft-danger {
            background-color: #ffebee;
        }

        .bg-soft-info {
            background-color: #e3f2fd;
        }

        .bg-soft-warning {
            background-color: #fff3e0;
        }

        .stat-card {
            transition: transform 0.2s ease;
            border-radius: 15px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .card {
            border-radius: 12px;
        }

        .table thead th {
            border-top: none;
            background-color: #f8f9fc;
            font-size: 0.75rem;
        }

        .table tbody tr:hover {
            background-color: #f1f4f9 !important;
        }

        @media print {

            .btn,
            .card-header.bg-dark,
            .content-header nav {
                display: none !important;
            }

            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
        }
    </style>
    <style>
        /* បង្រួម Card ឱ្យមកទំហំស្តង់ដា System */
        .custom-card-std {
            border-radius: 10px;
            background-color: #ffffff;
            transition: all 0.2s ease-in-out;
        }

        .custom-card-std:hover {
            background-color: #fcfcfc;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }

        /* Icon Box ទំហំល្មម */
        .std-icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.25rem;
        }

        /* ពណ៌ស្រាលៗសម្រាប់ Icon background */
        .bg-light-blue {
            background-color: #eef2ff;
        }

        .bg-light-orange {
            background-color: #fff7ed;
        }

        .bg-light-green {
            background-color: #f0fdf4;
        }

        /* ទំហំអក្សរស្តង់ដា */
        .text-label-std {
            font-family: 'Kantumruy Pro', sans-serif;
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .text-value-std {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 1.4rem;
            color: #111827;
        }

        /* បន្ទាត់បញ្ជាក់ពណ៌នៅខាងក្រោម (Optionally) */
        .border-bottom-std-success {
            border-bottom: 3px solid #22c55e !important;
        }
    </style>
@endsection
