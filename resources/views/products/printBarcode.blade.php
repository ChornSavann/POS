@extends('layout.app')

@section('content')
{{--  --}}
<style>
    /* រចនាប័ទ្ម Glass Effect សម្រាប់ Header */
    .header-glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
    }

    /* ពណ៌ Gradient សម្រាប់ Icon */
    .text-primary-gradient {
        background: linear-gradient(45deg, #1a4d7c, #007bff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 1.5rem;
    }

    /* រចនាប័ទ្មប៊ូតុងបែប Glass */
    .btn-glass-primary {
        background: rgba(0, 123, 255, 0.1);
        color: #007bff;
        border: 1px solid rgba(0, 123, 255, 0.2);
        transition: all 0.3s ease;
    }
    .btn-glass-primary:hover {
        background: #007bff;
        color: white;
    }

    .btn-glass-danger {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    .btn-glass-danger:hover {
        background: #dc3545;
        color: white;
    }

    .uppercase-tracking {
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.75rem;
    }
</style>
<style>
    .item {
        border: 1px solid #ddd;
        padding: 8px;          /* បន្ថយ Padding ជុំវិញ */
        width: fit-content;    /* ឱ្យ Frame រួមទៅតាមទំហំបាកូដ */
        margin: 0 auto;        /* ឱ្យ Frame នៅចំកណ្ដាលជួរ */
        display: flex;
        flex-direction: column;
        align-items: center;   /* ឱ្យទិន្នន័យទាំងអស់នៅចំកណ្ដាល */
        background: #fff;
    }

    .p-name {
        font-size: 11px;
        font-weight: bold;
        margin-bottom: 0px;    /* កាត់បន្ថយគម្លាតរវាងឈ្មោះ និងតម្លៃ */
    }

    .p-price {
        font-size: 10px;
        margin-bottom: 2px;
        color: #333;
    }

    svg {
        display: block;        /* បំបាត់គម្លាតសល់ខាងក្រោម SVG */
        margin: 0 auto;
    }
</style>
<div class="container-fluid ">
    <div class="d-flex align-items-center justify-content-between mb-4 header-glass p-4 rounded-4 shadow-sm border-0">
        <div>
            <h3 class="mb-1 fw-bolder text-dark">
                <span class="text-primary-gradient"><i class="bi bi-barcode me-2"></i></span>បោះពុម្ពបាកូដទំនិញ
            </h3>
            <p class="text-muted mb-0 small uppercase-tracking">Barcode Generation & Label Printing System</p>
        </div>
        <div class="action-buttons d-flex gap-3">
            <button class="btn btn-glass-danger shadow-sm" onclick="location.reload()">
                <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Form
            </button>
            <button class="btn btn-glass-primary shadow-sm" id="#">
                <i class="bi bi-printer me-2"></i>Print Labels
            </button>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <p class="text-muted small mb-3">សូមបំពេញព័ត៌មានខាងក្រោមដើម្បីបោះពុម្ព</p>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold small">Style (ចំនួនក្នុងមួយជួរ)</label>
                    <select id="printStyle" class="form-select">
                        <option value="1">1 Per Line (ធំ)</option>
                        <option value="2">2 Per Line (មធ្យម)</option>
                        <option value="3" selected>3 Per Line (តូច)</option>
                        <option value="4">4 Per Line</option>
                    </select>
                </div>
            </div>

            <div class="input-group mb-4 shadow-sm border rounded">
                <span class="input-group-text border-0 bg-white"><i class="bi bi-upc-scan text-muted"></i></span>
                <input type="text" id="productSearch" class="form-control border-0" placeholder="ស្កេនបាកូដ ឬវាយឈ្មោះទំនិញដើម្បីបន្ថែម...">
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center" id="barcodeTable">
                    <thead>
                        <tr>
                            <th width="50">№</th>
                            <th class="text-start">Product</th>
                            <th width="150">Barcode Preview</th>
                            <th width="100">Quantity</th>
                            <th width="100">Unit</th>
                            <th width="100">Price</th>
                            <th width="50"><i class="bi bi-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div id="empty-state" class="text-center py-4 border rounded mb-4 bg-light">
                <span class="text-muted small">មិនទាន់មានទំនិញក្នុងបញ្ជីនៅឡើយទេ</span>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary px-4 shadow" id="btnSubmit">
                    <i class="bi bi-printer me-2"></i> Submit & Print
                </button>
                <button type="button" class="btn btn-outline-danger px-4" onclick="location.reload()">Reset</button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden area សម្រាប់គូរ Barcode មុននឹងបោះពុម្ព --}}
<div id="print-temp-area" class="d-none"></div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
    let rowCount = 0;

    $(document).ready(function () {
        $("#productSearch").focus();

        // Autocomplete Search
        $("#productSearch").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('products.suggestions') }}",
                    type: "GET",
                    data: { term: request.term },
                    success: function (data) {
                        if (data.length === 1) {
                            addProductToTable(data[0]);
                            $("#productSearch").val("");
                            $("#productSearch").autocomplete("close");
                            return;
                        }
                        response($.map(data, function (item) {
                            return {
                                label: item.name + " (" + item.barcode + ")",
                                value: item.barcode,
                                product: item
                            };
                        }));
                    }
                });
            },
            minLength: 1,
            select: function (event, ui) {
                addProductToTable(ui.item.product);
                $(this).val("");
                return false;
            }
        });
    });

    function addProductToTable(p) {
        let existingRow = null;
        $('#barcodeTable tbody tr').each(function() {
            if($(this).data('code') === p.barcode) existingRow = $(this);
        });

        if(existingRow) {
            let qtyInput = existingRow.find('.qty-input');
            qtyInput.val(parseInt(qtyInput.val()) + 1);
            return;
        }

        $('#empty-state').addClass('d-none');
        rowCount++;

        let tr = `
            <tr id="row-${rowCount}" data-name="${p.name}" data-code="${p.barcode}" data-price="${p.price}">
                <td>${rowCount}</td>
                <td class="text-start">
                    <div class="fw-bold text-dark">${p.name}</div>
                    <div class="text-muted small">${p.barcode}</div>
                </td>
                <td><svg class="preview-bc" id="bc-${rowCount}"></svg></td>
                <td><input type="number" class="form-control form-control-sm qty-input mx-auto" value="1" min="1"></td>
                <td><span class="badge bg-light text-dark border px-2 py-1">${p.unitName}</span></td>
                <td class="fw-bold text-primary">$${parseFloat(p.price).toFixed(2)}</td>
                <td><button type="button" class="btn btn-link text-danger p-0" onclick="removeRow('row-${rowCount}')"><i class="bi bi-trash"></i></button></td>
            </tr>`;

        $('#barcodeTable tbody').append(tr);

        JsBarcode(`#bc-${rowCount}`, p.barcode, {
            format: "CODE128", width: 1, height: 30, displayValue: false
        });
    }

    function removeRow(id) {
        $(`#${id}`).remove();
        if ($('#barcodeTable tbody tr').length === 0) $('#empty-state').removeClass('d-none');
    }

    // Print Logic
    $('#btnSubmit').on('click', function() {
        const rows = $('#barcodeTable tbody tr');
        if (rows.length === 0) return alert("សូមបញ្ចូលទំនិញជាមុនសិន!");

        const cols = $('#printStyle').val();
        const printWin = window.open('', '_blank');

        let html = `<html><head><link href="https://fonts.googleapis.com/css2?family=Hanuman&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Hanuman', serif; text-align: center; margin: 0; padding: 10px; }
                .grid { display: grid; grid-template-columns: repeat(${cols}, 1fr); gap: 10px; }
                .item { border: 0.1px solid #ccc; padding: 10px; page-break-inside: avoid; margin-bottom: 5px; }
                .p-name { font-size: 11px; font-weight: bold; margin-bottom: 2px; height: 15px; overflow: hidden; }
                .p-price { font-size: 10px; margin-bottom: 5px; }
                svg { width: 100%; height: auto; max-height: 50px; }
            </style></head><body><div class="grid">`;

        rows.each(function() {
            const name = $(this).data('name');
            const code = $(this).data('code');
            const price = $(this).data('price');
            const qty = $(this).find('.qty-input').val();

            for (let i = 0; i < qty; i++) {
                html += `<div class="item">
                    <div class="p-name">${name}</div>
                    <div class="p-price">Price: USD ${parseFloat(price).toFixed(2)}</div>
                    <svg class="bc-print" data-code="${code}"></svg>
                </div>`;
            }
        });

        html += `</div><script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
            <script>window.onload = function() {
               document.querySelectorAll(".bc-print").forEach(el => {
    JsBarcode(el, el.getAttribute("data-code"), {
        format: "CODE128",
        width: 1.2,           // បន្ថយពី 1.5 មក 1.2 ដើម្បីឱ្យបាកូដខ្លីល្មមក្នុង Frame
        height: 40,          // កម្ពស់សមសួន
        displayValue: true,  // បង្ហាញលេខបាកូដខាងក្រោម
        fontSize: 12,        // ទំហំអក្សរលេខបាកូដ
        font: "monospace",   // ប្រើ Font monospaced ដើម្បីឱ្យលេខតម្រៀបគ្នាស្អាត
        margin: 5,           // បន្ថែមគម្លាតជុំវិញបាកូដបន្តិច (Padding)
        background: "#fff"   // ពណ៌ផ្ទៃក្រោយស
    });
});
                setTimeout(() => { window.print(); window.close(); }, 600);
            };<\/script></body></html>`;

        printWin.document.write(html);
        printWin.document.close();
    });
</script>
@endpush
