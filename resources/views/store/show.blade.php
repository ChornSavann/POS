<div class="modal fade" id="viewStoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered shadow-lg">
        <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
            
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); min-height: 80px; align-items: flex-start;">
                <h5 class="modal-title text-white mt-2 fw-bold">
                    <i class="bi bi-shop-window me-2"></i>ព័ត៌មានហាង
                </h5>
                <button type="button" class="btn-close btn-close-white mt-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 pt-0" style="margin-top: -45px;">
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        <img id="view-logo" src="" class="rounded-circle shadow border border-4 border-white" 
                             style="width: 100px; height: 100px; object-バランス: cover; background-color: #f8f9fa;">
                        <span id="view-status-dot" class="position-absolute border border-2 border-white rounded-circle" 
                              style="width: 18px; height: 18px; bottom: 5px; right: 5px;"></span>
                    </div>
                    <h4 id="view-name" class="mt-3 fw-bold text-dark mb-1"></h4>
                    <span id="view-status-text" class="badge rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.75rem;"></span>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-4 border-0 h-100 text-center shadow-sm">
                            <div class="text-primary mb-1"><i class="bi bi-telephone-fill"></i></div>
                            <div class="small text-muted mb-1">លេខទូរស័ព្ទ</div>
                            <div id="view-phone" class="fw-bold text-dark small"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-4 border-0 h-100 text-center shadow-sm">
                            <div class="text-info mb-1"><i class="bi bi-envelope-at-fill"></i></div>
                            <div class="small text-muted mb-1">អ៊ីមែល</div>
                            <div id="view-email" class="fw-bold text-dark small" style="word-break: break-all;"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-4 border-0 shadow-sm">
                            <div class="d-flex align-items-center mb-2 text-warning">
                                <i class="bi bi-geo-alt-fill me-2"></i>
                                <span class="small text-muted fw-bold text-uppercase">អាសយដ្ឋាន</span>
                            </div>
                            <p id="view-address" class="text-dark mb-0 small ps-4"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pb-4 justify-content-center">
                <button type="button" class="btn btn-light px-5 py-2 rounded-pill fw-bold text-muted border shadow-sm" data-bs-dismiss="modal">
                    បិទ
                </button>
            </div>
        </div>
    </div>
</div>