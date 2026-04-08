@extends('layout.app')

@section('content')
    <div class="header mb-2">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom custom-header">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-dark text-white me-3">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark text-khmer">របាយការណ៍វិក្កយបត្រប្រចាំខែ</h5>
                    <small class="text-muted italic">ប្រព័ន្ធគ្រប់គ្រងការលក់ និងវិក្កយបត្រ</small>
                </div>
            </div>

            <div class="search-container mx-3">
                <div class="input-group input-group-sm" style="width: 350px;">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="tableSearch" class="form-control border-start-0 ps-0 shadow-none"
                        placeholder="ស្វែងរកអតិថិជន ឬលេខវិក្កយបត្រ...">
                    <button class="btn btn-primary px-3 fw-bold" type="button">ស្វែងរក</button>
                </div>
            </div>

            <div>
                <a href="{{ route('reports.monthly') }}"
                    class="btn btn-sm btn-outline-secondary px-3 rounded-2 fw-bold shadow-sm float-end">
                    <i class="fas fa-arrow-left me-1"></i> ត្រឡប់ក្រោយ
                </a>
            </div>
        </div>
    </div>
    {{-- ២. បន្ថែម Card សរុបប្រចាំខែនៅទីនេះ --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 border-start border-primary border-5">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">វិក្កយបត្រសរុប (Invoices)</p>
                            <h4 class="mb-0 fw-bold">{{ count($invoices) }}</h4>
                        </div>
                        <div class="text-primary opacity-50">
                            <i class="fas fa-receipt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 border-start border-success border-5">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">ចំណូលសរុប (Total Revenue)</p>
                            <h4 class="mb-0 fw-bold text-success">${{ number_format($invoices->sum('grand_total'), 2) }}
                            </h4>
                        </div>
                        <div class="text-success opacity-50">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 border-start border-warning border-5">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">ទំនិញលក់ចេញសរុប (Total Items)</p>
                            <h4 class="mb-0 fw-bold text-warning">
                                {{ number_format($invoices->sum('total_items_count'), 0) }} មុខ
                            </h4>
                        </div>
                        <div class="text-warning opacity-50">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered custom-std-grid mb-0" id="invoiceTable">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 60px;">ល.រ</th>
                                    <th rowspan="2">កាលបរិច្ឆេទ <i class="fas fa-sort float-end mt-1 opacity-50"></i>
                                    </th>
                                    <th rowspan="2">លេខវិក្កយបត្រ <i class="fas fa-sort float-end mt-1 opacity-50"></i>
                                    </th>
                                    <th rowspan="2">ឈ្មោះអតិថិជន <i class="fas fa-sort float-end mt-1 opacity-50"></i>
                                    </th>
                                    <th rowspan="2" class="text-center">ចំនួនមុខ <i
                                            class="fas fa-sort float-end mt-1 opacity-50"></i></th>
                                    <th colspan="1" class="text-center">សរុបទឹកប្រាក់</th>
                                    <th rowspan="1" class="text-center">ស្ថានភាព</th>
                                    <th rowspan="1" class="text-center">សកម្មភាព</th>
                                </tr>

                            </thead>
                            <tbody>
                                @forelse ($invoices as $key => $inv)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ date('d-M-Y H:i', strtotime($inv->order_date)) }}</td>
                                        <td class="fw-bold">#{{ $inv->invoice_no ?? $inv->id }}</td>
                                        <td>{{ $inv->Customer->name ?? 'អតិថិជនទូទៅ' }}</td>
                                        <td class="text-center fw-bold text-primary">{{ $inv->total_items_count ?? 0 }}</td>

                                        <td class="text-end fw-bold text-dark">
                                            {{ number_format($inv->grand_total ?? 0, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-paid">រួចរាល់</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-sm btn-soft-primary p-0 btn-view-invoice"
                                                data-bs-toggle="modal" data-bs-target="#viewInvoiceModal"
                                                data-id="{{ $inv->invoice_no ?? $inv->id }}"
                                                data-date="{{ date('d-M-Y H:i', strtotime($inv->order_date)) }}"
                                                data-customer="{{ $inv->customer?->name ?? 'អតិថិជនទូទៅ' }}"
                                                data-items="{{ $inv->total_items_count ?? 0 }}"
                                                data-total="{{ number_format($inv->grand_total ?? 0, 2) }}"
                                                style="width: 26px; height: 26px; border-radius: 6px;">
                                                <i class="fas fa-eye" style="font-size: 10px;"></i>
                                            </button>
                                        </td>

                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">មិនមានទិន្នន័យ</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 38px; height: 38px;">
                            <i class="fas fa-file-invoice text-dark fs-5"></i>
                        </div>
                        <div>
                            <h6 class="modal-title text-khmer mb-0 fw-bold">ព័ត៌មានវិក្កយបត្រ</h6>
                            <small class="text-white-50">ID: #<span id="modal-inv-id" class="fw-bold"></span></small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-light-gray">
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body p-0">
                            <table class="table mb-0 enterprise-grid-layout">
                                <tbody>
                                    <tr>
                                        <th class="ps-4 py-3 text-khmer text-muted fw-normal" style="width: 45%;">
                                            <i class="far fa-calendar-alt me-2"></i>កាលបរិច្ឆេទ
                                        </th>
                                        <td class="pe-4 py-3 text-end fw-bold text-dark" id="modal-inv-date"></td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 py-3 text-khmer text-muted fw-normal">
                                            <i class="far fa-user me-2"></i>អតិថិជន
                                        </th>
                                        <td class="pe-4 py-3 text-end fw-bold text-khmer text-primary"
                                            id="modal-inv-customer"></td>
                                    </tr>
                                    <tr>
                                        <th class="ps-4 py-3 text-khmer text-muted fw-normal border-0">
                                            <i class="fas fa-boxes me-2"></i>ចំនួនមុខទំនិញ
                                        </th>
                                        <td class="pe-4 py-3 text-end fw-bold text-dark border-0">
                                            <span class="badge bg-info-soft text-info rounded-pill px-3"
                                                id="modal-inv-items"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                        <div class="p-3 border-start border-danger border-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-khmer text-muted mb-0">សរុបទឹកប្រាក់ត្រូវបង់</p>
                                    <h3 class="mb-0 fw-bold text-danger">$<span id="modal-inv-total"></span></h3>
                                </div>
                                <div class="text-end">
                                    <span
                                        class="badge bg-success-soft text-success border border-success-subtle px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i>រួចរាល់
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-white justify-content-center pb-4">
                    <button type="button" class="btn btn-light text-khmer px-4 py-2 border rounded-pill"
                        data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>បិទ
                    </button>
                    <button class="btn btn-sm btn-outline-primary rounded-circle shadow-sm btn-print-invoice"
                        onclick="printInvoice('{{ $inv->invoice_no ?? $inv->id }}')" {{-- ថែមសញ្ញា ' ' នៅទីនេះ --}}
                        style="width: 35px; height: 35px; padding: 0; transition: all 0.2s ease;">
                        <i class="bi bi-printer"></i>
                    </button>

                    <style>
                        .btn-print-invoice:hover {
                            background-color: #0d6efd;
                            color: white;
                            transform: translateY(-2px);
                            /* ហោះឡើងលើតិចៗ */
                            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3) !important;
                        }

                        .btn-print-invoice:active {
                            transform: translateY(0);
                            /* ត្រឡប់មកធម្មតាពេលចុច */
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* ពណ៌ Background បន្តិចបន្តួចសម្រាប់ Body */
        .bg-light-gray {
            background-color: #f1f4f9;
        }

        /* រចនា Table ឱ្យបាត់បន្ទាត់ខ្លះៗ មើលទៅ Clean */
        .enterprise-grid-layout th,
        .enterprise-grid-layout td {
            border-color: #f1f1f1 !important;
            font-size: 0.95rem;
        }

        /* Soft Badge Styles */
        .bg-success-soft {
            background-color: #d1f7e0;
            color: #0f6130;
        }

        .bg-info-soft {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        /* Rounded Shapes */
        .rounded-4 {
            border-radius: 1rem !important;
        }

        /* Font ខ្មែរ */
        .text-khmer {
            font-family: 'Kantumruy Pro', 'Koumoneane', sans-serif;
        }

        /* ប៊ូតុង Hover Effect */
        .btn-primary.rounded-pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25) !important;
        }

        /* បន្ថែម CSS នេះដើម្បីឱ្យ Button មើលទៅមានលក្ខណៈ Premium */
        .btn-soft-primary {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border: none;
            transition: all 0.2s;
        }

        .btn-soft-primary:hover {
            background-color: #0d6efd;
            color: white;
        }

        /* 1. រៀបចំ Font និងបន្ទាត់ខាងក្រៅតារាង */
        .custom-std-grid {
            border: 1.5px solid #343a40 !important;
            /* បន្ទាត់ក្រៅបង្អស់ឱ្យដិត */
            font-family: 'Kantumruy Pro', 'Segoe UI', sans-serif;
            font-size: 14px;
            border-collapse: collapse !important;
        }

        /* 2. Header ស្ទីលដិត (ដូចក្នុងរូបភាព) */
        .custom-std-grid thead tr {
            background-color: #343a40 !important;
            /* ពណ៌ Charcoal */
            color: #ffffff !important;
        }

        .custom-std-grid thead th {
            border: 1px solid #4b545c !important;
            /* បន្ទាត់ខណ្ឌចន្លោះ Header */
            font-weight: 500;
            padding: 12px 10px !important;
            vertical-align: middle;
            text-align: center;
        }

        /* 3. រៀបចំ Body ឱ្យមានបន្ទាត់ Grid ច្បាស់ៗ */
        .custom-std-grid tbody td {
            border: 1px solid #ced4da !important;
            /* បន្ទាត់ក្រឡាច្បាស់ៗ */
            padding: 10px !important;
            vertical-align: middle;
            color: #2d3436;
        }

        /* 4. Highlight ជួរឆ្លាស់ពណ៌ (Zebra Stripe) */
        .custom-std-grid tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* 5. នៅពេល Hover ឱ្យចេញពណ៌ខៀវខ្ចីបន្តិច */
        .custom-std-grid tbody tr:hover {
            background-color: #f1f4f7 !important;
            transition: 0.2s;
        }

        /* 6. ស្ទីល Status Badge */
        .badge-paid {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: 600;
        }

        /* 7. Icon Sort ឱ្យនៅខាងស្តាំដៃ */
        .fa-sort {
            font-size: 11px;
            margin-left: 5px;
            color: #6c757d;
            opacity: 0.6;
        }

        /* កែសម្រួល Font និងពណ៌ Border ខាងក្រៅ */
        .custom-std-grid {
            border: 1px solid #333 !important;
            /* ប្តូរពី 1.5px មក 1px ដើម្បីឱ្យបន្ទាត់តូចតែដិត */
            font-family: 'Kantumruy Pro', sans-serif;
            font-size: 13px;
            /* បន្ថយទំហំអក្សរបន្តិចដើម្បីឱ្យសមជាមួយតារាងធំ */
            border-collapse: collapse !important;
        }

        /* រៀបចំ Header ឱ្យមានបន្ទាត់ខណ្ឌដិតច្បាស់ */
        .custom-std-grid thead th {
            background-color: #343a40 !important;
            color: #ffffff !important;
            border: 1px solid #555 !important;
            /* បន្ទាត់ខណ្ឌក្នុង Header */
            padding: 8px 10px !important;
            vertical-align: middle;
            text-align: center;
        }

        /* រៀបចំ Body ឱ្យមានបន្ទាត់ Grid ពណ៌ប្រផេះដិត */
        .custom-std-grid tbody td {
            border: 1px solid #ccc !important;
            /* បន្ទាត់ Grid ក្នុងតារាង */
            padding: 8px 10px !important;
            vertical-align: middle;
            color: #333;
        }

        /* Zebra Stripe ជួរឆ្លាស់ពណ៌ */
        .custom-std-grid tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Hover Effect */
        .custom-std-grid tbody tr:hover {
            background-color: #f1f4f7 !important;
        }

        /* ស្ទីល Status Badge */
        .badge-paid {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: 600;
        }
    </style>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // មុខងារស្វែងរកក្នុងតារាង (Search)
            $('#tableSearch').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $("#invoiceTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // មុខងារបោះទិន្នន័យទៅកាន់ Modal (View Details)
            $('.btn-view-invoice').on('click', function() {
                // ទាញយកទិន្នន័យពី data attributes
                let id = $(this).data('id');
                let date = $(this).data('date');
                let customer = $(this).data('customer');
                let items = $(this).data('items');
                let total = $(this).data('total');

                // បោះទិន្នន័យចូលទៅក្នុង Modal Elements
                $('#modal-inv-id').text(id);
                $('#modal-inv-date').text(date);
                $('#modal-inv-customer').text(customer);
                $('#modal-inv-items').text(items + ' មុខ');
                $('#modal-inv-total').text(total);
            });
        });

        $(document).on('click', '.btn-print-invoice', function() {
            let id = $(this).data('id'); // ចាប់យក ID ពី Attribute

            // ហៅ Function Print របស់បង
            printInvoice(id);
        });

        function printInvoice(id) {
            let oldFrame = document.getElementById("printFrame");
            if (oldFrame) oldFrame.remove();

            let iframe = document.createElement("iframe");
            iframe.id = "printFrame";
            iframe.style.display = "none";

            // ត្រូវប្រាកដថា Route ក្នុង Controller របស់បងប្រើ ID ដើម្បីទាញទិន្នន័យ
            iframe.src = window.location.origin + "/reports/invoice/" + id;

            document.body.appendChild(iframe);
            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
        }
    </script>
@endpush
