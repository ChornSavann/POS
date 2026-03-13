<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8" />
    <title>របាយការណ៍លក់សរុប - Sales Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ===== ការកំណត់ទំព័រ (A4 Landscape) ===== */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f4f7f6;
            font-family: 'Khmer OS Battambang', Arial, sans-serif;
            color: #333;
        }

        /* Container សម្រាប់កាន់ក្រដាស */
        .report-wrapper {
            background: #fff;
            width: 277mm; /* ទទឹង A4 Landscape */
            margin: 20px auto;
            padding: 15mm;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: auto; /* អនុញ្ញាតឱ្យរីកតាមទិន្នន័យ */
        }

        /* Header Section */
        .report-header table { width: 100%; border: none; }
        .store-title {
            color: #0056b3;
            font-family: 'Khmer OS Muol Light', serif;
            margin: 0;
            font-size: 24px;
        }

        /* Title Section */
        .invoice-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #0056b3;
            border-top: 2px solid #0056b3;
            border-bottom: 2px solid #0056b3;
            margin: 20px 0;
            padding: 10px 0;
            font-family: 'Khmer OS Muol Light', serif;
        }

        /* តារាងទិន្នន័យ */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .items-table th {
            background: #1e293b !important; /* ពណ៌ខ្មៅក្រមៅ Modern */
            color: #fff !important;
            padding: 12px 8px;
            border: 1px solid #334155;
            font-size: 13px;
            -webkit-print-color-adjust: exact;
        }

        .items-table td {
            border: 1px solid #dee2e6;
            padding: 10px 8px;
            font-size: 13px;
            word-wrap: break-word;
        }

        /* បច្ចេកទេសបំបែកទំព័រ */
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }

        /* តារាងសរុប (Total) */
        .total-container { width: 100%; margin-top: 10px; }
        .total-box {
            width: 320px;
            float: right;
            border-collapse: collapse;
        }
        .total-box td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .grand-total-row {
            background: #0056b3 !important;
            color: white !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
        }

        /* Footer ហត្ថលេខា */
        .report-footer {
            margin-top: 40px;
            page-break-inside: avoid;
            clear: both;
        }
        .signature-table { width: 100%; text-align: center; margin-top: 30px; }
        .signature-line {
            border-top: 1px dashed #333;
            width: 180px;
            margin: 50px auto 5px auto;
            padding-top: 5px;
            font-weight: bold;
        }

        /* ប៊ូតុងបញ្ជា */
        .no-print-header {
            background: #1e293b;
            padding: 12px 40px;
            display: flex;
            justify-content: space-between;
            color: white;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        @media print {
            body { background: none; }
            .no-print { display: none !important; }
            .report-wrapper {
                margin: 0;
                width: 100% !important;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
    <style>
.invoice-header{
    width:100%;
    border-bottom:2px solid #0d6efd;
    padding-bottom:10px;
    margin-bottom:15px;
}

.logo-box{
    width:90px;
    height:90px;
    border-radius:10px;
    overflow:hidden;
    border:1px solid #ddd;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#f8f9fa;
}

.logo-box img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.store-title{
    margin:0;
    font-size:24px;
    font-weight:bold;
    color:#0d6efd;
}

.store-info{
    font-size:14px;
    color:#444;
}

.print-date{
    font-size:12px;
    color:#888;
}
</style>
</head>
<body>

<div class="no-print no-print-header">
    <a href="{{ url()->previous() }}" style="color:white; text-decoration:none; font-size: 14px;">
        <i class="fas fa-arrow-left me-2"></i> ត្រឡប់ក្រោយ
    </a>
    <button onclick="window.print()" style="background:#10b981; color:white; border:none; padding:8px 25px; border-radius:6px; cursor:pointer; font-weight:bold;">
        <i class="fas fa-print me-2"></i> បោះពុម្ព (Print Report)
    </button>
</div>

<div class="report-wrapper">
    <div class="report-header">
        <table class="invoice-header">
    <tr>
        <td style="width:120px;">
            <div class="logo-box">
                @if($store && $store->logo)
                    <img src="{{ asset('Image/stores/' . $store->logo) }}">
                @else
                    <span style="color:#999;font-weight:bold;">LOGO</span>
                @endif
            </div>
        </td>

        <td style="text-align:right;">
            <h2 class="store-title">{{ $store->name ?? 'ឈ្មោះហាងរបស់អ្នក' }}</h2>

            <div class="store-info">
                <p style="margin:3px 0;">
                    {{ $store->address ?? 'អាសយដ្ឋានមិនទាន់កំណត់' }}
                </p>

                <p style="margin:3px 0;">
                    📞 លេខទូរស័ព្ទ៖
                    <strong>{{ $store->phone ?? '000 000 000' }}</strong>
                </p>
            </div>

            <p class="print-date">
                កាលបរិច្ឆេទបោះពុម្ព៖ {{ now()->format('d/M/Y H:i') }}
            </p>
        </td>
    </tr>
</table>
    </div>

    <div class="invoice-title">របាយការណ៍លក់សរុប / SALES REPORT</div>

    <div class="report-body">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:50px; text-align:center;">ល.រ</th>
                    <th style="width:140px; text-align:center;">កាលបរិច្ឆេទ</th>
                    <th style="width:130px; text-align:center;">លេខវិក្កយបត្រ</th>
                    <th>អតិថិជន</th>
                    <th style="text-align:right; width:120px;">សរុបដើម</th>
                    <th style="text-align:right; width:110px;">បញ្ចុះតម្លៃ</th>
                    <th style="text-align:right; width:130px;">សរុបត្រូវបង់</th>
                </tr>
            </thead>
            <tbody>
                @php $sumST = 0; $sumD = 0; $sumGT = 0; @endphp
                @forelse ($orders as $key => $order)
                    @php
                        $sumST += $order->sub_total;
                        $sumD += $order->total_discount;
                        $sumGT += $order->grand_total;
                    @endphp
                    <tr>
                        <td style="text-align:center;">{{ $key + 1 }}</td>
                        <td style="text-align:center;">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                        <td style="font-weight:bold; color:#0056b3; text-align:center;">#{{ $order->invoice_no }}</td>
                        <td>{{ $order->customer->name ?? 'Walk-in Customer' }}</td>
                        <td style="text-align:right;">${{ number_format($order->sub_total, 2) }}</td>
                        <td style="text-align:right; color:#dc2626;">-${{ number_format($order->total_discount, 2) }}</td>
                        <td style="text-align:right; font-weight:bold;">${{ number_format($order->grand_total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4">មិនមានទិន្នន័យសម្រាប់បង្ហាញឡើយ។</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-container">
            <table class="total-box">
                <tr>
                    <td>សរុបដើមសរុប:</td>
                    <td style="text-align:right;">${{ number_format($sumST, 2) }}</td>
                </tr>
                <tr>
                    <td>បញ្ចុះតម្លៃសរុប:</td>
                    <td style="text-align:right; color:#dc2626;">-${{ number_format($sumD, 2) }}</td>
                </tr>
                <tr class="grand-total-row">
                    <td>សរុបត្រូវបង់សរុប:</td>
                    <td style="text-align:right;">${{ number_format($sumGT, 2) }}</td>
                </tr>
            </table>
            <div style="clear:both;"></div>
        </div>
    </div>

    <div class="report-footer">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line">Client / អតិថិជន</div>
                </td>
                <td>
                    <div class="signature-line">
                        {{ Auth::user()->name ?? 'Cashier' }}<br>
                        <span style="font-size: 12px; font-weight: normal;">(អ្នកលក់)</span>
                    </div>
                </td>
                <td>
                    <div class="signature-line">Deliverer / អ្នកដឹក</div>
                </td>
            </tr>
        </table>
        <div style="margin-top: 50px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 10px;">
            របាយការណ៍នេះបង្កើតដោយប្រព័ន្ធ POS អាជីព - Printed at {{ now()->format('d-M-Y H:i') }}
        </div>
    </div>
</div>

</body>
</html>
