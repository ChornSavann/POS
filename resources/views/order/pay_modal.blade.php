<div class="modal fade" id="payDebtModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-hand-holding-usd me-2"></i> បង់ប្រាក់ជំពាក់ #{{ $order->invoice_no }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body text-center">
                        <span class="text-muted small uppercase fw-bold">ទឹកប្រាក់នៅជំពាក់សរុប</span>
                        {{-- ប្តូរមកប្រើ number_format របស់ PHP --}}
                        <h2 class="text-danger fw-bolder mb-0">${{ number_format($order->debt_amount, 2) }}</h2>
                        <div class="text-muted small mt-1">≈ {{ number_format($order->debt_amount * 4100, 0) }} ៛</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-secondary">បង់ជាដុល្លារ ($)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-dollar-sign text-success"></i></span>
                            <input type="number" id="payDollar-{{ $order->id }}" class="form-control form-control-lg border-start-0 text-end fw-bold input-pay"
                                   data-order-id="{{ $order->id }}" placeholder="0.00" step="0.01" />
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-secondary">បង់ជារៀល (៛)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary">៛</span>
                            <input type="number" id="payRiel-{{ $order->id }}" class="form-control form-control-lg border-start-0 text-end fw-bold input-pay"
                                   data-order-id="{{ $order->id }}" placeholder="0" step="100" />
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded-3 bg-soft-success border border-success border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold">សរុបទទួលបាន៖</span>
                        <span class="fs-5 fw-bold text-success" id="totalInputDisplay-{{ $order->id }}">$ 0.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">បោះបង់</button>
                <button type="button" class="btn btn-warning px-4 fw-bold shadow-sm confirmPayDebt" data-order="{{ $order->id }}">
                    <i class="fas fa-check-circle me-1"></i> យល់ព្រមបង់
                </button>
            </div>
        </div>
    </div>
</div>
