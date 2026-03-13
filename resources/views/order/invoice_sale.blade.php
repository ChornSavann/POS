<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8" />
    <title>Invoice - {{ $order->invoice_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&display=swap');

        :root {
            --primary-color: #1a237e;
            --secondary-color: #f8f9fa;
            --text-main: #2c3e50;
            --border: #e0e0e0;
        }

        body {
            background: #f4f7f6;
            font-family: 'Hanuman', serif;
            margin: 0; padding: 0;
            color: var(--text-main);
        }

        .print-container {
            width: 210mm;
            margin: 20px auto;
            background: #fff;
            padding: 15mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }

        .brand-box h2 {
            font-size: 18px;
            color: var(--primary-color);
            margin: 5px 0;
            font-weight: bold;
        }

        .invoice-title-box h1 {
            font-size: 26px;
            color: var(--primary-color);
            margin: 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
        }

        .info-card {
            padding: 10px;
            background: var(--secondary-color);
            border-left: 4px solid var(--primary-color);
        }

        .info-card h4 {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: var(--primary-color);
            text-transform: uppercase;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background: var(--primary-color) !important;
            color: white !important;
            padding: 12px 10px;
            font-size: 13px;
            text-align: left;
            -webkit-print-color-adjust: exact;
        }

        .data-table td {
            padding: 10px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        .grand-total-box {
            background: var(--primary-color);
            color: white;
            padding: 12px;
            border-radius: 4px;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
        }

        .signature-section {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            text-align: center;
        }

        .sig-line {
            width: 70%;
            margin: 40px auto 10px;
            border-top: 1px solid #333;
        }

        @media print {
            body { background: none; }
            .print-container { width: 100%; margin: 0; padding: 0; box-shadow: none; }
            .no-print { display: none; }
            @page { size: A4; margin: 10mm; }
        }
    </style>
</head>
<body>

    <div class="print-container">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <td>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div class="brand-box">
                                {{-- <img src="{{ asset('assets/img/no-image.png') }}" onerror="this.src='https://via.placeholder.com/150x60?text=LOGO'" height="60" /> --}}
                                <div class="brand-box">
                                    @if($store && $store->logo)
                                        <img src="{{ asset('Image/stores/' . $store->logo) }}"
                                            onerror="this.src='{{ asset('assets/img/no-image.png') }}'"
                                            height="60" />
                                    @else
                                        <img src="{{ asset('assets/img/no-image.png') }}" height="60" />
                                    @endif

                                    <h2>{{ $store->name ?? 'ឈ្មោះក្រុមហ៊ុនរបស់អ្នក' }}</h2>
                                    <div style="font-size: 11px; color: #555;">
                                        អាសយដ្ឋាន៖ {{ $store->address ?? 'ភ្នំពេញ, ប្រទេសកម្ពុជា' }} |
                                        ទូរស័ព្ទ៖ {{ $store->phone ?? '012 345 678' }}
                                    </div>
                                </div>
                                <h2>ឈ្មោះហាង ឬក្រុមហ៊ុនរបស់អ្នក</h2>
                                <div style="font-size: 12px; color: #555;">
                                    ភ្នំពេញ, ប្រទេសកម្ពុជា | ទូរស័ព្ទ៖ 096 678 2932
                                </div>
                            </div>
                            <div class="invoice-title-box" style="text-align: right;">
                                <h1>វិក្កយបត្រ</h1>
                                <div style="font-size: 14px; margin-top: 8px;">
                                    លេខ: <strong>#{{ $order->invoice_no }}</strong><br>
                                    កាលបរិច្ឆេទ: <strong>{{ \Carbon\Carbon::parse($order->order_date)->format('d-M-Y') }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="info-grid">
                            <div class="info-card">
                                <h4>អតិថិជន / Bill To</h4>
                                <p style="margin:0; font-weight: bold;">{{ $order->customer->name ?? 'អតិថិជនទូទៅ' }}</p>
                                <p style="margin:3px 0 0; font-size: 13px;">ទូរស័ព្ទ៖ {{ $order->customer->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="info-card">
                                <h4>ព័ត៌មានលក់ / Sales Info</h4>
                                <p style="margin:0; font-size: 13px;">បេឡាករ៖ {{ $cashierName }}</p>
                                <p style="margin:3px 0 0; font-size: 13px;">ម៉ោងចេញ៖ {{ date('h:i A') }}</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;">ល.រ</th>
                                    <th>បរិយាយមុខទំនិញ</th>
                                    <th style="width: 60px; text-align: center;">ចំនួន</th>
                                    <th style="width: 90px; text-align: right;">តម្លៃរាយ</th>
                                    <th style="width: 80px; text-align: center;">បញ្ចុះតម្លៃ</th>
                                    <th style="width: 110px; text-align: right;">សរុប</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $index => $item)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td style="text-align: center;">{{ $item->qty }}</td>
                                    <td style="text-align: right;">${{ number_format($item->price, 2) }}</td>
                                    <td style="text-align: center; color: red;">
                                        {{ $item->discount > 0 ? '$'.number_format($item->discount, 2) : '0' }}
                                    </td>
                                    <td style="text-align: right; font-weight: bold;">
                                        ${{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td>
                        <div style="display: flex; justify-content: space-between; margin-top: 30px; page-break-inside: avoid;">
                            <div style="text-align: center; border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
                                <div style="font-size: 11px; font-weight: bold; margin-bottom: 5px;">Scan for Verification</div>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ route('orders.print', $order->id) }}" width="90" />
                            </div>

                            <div style="width: 300px;">
                                <div style="display: flex; justify-content: space-between; padding: 4px 0;">
                                    <span>សរុបអនុបូក:</span>
                                    <span>${{ number_format($order->sub_total, 2) }}</span>
                                </div>
                                @if($order->total_discount > 0)
                                <div style="display: flex; justify-content: space-between; padding: 4px 0; color: red;">
                                    <span>បញ្ចុះតម្លៃសរុប:</span>
                                    <span>-${{ number_format($order->total_discount, 2) }}</span>
                                </div>
                                @endif
                                <div class="grand-total-box">
                                    <div style="display: flex; justify-content: space-between; font-size: 16px;">
                                        <span>សរុបរួម (USD):</span>
                                        <span>${{ number_format($order->grand_total, 2) }}</span>
                                    </div>
                                </div>
                                <div style="text-align: right; margin-top: 10px; font-weight: bold; color: var(--primary-color); font-size: 17px; border-bottom: 2px double var(--primary-color);">
                                    សរុបជាប្រាក់រៀល: {{ number_format($order->grand_total * $rate, 0) }} ៛
                                </div>
                            </div>
                        </div>

                        <div class="signature-section">
                            <div><div class="sig-line"></div>អតិថិជន</div>
                            <div><div class="sig-line"></div>អ្នកលក់</div>
                            <div><div class="sig-line"></div>អ្នកដឹកជញ្ជូន</div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="no-print" style="text-align: center; margin: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; background: var(--primary-color); color: white; border: none; border-radius: 5px; cursor: pointer;">បោះពុម្ពវិក្កយបត្រ (Print)</button>
    </div>
</body>
</html>
