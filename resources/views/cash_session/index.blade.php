<div class="modal fade" id="openSessionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-dark text-white border-0 py-3" style="background: linear-gradient(45deg, #1a1a1a, #333); border-radius: 15px 15px 0 0;">
                <h5 class="modal-title fw-bold" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fas fa-cash-register me-2 text-warning"></i> ចាប់ផ្តើមបើកបញ្ជីលក់
                </h5>
            </div>
            <form action="{{ route('cash-session.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <p class="text-muted small text-uppercase fw-bolder">សូមបញ្ចូលទឹកប្រាក់ដើមគ្រា (Opening Cash)</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ទឹកប្រាក់ក្នុងថត ($)</label>
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" step="0.01" name="opening_balance" class="form-control border-start-0 ps-0 fw-bold" placeholder="0.00" required autofocus>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">កំណត់ចំណាំ</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="បញ្ជាក់បន្ថែមបើមាន..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="border-radius: 10px; background: linear-gradient(45deg, #4361ee, #4895ef);">
                        <i class="fas fa-key me-2"></i> បើកបញ្ជីលក់ឥឡូវនេះ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
