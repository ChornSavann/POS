@extends('layout.app') {{-- ឬ layout ដែលបងប្រើ --}}

@section('style')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #4895ef);
            --success-gradient: linear-gradient(135deg, #2ec4b6, #cbf3f0);
        }

        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: #334155;
        }

        .header-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .text-primary-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .uppercase-tracking {
            letter-spacing: 0.1em;
            font-size: 0.7rem;
        }

        .custom-input {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 15px;
            background: #fff;
            transition: 0.3s;
        }

        .custom-input:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .stat-card {
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-primary-gradient {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
        }

        .btn-glass-primary {
            background: rgba(67, 97, 238, 0.1);
            color: #4361ee;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-glass-primary:hover {
            background: #4361ee;
            color: white;
        }

        .btn-glass-success {
            background: rgba(46, 196, 182, 0.1);
            color: #2ec4b6;
            border: none;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-glass-success:hover {
            background: #2ec4b6;
            color: white;
        }

        .bg-faded {
            background-color: #f1f5f9;
        }

        .table-row-hover:hover {
            background-color: #f8fafc !important;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .bg-soft-primary {
            background-color: rgba(67, 97, 238, 0.1);
        }

        .text-success-gradient {
            color: #06d6a0;
        }

        @media print {
            .content-wrapper {
                margin: 0;
                padding: 0;
            }

            .action-buttons,
            form,
            .data-card .card-header {
                display: none !important;
            }

            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
        }


    </style>
@endsection

@section('content')
    <div class="content-wrapper mt-4">
        <div class="container-fluid px-4">

            {{-- Header Section --}}
            <div
                class="d-flex align-items-center justify-content-between mb-4 header-glass p-4 rounded-4 shadow-sm border-0">
                <div>
                    <h3 class="mb-1 fw-bolder text-dark">
                        <span class="text-primary-gradient"><i class="fas fa-chart-line me-2"></i></span>Sales Intelligence
                    </h3>
                    <p class="text-muted mb-0 small uppercase-tracking">Reports & Analytics Dashboard</p>
                </div>
                <div class="action-buttons d-flex gap-3">
                    <button class="btn btn-glass-success shadow-sm" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                    <button class="btn btn-glass-primary shadow-sm" onclick="printReport()">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row g-4 mb-4">
                @php
                    $summary = [
                        [
                            'title' => 'Total Orders',
                            'value' => number_format($orders->count()),
                            'icon' => 'fa-shopping-basket',
                            'color' => '#4361ee',
                        ],
                        [
                            'title' => 'Gross Sales',
                            'value' => '$' . number_format($subTotal, 2),
                            'icon' => 'fa-funnel-dollar',
                            'color' => '#06d6a0',
                        ],
                        [
                            'title' => 'Total Discount',
                            'value' => '-$' . number_format($totalDiscount, 2),
                            'icon' => 'fa-percentage',
                            'color' => '#ff9f43',
                        ],
                        [
                            'title' => 'Net Revenue',
                            'value' => '$' . number_format($totalSales, 2),
                            'icon' => 'fa-wallet',
                            'color' => '#7209b7',
                        ],
                    ];
                @endphp

                @foreach ($summary as $stat)
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4" style="overflow: hidden;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 50px; height: 50px; background-color: {{ $stat['color'] }}15; color: {{ $stat['color'] }};">
                                        <i class="fas {{ $stat['icon'] }} fs-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted small fw-bold mb-0 uppercase-tracking">{{ $stat['title'] }}</p>
                                        <h3 class="fw-bolder mb-0" style="color: #2b2d42;">{{ $stat['value'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Charts Section --}}
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-transparent py-3 border-0 d-flex align-items-center">
                            <div class="bg-primary rounded-2 me-2" style="width: 4px; height: 18px;"></div>
                            <h6 class="mb-0 fw-bold text-dark opacity-75">REVENUE TREND</h6>
                        </div>
                        <div class="card-body pt-0">
                            <canvas id="dailyChart" style="max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                        <div class="card-header bg-transparent py-3 border-0 d-flex align-items-center">
                            <div class="bg-primary rounded-2 me-2" style="width: 4px; height: 18px;"></div>
                            <h6 class="mb-0 fw-bold text-dark opacity-75">PAYMENT METHODS</h6>
                        </div>
                        <div
                            class="card-body d-flex flex-column align-items-center justify-content-center position-relative">
                            <canvas id="paymentMethodChart" style="max-height: 250px;"></canvas>
                            <div class="position-absolute text-center"
                                style="top: 45%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;">
                                <p class="mb-0 text-muted small fw-bold">TOTAL</p>
                                <h4 class="mb-0 fw-bolder" style="color: #2b2d42;">${{ number_format($totalSales, 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Table Section --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 data-card">
                <div class="card-header bg-light-gradient py-4 px-4 border-0">
                    <form method="get" action="{{ route('reports.sales') }}" class="row g-3 align-items-end">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold small text-muted">START DATE</label>
                            <input type="date" name="start_date" class="form-control custom-input"
                                value="{{ $startDate }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold small text-muted">END DATE</label>
                            <input type="date" name="end_date" class="form-control custom-input"
                                value="{{ $endDate }}">
                        </div>
                        <div class="col-lg-4 col-md-8">
                            <label class="form-label fw-bold small text-muted">TIME PRESETS</label>
                            <select class="form-select custom-input" id="quickDate">
                                <option value="">Custom Range</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="thisMonth">This Month</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <button type="submit" class="btn btn-primary-gradient w-100 fw-bold py-2 shadow-sm">
                                <i class="fas fa-sync-alt me-2"></i>Update
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="salesTable">

                        <thead class="text-uppercase small fw-bold text-muted bg-dark border-bottom">    <tr style="font-family: 'Kantumruy Pro', 'Siemreap', sans-serif; font-size: 0.85rem;">
                                <th class="ps-4 py-3 align-middle cursor-pointer" style="width: 15%;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>លេខវិក្កយបត្រ</span>
                                        <i class="fas fa-sort opacity-25"></i>
                                    </div>
                                </th>

                                <th class="py-3 align-middle cursor-pointer" style="width: 15%;">
                                    <div class="d-flex align-items-center">
                                        <span>កាលបរិច្ឆេទ</span>
                                        <i class="fas fa-sort-amount-down ms-2 text-primary"></i>
                                    </div>
                                </th>

                                <th class="py-3 align-middle cursor-pointer">
                                    <div class="d-flex align-items-center">
                                        <span>អតិថិជន</span>
                                        <i class="fas fa-sort-alpha-down ms-2 opacity-25"></i>
                                    </div>
                                </th>

                                <th class="py-3 align-middle text-center" style="width: 12%;">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span>ការបង់ប្រាក់</span>
                                        <i class="fas fa-filter ms-1 opacity-25" style="font-size: 0.7rem;"></i>
                                    </div>
                                </th>

                                <th class="py-3 align-middle text-end cursor-pointer" style="width: 12%;">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <span>សរុបដើម</span>
                                        <i class="fas fa-sort ms-2 opacity-25"></i>
                                    </div>
                                </th>

                                <th class="py-3 align-middle text-end text-danger cursor-pointer" style="width: 10%;">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <span>បញ្ចុះតម្លៃ</span>
                                        <i class="fas fa-sort ms-2 opacity-25"></i>
                                    </div>
                                </th>

                                <th class="py-3 align-middle text-end pe-4 cursor-pointer" style="width: 15%;">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <span class="text-dark">សរុបចុងក្រោយ</span>
                                        <i class="fas fa-sort ms-2 opacity-25"></i>
                                    </div>
                                </th>

                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach ($orders as $order)
                                <tr class="table-row-hover">
                                    <td class="ps-4">
                                        <span class="fw-bold text-dark">#{{ $order->invoice_no }}</span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2 bg-soft-primary text-primary">
                                                {{ $order->customer->name[0] ?? 'W' }}
                                            </div>
                                            <span>{{ $order->customer->name ?? 'Walk-in' }}</span>
                                        </div>
                                    </td>
                                    {{-- <td class="text-center">
                                        @php
                                            $methods = $order->payments->map(function ($payment) {
                                                // បើមាន Relationship ទៅ Bank យកឈ្មោះ Bank បើអត់ទេយក payment_method បើ null ទៀតយក CASH
                                                return $payment->bank ? $payment->bank->bank_name : ($payment->payment_method ?: 'CASH');
                                            })->unique();

                                            $display = $methods->isEmpty() ? 'CASH' : $methods->implode(', ');
                                            $isCash  = strtoupper($display) === 'CASH';
                                        @endphp
                                        <span class="badge rounded-pill border-0 px-3 py-2"
                                            style="{{ $isCash ? 'background: rgba(67, 97, 238, 0.1); color: #4361ee;' : 'background: rgba(6, 214, 160, 0.1); color: #05c08e;' }}">
                                            <i class="fas {{ $isCash ? 'fa-wallet' : 'fa-university' }} me-1"></i>
                                            {{ $display }}
                                        </span>
                                    </td> --}}
                                    <td class="text-center">
                                        @php
                                            // ១. ទាញយកឈ្មោះវិធីបង់ប្រាក់ (Bank Name ឬ CASH)
                                            $methods = $order->payments
                                                ->map(function ($payment) {
                                                    if ($payment->bank) {
                                                        return $payment->bank->bank_name;
                                                    }
                                                    return $payment->payment_method ?: 'CASH';
                                                })
                                                ->unique();

                                            // ២. រៀបចំ String សម្រាប់បង្ហាញ និងឆែកថាជាសាច់ប្រាក់សុទ្ធឬអត់
                                            $display = $methods->isEmpty() ? 'CASH' : $methods->implode(', ');
                                            $isCash = strtoupper($display) === 'CASH';

                                            // ៣. កំណត់ពណ៌ និង Icon តាមលក្ខខណ្ឌ
                                            $config = $isCash
                                                ? [
                                                    'bg' => 'rgba(67, 97, 238, 0.1)',
                                                    'text' => '#4361ee',
                                                    'icon' => 'fa-wallet',
                                                ]
                                                : [
                                                    'bg' => 'rgba(6, 214, 160, 0.1)',
                                                    'text' => '#05c08e',
                                                    'icon' => 'fa-university',
                                                ];
                                        @endphp

                                        <span class="badge rounded-pill border-0 px-3 py-2"
                                            style="background: {{ $config['bg'] }}; color: {{ $config['text'] }}; font-weight: 600;">
                                            <i class="fas {{ $config['icon'] }} me-1"></i>
                                            {{ strtoupper($display) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold text-muted">${{ number_format($order->sub_total, 2) }}
                                    </td>
                                    <td class="text-end text-danger small">
                                        -${{ number_format($order->total_discount, 2) }}</td>
                                    <td class="text-end pe-4 fw-bold text-success-gradient">
                                        ${{ number_format($order->grand_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        $(document).ready(function() {
            const salesData = {!! $chartData !!};
            const paymentData = {!! $paymentData !!};

            // // Revenue Line Chart
            const dailyCtx = document.getElementById('dailyChart');
            if (dailyCtx) {
                new Chart(dailyCtx, {
                    type: 'line',
                    data: {
                        labels: salesData.map(d => d.date),
                        datasets: [{
                            label: 'Total Revenue ($)',
                            data: salesData.map(d => d.total),
                            borderColor: '#4361ee',
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }


            // Payment Method Doughnut Chart
            const paymentCtx = document.getElementById('paymentMethodChart');
            if (paymentCtx) {
                new Chart(paymentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: paymentData.map(d => d.name),
                        datasets: [{
                            data: paymentData.map(d => d.total),
                            backgroundColor: ['#4361ee', '#06d6a0', '#ff9f43', '#f72585',
                                '#7209b7'
                            ],
                            borderWidth: 5,
                            borderColor: '#ffffff',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        // --- បន្ថែមផ្នែក Animation នៅទីនេះ ---
                        animation: {
                            animateRotate: true, // ឱ្យវាបង្វិលចេញមក
                            animateScale: true, // ឱ្យវារីកចេញពីកណ្តាល
                            duration: 2000, // រយៈពេល ២ វិនាទី
                            easing: 'easeOutQuart'
                        },
                        // ----------------------------------
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            datalabels: {
                                color: '#fff',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: (value, ctx) => {
                                    let sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    if (sum === 0) return "0%";
                                    let percentage = (value * 100 / sum).toFixed(0);
                                    return percentage > 0 ? percentage + "%" :
                                        ""; // បង្ហាញតែភាគរយណាដែលលើសពី 0
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }

            // Quick Date Filter
            $('#quickDate').on('change', function() {
                const mode = $(this).val();
                let start = new Date();
                let end = new Date();

                if (mode === 'yesterday') {
                    start.setDate(start.getDate() - 1);
                    end.setDate(end.getDate() - 1);
                } else if (mode === 'thisMonth') {
                    start = new Date(start.getFullYear(), start.getMonth(), 1);
                } else if (mode !== 'today') return;

                $('input[name="start_date"]').val(start.toISOString().split('T')[0]);
                $('input[name="end_date"]').val(end.toISOString().split('T')[0]);
                $(this).closest('form').submit();
            });
        });

        function printReport() {
            window.print();
        }

        function exportToExcel() {
            let table = document.getElementById("salesTable");
            let html = table.outerHTML;
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
        }
    </script>
@endpush
