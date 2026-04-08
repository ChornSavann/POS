@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-4 mt-3">
                    {{-- Header - ប្រើពណ៌បៃតងសម្រាប់សញ្ញា Edit --}}
                    <div class="card-header bg-success bg-gradient text-white py-3 rounded-top-4">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> កែប្រែទំនិញ (Edit Product): {{ $product->name }}
                        </h5>
                    </div>

                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body p-4">
                            <div class="row">
                                {{-- ផ្នែកខាងឆ្វេង: ព័ត៌មានទូទៅ --}}
                                <div class="col-xl-8 border-end">
                                    <h6 class="text-success fw-bold mb-3 border-bottom pb-2">
                                        <i class="bi bi-info-square me-2"></i> ព័ត៌មានទូទៅ
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-7">
                                            <label class="form-label fw-bold">ឈ្មោះទំនិញ <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name"
                                                class="form-control form-control-lg border-success-subtle @error('name') is-invalid @enderror"
                                                value="{{ old('name', $product->name) }}" placeholder="ឈ្មោះទំនិញ">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-5">
                                            <label class="form-label fw-bold">លេខកូដ (Barcode)</label>
                                            <div class="input-group shadow-sm">
                                                <input type="text" name="barcode" id="barcode_input"
                                                    class="form-control form-control-lg border-success-subtle @error('barcode') is-invalid @enderror"
                                                    value="{{ old('barcode', $product->barcode) }}" placeholder="ស្កេនកូដ">

                                                <button type="button"
                                                    class="btn btn-success px-3 d-flex align-items-center"
                                                    id="generate_barcode" title="បង្កើតកូដថ្មី">
                                                    <i class="fa-solid fa-shuffle me-1"></i>
                                                    <span class="fw-bold small">បង្កើតកូដ</span>
                                                </button>
                                            </div>
                                            @error('barcode')
                                                <div class="text-danger small fw-bold mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">ប្រភេទទំនិញ
                                                (Category)</label>
                                            <select name="category_id" class="form-select select2 border-success-subtle">
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">ម៉ាកយីហោ (Brand)</label>
                                            <select name="brand_id" class="form-select select2 border-success-subtle">
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- ខ្នាត និងការកំណត់តម្លៃ --}}
                                        <div class="col-12 mt-4">
                                            <h6 class="text-success fw-bold mb-3 border-bottom pb-2">
                                                <i class="bi bi-layers me-2"></i> ខ្នាត និងការកំណត់តម្លៃ
                                            </h6>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small">ខ្នាតគោល (Base Unit)</label>
                                            <select id="base_unit" name="unit_id"
                                                class="form-select bg-light border-success-subtle fw-bold">
                                                <option value="">-- ជ្រើសរើស --</option>
                                                @foreach ($units as $unit)
                                                    @if (!$unit->baseunit_id)
                                                        <option value="{{ $unit->id }}"
                                                            {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                                            {{ $unit->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small">លក់ចេញ (Sale Unit)</label>
                                            <select id="sale_unit" name="sale_unit_id"
                                                class="form-select border-success-subtle">
                                                <option value="{{ $product->sale_unit_id }}">
                                                    {{ $product->saleUnit->name ?? '-- រង់ចាំខ្នាតគោល --' }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small">ទិញចូល (Purchase Unit)</label>
                                            <select id="purchase_unit" name="purchase_unit_id"
                                                class="form-select border-success-subtle">
                                                <option value="{{ $product->purchase_unit_id }}">
                                                    {{ $product->purchaseUnit->name ?? '-- រង់ចាំខ្នាតគោល --' }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small">តម្លៃដើម (Cost)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-success-subtle">$</span>
                                                <input type="number" step="0.01" name="cost" id="cost"
                                                    class="form-control fw-bold border-success-subtle"
                                                    value="{{ old('cost', $product->cost) }}" placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small">តម្លៃលក់ (Price)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success text-white border-success">$</span>
                                                <input type="number" step="0.01" name="price" id="price"
                                                    class="form-control text-success fw-bold border-success"
                                                    value="{{ old('price', $product->price) }}" placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mt-3">
                                            <label class="form-label fw-bold small">ស្តុកអាសន្ន (Alert Qty)</label>
                                            <input type="number" name="alert_qty"
                                                class="form-control border-success-subtle"
                                                value="{{ old('alert_qty', $product->alert_qty) }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- ផ្នែកខាងស្តាំ: រូបភាព និងស្ថានភាព --}}
                                <div class="col-xl-4 ps-xl-4 mt-xl-0 mt-4">
                                    <h6 class="text-success fw-bold mb-3 border-bottom pb-2">
                                        <i class="bi bi-image me-2"></i> រូបភាព និងស្ថានភាព
                                    </h6>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small">ស្ថានភាពទំនិញ</label>
                                        <div class="p-3 border rounded bg-light">
                                            <div class="form-check form-switch">
                                                {{-- បន្ថែម Hidden Input នេះ --}}
                                                <input type="hidden" name="status" value="0">

                                                <input class="form-check-input" type="checkbox" name="status"
                                                    value="1" id="statusSwitch"
                                                    {{ $product->status ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="statusSwitch"
                                                    id="statusLabel">
                                                    {{ $product->status ? 'បើកលក់ (Active)' : 'ផ្អាកលក់ (Inactive)' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">រូបភាពទំនិញ
                                            (ទុកទទេបើមិនប្តូរ)</label>
                                        <div
                                            class="upload-zone text-center p-3 border-dashed rounded-4 bg-light position-relative">
                                            <img id="preview"
                                                src="{{ asset($product->image ? $product->image : 'assets/img/no-image.png') }}"
                                                class="img-fluid rounded mb-2"
                                                style="max-height: 200px; width: 100%; object-fit: contain;">
                                            <input type="file" name="image"
                                                class="form-control position-absolute opacity-0 top-0 start-0 w-100 h-100 cursor-pointer"
                                                onchange="previewImage(this)">
                                            <div class="text-muted small">
                                                <i class="bi bi-cloud-arrow-up fs-3"></i>
                                                <p class="mb-0">ចុចដើម្បីប្តូររូបភាព</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="card-footer bg-light p-4 rounded-bottom-4 border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.index') }}"
                                    class="btn btn-outline-secondary px-5 rounded-pill shadow-sm">
                                    <i class="bi bi-x-circle me-1"></i> បោះបង់
                                </a>
                                <button type="submit"
                                    class="btn btn-success px-5 rounded-pill shadow-lg bg-gradient text-white">
                                    <i class="bi bi-check-circle-fill me-1"></i> រក្សាទុកការកែប្រែ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-dashed {
            border: 2px dashed #dee2e6 !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .bg-success.bg-gradient {
            background: linear-gradient(45deg, #198754, #146c43) !important;
        }

        .upload-zone:hover {
            background-color: #f1f3f5 !important;
            border-color: #198754 !important;
        }

        #generate_barcode:hover i {
            transform: rotate(180deg);
            transition: 0.4s ease-in-out;
        }
    </style>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            // --- ផ្នែកទី ២: Units Control ---
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
                                if (data.length > 1) {
                                    saleSelect.selectedIndex = 1;
                                    purchaseSelect.selectedIndex = 1;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                });
            }

            // --- ផ្នែកទី ៣: Barcode Generator ---
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

        document.getElementById('statusSwitch').addEventListener('change', function() {
            const label = document.getElementById('statusLabel');
            if (this.checked) {
                label.textContent = 'បើកលក់ (Active)';
                label.classList.add('text-success');
                label.classList.remove('text-danger');
            } else {
                label.textContent = 'ផ្អាកលក់ (Inactive)';
                label.classList.add('text-danger');
                label.classList.remove('text-success');
            }
        });
    </script>
@endsection
