@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* រចនាប័ទ្មទូទៅ និងពុម្ពអក្សរខ្មែរ */
        body {
            background: #f8f9fa;
            font-family: 'Khmer OS Battambang', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .full-width {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }

        /* ផ្នែក Filter Box */
        .filter-box {
            background: #fff;
            padding: 15px 20px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        .filter-box label {
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* ផ្នែក Report Header */
        .report-header-container {
            background-color: #ffffff;
            border-left: 5px solid #0d6efd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 0.25rem;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, 0.075) !important;
        }

        .title-wrapper h3 {
            letter-spacing: 1px;
            font-weight: 700;
        }

        .title-wrapper small {
            display: block;
            color: #6c757d;
        }

        /* 🛠️ រចនាប័ទ្មតារាងរបាយការណ៍ឱ្យដូច DESIGN ចាស់ ១០០% */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            margin-bottom: 20px;
        }

        /* Header Table ព័ណ៌ខ្មៅ */
        .report-table thead th {
            background-color: #212529 !important;
            color: white !important;
            font-weight: 500;
            padding: 12px;
            font-size: 0.9rem;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        /* Cells ទូទៅ */
        .report-table th,
        .report-table td {
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
            vertical-align: middle;
        }

        /* 📌 ធ្វើឱ្យ Column ដំបូងជាប់នៅកន្លែងដើម (Sticky Column) */
        .sticky-col {
            position: sticky;
            left: 0;
            background-color: inherit;
            z-index: 10;
            min-width: 220px;
        }

        /* ពណ៌ nền សម្រាប់ Sticky Column (ដើម្បីកុំឱ្យមើលធ្លុះ) */
        tr:nth-child(even) .sticky-col {
            background-color: #f8f9fa;
        }

        tr:nth-child(odd) .sticky-col {
            background-color: #ffffff;
        }

        /* ជួរព័ណ៌ប្រផេះបែងចែកផ្នែក (REVENUE / EXPENSES) */
        .table-secondary {
            background-color: #e9ecef !important;
            border-top: 2px solid #dee2e6;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* ជួរ Net Profit ព័ណ៌ខៀវខ្ចី ដូចក្នុងរូបភាព */
        .net-profit-row {
            background-color: #e0f7fa !important;
            font-weight: 700;
        }

        /* សម្រាប់លេខ */
        .text-end {
            text-align: right;
        }

        .category-icon {
            margin-right: 8px;
        }

        /* ស្ទីលសម្រាប់បោះពុម្ព (Print) */
        @media print {

            .filter-box,
            .filter-wrapper,
            .btn-primary,
            .d-print-none {
                display: none !important;
            }

            .report-header-container {
                border: none;
                box-shadow: none !important;
            }

            .sticky-col {
                position: static;
                background-color: transparent !important;
            }

            .net-profit-row {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>

    <div class="full-width">
        <div class="filter-box d-print-none shadow-sm">
            <div>
                <label><i class="bi bi-calendar-event"></i> Start Date</label>
                <input type="date" id="startDate" class="form-control" value="{{ $startDate }}">
            </div>
            <div>
                <label><i class="bi bi-calendar-event"></i> End Date</label>
                <input type="date" id="endDate" class="form-control" value="{{ $endDate }}">
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer-fill"></i> Print</button>
                <button class="btn btn-dark" onclick="submitFilter()"><i class="bi bi-search"></i> Submit</button>
            </div>
        </div>

        <div class="report-header-container mb-4">
            <div class="d-flex align-items-center">
                <div class="logo-wrapper me-3">
                    <img src="{{ asset('/assets/logo.png') }}" alt="Logo" class="img-fluid" style="height: 70px;" />
                </div>
                <div class="title-wrapper border-start ps-3">
                    <h3 class="mb-0 fw-bold text-dark text-uppercase">
                        <i class="bi bi-bar-chart-line-fill text-primary"></i> Profit and Loss Report
                    </h3>
                    <small>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-calendar-event"></i> Period: {{ $startDate }} — {{ $endDate }}
                        </span>
                    </small>
                </div>
            </div>

            <div class="filter-wrapper bg-white p-3 rounded shadow-sm border d-print-none" style="min-width: 320px;">
                <form action="{{ route('reports.profit_loss') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label small fw-bold mb-1 text-secondary">Reporting Year</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-primary text-white"><i class="bi bi-calendar-check"></i></span>
                            <input type="number" name="year" class="form-control" value="{{ $year }}"
                                min="2000" max="2099" style="width: 100px;" />
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">Generate</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 report-table">
                    <thead>
                        <tr>
                            <th class="sticky-col"><i class="bi bi-calendar3"></i> Category / Month</th>
                            @foreach ($months as $monthName)
                                <th>{{ $monthName }}</th>
                            @endforeach
                            <th><i class="bi bi-calculator"></i> Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ១. បង្ហាញតាម Category --}}
                        @if (count($salesByCategory) > 0)
                            @foreach ($salesByCategory as $categoryName => $monthlyData)
                                <tr>
                                    <td class="fw-bold text-primary sticky-col">
                                        <i class="bi bi-tag-fill category-icon"></i> {{ $categoryName }}
                                    </td>
                                    @php $catRowTotal = 0; @endphp
                                    @for ($m = 1; $m <= 12; $m++)
                                        @php
                                            $val = $monthlyData[$m] ?? 0;
                                            $catRowTotal += $val;
                                        @endphp
                                        <td class="text-end">{{ number_format($val, 2) }}</td>
                                    @endfor
                                    <td class="text-end fw-bold">{{ number_format($catRowTotal, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif

                        {{-- --- ជួរ REVENUE (ជួរព័ណ៌ប្រផេះ) --- --}}
                        <tr class="table-secondary">
                            <td colspan="14">
                                <i class="bi bi-cash-stack category-icon"></i> REVENUE
                            </td>
                        </tr>
                        <tr>
                            <td class="sticky-col"><i class="bi bi-cart-check category-icon"></i> Total Sales</td>
                            @for ($m = 1; $m <= 12; $m++)
                                <td class="text-end text-primary fw-bold">{{ number_format($monthlySales[$m], 2) }}</td>
                            @endfor
                            <td class="text-end fw-bold text-primary bg-light">
                                {{ number_format(array_sum($monthlySales), 2) }}</td>
                        </tr>

                        {{-- --- ជួរ EXPENSES (ជួរព័ណ៌ប្រផេះ) --- --}}
                        <tr class="table-secondary">
                            <td colspan="14">
                                <i class="bi bi-wallet2 category-icon"></i> EXPENSES
                            </td>
                        </tr>
                        {{-- ២. ការចំណាយផ្សេងៗ (ចំណុចដែលបងបាត់) --}}
                        <tr>
                            <td class="sticky-col"><i class="bi bi-receipt-cutoff category-icon text-secondary"></i>
                                Operating Expenses (ចំណាយផ្សេងៗ)</td>
                            @for ($m = 1; $m <= 12; $m++)
                                <td class="text-end text-danger">{{ number_format($monthlyExpenses[$m], 2) }}</td>
                            @endfor
                            <td class="text-end fw-bold text-danger bg-light">
                                {{ number_format(array_sum($monthlyExpenses), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="sticky-col"><i class="bi bi-box-seam category-icon"></i> Cost of Goods Sold (COGS)
                            </td>
                            @for ($m = 1; $m <= 12; $m++)
                                <td class="text-end text-danger">{{ number_format($monthlyCOGS[$m], 2) }}</td>
                            @endfor
                            <td class="text-end fw-bold text-danger bg-light">
                                {{ number_format(array_sum($monthlyCOGS), 2) }}</td>
                        </tr>

                        {{-- --- ជួរ Gross Profit --- --}}
                        <tr class="fw-bold bg-white">
                            <td class="sticky-col"><i class="bi bi-piggy-bank category-icon text-success"></i> Gross Profit
                            </td>
                            @for ($m = 1; $m <= 12; $m++)
                                @php $gp = $monthlySales[$m] - $monthlyCOGS[$m]; @endphp
                                <td class="text-end text-primary">{{ number_format($gp, 2) }}</td>
                            @endfor
                            <td class="text-end text-primary bg-light">
                                {{ number_format(array_sum($monthlySales) - array_sum($monthlyCOGS), 2) }}</td>
                        </tr>

                        {{-- --- ជួរ Net Profit (ព័ណ៌ខៀវខ្ចី) --- --}}
                        <tr class="net-profit-row">
                            <td class="sticky-col">
                                <i class="bi bi-graph-up-arrow category-icon text-success"></i> Net Profit
                            </td>
                            @for ($m = 1; $m <= 12; $m++)
                                @php
                                    $net = $monthlySales[$m] - $monthlyCOGS[$m] - $monthlyExpenses[$m];
                                @endphp
                                <td class="text-end fw-bold {{ $net < 0 ? 'text-danger' : 'text-dark' }}">
                                    {{ number_format($net, 2) }}
                                </td>
                            @endfor
                            <td class="text-end fw-bolder text-dark" style="background-color: #b2ebf2 !important;">
                                {{ number_format(array_sum($monthlySales) - array_sum($monthlyCOGS) - array_sum($monthlyExpenses), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function submitFilter() {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            if (!start || !end) {
                alert("សូមជ្រើសរើសថ្ងៃខែឱ្យបានត្រឹមត្រូវ!");
                return;
            }
            window.location.href = `${window.location.pathname}?startDate=${start}&endDate=${end}`;
        }
    </script>
@endsection
