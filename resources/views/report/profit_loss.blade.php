@extends('layout.app') {{-- ឬ layout ដែលបងប្រើ --}}

@section('content')
    <style>
        /* Custom Styles for Profit & Loss Table */
        .report-table thead th {
            background-color: #0d6efd !important;
            color: white !important;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            font-size: 0.85rem;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            background-color: #f8f9fa !important;
            z-index: 5;
            border-right: 2px solid #dee2e6 !important;
            min-width: 220px;
        }

        .table-secondary td {
            background-color: #e9ecef !important;
            font-weight: bold;
            color: #495057;
        }

        .net-profit-row td {
            background-color: #b2ebf2 !important;
            font-weight: 900 !important;
            border-top: 2px solid #00acc1 !important;
        }

        .category-icon {
            font-size: 1.1rem;
            vertical-align: middle;
        }

        /* Summary Card Decorations */
        .summary-card {
            border-left: 4px solid #0d6efd;
            transition: transform 0.2s;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        /* Print Optimization */
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }

            .d-print-none,
            .btn,
            .filter-box,
            .filter-wrapper {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .sticky-col {
                position: static;
                background-color: white !important;
            }

            .table {
                width: 100% !important;
                font-size: 10pt;
            }

            body {
                background-color: white !important;
            }

            .table-responsive {
                overflow: visible !important;
            }
        }
    </style>
    <style>
        .pl-cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        @media(max-width:768px) {
            .pl-cards {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width:480px) {
            .pl-cards {
                grid-template-columns: 1fr;
            }
        }

        .pl-mc {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .15s;
        }

        .pl-mc:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, .07);
        }

        .pl-mc-body {
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
        }

        .pl-mc-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.3rem;
        }

        .pl-mc-label {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .pl-mc-value {
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1;
            color: #111827;
        }

        .pl-mc-badge {
            display: inline-block;
            margin-top: 5px;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 600;
        }

        .pl-mc-bar {
            height: 3px;
            width: 100%;
        }

        .pl-margin-track {
            height: 4px;
            width: 90px;
            background: #e5e7eb;
            border-radius: 2px;
            margin-top: 6px;
            overflow: hidden;
        }

        .pl-margin-fill {
            height: 100%;
            border-radius: 2px;
        }
    </style>
    <div class="container-fluid py-4">
        {{-- ១. ផ្នែក Header និង Filter --}}
        <div class="report-header-container d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <div class="logo-wrapper me-3">
                    <img src="{{ asset('/assets/logo.png') }}" alt="Logo" style="height: 60px;" />
                </div>
                <div class="title-wrapper border-start ps-3">
                    <h3 class="mb-0 fw-bold text-dark text-uppercase">
                        Profit and Loss Report
                    </h3>
                    <span class="badge bg-light text-dark border">
                        <i class="bi bi-calendar-event"></i> Year: {{ $year }}
                    </span>
                </div>
            </div>

            <div class="filter-wrapper bg-white p-3 rounded shadow-sm border d-print-none">
                <form action="{{ route('reports.profit_loss') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label small fw-bold mb-1">Reporting Year</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-primary border-end-0">
                                <i class="bi bi-calendar-check"></i>
                            </span>
                            <select name="year" id="yearSelect" class="form-select border-start-0 ps-0"
                                style="width: 100px; font-weight: 500;" onchange="submitFilter()">
                                @for ($i = 2023; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm">
                            <i class="bi bi-arrow-clockwise"></i> Generate
                        </button>
                        <button type="button" class="btn btn-dark btn-sm px-3 shadow-sm" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ២. Summary Cards (បង្ហាញពីលើ Table) --}}


        <div class="pl-cards d-print-none">

            {{-- ── Total Sales ── --}}
            <div class="pl-mc">
                <div class="pl-mc-body">
                    <div class="pl-mc-icon" style="background:#eff6ff;">
                        <i class="bi bi-cash-stack" style="color:#1d4ed8;"></i>
                    </div>
                    <div>
                        <div class="pl-mc-label">Total Sales</div>
                        <div class="pl-mc-value" style="color:#1d4ed8;">
                            ${{ number_format($summary['total_sales'], 2) }}
                        </div>
                        <span class="pl-mc-badge" style="background:#dbeafe;color:#1e40af;">
                            Completed orders
                        </span>
                    </div>
                </div>
                <div class="pl-mc-bar" style="background:#3b82f6;"></div>
            </div>

            {{-- ── Total Expenses ── --}}
            <div class="pl-mc">
                <div class="pl-mc-body">
                    <div class="pl-mc-icon" style="background:#fff1f2;">
                        <i class="bi bi-wallet2" style="color:#be123c;"></i>
                    </div>
                    <div>
                        <div class="pl-mc-label">Total Expenses</div>
                        <div class="pl-mc-value" style="color:#be123c;">
                            ${{ number_format($summary['total_expenses'] + $summary['total_cogs'], 2) }}
                        </div>
                        <span class="pl-mc-badge" style="background:#fee2e2;color:#991b1b;">
                            COGS + Operating
                        </span>
                    </div>
                </div>
                <div class="pl-mc-bar" style="background:#ef4444;"></div>
            </div>

            {{-- ── Net Profit ── --}}
            @php $profitPositive = $summary['net_profit'] >= 0; @endphp
            <div class="pl-mc">
                <div class="pl-mc-body">
                    <div class="pl-mc-icon" style="background:{{ $profitPositive ? '#f0fdf4' : '#fff1f2' }};">
                        <i class="bi bi-graph-up-arrow" style="color:{{ $profitPositive ? '#15803d' : '#be123c' }};"></i>
                    </div>
                    <div>
                        <div class="pl-mc-label">Net Profit</div>
                        <div class="pl-mc-value" style="color:{{ $profitPositive ? '#15803d' : '#be123c' }};">
                            ${{ number_format($summary['net_profit'], 2) }}
                        </div>
                        <span class="pl-mc-badge"
                            style="background:{{ $profitPositive ? '#dcfce7' : '#fee2e2' }};
                           color:{{ $profitPositive ? '#166534' : '#991b1b' }};">
                            {{ $profitPositive ? 'Profitable' : 'Loss' }}
                        </span>
                    </div>
                </div>
                <div class="pl-mc-bar" style="background:{{ $profitPositive ? '#22c55e' : '#ef4444' }};"></div>
            </div>

            {{-- ── Net Margin ── --}}
            @php $marginPct = min(max((float)$summary['net_margin_pct'], 0), 100); @endphp
            <div class="pl-mc">
                <div class="pl-mc-body">
                    <div class="pl-mc-icon" style="background:#fffbeb;">
                        <i class="bi bi-pie-chart" style="color:#b45309;"></i>
                    </div>
                    <div>
                        <div class="pl-mc-label">Net Margin</div>
                        <div class="pl-mc-value" style="color:#b45309;">
                            {{ $summary['net_margin_pct'] }}%
                        </div>
                        <div class="pl-margin-track">
                            <div class="pl-margin-fill"
                                style="width:{{ $marginPct }}%;
                                background:{{ $marginPct >= 20 ? '#f59e0b' : ($marginPct >= 10 ? '#fbbf24' : '#fcd34d') }};">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pl-mc-bar" style="background:#f59e0b;"></div>
            </div>

        </div>
        {{-- ៣. Table របាយការណ៍ --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 report-table">
                    <thead>
                        <tr>
                            <th class="sticky-col">Category / Month</th>
                            @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $mName)
                                <th>{{ $mName }}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ១. Sales by Category --}}
                        @foreach ($salesByCategory as $categoryName => $monthlyData)
                            <tr>
                                <td class="fw-bold text-dark sticky-col ps-4">
                                    <i class="bi bi-tag text-primary category-icon"></i> {{ $categoryName }}
                                </td>
                                @php $rowTotal = 0; @endphp
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $val = $monthlyData[$m];
                                        $rowTotal += $val;
                                    @endphp
                                    <td class="text-end text-muted">{{ number_format($val, 2) }}</td>
                                @endfor
                                <td class="text-end fw-bold bg-light">{{ number_format($rowTotal, 2) }}</td>
                            </tr>
                        @endforeach

                        {{-- ២. Revenue Section --}}
                        <tr class="table-secondary">
                            <td colspan="14"><i class="bi bi-cash-stack"></i> TOTAL REVENUE</td>
                        </tr>
                        <tr>
                            <td class="sticky-col ps-4">Total Sales (Income)</td>
                            @for ($m = 1; $m <= 12; $m++)
                                <td class="text-end text-primary fw-bold">{{ number_format($monthlySales[$m], 2) }}</td>
                            @endfor
                            <td class="text-end fw-bold text-white bg-primary">
                                {{ number_format(array_sum($monthlySales), 2) }}</td>
                        </tr>

                        {{-- ៣. Expenses Section --}}
                        <tr class="table-secondary">
                            <td colspan="14"><i class="bi bi-wallet2"></i> TOTAL EXPENSES</td>
                        </tr>
                        <tr>
                            <td class="sticky-col ps-4">Cost of Goods Sold (COGS)</td>
                            @for ($m = 1; $m <= 12; $m++)
                                <td class="text-end text-danger">{{ number_format($monthlyCOGS[$m], 2) }}</td>
                            @endfor
                            <td class="text-end fw-bold text-danger">{{ number_format(array_sum($monthlyCOGS), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="sticky-col ps-4">Operating Expenses</td>
                            @for ($m = 1; $m <= 12; $m++)
                                <td class="text-end text-danger">{{ number_format($monthlyExpenses[$m], 2) }}</td>
                            @endfor
                            <td class="text-end fw-bold text-danger">{{ number_format(array_sum($monthlyExpenses), 2) }}
                            </td>
                        </tr>

                        {{-- ៤. Final Profit Section --}}
                        <tr class="fw-bold">
                            <td class="sticky-col ps-4">Gross Profit</td>
                            @for ($m = 1; $m <= 12; $m++)
                                <td class="text-end text-success">{{ number_format($monthlyGrossProfit[$m], 2) }}</td>
                            @endfor
                            <td class="text-end text-success">{{ number_format(array_sum($monthlyGrossProfit), 2) }}</td>
                        </tr>
                        <tr class="net-profit-row">
                            <td class="sticky-col ps-4">NET PROFIT (ចំណេញសុទ្ធ)</td>
                            @for ($m = 1; $m <= 12; $m++)
                                <td class="text-end {{ $monthlyNetProfit[$m] < 0 ? 'text-danger' : 'text-dark' }}">
                                    {{ number_format($monthlyNetProfit[$m], 2) }}
                                </td>
                            @endfor
                            <td class="text-end text-dark">
                                {{ number_format(array_sum($monthlyNetProfit), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function submitFilter() {
            const year = document.getElementById('yearSelect').value;
            const start = document.getElementById('startDate') ? document.getElementById('startDate').value : '';
            const end = document.getElementById('endDate') ? document.getElementById('endDate').value : '';

            const urlParams = new URLSearchParams(window.location.search);

            // បញ្ចូល Parameter ឆ្នាំ
            urlParams.set('year', year);

            // បើមានការរើសថ្ងៃខែ ទើបបញ្ចូលទៅក្នុង URL
            if (start && end) {
                urlParams.set('startDate', start);
                urlParams.set('endDate', end);
            }

            window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
        }
    </script>
@endpush
