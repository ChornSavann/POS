@php
    // កំណត់អត្រាប្តូរប្រាក់ និងឈ្មោះអ្នកលក់
    $exchangeRate = 4100;
    $cashierName = Auth::user()->name ?? 'Admin';

    // ទាញយកព័ត៌មានការបង់ប្រាក់ (Payment Info)
    // សន្មតថា Order hasMany Payments
    $paymentInfo = $order->payments()->first();
    $receivedUSD = $paymentInfo ? $paymentInfo->paid_amount : 0;
    $changeUSD = $receivedUSD > $order->grand_total ? $receivedUSD - $order->grand_total : 0;
@endphp

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="utf-8" />
    <title>POS Receipt - {{ $order->invoice_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap');

        body {
            font-family: 'Kantumruy Pro', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            color: #000;
            background-color: #fff;
        }

        .ticket {
            width: 76mm;
            margin: 0 auto;
            padding: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .logo {
            width: 60px;
            height: auto;
            margin-bottom: 5px;
            filter: grayscale(100%);
        }

        .shop-name {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .info-table {
            width: 100%;
            font-size: 10px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .item-table th {
            background-color: #000;
            color: #fff;
            text-align: left;
            padding: 5px 2px;
            font-size: 9px;
            -webkit-print-color-adjust: exact;
        }

        .item-table td {
            padding: 6px 2px;
            vertical-align: middle;
            border-bottom: 0.5px solid #eee;
            font-size: 9.5px;
        }

        .total-section-table {
            width: 100%;
            border-collapse: collapse;
        }

        .total-section-table td {
            padding: 2px 0;
        }

        .bold-text {
            font-weight: 700;
        }

        .grand-total-row {
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
        }

        .grand-total-row td {
            padding: 6px 0 !important;
            font-weight: 700;
            font-size: 13px;
        }

        .aba-payment-card {
            width: 220px;
            margin: 15px auto;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            text-align: center;
        }

        .aba-brand {
            font-family: Arial, sans-serif;
            font-size: 20px;
            font-weight: 900;
            color: #005d7d;
            margin-bottom: 2px;
        }

        .aba-brand span {
            color: #ed1c24;
        }

        .qr-only-image {
            width: 150px;
            height: 150px;
            display: block;
            margin: 0 auto;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 9px;
            line-height: 1.5;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        @media print {
            .no-print { display: none !important; }
            @page {
                margin: 0;
                size: auto;
            }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="ticket">
        <div class="header">
            <img src="{{ asset('Image/Logo/Logo-removebg-preview (1).png') }}" class="logo" alt="Logo">
            <div class="shop-name">Restaurant Shop</div>
        </div>

        <div class="divider"></div>

        <table class="info-table">
            <tr>
                <td>អតិថិជន: <b>{{ $order->customer->name ?? 'Walk-In' }}</b></td>
                <td class="text-right">កាលបរិច្ឆេទ: <span class="bold-text">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/y H:i') }}</span></td>
            </tr>
            <tr>
                <td>លេខតុ / Table: <b style="font-size: 12px;">{{ $order->table_id != 0 ? $order->table_id : 'N/A' }}</b></td>
                <td class="text-right">វិក្កយបត្រ: <b class="bold-text">#{{ $order->invoice_no }}</b></td>
            </tr>
            <tr>
                <td>បេឡាករ: {{ $cashierName }}</td>
                <td class="text-right">អាសយដ្ឋាន: <span class="bold-text">PHNOM PENH</span></td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 35%;">ការពិពណ៌នា</th>
                    <th class="text-center">តម្លៃ</th>
                    <th class="text-center">បញ្ចុះ</th>
                    <th class="text-center">ចំនួន</th>
                    <th class="text-right">សរុប</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td><div style="font-weight: bold;">{{ $item->product->name ?? 'Unknown' }}</div></td>
                        <td class="text-center bold-text">${{ number_format($item->price, 2) }}</td>
                        <td class="text-center bold-text">{{ $item->discount > 0 ? "-$" . number_format($item->discount, 2) : "0" }}</td>
                        <td class="text-center bold-text">{{ number_format($item->qty, 0) }}</td>
                        <td class="text-right"><b style="font-size: 10px;">${{ number_format($item->total, 2) }}</b></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-section-table">
                <tr>
                    <td>សរុបរង (Sub-Total):</td>
                    <td class="text-right bold-text">${{ number_format($order->sub_total, 2) }}</td>
                </tr>

                @if ($order->discount > 0)
                    <tr>
                        <td class="bold-text">បញ្ចុះតម្លៃបន្ថែម (Order Discount):</td>
                        <td class="text-right bold-text">-${{ number_format($order->discount, 2) }}</td>
                    </tr>
                @endif

                <tr class="grand-total-row">
                    <td>សរុបរួម (Grand Total):</td>
                    <td class="text-right">${{ number_format($order->grand_total, 2) }}</td>
                </tr>

                <tr>
                    <td style="padding-top: 5px; font-style: italic;">សរុបជាប្រាក់រៀល (RIEL):</td>
                    <td class="text-right bold-text" style="padding-top: 5px; font-size: 11px;">{{ number_format($order->grand_total * $exchangeRate, 0) }} ៛</td>
                </tr>
            </table>

            <div class="divider"></div>

            <table class="total-section-table">
                <tr>
                    <td>ប្រាក់ទទួលបាន (Received):</td>
                    <td class="text-right bold-text">${{ number_format($receivedUSD, 2) }}</td>
                </tr>
                <tr>
                    <td><b style="font-size: 11px;">ប្រាក់អាប់ (Change):</b></td>
                    <td class="text-right"><b style="font-size: 12px;">${{ number_format($changeUSD, 2) }}</b></td>
                </tr>
                @if ($paymentInfo)
                    <tr style="font-size: 9px;">
                        <td>វិធីបង់ប្រាក់:</td>
                        <td class="text-right bold-text">{{ $paymentInfo->payment_method }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div class="aba-payment-card">
            <div class="aba-brand">ABA<span>'</span>PAY</div>
            <img src="{{ asset('Image/Qr.jpg') }}" class="qr-only-image" alt="QR">
            <div style="font-weight: bold; margin-top: 10px; text-transform: uppercase;">CHORN SAVANN</div>
        </div>

        <div class="footer">
            <div class="divider"></div>
            <p style="font-weight: bold; font-size: 10px;">សូមពិនិត្យទំនិញ និង លុយអាប់មុនចាកចេញ</p>
            <p>ទំនិញទិញហើយមិនអាចប្តូរវិញបានទេ<br><b>*** អរគុណ សូមអញ្ជើញមកម្តងទៀត ***</b></p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px; padding-bottom: 30px;">
        <button onclick="window.print()" style="padding: 10px 25px; background: #005d7d; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Print Invoice</button>
        <a href="{{ route('order.index') }}" style="padding: 10px 25px; background: #4caf50; color: #fff; text-decoration: none; border-radius: 5px; margin-left: 10px; display: inline-block; font-weight: bold;">Back to POS</a>
    </div>

</body>
</html>
<script>
    // ប្រសិនបើបើកក្នុង Iframe ឱ្យវា Print អូតូ
    window.onload = function() {
        if (window.self !== window.top) {
            // បើកក្នុង Iframe មិនបាច់ធ្វើអ្វីទេ ទុកឱ្យ parent handle
        } else {
            // បើបើកផ្ទាល់ ឱ្យវាលោតផ្ទាំង print មកដែរ
            window.print();
        }
    }
</script>
