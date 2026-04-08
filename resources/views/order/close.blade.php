<div class="modal fade" id="closeSessionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-danger text-white border-0 py-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title fw-bold" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fas fa-lock me-2"></i> បញ្ចប់ការលក់ និងបិទបញ្ជី
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="closeSessionForm" action="{{ isset($session) ? route('cash-session.update', $session->id) : '#' }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="system_cash" value="{{ $system_cash ?? 0 }}">
                <input type="hidden" name="system_bank" value="{{ $system_bank ?? 0 }}">

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <h6 class="text-muted small fw-bold mb-3">របាយការណ៍ក្នុងប្រព័ន្ធ</h6>
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>លុយដើមគ្រា:</span>
                                    <span class="fw-bold">${{ number_format($session->opening_balance ?? 0, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>លក់ជាសាច់ប្រាក់:</span>
                                    <span class="fw-bold">+ ${{ number_format($system_cash ?? 0, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between text-primary border-top pt-2">
                                    <span class="fw-bold">សរុបត្រូវមាន:</span>
                                    <span class="fw-bold fs-5">${{ number_format(($session->opening_balance ?? 0) + ($system_cash ?? 0), 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small fw-bold mb-3">ការរាប់ជាក់ស្តែង</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">លុយសាច់ប្រាក់រាប់ឃើញ ($)</label>
                                <input type="number" step="0.01" name="actual_cash" class="form-control form-control-lg border-danger fw-bold" placeholder="0.00" required>
                            </div>
                            <div class="mb-0">
                                <label class="form-label small fw-bold">ចំណាំ</label>
                                <textarea name="note" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-danger w-100 py-3 fw-bold shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-check-circle me-2"></i> បញ្ជាក់ការបិទបញ្ជី
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
