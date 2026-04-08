@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('daily', 'active')
@section('content')
    @php
        $today = now()->format('d M Y');
        $totalOrders = $orders->count();
        $totalItems = $orders->sum(function ($order) {
            return $order->orderItems ? $order->orderItems->sum('qty') : 0;
        });
        $totalDiscount = $orders->sum('total_discount') ?? 0;
        $totalSales = $orders->sum('sub_total') ?? 0;
        $netSales = $orders->sum('grand_total') ?? 0;
    @endphp

    <style>
        :root {
            --primary-blue: #4361ee;
            --soft-bg: #f8fafc;
            --border-color: #e2e8f0;
        }

        body {
            background-color: var(--soft-bg);
            font-family: 'Inter', 'Kantumruy Pro', sans-serif;
        }

        /* Modern Report Header */
        .report-header {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        /* Modern Stat Cards */
        .stat-card-modern {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .stat-card-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        /* Table Refinement */
        .table-container {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        #ordersTable thead {
            background-color: #f1f5f9;
        }

        #ordersTable thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border: none;
            padding: 15px;
        }

        #ordersTable tbody td {
            padding: 14px 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        .badge-qty {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-blue);
            font-weight: 600;
        }

        .text-net-sales {
            color: #059669;
            font-weight: 700;
        }

        /* Elevation for Print Button */
        .hover-elevate:hover {
            transform: scale(1.1);
            transition: all 0.2s ease;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .table-container {
                border: none;
            }

            body {
                background: white;
            }
        }
    </style>

    <div class="container-fluid py-4 px-lg-4">

        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="/reports" class="text-decoration-none text-muted">Reports</a></li>
                    <li class="breadcrumb-item active fw-bold text-dark">Daily Sales Report</li>
                </ol>
            </nav>
            <button class="btn btn-white border shadow-sm rounded-3 px-3 py-2" onclick="window.print()">
                <i class="bi bi-printer me-2 text-primary"></i> Print Report
            </button>
        </div>

        <div class="report-header p-4 mb-4 shadow-sm d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bolder text-dark mb-1">របាយការណ៍លក់ប្រចាំថ្ងៃ</h3>
                <span class="badge bg-light text-primary px-3 py-2 rounded-pill border">
                    <i class="bi bi-calendar-event me-2"></i> {{ $today }}
                </span>
            </div>
            <div class="text-end no-print">
                <small class="text-muted d-block">Generated at</small>
                <span class="fw-bold">{{ now()->format('H:i A') }}</span>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card-modern p-3 d-flex align-items-center">
                    <div class="icon-box bg-primary text-white me-3">
                        <i class="bi bi-cart-check fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Total Orders</small>
                        <h4 class="fw-bolder mb-0">{{ number_format($totalOrders) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card-modern p-3 d-flex align-items-center">
                    <div class="icon-box bg-info text-white me-3">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Items Sold</small>
                        <h4 class="fw-bolder mb-0">{{ number_format($totalItems) }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card-modern p-3 d-flex align-items-center">
                    <div class="icon-box bg-danger text-white me-3">
                        <i class="bi bi-tags fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Total Discount</small>
                        <h4 class="fw-bolder mb-0 text-danger">
                            -${{ number_format($totalDiscount, 2) }}
                        </h4>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card-modern p-3 d-flex align-items-center border-primary shadow-sm"
                    style="background: #f0f7ff;">
                    <div class="icon-box bg-success text-white me-3">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    <div>
                        <small class="text-primary fw-bold text-uppercase" style="font-size: 0.7rem;">Net Sales
                            (Today)</small>
                        <h4 class="fw-bolder mb-0 text-success">${{ number_format($netSales, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container shadow-sm mb-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="ordersTable">
                    <thead>
                        <tr>
                            <th class="ps-4">Invoice #</th>
                            <th class="text-center">Time</th>
                            <th>Customer</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Discount</th>
                            <th class="text-end pe-4">Grand Total</th>
                            <th class="no-print"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $order->invoice_no }}</td>
                                <td class="text-center text-muted small">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($order->order_date)->format('H:i') }}
                                </td>
                                <td>
                                    <span
                                        class="fw-medium text-dark">{{ $order->customer->name ?? 'Walk-in Customer' }}</span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge badge-qty rounded-pill px-3">{{ $order->orderItems->sum('qty') }}</span>
                                </td>
                                <td class="text-end text-muted">${{ number_format($order->sub_total, 2) }}</td>
                                <td class="text-end text-danger">-${{ number_format($order->total_discount, 2) }}</td>
                                <td class="text-end pe-4 fw-bold text-net-sales">
                                    ${{ number_format($order->grand_total, 2) }}</td>
                                <td class="text-center no-print">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle hover-elevate shadow-sm"
                                        onclick="printInvoice({{ $order->id }})"
                                        style="width: 35px; height: 35px; padding: 0;">
                                        <i class="bi bi-printer"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light-subtle border-top fw-bold">
                        <tr>
                            <td colspan="4" class="text-end py-3">GRAND TOTAL (TODAY)</td>
                            <td class="text-end py-3">${{ number_format($totalSales, 2) }}</td>
                            <td class="text-end py-3 text-danger">-${{ number_format($totalDiscount, 2) }}</td>
                            <td class="text-end py-3 pe-4 text-success fs-5">${{ number_format($netSales, 2) }}</td>
                            <td class="no-print"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script>
        function printInvoice(id) {
            let oldFrame = document.getElementById("printFrame");
            if (oldFrame) oldFrame.remove();

            let iframe = document.createElement("iframe");
            iframe.id = "printFrame";
            iframe.style.display = "none";
            iframe.src = "/reports/invoice/" + id;
            iframe.src = window.location.origin + "/reports/invoice/" + id;
            document.body.appendChild(iframe);

            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                // លុប iframe ចោលវិញក្រោយពេល Print Dialog បិទ (ដើម្បីកុំឱ្យធ្ងន់ម៉ាស៊ីន)
                setTimeout(() => {
                    // document.body.removeChild(iframe);
                }, 1000);
            };
        }
    </script>
@endsection
