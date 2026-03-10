
@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('products', 'active')
@section('content')
<style>

    .pagination {
        margin-bottom: 0;
        gap: 2px;
    }

    .page-item .page-link {
        border-radius: 6px !important;
        color: #6c757d;
        border: 1px solid #dee2e6;
        padding: 6px 12px;
        font-size: 0.875rem;
    }
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .page-link:hover {
        background-color: #f8f9fa;
    }
</style>
{{-- <style>
    /* រចនាបថ Header Dark ឱ្យមានទម្ងន់ */
    .bg-dark th {
        font-family: 'Khmer OS Battambang', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-top: 15px !important;
        padding-bottom: 15px !important;
        background-color: #1e293b !important; /* ពណ៌ខ្មៅក្រមៅបែប Modern */
        color: #f8fafc !important;
    }

    /* ពណ៌ពេល Hover លើ Column ដែលអាច Sort បាន */
    .sortable:hover {
        background-color: #334155 !important; /* ឱ្យភ្លឺជាងមុនបន្តិចពេល Hover */
        transition: 0.3s;
    }

    /* Highlight Icon ពេលកំពុង Sort */
    .bi-arrow-up, .bi-arrow-down {
        opacity: 1 !important;
        color: #fbbf24 !important; /* ពណ៌លឿងទុំ (Warning) ដើម្បីឱ្យលេចលើផ្ទៃខ្មៅ */
    }

    /* ប៊ូតុង Checkbox ក្នុង Header */
    .form-check-input:checked {
        background-color: #fbbf24;
        border-color: #fbbf24;
    }
</style> --}}
<div class="content-header">
    <div class="container-fluid">
      {{-- Row: Title + Action Buttons --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center">

                    {{-- Title --}}
                    <div class="col-md-3 mb-3 mb-md-0">
                        <h4 class="m-0 fw-bold text-dark d-flex align-items-center">
                            <span class="bg-primary bg-gradient text-white rounded-3 p-2 me-2 shadow-sm">
                                <i class="bi bi-box-seam-fill"></i>
                            </span>
                            បញ្ជីទំនិញ
                        </h4>
                        <small class="text-muted">Product Management System</small>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="col-md-9 text-md-end">

                        {{-- Bulk Delete Button (Hidden by default) --}}
                        <button id="btnBulkDelete" class="btn btn-outline-danger px-3 me-2 shadow-sm" style="display: none;">
                            <i class="bi bi-trash-fill me-1"></i> លុបដែលជ្រើសរើស (<span id="selectedCount">0</span>)
                        </button>

                        {{-- Export Excel --}}
                        <button onclick="exportExcel()" class="btn btn-success px-3 me-2 shadow-sm">
                            <i class="bi bi-file-earmark-excel-fill me-1"></i> Excel
                        </button>

                        {{-- Export PDF --}}
                        <button onclick="exportPDF()" class="btn btn-danger px-3 me-2 shadow-sm">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                        </button>

                        {{-- Add Product --}}
                        <a href="{{ route('products.create') }}" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> បន្ថែមទំនិញថ្មី
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body py-3">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="row g-2">
                        {{-- ស្វែងរកតាមឈ្មោះ ឬ Barcode --}}
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                       placeholder="ស្វែងរកឈ្មោះ ឬលេខកូដ..." value="{{ request('search') }}">
                            </div>
                        </div>

                        {{-- Filter តាមប្រភេទ --}}
                        <div class="col-md-3">
                            <select name="category" class="form-select select2">
                                <option value="">គ្រប់ប្រភេទទំនិញ</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter តាមម៉ាក --}}
                        <div class="col-md-3">
                            <select name="brand" class="form-select select2">
                                <option value="">គ្រប់ម៉ាកយីហោ</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ប៊ូតុង Filter និង Reset --}}
                        <div class="col-md-2 d-flex gap-1">
                            <button type="submit" class="btn btn-info text-white w-100">
                                <i class="bi bi-filter"></i> ស្វែងរក
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-light border" title="លុបការស្វែងរក">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="productTable">
                   <thead class="bg-dark text-white shadow-sm">
                        <tr style="border-bottom: 2px solid #444;">
                            <th class="ps-4" style="width: 50px; vertical-align: middle;">
                                <div class="form-check">
                                    <input class="form-check-input border-secondary" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th class="ps-3" style="width: 80px; vertical-align: middle;">រូបភាព</th>

                            <th class="sortable" style="cursor:pointer; vertical-align: middle;">
                                ព័ត៌មានទំនិញ <i class="bi bi-arrow-down-up ms-1 small opacity-50 text-warning"></i>
                            </th>

                            <th class="sortable" style="cursor:pointer; vertical-align: middle;">
                                ប្រភេទ/ម៉ាក <i class="bi bi-arrow-down-up ms-1 small opacity-50 text-warning"></i>
                            </th>

                            <th class="sortable text-center" style="cursor:pointer; vertical-align: middle;">
                                តម្លៃដើម <i class="bi bi-arrow-down-up ms-1 small opacity-50 text-warning"></i>
                            </th>

                            <th class="sortable text-center" style="cursor:pointer; vertical-align: middle;">
                                តម្លៃលក់ <i class="bi bi-arrow-down-up ms-1 small opacity-50 text-warning"></i>
                            </th>

                            <th class="sortable text-center" style="cursor:pointer; vertical-align: middle;">
                                ស្តុកអាសន្ន <i class="bi bi-arrow-down-up ms-1 small opacity-50 text-warning"></i>
                            </th>

                            <th class="text-center" style="vertical-align: middle;">ស្ថានភាព</th>
                            <th class="text-end pe-4" style="vertical-align: middle;">សកម្មភាព</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                        <tr>
                           <td class="text-center align-middle">
                                <div class="form-check">
                                    <input class="form-check-input select-item" type="checkbox" value="{{ $product->id }}">
                                </div>
                            </td>
                            <td class="ps-3">
                                <img src="{{ asset($product->image ? $product->image : 'assets\img\no-image.png') }}"
                                     alt="Product" class="rounded border shadow-sm"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $product->name }}</div>
                                <small class="text-muted"><i class="bi bi-upc"></i> {{ $product->barcode ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill small">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                                <div class="small text-muted mt-1">{{ $product->brand->name ?? 'No Brand' }}</div>
                            </td>
                            <td class="text-center text-secondary">${{ number_format($product->cost, 2) }}</td>
                            <td class="text-center fw-bold text-success">${{ number_format($product->price, 2) }}</td>
                            <td class="text-center">
                                @php
                                    $qty = $product->stock->qty ?? 0; // handle null stock
                                    if ($qty <= 10) {
                                        $badgeClass = 'bg-danger-subtle text-danger';
                                    } elseif ($qty <= 15) {
                                        $badgeClass = 'bg-warning-subtle text-warning';
                                    } elseif ($qty <= 20) {
                                        $badgeClass = 'bg-info-subtle text-info';
                                    } else {
                                        $badgeClass = 'bg-success-subtle text-success';
                                    }
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    {{$qty}} {{ $product->unit->name ?? 'Unit' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($product->status)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> លក់</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-pause-circle"></i> ផ្អាក</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group shadow-sm">
                                 <button type="button" class="btn btn-sm btn-outline-info btn-view-product"
                                    data-name="{{ $product->name }}"
                                    data-barcode="{{ $product->barcode }}"
                                    data-category="{{ $product->category->name ?? 'N/A' }}"
                                    data-brand="{{ $product->brand->name ?? 'N/A' }}"
                                    data-qty="{{ $product->stock->qty??0   }} {{ $product->unit->name ?? 'Unit' }}"
                                    data-unit="{{ $product->unit->name ?? 'N/A' }}"
                                    data-sale_unit_name="{{ $product->saleUnit->name ?? 'មិនទាន់កំណត់' }}"
                                    data-purchase_unit_name="{{ $product->purchaseUnit->name ?? 'មិនទាន់កំណត់' }}"
                                    data-cost="{{ number_format($product->cost, 2) }}"
                                    data-price="{{ number_format($product->price, 2) }}"
                                    data-alert="{{ $product->alert_qty }}"
                                    data-status="{{ $product->status }}"
                                    data-image="{{ asset($product->image ?: 'Image/no-image.png') }}"
                                    data-edit-url="{{ route('products.edit', $product->id) }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                មិនមានទិន្នន័យទំនិញឡើយ។
                            </td>
                        </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
           {{-- Pagination --}}
        <div class="card-footer bg-white py-3 border-top">
            <div class="d-flex justify-content-between align-items-center">
                {{-- បង្ហាញព័ត៌មានចំនួនជួរ (Showing X to Y of Z) --}}
                <div class="small text-muted">
                    បង្ហាញពី <b>{{ $products->firstItem() }}</b> ដល់ <b>{{ $products->lastItem() }}</b>
                    នៃទំនិញសរុប <b>{{ $products->total() }}</b>
                </div>

                {{-- ប៊ូតុងប្តូរទំព័រ (Pagination Links) --}}
                <div class="pagination-sm">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

</div>

@include('products.show')
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ១. សម្រាប់ Auto-submit Filter (ពេលប្តូរ Select វានឹង Submit Form)
        const filters = document.querySelectorAll('.form-select');
        filters.forEach(select => {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
        // ២. ដោះស្រាយបញ្ហា Modal Backdrop Error (ប្តូរមកបើកតាម Manual វិញ)
        document.addEventListener('click', function(e) {
                const viewBtn = e.target.closest('.btn-view-product');
                if (viewBtn) {
                    const productId = viewBtn.getAttribute('data-id');
                    const modalElement = document.getElementById('viewProduct' + productId);

                    if (modalElement) {
                        // ប្រើ Bootstrap Instance ផ្ទាល់ដើម្បីចៀសវាងការជាន់គ្នាជាមួយ jQuery UI
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
                        modalInstance.show();
                    }
                }
            });

            document.addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.btn-delete');
                if (deleteBtn) {
                    e.preventDefault();
                    const form = deleteBtn.closest('.delete-form');

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'តើអ្នកពិតជាចង់លុបទំនិញនេះមែនទេ?',
                            text: "ទិន្នន័យដែលលុបហើយមិនអាចយកមកវិញបានឡើយ!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'យល់ព្រម លុបចេញ!',
                            cancelButtonText: 'បោះបង់',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    } else {
                        // ប្រសិនបើ SweetAlert Load មិនទាន់មក ប្រើ confirm ធម្មតា
                        if (confirm('តើអ្នកពិតជាចង់លុបទំនិញនេះមែនទេ?')) form.submit();
                    }
                }
            });

            $(document).on('click', '.btn-view-product', function() {
            // ១. ចាប់យកទិន្នន័យពី Button Data Attributes
            const btn = $(this);
            const data = {
                name:     btn.data('name'),
                barcode:  btn.data('barcode'),
                category: btn.data('category'),
                brand:    btn.data('brand'),
                unit:     btn.data('unit'),
                sale_unit_name: btn.data('sale_unit_name'), // កែត្រង់នេះ
                purchase_unit_name: btn.data('purchase_unit_name'), // កែត្រង់នេះ
                qty :btn.data('qty'),
                cost:     btn.data('cost'),
                price:    btn.data('price'),
                alert:    btn.data('alert'),
                status:   btn.data('status'),
                image:    btn.data('image'),
                editUrl:  btn.data('edit-url')
            };

            // ២. បាញ់បញ្ចូលអក្សរទៅតាម ID
            $('#p-name-title, #p-name').text(data.name);
            $('#p-barcode').text(data.barcode || 'N/A');
            $('#p-category').text(data.category);
            $('#p-brand').text(data.brand || 'No Brand');
            $('#p-qty').text(data.qty)||'No In stock';
            $('#p-unit').text(data.unit || 'Unit');
            // បាញ់បញ្ចូលឈ្មោះខ្នាតទៅតាម ID ដែលយើងរៀបក្នុង Modal
            $('#p-sale-unit').text(data.sale_unit_name||'មិនទាន់កំណត់');
            $('#p-purchase-unit').text(data.purchase_unit_name||'មិនទាន់កំណត់');
            $('#p-cost').text(data.cost);
            $('#p-price').text(data.price);
            $('#p-alert').text(data.alert);
            $('#p-image').attr('src', data.image);
            $('#p-edit-link').attr('href', data.editUrl);

            // ៣. បាញ់ស្ថានភាព (Badge)
            let statusBadge = data.status == 1
                ? '<span class="badge bg-success rounded-pill px-3"><i class="bi bi-check-circle me-1"></i> លក់</span>'
                : '<span class="badge bg-secondary rounded-pill px-3"><i class="bi bi-pause-circle me-1"></i> ផ្អាក</span>';
            $('#p-status').html(statusBadge);

            // ៤. បើក Modal
            const modalEl = document.getElementById('viewProductModal');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        });

    });

    function exportExcel() {
        let table = document.getElementById("productTable");
        let cloneTable = table.cloneNode(true);

        // --- ១. រៀបចំ Header (thead) ---
        $(cloneTable).find('thead tr').each(function() {
            // លុប Column Checkbox និង រូបភាព (ដក Index 0 ពីរដង)
            $(this).find('th:eq(0), th:eq(0)').remove();
            // លុប Column Action ចុងក្រោយ
            $(this).find('th:last-child').remove();

            // ថែម ល.រ នៅមុខគេបង្អស់
            $(this).prepend('<th style="background-color: #f2f2f2;">ល.រ</th>');
            // ថែម លេខកូដ នៅបន្ទាប់ពី ឈ្មោះទំនិញ (Index 1)
            $(this).find('th:eq(1)').after('<th style="background-color: #f2f2f2;">លេខកូដ</th>');
        });

        // --- ២. រៀបចំ Body (tbody) ---
        $(cloneTable).find('tbody tr').each(function(index) {
            // ក. លុប Column Checkbox និង រូបភាព ចេញមុនគេ
            $(this).find('td:eq(0), td:eq(0)').remove();

            // ខ. ទាញយក Barcode ចេញពី <small> (Column ឈ្មោះឥឡូវនៅ Index 0)
            // យើងទាញយកអក្សរចេញពី <small> ហើយសម្អាតវាបន្តិច
            let barcodeElement = $(this).find('td:eq(0) small');
            let barcodeValue = barcodeElement.text().trim();

            // គ. លុប <small> ចេញពី Column ឈ្មោះ ដើម្បីឱ្យ Excel នៅសល់តែឈ្មោះសុទ្ធ
            barcodeElement.remove();

            // ឃ. លុប Column Action ចុងក្រោយ
            $(this).find('td:last-child').remove();

            // ង. បន្ថែមលេខរៀង (ល.រ) នៅខាងមុខគេ
            $(this).prepend('<td style="text-align: center;">' + (index + 1) + '</td>');

            // ច. បញ្ចូល Column លេខកូដ ដែលទាញបានពី "ខ" ទៅក្នុង Column ទី ២ (បន្ទាប់ពីឈ្មោះ)
            $(this).find('td:eq(1)').after('<td style="text-align: center;">' + barcodeValue + '</td>');
        });

        // --- ៣. Export ទៅជា File ---
        let wb = XLSX.utils.table_to_book(cloneTable, { sheet: "Product List" });
        XLSX.writeFile(wb, "បញ្ជីទំនិញ_ស្អាត.xlsx");
    }

    function exportPDF() {
        const { jsPDF } = window.jspdf;
        let doc = new jsPDF();

        doc.text("Product List", 14, 15);

        doc.autoTable({
            html: '#productTable',
            startY: 20,
            theme: 'grid',
            styles: { fontSize: 9 }
        });

        doc.save("Product_List.pdf");
    }


    $(document).ready(function() {
    // ១. មុខងារ Select All (Checkbox នៅលើក្បាលតារាង)
    $('#selectAll').on('click', function() {
        $('.select-item').prop('checked', this.checked);
        updateBulkDeleteButton();
    });

    // ២. មុខងារពេលចុច Check លើ Item នីមួយៗក្នុងតារាង
    $(document).on('change', '.select-item', function() {
        // បើ Check ទាំងអស់ ឱ្យ Checkbox លើក្បាលតារាងគ្រីដែរ
        if ($('.select-item:checked').length == $('.select-item').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
        updateBulkDeleteButton();
    });

    // ៣. មុខងារបង្ហាញ/លាក់ប៊ូតុង និងរាប់ចំនួន
    function updateBulkDeleteButton() {
        let selectedCount = $('.select-item:checked').length;
        $('#selectedCount').text(selectedCount);

        if (selectedCount > 0) {
            $('#btnBulkDelete').fadeIn(); // បង្ហាញប៊ូតុង
        } else {
            $('#btnBulkDelete').fadeOut(); // លាក់ប៊ូតុង
        }
    }

    // ៤. មុខងារពេលចុចប៊ូតុង Bulk Delete (ប្រើ SweetAlert2)
    $('#btnBulkDelete').on('click', function() {
        let ids = [];
        $('.select-item:checked').each(function() {
            ids.push($(this).val());
        });

        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: "អ្នកកំពុងរៀបនឹងលុបទំនិញទាំង " + ids.length + " នេះ!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'បាទ/ចាស លុបទាំងអស់!',
            cancelButtonText: 'បោះបង់'
        }).then((result) => {
            if (result.isConfirmed) {
                // បញ្ជូន IDs ទៅកាន់ Controller តាមរយៈ AJAX ឬ Form
                deleteMultipleProducts(ids);
            }
        });
    });
});

    function deleteMultipleProducts(ids) {
        $.ajax({
            url: "/products/0/delete",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE',
                ids: ids
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('ជោគជ័យ!', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire('កំហុស!', 'មិនអាចលុបទិន្នន័យបានទេ', 'error');
            }
        });
    }

    // --- កូដសម្រាប់ Single Delete ---
    $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'តើអ្នកប្រាកដទេ?',
                text: "ទិន្នន័យនេះនឹងត្រូវលុបចេញពីប្រព័ន្ធ!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'បាទ/ចាស លុបវាចុះ!',
                cancelButtonText: 'បោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });


        $(document).ready(function() {
        $('.sortable').click(function() {
            var table = $(this).parents('table').eq(0);
            var rows = table.find('tbody tr').toArray().sort(comparer($(this).index()));
            this.asc = !this.asc;

            // ប្តូរ Icon ដើម្បីបង្ហាញទិសដៅ Sort
            table.find('th i').removeClass('bi-arrow-up bi-arrow-down').addClass('bi-arrow-down-up opacity-50');
            if (this.asc) {
                $(this).find('i').removeClass('bi-arrow-down-up opacity-50').addClass('bi-arrow-up opacity-100');
            } else {
                $(this).find('i').removeClass('bi-arrow-down-up opacity-50').addClass('bi-arrow-down opacity-100');
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

            // ប្រសិនបើជាតម្លៃលេខ ឬតម្លៃទឹកប្រាក់ (ដកសញ្ញា $ ឬ រៀល ចេញមុននឹងប្រៀបធៀប)
            var numA = valA.replace(/[^\d.-]/g, '');
            var numB = valB.replace(/[^\d.-]/g, '');

            if (numA !== '' && numB !== '' && !isNaN(numA) && !isNaN(numB)) {
                return numA - numB;
            }

            return valA.toString().localeCompare(valB, 'km', { sensitivity: 'base' });
        };
    }

    function getCellValue(row, index) {
        return $(row).children('td').eq(index).text().trim();
    }
});
</script>
@endpush

