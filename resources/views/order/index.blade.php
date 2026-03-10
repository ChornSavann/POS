@extends('layout.pos')
@section('content')
<style>
        #categoryFilter {
        white-space: nowrap;
        scrollbar-width: none; /* លាក់របារ Scroll សម្រាប់ Firefox */
    }
    #categoryFilter::-webkit-scrollbar {
        display: none; /* លាក់របារ Scroll សម្រាប់ Chrome/Safari */
    }
    .cat-btn {
        transition: all 0.2s ease;
        border: 1px solid #343a40;
    }
                .table-card {
                cursor: pointer;
                transition: all 0.3s ease;
                border: 2px solid transparent;
                background: #ffffff;
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                position: relative;
                overflow: hidden;
            }
            .table-card:hover {
                transform: translateY(-8px);
                border-color: #007bff;
                box-shadow: 0 10px 20px rgba(0,123,255,0.15);
            }
            .table-card img {
                width: 60px;
                /* height: auto; */
                margin-bottom: 12px;
                transition: transform 0.3s ease;
                filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            }
            .table-card:hover img {
                transform: scale(1.1);
            }
            .table-name {
                font-size: 1.1rem;
                color: #333;
                margin-bottom: 2px;
            }
            .status-badge {
                font-size: 0.75rem;
                padding: 3px 10px;
                border-radius: 20px;
                background: #f8f9fa;
                color: #6c757d;
                border: 1px solid #eee;
            }
            .table-card.busy {
                background-color: #fff5f5;
                border-color: #feb2b2;
            }
            .table-card.busy .status-badge {
                background-color: #feb2b2;
                color: #c53030;
                border-color: #feb2b2;
            }
            body {
                background: #f5f7fa;
                font-family: 'Siemreap', sans-serif;
            }
            .khmer {
                font-family: 'Siemreap', sans-serif;
            }
            .cart-container {
                height: 92vh;
                display: flex;
                flex-direction: column;
                background: white;
            }
            .cart-body {
                overflow-y: auto;
                flex-grow: 1;
            }
            .product-card {
                transition: 0.2s;
                cursor: pointer;
                border: 1px solid #eee;
            }
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            .product-card img {
                height: 120px;
                object-fit: contain;
                padding: 5px;
            }
            .table-card {
                cursor: pointer;
                transition: 0.3s;
                border: 1px solid #ddd;
                background: white;
                border-radius: 8px;
            }
            .table-card:hover {
                border-color: #007bff;
                background: #f0f7ff;
            }
            .table-card.busy {
                background-color: #f8d7da !important;
                border-color: #f5c2c7 !important;
                color: #842029;
            }
            .table-card.busy i {
                color: #dc3545 !important;
            }
            .btn-orange {
                background-color: #f39c12 !important;
                color: white !important;
                border-bottom: 4px solid #d35400;
            }
            .d-none {
                display: none !important;
            }
            /* /// */
            /* Header Bar Styling */
    #section-products .bg-white {
        border-bottom: 2px solid #f8f9fa;
        align-items: center;
    }
    #productSearch:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.1);
        border-color: #007bff;
    }

    /* Product Card Design */
    /* ===== Product Card (STANDARD POS) ===== */
    .product-card {
        border: 1px solid #eef0f3;
        border-radius: 14px;
        background: #ffffff;
        height: 100%;
        position: relative;
        cursor: pointer;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,.06);
    }

    /* Hover / Touch feedback */
    .product-card:hover {
        transform: translateY(-4px);
        border-color: #0d6efd;
        box-shadow: 0 10px 22px rgba(13,110,253,.18);
    }

    /* Image area */
    .product-card img {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: contain;
        padding: 10px;
        background: #f8f9fa;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
    }

    /* Body */
    .product-card .card-body {
        padding: 8px 6px;
        text-align: center;
    }

    /* Product name */
    .product-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: #2d3436;
        line-height: 1.25;
        height: 2.5em;      /* max 2 lines */
        overflow: hidden;
        margin-bottom: 4px;
    }

    /* Price */
    .price-tag {
        font-size: 0.95rem;
        font-weight: 700;
        color: #198754;     /* POS green */
    }

    /* Touch active */
    .product-card:active {
        transform: scale(0.97);
    }

    /* Khmer font */
    .khmer {
        font-family: 'Kantumruy Pro', 'Hanuman', sans-serif;
    }

    /* Product filter animation */
    .product-item {
        transition: opacity .2s ease, transform .2s ease;
    }

    .product-item.hide {
        opacity: 0;
        pointer-events: none;
        position: absolute;
    }

    /* Grid align */
    #section-products .row {
        align-content: flex-start;
    }
    .product-card {
            transition: all 0.2s ease-in-out;
            border-radius: 12px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
        }

        .product-card:hover img {
            transform: scale(1.1);
        }

        .khmer {
            font-family: 'Kantumruy Pro', 'Hanuman', sans-serif;
        }
        .pointer {
        cursor: pointer;
    }
    /* បន្ថែម Effect ពេល Hover ឱ្យកាន់តែស្អាត */
    .pointer:hover {
        transform: scale(1.2);
        transition: 0.2s;
    }
    /* បើមាន class busy ឱ្យចេញពណ៌ក្រហម ឬព្រាល */
   /* ពណ៌សម្រាប់តុទំនេរ */
    .table-card {
        background-color: #ffffff;
        transition: all 0.3s ease;
    }

    /* ពណ៌សម្រាប់តុមានភ្ញៀវ (Busy) */
    .table-card.busy {
        background-color: #f8d7da !important; /* ពណ៌ផ្កាឈូកខ្ចី */
        border: 1px solid #f5c2c7 !important;
    }

    .table-card.busy .fw-bold {
        color: #842029; /* ពណ៌អក្សរក្រហមក្រមៅ */
    }
</style>
<div class="container-fluid pos-layout py-2">
    <div class="row g-2 h-100">
        <div class="col-md-4 h-100">
            <div class="cart-container shadow-sm rounded bg-white border">
            <!-- Top Inputs -->
            <div class="p-2 border-bottom">
                <div class="row g-1">
                    <!-- Table Selection -->
                    <div class="col-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-table"></i>
                            </span>
                            <input type="text" id="selected-table-name"
                                   class="form-control fw-bold text-primary"
                                   readonly placeholder="សូមជ្រើសរើសតុ">
                        </div>
                        <input type="hidden" id="selected-table-id" value="0">

                    </div>
                   <div class="col-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-primary">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <select id="CustomersId" name="CustomerId" class="form-select border-primary shadow-none">
                                <option value="0">Walk-In Customer</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}" data-phone="{{ $c->phone ?? '' }}">
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- ប៊ូតុងសម្រាប់ថែមអតិថិជនថ្មី --}}
                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Barcode Scanner -->
                    <div class="col-12">
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-primary text-white">
                                <i class="bi bi-upc-scan"></i>
                            </span>
                            <input type="text" id="barcodeScanner" class="form-control form-control-sm"
                                   placeholder="បាញ់បាកូដនៅទីនេះ (Auto-add)..." autofocus autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Header -->
           <div class="bg-dark text-white p-2 khmer d-flex align-items-center small">
                <span style="width:35%"><i class="bi bi-box-seam me-1"></i>ឈ្មោះទំនិញ</span>
                <span style="width:15%" class="text-center"><i class="bi bi-tag me-1"></i>តម្លៃ</span>
                <span style="width:15%" class="text-center"><i class="bi bi-percent me-1"></i>បញ្ចុះតម្លៃ</span>
                <span style="width:20%" class="text-center"><i class="bi bi-plus-slash-minus me-1"></i>ចំនួន</span>
                <span style="width:15%" class="text-end"><i class="bi bi-cash-stack me-1"></i>សរុប</span>
            </div>

            <!-- Cart Items -->
            <div id="cart-items" class="cart-body p-2">
                <div class="text-center text-muted mt-5">សូមជ្រើសរើសតុដើម្បីចាប់ផ្តើម</div>
            </div>

            <!-- Footer -->
            <div class="cart-footer p-0 border-top bg-white">
                <div class="summary-area p-2" style="background-color: #e3f2fd; font-size: 0.85rem;">
                    <div class="row g-0">
                        <div class="col-6">
                            <div class="d-flex justify-content-between pe-2 border-end border-white">
                                <span class="khmer">ទំនិញសរុប</span>
                                <span id="item-count-display" class="fw-bold">0 ($0.00)</span>
                            </div>
                            <div class="d-flex justify-content-between pe-2 border-end border-white mt-1">
                                <span class="khmer">បញ្ចុះតម្លៃ</span>
                                <span id="total-discount-display" class="text-primary">($0.00) $0.00</span>
                            </div>
                        </div>
                        <div class="col-6 ps-2">
                            <div class="d-flex justify-content-between">
                                <span class="khmer">សរុប</span>
                                <span id="subtotal" class="fw-bold">$0.00</span>
                            </div>
                           <div class="d-flex justify-content-between mt-1">
                            <span class="khmer">
                                អាករលើតម្លៃបន្ថែម
                                <a href="javascript:void(0)" onclick="openTaxModal()" class="text-primary ms-1"><i class="bi bi-pencil-square"></i></a>
                            </span>
                            <span id="vat-display" class="fw-bold">$0.00</span>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="grand-total-area px-3 py-2 d-flex justify-content-between align-items-center" style="background-color: #dcedc8;">
                    <span class="khmer fw-bold fs-6">ប្រាក់ត្រូវបង់ <i class="bi bi-chat-dots-fill small"></i></span>
                    <h3 id="grandtotal" class="mb-0 fw-bold">$0.00</h3>
                </div>

               <div class="d-flex" style="height: 80px;">
                    <div class="d-flex flex-column" style="width: 30%;">
                        <button class="btn btn-warning rounded-0 border-white text-white flex-fill khmer py-1" id="btn-hold" style="background-color: #ff9800;">
                            <i class="fa-solid fa-hand-pause me-1"></i> ពន្យារ
                        </button>
                        <button class="btn btn-danger rounded-0 border-white text-white flex-fill khmer py-1" id="clear-cart">
                            <i class="fa-solid fa-trash-can me-1"></i> ទេ
                        </button>
                    </div>

                    <div class="d-flex flex-column" style="width: 30%;">
                        <button class="btn rounded-0 border-white text-white flex-fill khmer py-1" id="btn-order" style="background-color: #7e57c2;">
                            <i class="fa-solid fa-utensils me-1"></i> កម្ម៉ង់
                        </button>
                        <button class="btn rounded-0 border-white text-white flex-fill khmer py-1" id="btn-print" style="background-color: #1a237e;">
                            <i class="fa-solid fa-print me-1"></i> ចេញបុង
                        </button>
                    </div>

                    <div class="flex-grow-1">
                        <button class="btn btn-success rounded-0 h-100 w-100 khmer fs-5 fw-bold" id="btn-pay-trigger" style="background-color: #2e7d32;">
                            <i class="fa-solid fa-money-bill-wave me-2"></i> បង់ប្រាក់
                        </button>
                    </div>
               </div>
            </div>
        </div>
        </div>
        <div class="col-md-8 h-100">
            <div id="section-tables" class="animate-fade mt-2">

                <div class="bg-white p-3 mb-3 shadow-sm rounded-3 d-flex justify-content-between align-items-center border-start border-primary border-4">
                    <h6 class="khmer mb-0 fw-bold"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>ជ្រើសរើសតុអាហារ</h6>
                    <span class="badge bg-dark rounded-pill">តុសរុប: {{ $tables->count() }}</span>
                </div>
                <div class="row g-2">
                    @foreach($tables as $table)
                        @php $isBusy = $table->status === 'busy'; @endphp
                        <div class="col-xl-2 col-lg-3 col-md-4 col-4">
                            <div class="table-card text-center p-3 select-table-btn {{ $isBusy ? 'busy' : '' }} bg-white shadow-sm rounded-3 cursor-pointer"
                                 data-name="{{ $table->name }}" data-id="{{ $table->id }}">
                                <img src="https://cdn-icons-png.flaticon.com/512/1663/1663945.png" style="width: 40px;" class="mb-2">
                                <div class="fw-bold small">{{ $table->name }}</div>
                                <span class="badge border {{ $isBusy ? 'text-danger border-danger' : 'text-success border-success' }}" style="font-size: 9px;">
                                    {{ $isBusy ? 'មានភ្ញៀវ' : 'ទំនេរ' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="section-products" class="d-none animate-fade mt-2">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <button class="btn btn-danger btn-sm khmer px-3 shadow-sm" onclick="backToTables()">
                        <i class="bi bi-arrow-left-circle"></i> ត្រឡប់
                    </button>
                   <div class="flex-grow-1 overflow-auto d-flex gap-2" id="categoryFilter">
                        <button class="btn btn-sm btn-dark rounded-pill px-3 cat-btn active" data-category="all">
                            ទាំងអស់
                        </button>

                        @foreach($categories as $cat)
                        <button class="btn btn-sm btn-outline-dark rounded-pill px-3 cat-btn text-nowrap"
                                data-category="{{ $cat->id }}">
                            {{ $cat->name }}
                        </button>
                        @endforeach
                    </div>
                   <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" id="productSearch" class="form-control rounded-pill px-3" placeholder="ស្វែងរក...">
                    </div>
                </div>

                <div class="row g-3">
                    @foreach ($products as $p)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-4 product-item" data-category="{{ $p->category_id }}">
                            <div class="card product-card border-0 shadow-sm h-100 cursor-pointer position-relative overflow-hidden product-name"
                                onclick="addToCart({
                                        id: '{{ $p->id }}',
                                        name: '{{ $p->name }}',
                                        price: {{ $p->price }},
                                        discount: {{ $p->Discount ?? 0 }},
                                        stock: {{ $p->stock->qty ?? 0 }}
                                    })">
                               <div class="product-image-container" style="height: 110px; background-color: #f8f9fa;">
                                    @php
                                        // ឆែកមើលថាប្រើ $p ឬ $product (ក្នុង @foreach របស់អ្នកប្រើ $p)
                                        $imgSource = ($p->image) ? $p->image : 'assets/img/no-image.png';
                                    @endphp

                                    <img src="{{ asset($imgSource) }}"
                                        class="card-img-top w-100 h-100"
                                        style="object-fit: cover; transition: transform 0.3s;"
                                        alt="{{ $p->Name }}"
                                        onerror="this.onerror=null; this.src='{{ asset('assets/img/no-image.png') }}';">
                                </div>

                                <div class="card-body p-2 text-center border-top">
                                    <p class="khmer small mb-1 text-truncate fw-bold text-dark">{{ $p->name }}</p>

                                    <div class="d-flex flex-column align-items-center">
                                        @if(isset($p->Discount) && $p->Discount > 0)
                                            <small class="text-muted text-decoration-line-through" style="font-size: 0.7rem;">
                                                ${{ number_format($p->price, 2) }}
                                            </small>
                                            <span class="text-danger fw-bold">
                                                ${{ number_format($p->price - $p->Discount, 2) }}
                                            </span>
                                        @else
                                            <span class="text-primary fw-bold">
                                                ${{ number_format($p->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-header bg-primary text-white rounded-top-4 py-2 px-3">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-credit-card me-2"></i> ការទូទាត់ប្រាក់
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body bg-light p-3">
                <div class="row g-3">

                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm mb-2 rounded-3">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small fw-semibold text-muted">សរុប ($)</span>
                                    <h4 id="modal-subtotal" class="text-primary fw-bold mb-0">$0.00</h4>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="small fw-semibold text-muted">សរុប (៛)</span>
                                    <h5 id="total-riel" class="text-success fw-bold mb-0">0 ៛</h5>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">ទទួលលុយដុល្លារ</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" id="cash-dollar" class="form-control text-end fw-bold" value="0">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">វិធីបង់ប្រាក់</label>
                            <select id="paymentMethod" class="form-select form-select-sm shadow-sm border-primary-subtle">
                                <option value="CASH">💵 សាច់ប្រាក់ (Cash)</option>

                                {{-- Loop Banks --}}
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">
                                        🏦 {{ $bank->bank_name }} ({{ $bank->account_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="small fw-bold mb-1">បញ្ចុះ (%)</label>
                                <input type="number" id="discountRate" class="form-control form-control-sm text-end" readonly>
                            </div>
                            <div class="col-6">
                                <label class="small fw-bold mb-1 text-danger">បញ្ចុះ ($)</label>
                                <input type="text" id="discountAmount" class="form-control form-control-sm text-end text-danger" readonly>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">ទទួលលុយរៀល</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">៛</span>
                                <input type="number" id="cash-riel" class="form-control text-end fw-bold" value="0">
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between small">
                            <span class="fw-bold text-danger">លុយអាប់:</span>
                            <span id="balance-dollar" class="fw-bold text-danger">$0.00</span>
                        </div>
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" id="allowCredit">
                            <label class="form-check-label extra-small fw-bold text-warning">អនុញ្ញាតជំពាក់</label>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <span class="fw-bold text-warning">ប្រាក់ជំពាក់ ($) :</span>
                            <h4 id="debt-amount" class="text-warning fw-bold mb-0">$0.00</h4>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="row g-1">
                            @foreach ([1, 5, 10, 20, 50, 100] as $val)
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary w-100 py-2 fw-bold small" onclick="addCash({{ $val }})">
                                        ${{ $val }}
                                    </button>
                                </div>
                            @endforeach
                            <div class="col-12 mt-1">
                                <button type="button" class="btn btn-outline-danger w-100 py-1 small" onclick="clearCash()">
                                    <i class="bi bi-trash small"></i> សម្អាត
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 p-3 pt-0">
                <button type="button" class="btn btn-success w-100 py-2 fw-bold rounded-3" id="confirm-payment">
                    ✔️ បង់ប្រាក់ & បោះពុម្ព
                </button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="taxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header py-3 bg-indigo text-white rounded-top-4" style="background-color: #6610f2;">
                <h6 class="modal-title khmer fw-bold mb-0">
                    <i class="bi bi-percent me-2"></i> កំណត់អត្រាអាករ (Tax)
                </h6>
                <button type="button" class="btn-close btn-close-white small" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <label class="khmer fw-bold text-muted mb-2">បញ្ចូលអត្រាភាគរយ</label>
                    <div class="input-group input-group-lg">
                        <input type="number" id="tax-rate-input"
                               class="form-control text-center fw-bold border-2 border-primary shadow-none"
                               value="0" min="0" max="100"
                               onfocus="this.select()">
                        <span class="input-group-text bg-primary text-white border-primary">%</span>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary w-100 fw-bold rounded-3" onclick="setQuickTax(0)">0%</button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary w-100 fw-bold rounded-3" onclick="setQuickTax(5)">5%</button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary w-100 fw-bold rounded-3" onclick="setQuickTax(10)">10%</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-3 pt-0">
                <button type="button" class="btn btn-light w-100 khmer py-2 rounded-3 text-muted" data-bs-dismiss="modal">បោះបង់</button>
                <button type="button" class="btn btn-primary w-100 khmer py-2 rounded-3 fw-bold" onclick="saveTaxRate()">
                    <i class="bi bi-check-circle me-1"></i> រក្សាទុក
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    /* កំណត់ទំហំអក្សរតូចៗសម្រាប់ label */
    .extra-small {
        font-size: 0.75rem;
    }

    /* សម្រួលកម្ពស់ Input កុំឱ្យខ្ពស់ពេក */
    #checkoutModal .form-control-sm,
    #checkoutModal .form-select-sm,
    #checkoutModal .input-group-text {
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
    }

    /* ប៊ូតុងលុយដុល្លារ */
    #checkoutModal .btn-outline-primary {
        font-size: 0.8rem;
        border-radius: 8px;
    }
    /* ធ្វើឱ្យ Input មើលទៅធំ និងច្បាស់ */
    #checkoutModal .form-control-lg {
        border: 2px solid #eee;
        font-size: 1.25rem;
    }
    #checkoutModal .form-control-lg:focus {
        border-color: #3742fa;
        box-shadow: none;
    }

    /* លៃពណ៌ Switch សម្រាប់ជំពាក់ */
    .form-check-input:checked {
        background-color: #ff9f43 !important;
        border-color: #ff9f43 !important;
    }

    /* ធ្វើឱ្យ Badge ស្ថានភាពមើលឃើញច្បាស់ */
    #payment-status {
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    /* បើចង់ឱ្យ Modal រាងរួមតូចជាង Standard */
    @media (min-width: 576px) {
        #checkoutModal .modal-dialog {
            max-width: 500px; /* កំណត់ឱ្យនៅចន្លោះ MD និង LG */
        }
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@endsection
@push('scripts')
<script>
    const EXCHANGE_RATE = 4100;
    let cart = [];
    let currentTableId = 0;
    let currentTaxRate = 0;
    let currentTaxPercent = 0;
    let selectedCat = 'all';

    // ១. រៀបចំទិន្នន័យ Products ពី Laravel មកជា JS Object
    @php
        $formattedProducts = $products->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'barcode' => $p->barcode,
                'price' => (float)$p->price,
                'discount' => (float)($p->discount ?? 0),
               'stock' => (int) ($p->stock->qty ?? 0)
            ];
        });
    @endphp

    const allProducts = @json($formattedProducts);

    $(document).ready(function() {
        // ២. កំណត់ CSRF Token សម្រាប់ AJAX (ការពារ Error 419)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        refreshTableUI();

        // --- ផ្នែក Barcode Scanner ---
        $('#barcodeScanner').on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                const code = $(this).val().trim();
                if (code !== "") {
                    const product = allProducts.find(p => p.barcode === code);
                    if (product) {
                        addItemToCartLogic(product);
                        showToast('success', 'បានថែម: ' + product.name);
                    } else {
                        showToast('error', 'រកមិនឃើញបាកូដ: ' + code);
                    }
                    $(this).val('').focus();
                }
            }
        });


        // --- ផ្នែកជ្រើសរើសតុ ---

        $('.select-table-btn').click(async function() {
            currentTableId = $(this).data('id');
            const tableName = $(this).data('name');
            const saved = localStorage.getItem('hold_pos_' + currentTableId);
            let customerCountDisplay = "";
            // ១. ពិនិត្យមើលថាតើតុនេះមានទិន្នន័យចាស់ (Hold) ឬអត់
            if (saved && JSON.parse(saved).length > 0) {
                cart = JSON.parse(saved);
            } else {
                // ២. បើគ្មានទិន្នន័យចាស់ទេ សួររកចំនួនមនុស្ស
                const { value: count } = await Swal.fire({
                    title: 'ចំនួនមនុស្ស',
                    text: `តុ: ${tableName}`,
                    input: 'number',
                    inputValue: 1,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'យល់ព្រម',
                    cancelButtonText: 'បោះបង់',
                    inputValidator: (value) => {
                        if (!value || value <= 0) return 'សូមបញ្ចូលចំនួនមនុស្ស!';
                    }
                });

                if (!count) return; // បើអ្នកប្រើចុច Cancel មិនធ្វើអ្វីបន្តទេ
                customerCountDisplay = ` (${count} នាក់)`;
                cart = []; // ចាប់ផ្តើមកន្ត្រកថ្មីសម្រាប់តុថ្មី
            }


            $('#selected-table-id').val(currentTableId);
            // ៣. បោះតម្លៃទៅកាន់ HTML Elements (កែតម្រូវត្រង់នេះ)
            $('#selected-table-id').val(currentTableId); // បោះ ID ទៅ Hidden Input

            // កែពី .text() មកជា .val() ព្រោះ selected-table-name របស់បងជា <input>
            $('#selected-table-name').val(`${tableName}${customerCountDisplay}`);

            // ៤. ប្តូរ Interface ពីតារាងតុ ទៅកាន់តារាងលក់ទំនិញ
            $('#section-tables').addClass('d-none');
            $('#section-products').removeClass('d-none');

            // ៥. Focus ទៅលើការ Scan Barcode
            setTimeout(() => {
                $('#barcodeScanner').val('').focus();
            }, 500);

            updateCart(); // បង្ហាញទិន្នន័យក្នុងកន្ត្រក (បើមានទិន្នន័យចាស់)
        });
        // --- Filter & Search ---
        $('#categoryFilter').on('click', 'button', function () {
            selectedCat = $(this).data('category').toString();
            $('#categoryFilter button').removeClass('btn-dark active').addClass('btn-outline-dark');
            $(this).removeClass('btn-outline-dark').addClass('btn-dark active');
            filterProducts();
        });

        $('#productSearch').on('input', function () {
            filterProducts();
        });

        // --- Checkout Trigger ---
        $('#btn-pay-trigger').click(function () {
                if(currentTableId === 0) return Swal.fire('សូមជ្រើសតុ!', '', 'warning');
                if (cart.length === 0) return Swal.fire('កន្ត្រកទទេ!', '', 'warning');
                calculateFinalTotal();
                const modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('checkoutModal'));
                modalInstance.show();
        });

        // --- Hold Order ---
        $('#btn-hold').click(function() {
                if(currentTableId === 0 || cart.length === 0) return;
                // រក្សាទុកក្នុង LocalStorage
                localStorage.setItem('hold_pos_' + currentTableId, JSON.stringify(cart));

                $.ajax({
                    url: "{{ route('orders.update-table-status') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}", // កុំភ្លេច Token
                        tableId: currentTableId,
                        status: 'busy' // ប្រើអក្សរតូចដើម្បីឱ្យស៊ីជាមួយលក្ខខណ្ឌក្នុង Blade
                    },
                    success: function(res) {
                        if(res.success) {
                            showToast('success', 'រក្សាទុកជោគជ័យ');
                            // --- ផ្នែកបន្ថែមដើម្បីឱ្យតុប្តូរពណ៌ភ្លាមៗ ---
                            let tableCard = $(`.select-table-btn[data-id="${currentTableId}"]`);
                            // ១. បន្ថែម Class busy (ដើម្បីឱ្យចេញពណ៌ផ្កាឈូកតាម CSS)
                            tableCard.addClass('busy');
                            // ២. ប្តូរអត្ថបទ និងពណ៌ Badge ក្នុង Card នោះ
                            tableCard.find('.badge')
                                .removeClass('text-success border-success')
                                .addClass('text-danger border-danger')
                                .text('មានភ្ញៀវ');

                            backToTables();

                        }
                    }
                });
        });

        //Clear Card
        $('#clear-cart').click(function() {
                if(cart.length === 0) return;

                // ចាប់យក ID ទុកក្នុង variable ឱ្យច្បាស់សិន កុំឱ្យវាបាត់តម្លៃពេលចូលក្នុង Swal
                const tid = currentTableId;
                const statusTarget = 'free';

                if (!tid) {
                    console.error("រកមិនឃើញ Table ID ទេ!");
                    return;
                }

                Swal.fire({
                    title: 'លុបកន្ត្រកទំនិញ និងបញ្ឈប់ការប្រើតុ?',
                    text: "រាល់ទិន្នន័យដែលបានរក្សាទុកនឹងត្រូវលុបចោល!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'បាទ លុបចោល',
                    cancelButtonText: 'បោះបង់'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('orders.update-table-status') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                tableId: tid, // ប្រើ tid ដែលយើងចាប់បានខាងលើ
                                status: statusTarget
                            },
                            success: function(res) {
                                if(res.success) {
                                    // ១. សម្អាតទិន្នន័យ
                                    cart = [];
                                    localStorage.removeItem('hold_pos_' + tid);

                                    // ២. Update Interface
                                    updateCart();

                                    // ហៅ function ដោយប្រើ tid និង statusTarget ដែលមានតម្លៃច្បាស់លាស់
                                    refreshTableUI(tid, statusTarget);

                                    backToTables();
                                    showToast('info', 'បានសម្អាតតុរួចរាល់');
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'មិនអាចទាក់ទងទៅកាន់ Server បានទេ', 'error');
                            }
                        });
                    }
                });
        });

    //Refresh UI
    function refreshTableUI(tableId, status) {
            if (!tableId || !status) {
                console.error("Table ID ឬ Status មិនទាន់មានតម្លៃ:", {tableId, status});
                return;
            }

            let tableCard = $(`.select-table-btn[data-id="${tableId}"]`);

            // បើ Card តុហ្នឹងអត់មានក្នុង HTML ទេ កុំឱ្យវាបន្តទៅមុខទៀត
            if (tableCard.length === 0) {
                console.warn("រកមិនឃើញ Card របស់តុ ID:", tableId);
                return;
            }

            let currentStatus = status.toLowerCase();

            if (currentStatus === 'free' || currentStatus === 'available') {
                tableCard.removeClass('busy');
                tableCard.find('.badge')
                    .removeClass('text-danger border-danger')
                    .addClass('text-success border-success')
                    .text('ទំនេរ');
            } else if (currentStatus === 'busy') {
                tableCard.addClass('busy');
                tableCard.find('.badge')
                    .removeClass('text-success border-success')
                    .addClass('text-danger border-danger')
                    .text('មានភ្ញៀវ');
            }
        }

        // --- Real-time Calculation on Modal Input ---
        $(document).on('input', '#cash-dollar, #cash-riel, #manual-discount, #discountRate', () => {
            calculateFinalTotal();
        });

        // --- ហៅមុខងារ Checkout ទៅ Server ---
        $('#confirm-payment').on('click', handleConfirmPayment);
    });

    // ================= FUNCTIONS =================

    function addToCart(product) {
        // ឆែកមើល Property ឱ្យច្បាស់ បើផ្ញើមកឈ្មោះ quantity ត្រូវប្រើ product.quantity
        const availableStock = parseInt(product.quantity || product.stock || 0);

        const item = cart.find(x => x.id === product.id);

        if (item) {
            if (item.qty < availableStock) {
                item.qty++;
            } else {
                return Swal.fire('អស់ស្តុក', 'មិនអាចថែមទៀតបានទេ', 'error');
            }
        } else {
            if (availableStock > 0) {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    discount: 0,
                    discountRate: 0,
                    qty: 1,
                    maxStock: availableStock // រក្សាទុកចំនួនស្តុកពិតប្រាកដក្នុង item តែម្តង
                });
            } else {
                return Swal.fire('អស់ស្តុក', 'ទំនិញនេះគ្មានក្នុងស្តុកទេ', 'error');
            }
        }
        updateCart();
    }

    // មុខងារសម្រាប់ប៊ូតុងជ្រើសរើសលឿន (0%, 5%, 10%)
    function setQuickTax(val) {
        $('#tax-rate-input').val(val);
    }

    // ២. អនុគមន៍សម្រាប់បើក Modal
    function openTaxModal() {
        var myModal = new bootstrap.Modal(document.getElementById('taxModal'));
        myModal.show();
    }

    // ៣. អនុគមន៍កំណត់តម្លៃលឿន (0%, 5%, 10%)
    function setQuickTax(val) {
        $('#tax-rate-input').val(val);
    }

    function saveTaxRate() {
            const taxValue = parseFloat($('#tax-rate-input').val()) || 0;

            // ត្រួតពិនិត្យកុំឱ្យបញ្ចូលលេខអវិជ្ជមាន ឬលើស ១០០
            if (taxValue < 0 || taxValue > 100) {
                return Swal.fire('កំហុស', 'សូមបញ្ចូលអត្រាពន្ធចន្លោះពី 0 ដល់ 100', 'error');
            }

            currentTaxPercent = taxValue;

            // បង្ហាញការជូនដំណឹងជោគជ័យ
            Swal.fire({
                icon: 'success',
                title: 'រក្សាទុកជោគជ័យ',
                text: `អត្រាពន្ធត្រូវបានកំណត់ទៅ ${currentTaxPercent}%`,
                timer: 1500,
                showConfirmButton: false
            });

            // បិទ Modal
            $('#taxModal').modal('hide');

            // ហៅ Function គណនាលុយក្នុង Cart ឡើងវិញ (Update totals)
            if (typeof updateCart === "function") {
                updateCart();
            }
    }

    function updateCart() {
        const container = $('#cart-items');
        container.empty();

        let totalQty = 0;
        let totalOriginalPrice = 0;
        let totalItemDiscDollar = 0;

        cart.forEach((item, index) => {
            const price = parseFloat(item.price) || 0;
            const qty = parseInt(item.qty) || 0;
            const discRate = parseFloat(item.discountRate) || 0;
            const itemDisc = (price * discRate / 100) * qty; // គណនាបញ្ចុះតម្លៃតាមមុខទំនិញ
            const itemTotal = (price * qty) - itemDisc;

            totalQty += qty;
            totalOriginalPrice += (price * qty);
            totalItemDiscDollar += itemDisc;

            container.append(`
                <div class="border-bottom p-2 small d-flex align-items-center bg-white">
                    <div style="width: 35%" class="khmer fw-bold text-primary text-truncate">${item.name}</div>
                    <div style="width: 15%" class="text-center">$${price.toFixed(2)}</div>
                    <div style="width: 15%" class="text-center px-1">
                        <input type="number" class="form-control form-control-sm text-center"
                            value="${discRate}" onchange="updateItemDiscount(${index}, this.value)">
                    </div>
                    <div style="width: 20%" class="text-center d-flex align-items-center justify-content-center">
                        <button class="btn btn-sm btn-outline-secondary py-0" onclick="changeQty(${index}, -1)">-</button>
                        <span class="mx-2 fw-bold">${qty}</span>
                        <button class="btn btn-sm btn-outline-secondary py-0" onclick="changeQty(${index}, 1)">+</button>
                    </div>
                    <div style="width: 15%" class="text-end fw-bold">$${itemTotal.toFixed(2)}</div>
                    <div style="width: 5%" class="text-end pl-2">
                        <i class="fa-solid fa-trash-can text-danger pointer" onclick="removeItem(${index})"></i>
                    </div>
                </div>
            `);
        });

        // ១. គណនា Discount (Item Discount + Manual Discount)
        let manualDisc = parseFloat($('#manual-discount').val()) || 0;
        let finalDiscountTotal = totalItemDiscDollar + manualDisc;

        // ២. គណនា Subtotal (ក្រោយដក Discount ទាំងអស់)
        let subtotal = totalOriginalPrice - finalDiscountTotal;
        subtotal = Math.max(0, subtotal);

        // ៣. គណនា Tax (VAT)
        let taxAmount = subtotal * (currentTaxPercent / 100);

        // ៤. គណនា Grand Total
        let grand = subtotal + taxAmount;

        // --- បង្ហាញលទ្ធផលទៅកាន់ UI ---
        $('#item-count-display').text(`${totalQty} ($${totalOriginalPrice.toFixed(2)})`);

        // បង្ហាញ Discount តាមទម្រង់ ($Item) $Total
        $('#total-discount-display').text(`($${totalItemDiscDollar.toFixed(2)}) $${finalDiscountTotal.toFixed(2)}`);

        $('#subtotal').text('$' + subtotal.toFixed(2));
        $('#vat-display').text('$' + taxAmount.toFixed(2));
        $('#grandtotal').text('$' + grand.toFixed(2));

        // ហៅទៅ Function គណនាក្នុង Modal បង់ប្រាក់
        if (typeof calculateFinalTotal === "function") {
            calculateFinalTotal();
        }
        $('#barcodeScanner').focus();
    }

    const categories = @json($categories);

    // ២. អនុគមន៍សម្រាប់បង្កើតប៊ូតុង Category
    function renderCategories() {
        const container = $('#categoryFilter');
        container.empty();

        // ប៊ូតុង "ទាំងអស់" (ត្រូវថែម data-category="all")
        container.append(`
            <button class="btn btn-sm btn-dark rounded-pill px-3 cat-btn active" data-category="all">
                ទាំងអស់
            </button>
        `);

        // ប៊ូតុងតាមក្រុមនីមួយៗ (ត្រូវថែម data-category)
        categories.forEach(cat => {
            container.append(`
                <button class="btn btn-sm btn-outline-dark rounded-pill px-3 cat-btn text-nowrap"
                        data-category="${cat.id}">
                    ${cat.name}
                </button>
            `);
        });
    }

    // ៣. អនុគមន៍សម្រាប់ Filter (Safe Version)
    function filterProducts() {
        // ១. ទាញតម្លៃពី Input Search (ប្រើ ID ដែលយើងទើបប្តូរមិញ)
        let searchText = ($('#productSearch').val() || "").toLowerCase().trim();
        $('.product-item').each(function () {
            // ២. ទាញឈ្មោះទំនិញ (ត្រូវមាន class="product-name" ក្នុង HTML របស់ទំនិញ)
            let nameElement = $(this).find('.product-name');
            let productName = nameElement.length ? nameElement.text().toLowerCase() : "";

            // ៣. ទាញ Category
            let itemCatRaw = $(this).data('category');
            let itemCat = (itemCatRaw !== undefined && itemCatRaw !== null) ? itemCatRaw.toString() : "";

            // ៤. លក្ខខណ្ឌ Filter
            let matchCat = (selectedCat === "all" || itemCat === selectedCat);
            let matchSearch = productName.includes(searchText);

            // ៥. បង្ហាញ ឬ លាក់
            if (matchCat && matchSearch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // ចាប់ Event ពេលវាយអក្សរក្នុងប្រអប់ Search
    $(document).on('input', '#productSearch', function () {
        filterProducts();
    });
    // ៤. ចាប់ផ្ដើមការងារពេល Load Page រួចរាល់
    $(document).ready(function() {

        // បង្កើតប៊ូតុង Categories
        renderCategories();

        // ចាប់ Event ពេលចុចលើប៊ូតុង Category (លែងប្រើ onclick ក្នុង HTML ទៀតហើយ)
        $('#categoryFilter').on('click', 'button', function () {
            let catVal = $(this).data('category');

            // បង្ការបញ្ហា toString()
            selectedCat = (catVal !== undefined && catVal !== null) ? catVal.toString() : "all";

            // ប្តូរពណ៌ប៊ូតុង
            $('#categoryFilter button').removeClass('btn-dark active').addClass('btn-outline-dark');
            $(this).removeClass('btn-outline-dark').addClass('btn-dark active');

            filterProducts();
        });

        // ចាប់ Event ពេល Search
        $('#productSearch').on('input', function () {
            filterProducts();
        });
    });

    function changeQty(index, val) {
        const item = cart[index];
        if (!item) return;

        // បម្លែង allProducts ឱ្យទៅជា Array (ការពារ Error បើវាជា Object)
        let productsArray = Array.isArray(allProducts) ? allProducts : Object.values(allProducts);
        // ស្វែងរកទំនិញក្នុងស្តុកដើម្បីផ្ទៀងផ្ទាត់
        const p = productsArray.find(x => x.id == item.id);
        const stockAvailable = p ? parseInt(p.stock) : (parseInt(item.stock) || 0);
        // បើបន្ថែមចំនួន (val > 0) ត្រូវឆែកស្តុកសិន
        if (val > 0) {
            if (item.qty >= stockAvailable) {
                return Swal.fire({
                    icon: 'warning', // ប្តូរជា warning ឱ្យសមនឹងការដាស់តឿនស្តុក
                    title: 'ស្តុកមិនគ្រប់គ្រាន់',
                    text: `ទំនិញនេះមានក្នុងស្តុកតែ ${stockAvailable} ប៉ុណ្ណោះ`,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }
        // ប្តូរចំនួនទំនិញ
        item.qty += val;
        // បើចំនួនស្មើ ០ ឬតិចជាង លុបចេញពី Cart
        if (item.qty <= 0) {
            cart.splice(index, 1);
        }

        updateCart();
    }

    function updateItemDiscount(index, rate) {
        cart[index].discountRate = Math.min(100, Math.max(0, parseFloat(rate) || 0));
        updateCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        updateCart();
    }

    function backToTables() {
        $('#section-products').addClass('d-none');
        $('#section-tables').removeClass('d-none');
        currentTableId = 0;
        cart = [];
        updateCart();
        refreshTableUI();
    }

    function updatePaymentStatusUI(grand, paid) {
        const $s = $('#payment-status');
        $s.removeClass('bg-danger bg-warning bg-success text-white');
        if (paid <= 0) $s.text('ជំពាក់').addClass('bg-danger text-white');
        else if (paid < grand - 0.01) $s.text('បង់ខ្លះ').addClass('bg-warning text-white');
        else $s.text('បង់គ្រប់').addClass('bg-success text-white');
    }

    function showToast(icon, title) {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 }).fire({ icon, title });
    }
    // ១. មុខងារបើក Modal និងរៀបចំតម្លៃដំបូង
    function openCheckoutModal() {
        const subtotal = calculateTotal(); // មុខងារគណនាសរុបក្នុងកន្ត្រកដែលបងមានស្រាប់

        $('#modal-subtotal').text(`$${subtotal.toFixed(2)}`);
        $('#total-riel').text(`${(subtotal * EXCHANGE_RATE).toLocaleString()} ៛`);

        // reset តម្លៃក្នុង input
        $('#cash-dollar').val(0);
        $('#cash-riel').val(0);
        $('#discountRate').val(0);
        $('#discountAmount').val('$0.00');

        calculateChange();
        $('#checkoutModal').modal('show');
    }

    // ២. មុខងារគណនាប្រាក់អាប់ និងប្រាក់ជំពាក់
    function calculateChange() {
        const totalGoal = parseFloat($('#modal-subtotal').text().replace('$', '')) || 0;
        const discRate = parseFloat($('#discountRate').val()) || 0;

        // គណនាការបញ្ចុះតម្លៃ
        const discountAmt = totalGoal * (discRate / 100);
        const finalTotal = totalGoal - discountAmt;
        $('#discountAmount').val(`$${discountAmt.toFixed(2)}`);

        // សរុបលុយដែលទទួល
        const receivedDollar = parseFloat($('#cash-dollar').val()) || 0;
        const receivedRiel = parseFloat($('#cash-riel').val()) || 0;
        const totalReceived = receivedDollar + (receivedRiel / EXCHANGE_RATE);

        const balance = totalReceived - finalTotal;

        if (balance >= 0) {
            // ករណីលុយគ្រប់ (មានលុយអាប់)
            $('#balance-dollar').text(`$${balance.toFixed(2)}`).removeClass('text-warning').addClass('text-danger');
            $('#debt-amount').text('$0.00');
            $('#payment-status').text('បង់រួច').removeClass('bg-secondary bg-warning').addClass('bg-success');
        } else {
            // ករណីលុយមិនគ្រប់ (ជំពាក់)
            $('#balance-dollar').text('$0.00');
            $('#debt-amount').text(`$${Math.abs(balance).toFixed(2)}`);
            $('#payment-status').text('ជំពាក់').removeClass('bg-secondary bg-success').addClass('bg-warning');
        }
    }
    function calculateFinalTotal() {
            let grand = parseFloat($('#grandtotal').text().replace('$', '')) || 0;
            let bankRate = parseFloat($('#discountRate').val()) || 0;
            let bankAmount = (grand * bankRate) / 100;
            let finalGrand = Math.max(0, grand - bankAmount);

            $('#discountAmount').val(bankAmount.toFixed(2));
            $('#modal-subtotal').text('$' + finalGrand.toFixed(2));
            $('#total-riel').text((finalGrand * EXCHANGE_RATE).toLocaleString() + ' ៛');

            let paidD = parseFloat($('#cash-dollar').val()) || 0;
            let paidR = parseFloat($('#cash-riel').val()) || 0;
            let totalPaid = paidD + (paidR / EXCHANGE_RATE);

            let balance = totalPaid - finalGrand;
            $('#balance-dollar').text(balance > 0 ? '$' + balance.toFixed(2) : '$0.00');
            $('#balance-riel').text(balance > 0 ? (Math.round(balance * EXCHANGE_RATE)).toLocaleString() + ' ៛' : '0 ៛');

            updatePaymentStatusUI(finalGrand, totalPaid);
    }

    // ៤. មុខងារប៊ូតុងកាត់លុយរហ័ស (Add Cash)
    function addCash(amount) {
            let current = parseFloat($('#cash-dollar').val()) || 0;
        $('#cash-dollar').val(current + amount);
        calculateChange();
    }

    function clearCash() {
        $('#cash-dollar').val(0);
        $('#cash-riel').val(0);
        calculateChange();
    }

    $(document).ready(function() {
            // រាល់ពេលវាយលុយដុល្លារ ឬរៀល ឱ្យវាគណនាលុយអាប់ និងលុយជំពាក់
            $('#cash-dollar, #cash-riel').on('input', function() {
                const subtotal = parseFloat($('#modal-subtotal').text().replace(/[^\d.-]/g, '')) || 0;
                const usd = parseFloat($('#cash-dollar').val()) || 0;
                const riel = parseFloat($('#cash-riel').val()) || 0;

                const totalReceived = usd + (riel / 4000);
                const diff = subtotal - totalReceived;

                if (diff > 0) {
                    // បើនៅខ្វះ (ជំពាក់)
                    $('#debt-amount').text('$' + diff.toFixed(2));
                    $('#balance-dollar').text('$0.00');
                } else {
                    // បើលើស (លុយអាប់)
                    $('#debt-amount').text('$0.00');
                    $('#balance-dollar').text('$' + Math.abs(diff).toFixed(2));
                }
            });
    });

    function handleConfirmPayment() {
        // ១. ត្រួតពិនិត្យថាមានទំនិញឬអត់
        if (cart.length === 0) {
            return Swal.fire('កំហុស', 'មិនមានទំនិញក្នុងកន្ត្រក', 'error');
        }

        // ២. ទាញទិន្នន័យ និងគណនា
        const subtotal = parseFloat($('#modal-subtotal').text().replace(/[^\d.-]/g, '')) || 0;
        const receivedUsd = parseFloat($('#cash-dollar').val()) || 0;
        const receivedRiel = parseFloat($('#cash-riel').val()) || 0;

        const exchangeRate = 4000;
        const totalReceived = receivedUsd + (receivedRiel / exchangeRate);
        const debtCalc = subtotal - totalReceived;
        const isAllowCredit = $('#allowCredit').is(':checked');

        // ទាញតម្លៃពី Select ID "CustomersId"
        const customerValue = $('#CustomersId').val();
        const customerId = parseInt(customerValue); // វានឹងក្លាយជាលេខ 0 សម្រាប់ Walk-In

        // ទាញតម្លៃ Tax
        let taxRateValue = typeof currentTaxPercent !== 'undefined' ? currentTaxPercent : 0;
        let calculatedTaxAmount = (subtotal / (1 + (taxRateValue / 100))) * (taxRateValue / 100);

        // ៣. ឆែកលក្ខខណ្ឌការទូទាត់
        if (debtCalc > 0.005 && !isAllowCredit) {
            return Swal.fire({
                icon: 'warning',
                title: 'ការទូទាត់មិនគ្រប់គ្រាន់',
                text: `លោកអ្នកនៅខ្វះចំនួន $${debtCalc.toFixed(2)} ទៀត។ សូមបង់ឱ្យគ្រប់ ឬបើកសញ្ញា "អនុញ្ញាតឱ្យជំពាក់"`,
                confirmButtonColor: '#ff9f43'
            });
        }

        // ✅ ឆែកលក្ខខណ្ឌជំពាក់ (កែសម្រួល Syntax ឱ្យត្រូវវិញ)
        if (isAllowCredit) {
            if (customerId <= 0 || isNaN(customerId)) {
                return Swal.fire({
                    icon: 'error',
                    title: 'មិនអាចជំពាក់បានទេ',
                    text: 'ករណីជំពាក់ លោកអ្នកត្រូវតែជ្រើសរើសឈ្មោះអតិថិជនជាក់លាក់ (មិនមែន Walk-In ទេ)!',
                    confirmButtonColor: '#d33'
                });
            }
        }
        // ៤. រៀបចំទិន្នន័យបញ្ជូនទៅ Server
        let orderData = {
            _token: '{{ csrf_token() }}',
            table_id: typeof currentTableId !== 'undefined' ? currentTableId : null,
            customer_id: customerId || 0,
            tax_rate: taxRateValue,
            tax_amount: calculatedTaxAmount.toFixed(2),
            subtotal: subtotal,
            discount: parseFloat($('#discountAmount').val()) || 0,
            received_usd: receivedUsd,
            received_riel: receivedRiel,
            balance_dollar: debtCalc < 0 ? Math.abs(debtCalc).toFixed(2) : 0,
            payment_method: $('#paymentMethod').val(),
            is_credit: isAllowCredit ? 1 : 0,
            debt_amount: debtCalc > 0 ? debtCalc.toFixed(2) : 0,

            items: cart.map(item => ({
                id: item.id,
                qty: item.qty,
                price: item.price,
                discount: (item.price * (item.discountRate || 0) / 100) * item.qty
            }))
        };

        // ៥. បាញ់ AJAX
        $.ajax({
            url: "{{ route('orders.checkout') }}",
            type: "POST",
            data: orderData,
            beforeSend: function() {
                $('#confirm-payment').prop('disabled', true).text('កំពុងរក្សាទុក...');
            },
            success: function(res) {
                if(res.success) {
                    localStorage.removeItem('hold_pos_' + currentTableId);
                    let printFrame = document.createElement('iframe');
                    printFrame.style.display = 'none';
                    printFrame.src = '/order/invoice/' + res.order_id;
                    document.body.appendChild(printFrame);

                    printFrame.onload = function() {
                        printFrame.contentWindow.focus();
                        printFrame.contentWindow.print();
                        setTimeout(() => { location.reload(); }, 1000);
                    };
                } else {
                    Swal.fire('បរាជ័យ', res.message || 'មានបញ្ហាក្នុងការរក្សាទុក', 'error');
                    $('#confirm-payment').prop('disabled', false).text('✔️ បង់ប្រាក់ & បោះពុម្ព');
                }
            },
            error: function(xhr) {
                Swal.fire('បរាជ័យ', 'មានបញ្ហាបច្ចេកទេស (Server Error)', 'error');
                $('#confirm-payment').prop('disabled', false).text('✔️ បង់ប្រាក់ & បោះពុម្ព');
            }
        });
    }
</script>
@endpush
