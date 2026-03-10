@extends('layout.app') {{-- ប្រើ Layout របស់បង --}}

@section('content')
<div class="content-wrapper bg-light">
    {{-- Header Section --}}
    <div class="purchase-header d-flex justify-content-between align-items-center p-4 bg-white shadow-sm rounded-3 mb-2 mt-1">
        <div class="d-flex align-items-center">
            <div class="icon-box bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                <i class="bi bi-cart-plus-fill text-primary fs-4"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-bold text-dark">បង្កើតការទិញថ្មី (Add Purchase)</h4>
                <p class="text-muted mb-0 small">បញ្ចូលទិន្នន័យទំនិញចូលស្តុករបស់អ្នក។</p>
            </div>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent">
                <li class="breadcrumb-item small"><a href="{{ route('purchases.index') }}" class="text-decoration-none text-muted">Purchases</a></li>
                <li class="breadcrumb-item small active fw-bold text-primary">Add New</li>
            </ol>
        </nav>
    </div>

    <section class="content p-1">
        <div class="form-section shadow-sm border-0">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">កាលបរិច្ឆេទ</label>
                    <input type="date" id="PurchaseDate" class="form-control" value="{{ date('Y-m-d') }}" />
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">លេខវិក្កយបត្រ (Reference)</label>
                    <input type="text" id="ReferenceNo" class="form-control" placeholder="PO-{{ time() }}" />
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">ស្ថានភាព (Status)</label>
                    <select id="Status" class="form-select">
                        <option value="Received">Received</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4 border-bottom pb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">អ្នកផ្គត់ផ្គង់ (Supplier) *</label>
                    <select id="SupplierId" class="form-select select2">
                        <option value="">ជ្រើសរើសអ្នកផ្គត់ផ្គង់</option>
                        @foreach ($suppliers as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">ឃ្លាំង (Store) *</label>
                    <select id="Storeid" class="form-select select2">
                        @foreach ($stores as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">អ្នកលក់ (Seller) *</label>
                    <select id="Sellerid" class="form-select select2">
                        @foreach ($sellers as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Search Section --}}
            <div class="search-section mb-4">
                <div class="input-group search-group-modern shadow-sm border">
                    <span class="input-group-text bg-white border-0 ">
                        <i class="bi bi-upc-scan text-primary fs-5"></i>
                    </span>
                    <select id="productSearch" class="form-select select2-custom border-0">
                        <option value="">ស្វែងរកទំនិញ ឬស្កេនបាកូដនៅទីនេះ...</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}"
                                    data-name="{{ $p->name }}"
                                    data-cost="{{ $p->cost }}"
                                    data-price="{{ $p->price }}"
                                    data-unit="{{ $p->unit->name ?? 'unit' }}">
                                {{ $p->name }} ({{ $p->barcode }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark small">
                        <tr>
                            <th width="5%">Nº</th>
                            <th>Product</th>
                            <th width="12%">Qty</th>
                            <th width="10%">Unit</th>
                            <th width="12%">Cost ($)</th>
                            <th width="12%">Price ($)</th>
                            <th width="12%">Subtotal</th>
                            <th width="5%"><i class="bi bi-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody id="purchaseItems">
                        <tr id="emptyRow">
                            <td colspan="8" class="text-center py-4 text-muted">សូមស្វែងរក និងជ្រើសរើសទំនិញខាងលើ</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td colspan="6" class="text-end">សរុប (Grand Total) :</td>
                            <td class="text-end text-primary" id="grandTotal">0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label fw-bold small">សម្គាល់ (Note)</label>
                    <textarea id="Note" class="form-control" rows="3" placeholder="បញ្ចូលព័ត៌មានបន្ថែម..."></textarea>
                </div>
                <div class="col-md-6 d-flex align-items-end justify-content-end gap-2">
                    <button type="button" onclick="location.reload()" class="btn btn-light border px-4">Reset</button>
                    <button type="button" onclick="submitPurchase()" class="btn btn-primary px-5 shadow-sm">Save Purchase</button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
<style>

    .content-wrapper {
        background: linear-gradient(135deg, #f4f7fb, #eef2f7);
        min-height: 100vh;
    }


    .purchase-header {
        border-left: 5px solid #0d6efd;
        transition: all 0.3s ease;
    }

    .purchase-header:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .icon-box {
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .form-section {
        background: #ffffff;
        padding: 30px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        animation: fadeInUp 0.4s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
    }


    .select2-container--default .select2-selection--single {
        border-radius: 10px !important;
        height: 38px;
        border: 1px solid #dee2e6;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #0d6efd !important;
    }


    .search-group-modern {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .search-group-modern:focus-within {
        border: 1px solid #0d6efd;
        box-shadow: 0 0 12px rgba(13,110,253,0.15);
    }


    .table {
        border-radius: 12px;
        overflow: hidden;
    }

    .table thead {
        background: linear-gradient(45deg, #0d6efd, #3d8bfd);
        color: #fff;
    }

    .table tbody tr:hover {
        background-color: #f2f7ff;
        transition: 0.2s;
    }

    .table td,
    .table th {
        vertical-align: middle !important;
    }


    #grandTotal {
        font-size: 18px;
        font-weight: bold;
    }

    .btn-primary {
        border-radius: 10px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(13,110,253,0.3);
    }

    .btn-light {
        border-radius: 10px;
    }

    /* ===============================
    NOTE TEXTAREA
    ================================ */
    textarea.form-control {
        border-radius: 12px;
    }

    /* ===============================
    RESPONSIVE IMPROVEMENT
    ================================ */
    @media (max-width: 768px) {
        .form-section {
            padding: 20px;
        }

        .purchase-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 15px;
        }
    }
</style>
<style>

    .search-group-modern {
        height: 48px; /* កម្ពស់ស្តង់ដារដែលមើលទៅស្អាត */
        border: 1px solid #dee2e6;
        border-radius: 12px !important; /* រាងមូលជាងមុនបន្តិចតាមរូបភាព */
        overflow: hidden;
        display: flex !important;
        align-items: stretch !important;
        background: #fff;
        transition: all 0.2s ease-in-out;
    }

    /* តម្រឹម Icon ឱ្យនៅចំកណ្ដាល */
    .search-group-modern .input-group-text {
        background-color: transparent !important;
        border: none !important;
        padding-left: 18px;
        padding-right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* កែសម្រួល Select2 Container ឱ្យពេញកម្ពស់ */
    .search-group-modern .select2-container--bootstrap-5 {
        flex-grow: 1;
        height: 100% !important;
    }

    /* កែសម្រួលផ្នែក Selection របស់ Select2 ឱ្យចំកណ្ដាល Vertical */
    .search-group-modern .select2-container--bootstrap-5 .select2-selection {
        height: 100% !important;
        border: none !important;
        background: transparent !important;
        display: flex !important;
        align-items: center !important; /* នេះជាចំណុចសំខាន់ដែលធ្វើឱ្យអក្សរឡើងមកលើវិញ */
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        box-shadow: none !important;
    }

    /* កែសម្រួលអក្សរ Placeholder និងអក្សរដែលជ្រើសរើសរួច */
    .search-group-modern .select2-selection__rendered {
        line-height: normal !important; /* បំបាត់ Line-height ដើមដែលរុញអក្សរចុះក្រោម */
        padding-left: 8px !important;
        margin: 0 !important;
        color: #6c757d !important;
        display: flex !important;
        align-items: center !important;
    }

    /* ពេល Focus លើប្រអប់ Search */
    .search-group-modern:focus-within {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
    }
</style>
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let items = [];
    $(document).ready(function() {
        $('#productSearch').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "ស្វែងរកទំនិញ ឬស្កេនបាកូដនៅទីនេះ...",
            allowClear: true,
            // បើបងចង់ឱ្យវាលោត Dropdown មកស្អាត
            dropdownParent: $('.search-section')
        });
    });
    // បន្ថែម CSRF Token សម្រាប់ AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

   $(document).ready(function() {
    $('#productSearch').select2({ theme: 'bootstrap-5' }).on('change', function() {
        let selected = $(this).find(':selected');
        let id = selected.val();
        if (!id) return;

        // បម្លែង id ទៅជា Integer ដើម្បីឱ្យ Validation ស្រួលឆែក
        let productId = parseInt(id);

        let existing = items.find(x => x.productId == productId);
        if (existing) {
            existing.qty++;
        } else {
            items.push({
                productId: productId, // ប្រើតម្លៃជា Integer
                name: selected.data('name'),
                qty: 1,
                unitName: selected.data('unit'),
                unitCost: parseFloat(selected.data('cost')) || 0,
                unitPrice: parseFloat(selected.data('price')) || 0,
                discount: 0 // បន្ថែម discount: 0 ដើម្បីកុំឱ្យបាត់ Field ពេល Save
            });
        }
        $(this).val(null).trigger('change');
        renderTable();
    });
});

    function renderTable() {
        let html = '';
        let total = 0;
        items.forEach((item, index) => {
            let subtotal = item.unitCost * item.qty;
            total += subtotal;
            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td class="fw-bold">${item.name}</td>
                    <td><input type="number" class="form-control form-control-sm text-center" value="${item.qty}" onchange="updateItem(${index}, 'qty', this.value)"></td>
                    <td class="text-center"><span class="badge bg-secondary text-white">${item.unitName}</span></td>
                    <td><input type="number" class="form-control form-control-sm text-end" value="${item.unitCost}" onchange="updateItem(${index}, 'unitCost', this.value)"></td>
                    <td><input type="number" class="form-control form-control-sm text-end" value="${item.unitPrice}" onchange="updateItem(${index}, 'unitPrice', this.value)"></td>
                    <td class="text-end fw-bold text-dark">${subtotal.toFixed(2)}</td>
                    <td class="text-center text-danger" style="cursor:pointer" onclick="removeItem(${index})"><i class="bi bi-trash"></i></td>
                </tr>`;
        });
        $('#purchaseItems').html(html || '<tr><td colspan="8" class="text-center py-4">សូមបន្ថែមទំនិញ...</td></tr>');
        $('#grandTotal').text(total.toFixed(2));
    }

    function updateItem(i, f, v) {
        items[i][f] = parseFloat(v) || 0;
        renderTable();
    }

    function removeItem(i) {
        items.splice(i, 1);
        renderTable();
    }


    function submitPurchase() {
        if (items.length === 0) {
            Swal.fire('Warning', 'សូមបន្ថែមទំនិញយ៉ាងហោចណាស់មួយ!', 'warning');
            return;
        }

        // Prepare purchase data
        let purchaseData = {
            reference_no:  $('#ReferenceNo').val(),
            purchase_date: $('#PurchaseDate').val(),
            supplier_id:   $('#SupplierId').val(),
            store_id:      $('#Storeid').val(),
            seller_id:     $('#Sellerid').val(),
            status:        $('#Status').val(),
            note:          $('#Note').val() || null,
            grand_total:   parseFloat($('#grandTotal').text().replace(/,/g, '')) || 0,
            items:         items
        };

        // AJAX call to Laravel
        $.ajax({
            url: "{{ route('purchases.store') }}",
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(purchaseData),
            success: function(res) {
                if (res.success) {
                    Swal.fire('ជោគជ័យ', 'វិក្កយបត្រត្រូវបានរក្សាទុក!', 'success')
                        .then(() => window.location.href = "{{ route('purchases.index') }}");
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '<br>';
                    });
                    Swal.fire('Validation Error', errorMsg, 'error');
                } else {
                    // Server error
                    console.error(xhr.responseText);
                    Swal.fire('Server Error', 'មានបញ្ហាបច្ចេកទេសក្នុង Server (500)។ សូមឆែកមើល Laravel Log!', 'error');
                }
            }
        });
    }
</script>
@endpush
