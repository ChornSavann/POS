<div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            {{-- Header --}}
            <div class="modal-header bg-primary bg-gradient text-white border-0 py-3 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-info-circle me-2"></i> ព័ត៌មានលម្អិត៖ <span id="p-name-title"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">
                    {{-- ផ្នែកខាងឆ្វេង៖ រូបភាព និង Barcode --}}
                    <div class="col-md-5 text-center border-end">
                       <div class="position-relative mb-3">
                            <img id="p-image"
                                src="{{ asset('assets/img/no-image.png') }}"
                                class="img-fluid rounded-4 shadow-sm border"
                                style="max-height: 280px; width: 100%; object-fit: contain; background: #f8f9fa;"
                                onerror="this.src='{{ asset('assets/img/no-image.png') }}';">
                        </div>
                        <div class="p-3 bg-light rounded-4 border border-dashed">
                            <span class="d-block fw-bold text-muted small text-uppercase mb-1">Barcode លេខកូដ</span>
                            <div class="fs-5 fw-bold text-primary" id="p-barcode"></div>
                        </div>
                    </div>

                    {{-- ផ្នែកខាងស្តាំ៖ ព័ត៌មានលម្អិត --}}
                    <div class="col-md-7">
                        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text me-2"></i> ព័ត៌មានទូទៅ
                        </h6>

                        <table class="table table-sm table-borderless align-middle">
                            <tbody>
                                <tr>
                                    <th class="text-muted fw-normal py-2" style="width: 160px;">
                                        <i class="bi bi-tag me-2 text-primary"></i>ឈ្មោះទំនិញ:
                                    </th>
                                    <td class="fw-bold text-dark fs-6" id="p-name"></td>
                                </tr>

                                <tr>
                                    <th class="text-muted fw-normal py-2">
                                        <i class="bi bi-grid me-2 text-primary"></i>ប្រភេទ (Category):
                                    </th>
                                    <td>
                                        <span id="p-category" class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-3">
                                            </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-muted fw-normal py-2">
                                        <i class="bi bi-award me-2 text-primary"></i>ម៉ាក (Brand):
                                    </th>
                                    <td id="p-brand" class="fw-semibold text-secondary"></td>
                                </tr>

                                <tr>
                                    <th class="text-muted fw-normal py-2">
                                        <i class="bi bi-box-seam me-2 text-primary"></i>ស្តុកក្នុងឃ្លាំង:
                                    </th>
                                    <td class="py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span id="p-qty" class="badge rounded-pill bg-primary-subtle text-primary fw-bold px-3 py-2" style="font-size: 0.95rem; min-width: 45px;">
                                                0
                                            </span>
                                            <span class="text-muted small">ឯកតា</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h6 class="fw-bold text-primary mt-4 mb-3 border-bottom pb-2">
                            <i class="bi bi-box-seam me-2"></i> ខ្នាត និងស្តុក
                        </h6>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 border rounded bg-white shadow-xs text-center">
                                    <small class="text-muted d-block">ខ្នាតលក់ (Sale)</small>
                                    <span id="p-sale-unit" class="fw-bold text-dark"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded bg-white shadow-xs text-center">
                                    <small class="text-muted d-block">ខ្នាតទិញ (Purchase)</small>
                                    <span id="p-purchase-unit" class="fw-bold text-dark"></span>
                                </div>
                            </div>
                        </div>

                        <table class="table table-sm table-borderless mt-2">
                            <tbody>
                                <tr>
                                    <th class="text-muted fw-normal" style="width: 150px;">តម្លៃដើម (Cost):</th>
                                    <td class="text-secondary fw-bold fs-6">$<span id="p-cost"></span></td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">តម្លៃលក់ (Price):</th>
                                    <td class="text-success fw-extrabold fs-4">$<span id="p-price"></span></td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal border-top pt-2">ស្តុកអាសន្ន:</th>
                                    <td class="text-danger fw-bold pt-2 border-top">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <span id="p-alert"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted fw-normal">ស្ថានភាព:</th>
                                    <td id="p-status"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="modal-footer bg-light border-0 py-3 rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">បិទ</button>
                <a id="p-edit-link" href="#" class="btn btn-primary px-4 rounded-pill shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> កែប្រែព័ត៌មាន
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed { border-style: dashed !important; }
    .fw-extrabold { font-weight: 800; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .bg-primary-subtle { background-color: #e7f1ff !important; }
</style>
