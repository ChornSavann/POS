@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4">
                {{-- Header --}}
                <div class="card-header bg-primary bg-gradient text-white py-3 rounded-top-4">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-plus-circle-dotted me-2"></i> បន្ថែមទំនិញថ្មី (Add New Product)
                    </h5>
                </div>

                <form action="{{ route('products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4">
                        <div class="row">
                            {{-- ផ្នែកខាងឆ្វេង: ព័ត៌មានទូទៅ --}}
                            <div class="col-xl-8 border-end">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                    <i class="bi bi-info-square me-2"></i> ព័ត៌មានទូទៅ
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label fw-bold">ឈ្មោះទំនិញ <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control form-control-lg border-primary-subtle @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="ឧ. កូកាកូឡា">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label fw-bold">លេខកូដ (Barcode)</label>
                                        <div class="input-group shadow-sm">
                                            {{-- Input Field --}}
                                            <input type="text"
                                                name="barcode"
                                                id="barcode_input"
                                                class="form-control form-control-lg border-primary-subtle @error('barcode') is-invalid @enderror"
                                                value="{{ old('barcode') }}"
                                                placeholder="ស្កេនកូដ">

                                            {{-- ប៊ូតុង Generate នៅខាងស្តាំដៃ --}}
                                            <button type="button"
                                                class="btn btn-primary px-3 d-flex align-items-center justify-content-center"
                                                id="generate_barcode"
                                                title="បង្កើតលេខកូដស្វ័យប្រវត្តិ">
                                                <i class="fa-solid fa-shuffle me-1"></i>
                                                <span class="fw-bold small"></span>
                                            </button>
                                        </div>
                                        @error('barcode') <div class="text-danger small fw-bold mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted">ប្រភេទទំនិញ (Category)</label>
                                        <select name="category_id" class="form-select select2 border-primary-subtle">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted">ម៉ាកយីហោ (Brand)</label>
                                        <select name="brand_id" class="form-select select2 border-primary-subtle">
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                            <i class="bi bi-layers me-2"></i> ខ្នាត និងការកំណត់តម្លៃ
                                        </h6>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">ខ្នាតគោល (Base Unit)</label>
                                        <select id="base_unit" name="unit_id" class="form-select bg-light border-primary-subtle fw-bold">
                                            <option value="">-- ជ្រើសរើស --</option>
                                            @foreach($units as $unit)
                                                @if(!$unit->baseunit_id)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">លក់ចេញ (Sale Unit)</label>
                                        <select id="sale_unit" name="sale_unit_id" class="form-select border-primary-subtle">
                                            <option value="">-- រង់ចាំខ្នាតគោល --</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">ទិញចូល (Purchase Unit)</label>
                                        <select id="purchase_unit" name="purchase_unit_id" class="form-select border-primary-subtle">
                                            <option value="">-- រង់ចាំខ្នាតគោល --</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">តម្លៃដើម (Cost)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">$</span>
                                            <input type="number" step="0.01" name="cost" id="cost" class="form-control fw-bold border-primary-subtle" placeholder="0.00">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">តម្លៃលក់ (Price)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white border-success">$</span>
                                            <input type="number" step="0.01" name="price" id="price" class="form-control text-success fw-bold border-success" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small text-primary">ស្ថានភាពចំណេញ/ខាត</label>
                                        <div id="profit-display" class="p-2 border rounded bg-light d-flex align-items-center justify-content-between" style="height: 38px;">
                                            <span id="profit_amount" class="fw-bold text-muted">$0.00</span>
                                            <span id="profit_margin" class="badge bg-secondary">0%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">ស្តុកអាសន្ន (Alert Qty)</label>
                                        <input type="number" name="alert_qty" class="form-control border-primary-subtle" value="10">
                                    </div>
                                </div>
                            </div>

                            {{-- ផ្នែកខាងស្តាំ: រូបភាព និងស្ថានភាព --}}
                            <div class="col-xl-4 ps-xl-4 mt-xl-0 mt-4">
                                <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                    <i class="bi bi-image me-2"></i> រូបភាព និងស្ថានភាព
                                </h6>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small">ស្ថានភាពទំនិញ</label>
                                    <div class="p-3 border rounded bg-light">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch" checked>
                                            <label class="form-check-label fw-bold" for="statusSwitch">បើកលក់ (Active)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small">រូបភាពទំនិញ</label>
                                    <div class="upload-zone text-center p-4 border-dashed rounded-4 bg-light position-relative shadow-sm">
                                        <img id="preview" src="{{ asset('assets/img/no-image.png') }}" class="img-fluid rounded mb-3" style="max-height: 200px; width: 100%; object-fit: contain;">
                                        <input type="file" name="image" class="form-control position-absolute opacity-0 top-0 start-0 w-100 h-100 cursor-pointer" onchange="previewImage(this)">
                                        <div class="text-muted">
                                            <i class="bi bi-cloud-arrow-up fs-2"></i>
                                            <p class="mb-0 small">ចុចទីនេះដើម្បីបញ្ចូលរូបភាព</p>
                                        </div>
                                    </div>
                                    @error('image') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Buttons --}}
                    <div class="card-footer bg-light p-4 rounded-bottom-4 border-top-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-5 rounded-pill shadow-sm">
                                <i class="bi bi-x-circle me-1"></i> បោះបង់
                            </a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-lg bg-gradient">
                                <i class="bi bi-save-fill me-1"></i> រក្សាទុកទំនិញ
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed { border: 2px dashed #dee2e6 !important; }
    .cursor-pointer { cursor: pointer; }
    .bg-primary.bg-gradient { background: linear-gradient(45deg, #007bff, #0056b3) !important; }
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.1);
    }
    .upload-zone:hover {
        background-color: #e9ecef !important;
        border-color: #007bff !important;
    }
    /* បន្ថែមស្ទីលសម្រាប់ប៊ូតុង Shuffle */
    #generate_barcode:hover i {
        transform: rotate(180deg);
        transition: 0.4s ease-in-out;
    }
    
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // ១. មុខងារ Preview រូបភាព (Vanilla JS)

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ២. ការងារទូទៅក្នុងទំព័រ (Vanilla JS + jQuery)
    document.addEventListener('DOMContentLoaded', function() {

        // --- ផ្នែកទី ១: Units Control (Fetch Sub-Units) ---
        const baseUnitSelect = document.getElementById('base_unit');
        const saleSelect = document.getElementById('sale_unit');
        const purchaseSelect = document.getElementById('purchase_unit');

        if (baseUnitSelect) {
            baseUnitSelect.addEventListener('change', function() {
                let baseUnitId = this.value;
                if (baseUnitId) {
                    saleSelect.innerHTML = '<option>កំពុងផ្ទុក...</option>';
                    purchaseSelect.innerHTML = '<option>កំពុងផ្ទុក...</option>';

                    fetch("{{ url('products/get-sub-units') }}/" + baseUnitId)
                        .then(response => response.json())
                        .then(data => {
                            saleSelect.innerHTML = '';
                            purchaseSelect.innerHTML = '';
                            data.forEach((unit) => {
                                let option = document.createElement('option');
                                option.value = unit.id;
                                option.textContent = unit.name;
                                saleSelect.appendChild(option.cloneNode(true));
                                purchaseSelect.appendChild(option);
                            });
                            if(data.length > 1) {
                                saleSelect.selectedIndex = 1;
                                purchaseSelect.selectedIndex = 1;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        }

        // --- ផ្នែកទី ២: Barcode Generator ---
        const btnGenerate = document.getElementById('generate_barcode');
        const barcodeInput = document.getElementById('barcode_input');

        function generateRandomBarcode() {
            const prefix = 'P-';
            const randomNumber = Math.floor(100000000 + Math.random() * 900000000);
            return prefix + randomNumber;
        }

        if (btnGenerate && barcodeInput) {
            btnGenerate.addEventListener('click', function() {
                barcodeInput.value = generateRandomBarcode();
                barcodeInput.style.backgroundColor = '#e8f0fe';
                setTimeout(() => barcodeInput.style.backgroundColor = '', 500);
            });
        }
    });

    // ៣. ផ្នែកគណនាចំណេញ និង Validation (jQuery)
    $(document).ready(function() {
        // --- មុខងារគណនាចំណេញ Real-time ---
        function updateProfitDisplay() {
            let cost = parseFloat($('#cost').val()) || 0;
            let price = parseFloat($('#price').val()) || 0;
            let profit = price - cost;
            let margin = cost > 0 ? (profit / cost) * 100 : 0;

            // បង្ហាញទឹកប្រាក់ចំណេញ
            $('#profit_amount').text('$' + profit.toFixed(2));

            // ប្តូរពណ៌ និង Badge តាមស្ថានភាព
            if (price === 0 || cost === 0) {
                $('#profit_amount').addClass('text-muted').removeClass('text-success text-danger');
                $('#profit_margin').addClass('bg-secondary').removeClass('bg-success bg-danger').text('0%');
            } else if (profit < 0) {
                $('#profit_amount').addClass('text-danger').removeClass('text-success text-muted');
                $('#profit_margin').addClass('bg-danger').removeClass('bg-success bg-secondary').text(margin.toFixed(1) + '% (ខាត)');
                $('#price').addClass('is-invalid');
            } else {
                $('#profit_amount').addClass('text-success').removeClass('text-danger text-muted');
                $('#profit_margin').addClass('bg-success').removeClass('bg-danger bg-secondary').text(margin.toFixed(1) + '%');
                $('#price').removeClass('is-invalid');
            }
        }
        // ហៅឱ្យគណនារាល់ពេលវាយលេខ
        $('#cost, #price').on('input', updateProfitDisplay);
        // ហៅឱ្យគណនាម្តងពេល Page Load (សម្រាប់ករណី Edit)
        updateProfitDisplay();
        // --- មុខងារស្ទាក់ចាប់មុននឹងរក្សាទុក (Form Submit) ---
        $('#productForm').on('submit', function(e) {
            let cost = parseFloat($('#cost').val()) || 0;
            let price = parseFloat($('#price').val()) || 0;
            let form = this;

            if (price < cost) {
                e.preventDefault(); // ឈប់ Submit សិន

                Swal.fire({
                    title: 'បញ្ជាក់តម្លៃលក់!',
                    html: `តម្លៃលក់ (<b>$${price.toFixed(2)}</b>) ទាបជាងតម្លៃដើម (<b>$${cost.toFixed(2)}</b>)។<br>តើអ្នកនៅតែចង់បន្តរក្សាទុកមែនទេ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'បាទ/ចាស រក្សាទុក!',
                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // បើចុចយល់ព្រម ទើបឱ្យវាទៅមុខទៀត
                    }
                });
            }
        });
    });
</script>
@endsection
