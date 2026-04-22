@extends('layout.app')

@section('content')
    <style>
        /* Table Header Styling */
        .table-custom-admin thead th {
            background-color: #212529 !important;
            color: white !important;
            font-weight: 500;
            font-size: 13px;
            border: 1px solid #343a40;
            padding: 12px 10px;
            white-space: nowrap;
        }

        .table-custom-admin tbody td {
            border: 1px solid #dee2e6;
            font-size: 13px;
            padding: 8px 10px;
        }

        .border-top-primary {
            border-top: 3px solid #3c8dbc !important;
        }

        .border-top-success {
            border-top: 3px solid #28a745 !important;
        }

        .border-top-danger {
            border-top: 3px solid #dc3545 !important;
        }

        .stat-card {
            transition: all 0.3s;
            border: 1px solid #eee;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


        /* បង្រួមចន្លោះ Padding ក្នុង Cell ឱ្យតូចជាងមុន */
        .table-custom-admin td,
        .table-custom-admin th {
            padding: 8px 12px !important;
            /* បន្ថយពី 15px មកត្រឹម 8px */
            font-size: 13px;
            /* បន្ថយទំហំអក្សរបន្តិច */
        }

        /* កំណត់ទំហំ Column នីមួយៗឱ្យសមស្រប */
        #salesTable th:nth-child(1) {
            width: 140px;
        }

        /* Date */
        #salesTable th:nth-child(2) {
            width: 120px;
        }

        /* Invoice # */
        #salesTable th:nth-child(3) {
            width: 150px;
        }

        /* Customer (ទុកឱ្យវាទាញតាមជាក់ស្តែង) */
        #salesTable th:nth-child(4),
        #salesTable th:nth-child(5),
        #salesTable th:nth-child(6) {
            width: 100px;
        }

        /* Totals */
        #salesTable th:nth-child(7) {
            width: 80px;
        }

        /* Status */
        #salesTable th:nth-child(8) {
            width: 30px;
        }

        /* Actions */


        @media print {

            /* លាក់អ្វីដែលមិនចង់ឱ្យជាប់ក្នុង PDF/Print */
            .btn-group,
            .sidebar,
            .navbar,
            .pagination,
            .no-print {
                display: none !important;
            }

            /* កំណត់ទំហំក្រដាស */
            @page {
                size: auto;
                margin: 10mm;
            }

            body {
                background: white !important;
            }
        }
    </style>
    <style>
        /* កំណត់ស្ទីលពិសេសសម្រាប់តែពេលទាញយក PDF */
        .pdf-header {
            display: none;
        }

        /* លាក់ក្នុង Web ធម្មតា */

        .pdf-report-style thead th {
            background-color: #1a237e !important;
            /* ពណ៌ខៀវចាស់ */
            color: white !important;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .pdf-report-style td {
            border: 1px solid #dee2e6;
            padding: 8px !important;
        }

        thead th {
            cursor: pointer;
            user-select: none;
            /* ការពារកុំឱ្យ highlight អក្សរពេលចុចញាប់ */
        }

        thead th:hover {
            background-color: #f8f9fa;
            color: #0056b3;
        }

        /* Style សម្រាប់ Icon ពេលកំពុង Sort */
        .fa-sort-up,
        .fa-sort-down {
            opacity: 1 !important;
            color: #ffc107;
            /* ពណ៌លឿងទុំ ឬពណ៌ដែលបងស្រលាញ់ */
        }
    </style>


    <div class="content-wrapper bg-white p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0">Sales History</h4>
                <small class="text-muted">គ្រប់គ្រង និងតាមដានរាល់ប្រតិបត្តិការលក់ (Table: orders)</small>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">Sales</li>
                </ol>
            </nav>
        </div>

        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card border-top-primary shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Total Sales</small>
                        <h3 class="fw-bold text-primary mb-0">${{ number_format($totalSales, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-top-success shadow-sm">
                    <div class="card-body text-center">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Total Collected</small>
                        <h3 class="fw-bold text-success mb-0">${{ number_format($totalCollected, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-top-danger shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-center align-items-center mb-1">
                            <i class="fas fa-hand-holding-usd text-danger me-2"></i>
                            <small class="text-muted text-uppercase fw-bold">សរុបជំពាក់ (Total Debt)</small>
                        </div>
                        <h3 class="fw-bold text-danger mb-0">
                            ${{ number_format($totalDebt, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-top-primary shadow-sm text-center bg-light">
                    <div class="card-body">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Invoices</small>
                        <h3 class="fw-bold mb-0">{{ $orders->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-top-primary shadow-none border">
            <div class="card-body p-3">
                {{-- Toolbar --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-1 gap-2">
                    <div class="d-flex align-items-center small">
                        <form method="get" id="pageSizeForm" class="d-flex align-items-center">
                            Show
                            <select name="pageSize" class="form-select form-select-sm mx-2" style="width:70px"
                                onchange="this.form.submit()">
                                <option value="10" {{ request('pageSize') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('pageSize') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('pageSize') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            entries
                        </form>
                    </div>
                    <div class="btn-group shadow-sm">
                        <button class="btn btn-outline-secondary btn-sm" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-1 text-success"></i> Excel
                        </button>

                        <button class="btn btn-outline-secondary btn-sm" onclick="downloadPDF()">
                            <i class="fas fa-file-pdf me-1 text-danger"></i> PDF
                        </button>

                        <a href="javascript:void(0)" onclick="printReport('{{ route('orders.printAll') }}')"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-print me-1 text-primary"></i> Print All
                        </a>

                        <iframe id="printFrame" style="display:none;"></iframe>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover table-custom-admin align-middle mb-0" id="salesTable">
                        <thead>
                            <tr>
                                <th>Date <i class="fas fa-sort float-end opacity-50"></i></th>
                                <th>Invoice # <i class="fas fa-sort float-end opacity-50"></i></th>
                                <th>Customer <i class="fas fa-sort float-end opacity-50"></i></th>
                                <th class="text-end">Grand Total <i class="fas fa-sort float-end opacity-50"></i></th>
                                <th class="text-end">Paid <i class="fas fa-sort float-end opacity-50"></i></th>
                                <th class="text-end">Balance <i class="fas fa-sort float-end opacity-50"></i></th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php
                                    $balance = $order->debt_amount ?? 0;
                                    $paid = $order->grand_total - $balance;
                                @endphp
                                <tr>
                                    <iframe id="printFrame" style="display:none;"></iframe>
                                    <td class="small">{{ \Carbon\Carbon::parse($order->order_date)->format('d-M-Y H:i') }}
                                    </td>
                                    <td class="text-primary fw-bold">#{{ $order->invoice_no }}</td>
                                    <td>{{ $order->customer->name ?? 'Walking Customer' }}</td>
                                    <td class="text-end fw-bold">${{ number_format($order->grand_total, 2) }}</td>
                                    <td class="text-end text-success">${{ number_format($paid, 2) }}</td>
                                    <td class="text-end fw-bold">
                                        {{-- បង្ហាញពណ៌ក្រហមបើនៅជំពាក់ និងពណ៌បៃតងបើបង់ដាច់ --}}
                                        <span class="{{ $balance > 0.005 ? 'text-danger' : 'text-success' }}">
                                            ${{ number_format($balance, 2) }}
                                        </span>

                                    </td>
                                    <td class="text-center">
                                        @if ($order->is_paid)
                                            <span class="badge bg-success small shadow-sm">PAID</span>
                                        @elseif($order->is_credit)
                                            <span class="badge bg-warning text-dark small shadow-sm">CREDIT</span>
                                        @else
                                            <span class="badge bg-danger small shadow-sm">UNPAID</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm shadow-sm border" type="button"
                                                data-bs-toggle="dropdown" style="background: white;">
                                                <i class="fa-solid fa-gears"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-1">
                                                <li>
                                                    <a class="dropdown-item rounded-2 py-2" href="javascript:void(0)"
                                                        onclick="printInvoice({{ $order->id }})">
                                                        <i class="fas fa-print me-2 text-primary"></i> Print Invoice
                                                    </a>
                                                </li>

                                                @if (!$order->is_paid && $order->is_completed !== 'cancelled')
                                                    <li>
                                                        <hr class="dropdown-divider my-1">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item rounded-2 py-2" href="javascript:void(0)"
                                                            onclick="forceOpenModal({{ $order->id }})">
                                                            <i class="fas fa-hand-holding-usd me-2 text-warning"></i> Pay
                                                            Debt
                                                        </a>
                                                    </li>
                                                @endif

                                                @if ($order->is_completed !== 2)
                                                    <li>
                                                        <hr class="dropdown-divider my-1">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('orders.cancel', $order->id) }}"
                                                            id="cancel-form-{{ $order->id }}" method="POST">
                                                            @csrf
                                                            <button type="button"
                                                                onclick="confirmCancel({{ $order->id }}, '{{ $order->invoice_no }}')"
                                                                class="dropdown-item rounded-2 py-2 text-danger">
                                                                <i class="fas fa-times-circle me-2"></i> Cancel Order
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <span class="dropdown-item disabled text-muted italic">
                                                            <i class="fas fa-ban me-2"></i> Order Cancelled
                                                        </span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
     
            <div class="mt-3">
                {{ $orders->appends(request()->query())->links() }}
            </div>

            </div>
        </div>
    </div>
    {{-- ✅ ត្រូវដាក់ Modal នៅក្រៅ Loop ឬនៅខាងក្រោមបង្អស់នៃតារាង --}}
    @foreach ($orders as $order)
        @if (!$order->is_paid)
            @include('order.pay_modal', ['order' => $order, 'currentBalance' => $order->debt_amount])
        @endif
    @endforeach
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function forceOpenModal(orderId) {
            // ១. លុបចោលរាល់ backdrop ចាស់ៗដែលគាំង (បើមាន)
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');

            // ២. ទាញយក Element Modal
            var modalEl = document.getElementById('payDebtModal-' + orderId);

            if (modalEl) {
                // ៣. បង្កើត Instance ថ្មីហើយបង្ហាញ
                var modal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: true
                });
                modal.show();
            } else {
                alert('រកមិនឃើញ Modal ID: #payDebtModal-' + orderId);
            }
        }
        $(document).ready(function() {
            const EXCHANGE_RATE = 4100;

            // កូដសម្រាប់គណនាលុយដែលវាយបញ្ចូលក្នុង Modal (Real-time)
            $('.input-pay').on('input', function() {
                let orderId = $(this).data('order-id');
                let usd = parseFloat($(`#payDollar-${orderId}`).val()) || 0;
                let riel = parseFloat($(`#payRiel-${orderId}`).val()) || 0;

                // គណនាជាដុល្លារសរុប
                let totalInput = usd + (riel / EXCHANGE_RATE);

                // បង្ហាញលើ UI
                $(`#totalInputDisplay-${orderId}`).text(`$ ${totalInput.toFixed(2)}`);
            });

            // កូដសម្រាប់បញ្ជាក់ការបង់ប្រាក់ (Confirm Pay)
            $('.confirmPayDebt').on('click', function() {
                let orderId = $(this).data('order');
                let usd = parseFloat($(`#payDollar-${orderId}`).val()) || 0;
                let riel = parseFloat($(`#payRiel-${orderId}`).val()) || 0;
                let totalPay = usd + (riel / EXCHANGE_RATE);

                if (totalPay <= 0) {
                    return Swal.fire('បញ្ជាក់', 'សូមបញ្ចូលចំនួនទឹកប្រាក់ដែលត្រូវបង់!', 'warning');
                }

                Swal.fire({
                    title: 'បញ្ជាក់ការបង់ប្រាក់',
                    text: `តើបងពិតជាចង់កត់ត្រាការបង់ប្រាក់ចំនួន $${totalPay.toFixed(2)} នេះមែនទេ?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'បាទ/ចាស, បង់ប្រាក់',
                    cancelButtonText: 'បោះបង់'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('orders.pay-debt') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                order_id: orderId,
                                received_usd: usd,
                                received_riel: riel,
                                pay_amount: totalPay
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire('ជោគជ័យ', 'ការទូទាត់ត្រូវបានរក្សាទុក!',
                                            'success')
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire('បរាជ័យ', res.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('កំហុស', 'មានបញ្ហាបច្ចេកទេសលើ Server',
                                    'error');
                            }
                        });
                    }
                });
            });
        });


        function printInvoice(id) {
            // ១. បង្កើត URL
            var url = "{{ route('orders.print', ':id') }}";
            url = url.replace(':id', id);

            // ២. ទាញយក Iframe element
            var frame = document.getElementById('printFrame');

            // ៣. បោះ URL ទៅឱ្យ Iframe
            frame.src = url;

            // ៤. រង់ចាំឱ្យវា Load ចប់ រួចបញ្ជាឱ្យ Print
            frame.onload = function() {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            };
        }


        function exportToExcel() {
            // ១. ចាប់យក Table ដើម
            const table = document.getElementById("salesTable");
            const fileName = 'Sales_Report.xls';
            // ២. បង្កើតតារាង Clone ដើម្បីកុំឱ្យប៉ះពាល់ Table ដើមលើអេក្រង់
            const tableClone = table.cloneNode(true);

            // ៣. លុប Column ដែលមិនត្រូវការ (ឧទាហរណ៍៖ Column ទី ៧ និង ទី ៨)
            const rows = tableClone.querySelectorAll('tr');
            rows.forEach(row => {
                // លុបពីក្រោយមកមុខ ដើម្បីកុំឱ្យច្រឡំលេខលំដាប់ (Index)
                if (row.cells.length >= 8) {
                    row.cells[7].remove(); // លុប Action
                    row.cells[6].remove(); // លុប Status
                }
            });

            // ៤. រៀបចំមាតិកា HTML សម្រាប់ Excel (បញ្ចូលទាំង Header ខ្មែរ)
            const reportTitle = "របាយការណ៍លក់ប្រចាំថ្ងៃ";
            const printDate = new Date().toLocaleString('km-KH');
            const excelHtml = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset='utf-8'>
                <style>
                    .header-table { font-family: 'Hanuman', serif; text-align: center; }
                    .title { font-size: 18pt; font-weight: bold; color: #1a237e; }
                    table { border-collapse: collapse; }
                    th { background-color: #f8f9fa; border: 1px solid #000; padding: 5px; }
                    td { border: 1px solid #000; padding: 5px; }
                </style>
            </head>
            <body>
                <table class="header-table">
                    <tr><td colspan="6" class="title">${reportTitle}</td></tr>
                    <tr><td colspan="6">កាលបរិច្ឆេទបោះពុម្ព៖ ${printDate}</td></tr>
                    <tr><td colspan="6"></td></tr>
                </table>
                ${tableClone.outerHTML}
            </body>
            </html>
        `;

            // ៥. ដំណើរការទាញយកជា File .xls
            const blob = new Blob([excelHtml], {
                type: 'application/vnd.ms-excel'
            });
            const url = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.href = url;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function downloadPDF() {
            const element = document.getElementById('salesTable');

            // ១. លាក់ Column Status និង Action បណ្ដោះអាសន្នលើ Web
            const statusCols = document.querySelectorAll('#salesTable th:nth-child(7), #salesTable td:nth-child(7)');
            const actionCols = document.querySelectorAll('#salesTable th:nth-child(8), #salesTable td:nth-child(8)');

            statusCols.forEach(col => col.style.display = 'none');
            actionCols.forEach(col => col.style.display = 'none');

            // ២. បង្កើត Container ថ្មីដើម្បីខ្ចប់ Header និង Table ចូលគ្នា
            const wrapper = document.createElement('div');
            wrapper.style.padding = "20px";
            wrapper.style.background = "white";

            // បង្កើត Header ស្អាតមួយសម្រាប់ PDF
            const headerContent = `
            <div style="text-align: center; margin-bottom: 20px; font-family: 'Hanuman', serif;">
                <h2 style="color: #1a237e; margin: 0; font-size: 26px;">របាយការណ៍លក់ប្រចាំថ្ងៃ</h2>
                <p style="margin: 5px 0; font-size: 14px; color: #555;">កាលបរិច្ឆេទបោះពុម្ព៖ ${new Date().toLocaleString('km-KH')}</p>
                <hr style="border: 1px solid #1a237e; margin-top: 10px;">
            </div>
        `;

            // ៣. ចម្លងតារាង (Clone) ដាក់ចូលក្នុង Wrapper ដើម្បីកុំឱ្យដាច់ Header
            wrapper.innerHTML = headerContent;
            const tableClone = element.cloneNode(true);
            tableClone.style.width = "100%";
            wrapper.appendChild(tableClone);

            // ៤. កំណត់ Options ឱ្យច្បាស់លាស់
            const opt = {
                margin: [10, 10, 10, 10],
                filename: 'Sales_Report.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    letterRendering: true,
                    logging: false
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'landscape'
                }
            };

            // ៥. ដំណើរការទាញយកពី Wrapper (Header + Table)
            html2pdf().set(opt).from(wrapper).save().then(() => {
                // បង្ហាញ Column មកវិញក្រោយពេលចប់
                statusCols.forEach(col => col.style.display = '');
                actionCols.forEach(col => col.style.display = '');
            });
        }

        $(document).ready(function() {
            $('th').click(function() {
                var table = $(this).parents('table').eq(0);
                var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
                this.asc = !this.asc;

                // ប្តូរ Icon Sort
                $('th i').addClass('opacity-50').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
                if (this.asc) {
                    $(this).find('i').removeClass('fa-sort opacity-50').addClass('fa-sort-up');
                } else {
                    $(this).find('i').removeClass('fa-sort opacity-50').addClass('fa-sort-down');
                    rows = rows.reverse();
                }

                for (var i = 0; i < rows.length; i++) {
                    table.append(rows[i]);
                }
            });

            function comparer(index) {
                return function(a, b) {
                    var valA = getCellValue(a, index),
                        valB = getCellValue(b, index);

                    // បើជាលេខ ឬតម្លៃទឹកប្រាក់ ឱ្យដកសញ្ញា $ ចេញដើម្បីប្រៀបធៀប
                    if ($.isNumeric(valA.replace(/[$,]/g, '')) && $.isNumeric(valB.replace(/[$,]/g, ''))) {
                        return valA.replace(/[$,]/g, '') - valB.replace(/[$,]/g, '');
                    }

                    return valA.toString().localeCompare(valB);
                };
            }

            function getCellValue(row, index) {
                return $(row).children('td').eq(index).text();
            }
        });

        function printReport(url) {
            var iframe = document.getElementById('printFrame');

            // បង្ហាញ Loading បន្តិចបើសិនជាចង់
            // Swal.showLoading();

            iframe.src = url;

            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                }, 500); // រង់ចាំ 0.5 វិនាទីដើម្បីឱ្យទិន្នន័យ Load ពេញលេញ
            };
        }


        function confirmCancel(orderId) {
            Swal.fire({
                title: 'តើអ្នកពិតជាចង់ Cancel មែនទេ?',
                text: "រាល់ទំនិញក្នុងវិក្កយបត្រនេះ នឹងត្រូវបូកបញ្ចូលក្នុងស្តុកវិញ!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'បាទ/ចាស, Cancel វា!',
                cancelButtonText: 'ទេ, ទុកវិញ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // បញ្ជូន Form ទៅកាន់ Controller
                    document.getElementById('cancel-form-' + orderId).submit();
                }
            })
        }
    </script>
@endpush
