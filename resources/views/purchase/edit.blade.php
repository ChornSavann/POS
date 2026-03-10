@extends('layout.app')

@section('content')
<div class="content-wrapper bg-light">
    @method('PUT')

    {{-- Header Section --}}
    <div class="purchase-header d-flex justify-content-between align-items-center p-4 bg-white shadow-sm rounded-3 mb-2 mt-1">
        <div class="d-flex align-items-center">
            <div class="icon-box bg-warning bg-opacity-10 p-2 rounded-3 me-3">
                <i class="bi bi-pencil-square text-warning fs-4"></i>
            </div>
            <div>
                <h4 class="mb-0 fw-bold text-dark">កែប្រែការទិញ (Edit Purchase)</h4>
                <p class="text-muted mb-0 small">កែប្រែទិន្នន័យវិក្កយបត្រលេខ៖ <strong>{{ $purchase->reference_no }}</strong></p>
            </div>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent">
                <li class="breadcrumb-item small"><a href="{{ route('purchases.index') }}" class="text-decoration-none text-muted">Purchases</a></li>
                <li class="breadcrumb-item small active fw-bold text-primary">Edit</li>
            </ol>
        </nav>
    </div>

    <section class="content p-1">
        <div class="form-section shadow-sm border-0 bg-white p-4 rounded-3">
            {{-- Top Inputs --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">កាលបរិច្ឆេទ</label>
                    <input type="date" id="PurchaseDate" class="form-control" value="{{ $purchase->purchase_date }}" />
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">លេខវិក្កយបត្រ (Reference)</label>
                    <input type="text" id="ReferenceNo" class="form-control bg-light" value="{{ $purchase->reference_no }}" readonly />
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">ស្ថានភាព (Status)</label>
                    <select id="Status" class="form-select">
                        <option value="Received" {{ $purchase->status == 'Received' ? 'selected' : '' }}>Received</option>
                        <option value="Pending" {{ $purchase->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Ordered" {{ $purchase->status == 'Ordered' ? 'selected' : '' }}>Ordered</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4 border-bottom pb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">អ្នកផ្គត់ផ្គង់ (Supplier) *</label>
                    <select id="SupplierId" class="form-select select2">
                        @foreach ($suppliers as $item)
                            <option value="{{ $item->id }}" {{ $purchase->supplier_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">ឃ្លាំង (Store) *</label>
                    <select id="Storeid" class="form-select select2">
                        @foreach ($stores as $item)
                            <option value="{{ $item->id }}" {{ $purchase->store_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">អ្នកលក់ (Seller) *</label>
                    <select id="Sellerid" class="form-select select2">
                        @foreach ($sellers as $item)
                            <option value="{{ $item->id }}" {{ $purchase->seller_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Product Search --}}
            <div class="search-section-v4 mb-4">
                <div class="input-group search-group-modern shadow-sm border rounded-3">
                    <span class="input-group-text bg-white border-0 ps-3">
                        <i class="bi bi-upc-scan text-primary fs-5"></i>
                    </span>
                    <select id="productSearch" class="form-select select2 border-0">
                        <option value="">ស្វែងរកទំនិញថ្មីដើម្បីបន្ថែម...</option>
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

            {{-- Items Table --}}
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
                        {{-- JavaScript will render rows here --}}
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
                    <textarea id="Note" class="form-control" rows="3">{{ $purchase->note }}</textarea>
                </div>
                <div class="col-md-6 d-flex align-items-end justify-content-end gap-2">
                    <a href="{{ route('purchases.index') }}" class="btn btn-light border px-4">បោះបង់</a>
                    <button type="button" onclick="submitUpdate()" class="btn btn-warning px-5 shadow-sm text-white fw-bold">Update Purchase</button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
<style>

.content-wrapper {
    background: linear-gradient(135deg, #f8fafc, #eef2f7);
    min-height: 100vh;
}

.purchase-header {
    border-left: 5px solid #ffc107;
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
    border-color: #ffc107;
    box-shadow: 0 0 0 3px rgba(255,193,7,0.25);
}

/* Readonly Reference */
.form-control[readonly] {
    background: #f8f9fa !important;
    font-weight: 600;
    color: #6c757d;
}

.select2-container--default .select2-selection--single {
    border-radius: 10px !important;
    height: 38px;
    border: 1px solid #dee2e6;
}

.select2-container--default .select2-selection--single:focus {
    border-color: #ffc107 !important;
}


.search-group-modern {
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

.search-group-modern:focus-within {
    border: 1px solid #ffc107;
    box-shadow: 0 0 12px rgba(255,193,7,0.25);
}

.table {
    border-radius: 12px;
    overflow: hidden;
}

.table thead {
    background: linear-gradient(45deg, #343a40, #495057);
    color: #fff;
}

.table tbody tr:hover {
    background-color: #fff8e1;
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

.btn-warning {
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,193,7,0.4);
}

.btn-light {
    border-radius: 10px;
}

/* ===============================
   TEXTAREA
================================ */
textarea.form-control {
    border-radius: 12px;
}
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
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // ១. បោះទិន្នន័យ Items ចាស់ៗពី PHP ទៅ JavaScript Array
  // ១. រៀបចំទិន្នន័យក្នុង PHP ប្លុកដាច់ដោយឡែក ដើម្បីការពារ Parse Error
    @php
        $formattedItems = $purchase->items->map(function($item) {
            return [
                'productId' => (int)$item->product_id,
                'name'      => $item->product->name ?? 'Unknown',
                'qty'       => (float)$item->quantity,
                'unitName'  => $item->product->unit->name ?? 'unit',
                'unitCost'  => (float)$item->unit_cost,
                'unitPrice' => (float)$item->unit_price,
                'not'       =>$item->note,
            ];
        });
    @endphp

    // ២. ប្រកាស Variable items ឱ្យទៅជា JSON
    var items = @json($formattedItems);
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap-5' });
        renderTable();

        $('#productSearch').on('change', function() {
            let selected = $(this).find(':selected');
            let id = selected.val();
            if (!id) return;

            let productId = parseInt(id);
            let existing = items.find(x => x.productId == productId);

            if (existing) {
                existing.qty++;
            } else {
                items.push({
                    productId: productId,
                    name: selected.data('name'),
                    qty: 1,
                    unitName: selected.data('unit'),
                    unitCost: parseFloat(selected.data('cost')) || 0,
                    unitPrice: parseFloat(selected.data('price')) || 0,
                    note     :selected.data('note')
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
        $('#purchaseItems').html(html || '<tr><td colspan="8" class="text-center py-4 text-muted">មិនមានទំនិញក្នុងបញ្ជី...</td></tr>');
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

    function submitUpdate() {
        if (items.length === 0) {
            Swal.fire('Warning', 'សូមបន្ថែមទំនិញយ៉ាងហោចណាស់មួយ!', 'warning');
            return;
        }

        let updateData = {
            _method: 'PUT',
            reference_no:  $('#ReferenceNo').val(),
            purchase_date: $('#PurchaseDate').val(),
            supplier_id:   $('#SupplierId').val(),
            store_id:      $('#Storeid').val(),
            seller_id:     $('#Sellerid').val(),
            status:        $('#Status').val(),
            note:          $('#Note').val(),
            grand_total:   parseFloat($('#grandTotal').text()),
            items:         items
        };

        $.ajax({
           url: "/purchase/update/" + "{{ $purchase->id }}",
type: 'POST',
data: JSON.stringify(updateData),
            contentType: 'application/json',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: JSON.stringify(updateData),
            success: function(res) {
                if (res.success) {
                    Swal.fire('ជោគជ័យ', res.message, 'success')
                        .then(() => window.location.href = "{{ route('purchases.index') }}");
                }
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'មានបញ្ហាក្នុងការរក្សាទុក';
                Swal.fire('Error', msg, 'error');
            }
        });
    }
</script>
@endpush
