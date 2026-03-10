<div class="modal fade" id="lowStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden low-stock-modal-wrapper">

            <div class="modal-header border-0 bg-white px-4 pt-4 pb-2">
                <div class="d-flex align-items-center w-100">
                    <div class="status-icon-modal me-3 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="modal-title fw-bolder text-dark mb-0 fs-5">បញ្ជីទំនិញជិតអស់ស្តុក</h5>
                        <p class="text-muted mb-0 small">
                            មានទំនិញ <span class="text-danger fw-bold">{{ $lowStockProducts->count() }}</span> មុខ
                        </p>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body px-3 py-3 bg-light-f8">
                <div class="row g-2 custom-scrollbar" style="max-height: 55vh; overflow-y: auto;">
                    @forelse($lowStockProducts as $product)
                        <div class="col-md-6">
                            <div class="product-card-modal shadow-sm border bg-white rounded-3 p-2">
                                <div class="d-flex align-items-center">
                                    <div class="img-container">
                                        <img src="{{ asset($product->image ? $product->image : 'assets/img/no-image.png') }}"
                                             class="rounded-2 w-100 h-100 object-fit-cover shadow-sm"
                                             onerror="this.src='{{ asset('assets/img/no-image.png') }}'">
                                    </div>

                                    <div class="ms-2 flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="fw-bold text-dark text-truncate small-title" title="{{ $product->name }}">
                                                {{ $product->name }}
                                            </div>
                                            <div class="text-primary fw-bold small">
                                                ${{ number_format($product->price, 2) }}
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="badge bg-light text-secondary border-0 fw-normal extra-small">
                                                #{{ $product->barcode }}
                                            </span>

                                            <span class="badge {{ ($product->stock->qty ?? 0) <= 0 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill stock-badge">
                                                <i class="bi bi-box-seam me-1"></i>ស្តុក: {{ $product->stock->qty ?? 0 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                            <p class="mt-2">មិនមានទំនិញជិតអស់ស្តុកឡើយ!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="modal-footer border-0 bg-white p-3">
                <button type="button" class="btn btn-light btn-sm px-4 fw-bold" data-bs-dismiss="modal">បោះបង់</button>
                <a href="{{ route('purchases.create') }}" class="btn btn-dark btn-sm px-4 fw-bold rounded-2">
                    <i class="bi bi-plus-circle me-1"></i> បញ្ជាទិញថ្មី
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Scope Styles ឱ្យដើរតែក្នុង Modal នេះដើម្បីការពារ Conflict Layout ផ្សេងៗ */
    .low-stock-modal-wrapper .status-icon-modal {
        width: 45px;
        height: 45px;
        background: #fff5f5;
        color: #ff4757;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .low-stock-modal-wrapper .product-card-modal {
        min-height: 75px;
        border: 1px solid #f1f5f9 !important;
        transition: all 0.2s ease-in-out;
    }

    .low-stock-modal-wrapper .product-card-modal:hover {
        transform: translateY(-2px);
        border-color: #e2e8f0 !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
    }

    .low-stock-modal-wrapper .img-container {
        width: 55px;
        height: 55px;
        flex-shrink: 0;
    }

    .low-stock-modal-wrapper .small-title {
        font-size: 0.88rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .low-stock-modal-wrapper .extra-small {
        font-size: 0.75rem;
        padding: 4px 8px;
    }

    .low-stock-modal-wrapper .stock-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        letter-spacing: 0.3px;
    }

    .bg-light-f8 {
        background-color: #f8f9fa !important;
    }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
</style>
