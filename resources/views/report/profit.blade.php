@extends('layout.app')

@section('title', 'Profit & Loss — ' . $year)

@section('content')
    <style>
        .pl-grid {
            display: grid;
            gap: 16px;
        }

        .pl-4col {
            grid-template-columns: repeat(4, 1fr);
        }

        .pl-2col {
            grid-template-columns: repeat(2, 1fr);
        }

        @media(max-width:768px) {

            .pl-4col,
            .pl-2col {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width:480px) {

            .pl-4col,
            .pl-2col {
                grid-template-columns: 1fr;
            }
        }

        .metric-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
        }

        .metric-card .label {
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 6px;
        }

        .metric-card .value {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
        }

        .metric-card .sub {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .metric-card .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-green {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .chart-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
        }

        .chart-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 4px;
        }

        .chart-card .subtitle {
            font-size: 12px;
            color: #9ca3af;
            margin: 0 0 16px;
        }

        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 12px;
            color: #6b7280;
        }

        .legend span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .leg-box {
            width: 10px;
            height: 10px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .pl-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .pl-table th {
            background: #f9fafb;
            color: #6b7280;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 8px 10px;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
        }

        .pl-table th:first-child {
            text-align: left;
        }

        .pl-table td {
            padding: 8px 10px;
            text-align: right;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }

        .pl-table td:first-child {
            text-align: left;
            font-weight: 500;
        }

        .pl-table tr:hover td {
            background: #f9fafb;
        }

        .pl-table tfoot td {
            font-weight: 700;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            border-bottom: none;
        }

        .text-green {
            color: #059669;
        }

        .text-red {
            color: #dc2626;
        }

        .text-gray {
            color: #9ca3af;
        }

        .year-picker {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .year-picker select {
            padding: 6px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            background: #fff;
        }

        .btn-export {
            padding: 6px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            background: #fff;
            cursor: pointer;
        }

        .btn-export:hover {
            background: #f3f4f6;
        }

        /* បន្ថែមស្ទីលសម្រាប់ Header */
        .report-header {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .header-title p {
            font-size: 14px;
            color: #6b7280;
            margin: 4px 0 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .custom-select {
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            background-color: #f9fafb;
            cursor: pointer;
            transition: all 0.2s;
            outline: none;
        }

        .custom-select:focus {
            border-color: #3b82f6;
            ring: 2px rgba(59, 130, 246, 0.2);
        }

        .btn-export-premium {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background-color: #111827;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-export-premium:hover {
            background-color: #374151;
        }

        .btn-export-premium i {
            font-size: 16px;
        }
    </style>
    <style>
        /* បង្រួម Card ឱ្យតូច និងស្លីម */
        .metric-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            /* បន្ថយ padding ឱ្យតូចជាងមុន */
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.2s;
        }

        .metric-card:hover {
            border-color: #d1d5db;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .metric-card .label {
            font-size: 11px;
            /* អក្សរតូចស្លីម */
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: 2px;
        }

        .metric-card .value {
            font-size: 20px;
            /* ទំហំលេខល្មមមើលច្បាស់ មិនធំពេក */
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
        }

        .metric-card .sub {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
            font-weight: 400;
        }

        /* បន្ថែម Border ពណ៌តូចមួយនៅខាងឆ្វេងដើម្បីសម្គាល់ប្រភេទ Data */
        .card-blue {
            border-left: 4px solid #1d4ed8;
        }

        .card-red {
            border-left: 4px solid #dc2626;
        }

        .card-green {
            border-left: 4px solid #059669;
        }

        .th-content {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            /* តម្រឹមលេខទៅស្តាំ */
            gap: 6px;
            cursor: pointer;
            user-select: none;
        }

        .th-content.left {
            justify-content: flex-start;
            /* តម្រឹមអត្ថបទ "ខែ" ទៅឆ្វេង */
        }

        .sort-icon {
            color: #9ca3af;
            /* ពណ៌ប្រផេះស្រាល */
            transition: color 0.2s;
        }

        th:hover .sort-icon {
            color: #374151;
            /* ពេល Hover ឱ្យពណ៌ដិតជាងមុន */
        }
    </style>
    <style>
        /* ស្ទីលរួមសម្រាប់ Table */
        .pl-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            /* ដើម្បីឱ្យ border-radius ដើរ */
        }

        .pl-table thead th {
            background-color: #f9fafb;
            /* ពណ៌ប្រផេះស្រាល */
            padding: 12px 15px;
            text-align: right;
            /* តម្រឹមលេខទៅខាងស្តាំ */
            font-size: 11px;
            font-weight: 600;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid #f3f4f6;
        }

        /* តម្រឹមអក្សរ Month មកខាងឆ្វេងវិញ */
        .pl-table thead th:first-child {
            text-align: left;
            border-top-left-radius: 8px;
        }

        .pl-table thead th:last-child {
            border-top-right-radius: 8px;
        }

        /* បន្ថែមពណ៌ខុសគ្នាបន្តិចសម្រាប់ Net Profit ឬ Margin ដើម្បីឱ្យលេចធ្លោ */
        .th-highlight {
            color: #111827 !important;
            background-color: #f3f4f6 !important;
        }
    </style>
    <div class="container-fluid py-1">
        <div>
            {{-- ── Modern Header ── --}}
            <div class="report-header">
                <div class="header-title">
                    <h1>Profit & Loss</h1>
                    <p>
                        <i class="far fa-calendar-alt" style="margin-right: 4px;"></i>
                        Full year overview — <strong>{{ $year }}</strong>
                    </p>
                </div>

                <div class="header-actions">
                    <div class="year-picker">
                        <span style="font-size: 13px; color: #6b7280; font-weight: 500; margin-right: 8px;">Select
                            Year:</span>
                        <select class="custom-select"
                            onchange="location.href='{{ route('reports.profit_loss_report') }}?year='+this.value">
                            @foreach ($availableYears as $y)
                                <option value="{{ $y }}" @selected($y == $year)>{{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn-export-premium" onclick="exportCSV()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path
                                d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                            <path
                                d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>

            {{-- ── Summary cards ── --}}
            {{-- ── Summary cards ── --}}
            <div class="pl-grid pl-4col" style="margin-bottom:20px">
                @php
                    $total_outflow = $summary['total_cogs'] + $summary['total_expenses'];
                    $cards = [
                        [
                            'label' => 'Total Revenue',
                            'value' => $summary['total_sales'],
                            'sub' => 'Completed orders',
                            'class' => 'card-blue',
                            'text_color' => '#1d4ed8',
                        ],
                        [
                            'label' => 'Total COGS',
                            'value' => $total_outflow,
                            'sub' => 'Cost of goods sold',
                            'class' => 'card-red',
                            'text_color' => '#dc2626',
                        ],

                        [
                            'label' => 'Gross Profit',
                            'value' => $summary['gross_profit'],
                            'sub' => $summary['gross_margin_pct'] . '% margin',
                            'class' => $summary['gross_profit'] >= 0 ? 'card-green' : 'card-red',
                            'text_color' => $summary['gross_profit'] >= 0 ? '#059669' : '#dc2626',
                        ],
                        [
                            'label' => 'Net Profit',
                            'value' => $summary['net_profit'],
                            'sub' => $summary['net_margin_pct'] . '% margin',
                            'class' => $summary['net_profit'] >= 0 ? 'card-green' : 'card-red',
                            'text_color' => $summary['net_profit'] >= 0 ? '#059669' : '#dc2626',
                        ],
                    ];
                @endphp

                @foreach ($cards as $c)
                    <div class="metric-card {{ $c['class'] }}">
                        <div class="label">{{ $c['label'] }}</div>
                        <div class="value" style="color: {{ $c['text_color'] }}">
                            ${{ number_format($c['value'], 0) }}
                        </div>
                        <div class="sub">{{ $c['sub'] }}</div>
                    </div>
                @endforeach
            </div>

            {{-- ── Monthly P&L Chart ── --}}
            <div class="chart-card" style="margin-bottom:20px">
                <h3>Monthly revenue vs cost vs profit</h3>
                <p class="subtitle">getMonthlySales · getMonthlyCOGS · getMonthlyExpenses</p>
                <div class="legend">
                    <span><span class="leg-box" style="background:#3b82f6"></span>Revenue</span>
                    <span><span class="leg-box" style="background:#ef4444"></span>COGS</span>
                    <span><span class="leg-box" style="background:#f59e0b"></span>Expenses</span>
                    <span><span class="leg-box" style="background:#10b981;border-radius:50%"></span>Net Profit (line)</span>
                </div>
                <div style="position:relative;width:100%;height:280px">
                    <canvas id="chartMonthly"></canvas>
                </div>
            </div>

            {{-- ── Category + Top Products ── --}}
            <div class="pl-grid pl-2col" style="margin-bottom:20px">
                <div class="chart-card">
                    <h3>Sales by category</h3>
                    <p class="subtitle">getSalesByCategory — stacked monthly</p>
                    <div id="cat-legend" class="legend"></div>
                    <div style="position:relative;width:100%;height:240px">
                        <canvas id="chartCategory"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3>Top 10 products</h3>
                    <p class="subtitle">By revenue — {{ $year }}</p>
                    <div style="position:relative;width:100%;height:240px">
                        <canvas id="chartTopProducts"></canvas>
                    </div>
                </div>
            </div>

            {{-- ── Monthly P&L Table ── --}}
            <div class="chart-card" style="overflow-x:auto">
                <h3 style="margin-bottom:12px">Monthly P&amp;L breakdown</h3>
                <table class="pl-table" id="plTable">
                    <thead>
                        <tr>
                            <th>
                                <div class="th-content left">
                                    ខែ
                                    <svg class="sort-icon" width="12" height="12" fill="currentColor"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M3.5 3.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L3.5 12.293V3.5zm4 .5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3z" />
                                    </svg>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    ចំណូលសរុប
                                    <svg class="sort-icon" width="12" height="12" fill="currentColor"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8.5 2a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-1 0v-11a.5.5 0 0 1 .5-.5zm-2 2a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zm4 2a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />
                                    </svg>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    ថ្លៃដើម (COGS)
                                    <i class="fas fa-sort" style="font-size: 10px; margin-left: 4px; color: #ccc;"></i>
                                </div>
                            </th>
                            <th style="color: #059669;">
                                <div class="th-content">
                                    ចំណេញដុល
                                    <i class="fas fa-sort" style="font-size: 10px; margin-left: 4px;"></i>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    ចំណាយប្រតិបត្តិការ
                                    <i class="fas fa-sort" style="font-size: 10px; margin-left: 4px;"></i>
                                </div>
                            </th>
                            <th class="th-highlight">
                                <div class="th-content">
                                    ចំណេញសុទ្ធ
                                    <i class="fas fa-sort" style="font-size: 10px; margin-left: 4px;"></i>
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    ភាគរយចំណេញ
                                    <i class="fas fa-sort" style="font-size: 10px; margin-left: 4px;"></i>
                                </div>
                            </th>
                        </tr>

                    <tbody>
                        @php $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']; @endphp
                        @for ($m = 1; $m <= 12; $m++)
                            @php
                                $rev = $monthlySales[$m];
                                $cogs = $monthlyCOGS[$m];
                                $exp = $monthlyExpenses[$m];
                                $gross = $monthlyGrossProfit[$m];
                                $net = $monthlyNetProfit[$m];
                                $margin = $rev > 0 ? round(($net / $rev) * 100, 1) : 0;
                                $cls = $net >= 0 ? 'text-green' : 'text-red';
                            @endphp
                            <tr>
                                <td>{{ $months[$m - 1] }}</td>
                                <td>${{ number_format($rev, 0) }}</td>
                                <td class="text-red">${{ number_format($cogs, 0) }}</td>
                                <td class="{{ $gross >= 0 ? 'text-green' : 'text-red' }}">
                                    ${{ number_format($gross, 0) }}
                                </td>
                                <td class="text-gray">${{ number_format($exp, 0) }}</td>
                                <td class="{{ $cls }}"><strong>${{ number_format($net, 0) }}</strong></td>
                                <td class="{{ $cls }}">{{ $margin }}%</td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td>${{ number_format($summary['total_sales'], 0) }}</td>
                            <td>${{ number_format($summary['total_cogs'], 0) }}</td>
                            <td class="{{ $summary['gross_profit'] >= 0 ? 'text-green' : 'text-red' }}">
                                ${{ number_format($summary['gross_profit'], 0) }}
                            </td>
                            <td>${{ number_format($summary['total_expenses'], 0) }}</td>
                            <td class="{{ $summary['net_profit'] >= 0 ? 'text-green' : 'text-red' }}">
                                ${{ number_format($summary['net_profit'], 0) }}
                            </td>
                            <td class="{{ $summary['net_profit'] >= 0 ? 'text-green' : 'text-red' }}">
                                {{ $summary['net_margin_pct'] }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const monthlySales = @json(array_values($monthlySales));
        const monthlyCOGS = @json(array_values($monthlyCOGS));
        const monthlyExpenses = @json(array_values($monthlyExpenses));
        const monthlyNetProfit = @json(array_values($monthlyNetProfit));
        const salesByCategory = @json($salesByCategory);
        const topProducts = @json($topProducts);

        const CAT_COLORS = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'];
        const fmt = v => '$' + Math.round(Math.abs(v)).toLocaleString();

        // Chart 1: Monthly Overview
        new Chart(document.getElementById('chartMonthly'), {
            data: {
                labels: MONTHS,
                datasets: [{
                        type: 'bar',
                        label: 'Revenue',
                        data: monthlySales,
                        backgroundColor: '#3b82f6',
                        borderRadius: 4,
                        stack: 'rev'
                    },
                    {
                        type: 'bar',
                        label: 'COGS',
                        data: monthlyCOGS,
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                        stack: 'cost'
                    },
                    {
                        type: 'bar',
                        label: 'Expenses',
                        data: monthlyExpenses,
                        backgroundColor: '#f59e0b',
                        borderRadius: 4,
                        stack: 'cost'
                    },
                    {
                        type: 'line',
                        label: 'Net Profit',
                        data: monthlyNetProfit,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981',
                        tension: 0.35
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.dataset.label + ': ' + (ctx.parsed.y < 0 ? '-' : '') + fmt(ctx.parsed
                                .y)
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            autoSkip: false,
                            maxRotation: 0
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(0,0,0,.05)'
                        },
                        ticks: {
                            callback: v => '$' + Math.round(v / 1000) + 'k',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Chart 2: Category
        const catNames = Object.keys(salesByCategory);
        document.getElementById('cat-legend').innerHTML = catNames.map((n, i) =>
            `<span><span class="leg-box" style="background:${CAT_COLORS[i % CAT_COLORS.length]}"></span>${n}</span>`
        ).join('');

        new Chart(document.getElementById('chartCategory'), {
            type: 'bar',
            data: {
                labels: MONTHS,
                datasets: catNames.map((name, i) => ({
                    label: name,
                    data: Object.values(salesByCategory[name]),
                    backgroundColor: CAT_COLORS[i % CAT_COLORS.length],
                    borderRadius: 3,
                    stack: 'cat'
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.dataset.label + ': ' + fmt(ctx.parsed.y)
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            autoSkip: false,
                            maxRotation: 0
                        }
                    },
                    y: {
                        stacked: true,
                        grid: {
                            color: 'rgba(0,0,0,.05)'
                        },
                        ticks: {
                            callback: v => '$' + Math.round(v / 1000) + 'k',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Chart 3: Top Products
        new Chart(document.getElementById('chartTopProducts'), {
            type: 'bar',
            data: {
                labels: topProducts.map(p => p.product_name),
                datasets: [{
                    label: 'Revenue',
                    data: topProducts.map(p => p.total_revenue),
                    backgroundColor: '#6366f1',
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => fmt(ctx.parsed.x)
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,.05)'
                        },
                        ticks: {
                            callback: v => '$' + Math.round(v / 1000) + 'k',
                            font: {
                                size: 10
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        function exportCSV() {
            const headers = ['Month', 'Revenue', 'COGS', 'Gross Profit', 'Expenses', 'Net Profit', 'Margin %'];
            const rows = MONTHS.map((m, i) => {
                const rev = monthlySales[i];
                const cogs = monthlyCOGS[i];
                const exp = monthlyExpenses[i];
                const net = monthlyNetProfit[i];
                const margin = rev > 0 ? (net / rev * 100).toFixed(1) : 0;
                return [m, rev, cogs, (rev - cogs), exp, net, margin].join(',');
            });
            const csv = [headers.join(','), ...rows].join('\n');
            const a = document.createElement('a');
            a.href = 'data:text/csv,' + encodeURIComponent(csv);
            a.download = 'pl_{{ $year }}.csv';
            a.click();
        }


        document.querySelectorAll('#plTable th').forEach((headerCell, index) => {
            headerCell.addEventListener('click', () => {
                const tableElement = headerCell.parentElement.parentElement.parentElement;
                const headerIndex = index;
                const isAscending = headerCell.classList.contains('th-sort-asc');

                // តម្រៀបទិន្នន័យ
                sortTableByColumn(tableElement, headerIndex, !isAscending);
            });
        });

        function sortTableByColumn(table, column, asc = true) {
            const dirModifier = asc ? 1 : -1;
            const tBody = table.tBodies[0];
            const rows = Array.from(tBody.querySelectorAll('tr'));

            // តម្រៀប Rows
            const sortedRows = rows.sort((a, b) => {
                const aColText = a.querySelector(`td:nth-child(${column + 1})`).textContent.trim().replace(/[$,%]/g,
                    '');
                const bColText = b.querySelector(`td:nth-child(${column + 1})`).textContent.trim().replace(/[$,%]/g,
                    '');

                // ឆែកមើលថាជាលេខ ឬជាអក្សរ
                const aColValue = isNaN(parseFloat(aColText)) ? aColText : parseFloat(aColText);
                const bColValue = isNaN(parseFloat(bColText)) ? bColText : parseFloat(bColText);

                if (aColValue > bColValue) return 1 * dirModifier;
                if (aColValue < bColValue) return -1 * dirModifier;
                return 0;
            });

            // លុប Rows ចាស់ចេញ
            while (tBody.firstChild) {
                tBody.removeChild(tBody.firstChild);
            }

            // បញ្ចូល Rows ដែលតម្រៀបរួច
            tBody.append(...sortedRows);

            // រក្សាទុកស្ថានភាព Sort (Asc/Desc) ក្នុង Class
            table.querySelectorAll('th').forEach(th => th.classList.remove('th-sort-asc', 'th-sort-desc'));
            table.querySelectorAll('th')[column].classList.toggle('th-sort-asc', asc);
            table.querySelectorAll('th')[column].classList.toggle('th-sort-desc', !asc);
        }
    </script>
@endpush
