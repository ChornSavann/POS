@extends('layout.app')

@section('setting_menu_open', 'menu-open')
@section('cash_session_active', 'active')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center modern-wrapper">
    <div class="card session-card border-0 shadow-lg">

        <div class="card-header border-0 text-center py-4">
            <div class="icon-circle shadow-sm mx-auto mb-3">
                <i class="fas fa-cash-register"></i>
            </div>
            <h5 class="fw-bold mb-1">គ្រប់គ្រងបញ្ជីសាច់ប្រាក់</h5>

            @if(isset($currentSession))
                <div class="status-indicator active mt-2">
                    <span class="dot"></span> កំពុងដំណើរការ (#{{ $currentSession->id }})
                </div>
            @else
                <p class="text-white-50 small mb-0 font-khmer">បើក Session ដើម្បីចាប់ផ្តើមការលក់</p>
            @endif
        </div>

        <div class="card-body bg-white p-4">
            @if(!isset($currentSession))
                <form action="{{ route('cash-session.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">ទឹកប្រាក់ដើមគ្រា ($)</label>
                        <div class="input-modern border rounded-3 overflow-hidden">
                            <span class="input-group-text border-0 bg-transparent text-primary fw-bold">$</span>
                            <input type="number" step="0.01" name="opening_balance" class="form-control border-0 fw-bold" placeholder="0.00" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">កំណត់ចំណាំ</label>
                        <textarea name="note" class="form-control border-0 bg-light" rows="2" placeholder="បញ្ជាក់បន្ថែម..." style="border-radius: 10px; font-size: 0.9rem; resize: none;"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-modern py-2 fw-bold shadow-sm">
                            <i class="fas fa-rocket me-2"></i> ចាប់ផ្តើមលក់
                        </button>
                    </div>
                </form>
            @else
                <div class="active-display text-center py-2">
                    <div class="balance-box p-3 rounded-4 mb-4 border border-primary border-opacity-10">
                        <span class="text-muted small fw-bold d-block mb-1">សាច់ប្រាក់ដើមគ្រា</span>
                        <h2 class="fw-bold text-primary mb-0">${{ number_format($currentSession->opening_balance, 2) }}</h2>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('order.index') }}" class="btn btn-dark-modern py-2 fw-bold">
                            <i class="fas fa-shopping-cart me-2"></i> ទៅកាន់ POS
                        </a>

                        <button type="button" class="btn btn-outline-danger py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#closeSessionModal">
                            <i class="fas fa-power-off me-2"></i> បិទបញ្ជីលក់
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <div class="card-footer bg-white border-0 text-center pb-4">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small hover-primary">
                <i class="fas fa-arrow-left me-1"></i> ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង
            </a>
        </div>
    </div>
</div>

@if(isset($currentSession))
<div class="modal fade" id="closeSessionModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg modal-custom-radius">

            <div class="modal-header bg-danger text-white border-0 py-3 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calculator me-2"></i>
                    <h6 class="modal-title fw-bold mb-0">ផ្ទៀងផ្ទាត់ និងបិទបញ្ជី</h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('cash-session.update', $currentSession->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body p-4 bg-white">
                    <div class="system-report p-3 rounded-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small font-khmer">សរុបសាច់ប្រាក់ក្នុងប្រព័ន្ធ:</span>
                            <span class="fw-bold text-danger fs-5">
                                ${{ number_format($currentSession->opening_balance + $system_cash, 2) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between x-small text-muted italic">
                            <span>(ដើមគ្រា ${{ number_format($currentSession->opening_balance, 2) }} + លក់បាន ${{ number_format($system_cash, 2) }})</span>
                        </div>
                    </div>

                    <input type="hidden" name="system_cash" value="{{ $system_cash }}">
                    <input type="hidden" name="system_bank" value="{{ $system_bank }}">

                    <div class="mb-3 text-center">
                        <label class="form-label fw-bold text-dark small text-uppercase mb-2">សាច់ប្រាក់រាប់ឃើញជាក់ស្តែង ($)</label>
                        <div class="input-group shadow-none border border-danger border-opacity-25 rounded-3 overflow-hidden custom-input-focus">
                            <span class="input-group-text bg-danger text-white border-0 px-3">
                                <i class="fas fa-coins"></i>
                            </span>
                            <input type="number" step="0.01" name="actual_cash" class="form-control border-0 fw-bold text-center py-3 fs-4" placeholder="0.00" required autofocus>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small text-uppercase">កំណត់ចំណាំបិទបញ្ជី</label>
                        <textarea name="note" class="form-control border-0 bg-light" rows="2" placeholder="បញ្ជាក់ពីការលើស ឬខ្វះខាត (បើមាន)..." style="border-radius: 10px; font-size: 0.85rem; resize: none;"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0 d-flex gap-2">
                    <button type="button" class="btn btn-light flex-fill fw-bold text-muted py-2 radius-10" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-danger flex-fill fw-bold py-2 shadow-sm radius-10">
                        <i class="fas fa-check-circle me-1"></i> បញ្ជាក់បិទបញ្ជី
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    /* CSS Variables for Easy Customization */
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #4895ef);
        --header-bg: #1e293b;
        --accent-orange: linear-gradient(135deg, #fbbf24, #f59e0b);
    }

    /* General Layout */
    .modern-wrapper {
        min-height: 85vh;
        background: radial-gradient(circle at top right, #1e3a8a, #0f172a);
        font-family: 'Kantumruy Pro', sans-serif;
    }

    .session-card {
        width: 100%;
        max-width: 380px;
        border-radius: 20px;
        background: var(--header-bg);
        color: white;
        overflow: hidden;
    }

    /* Components */
    .icon-circle {
        width: 60px;
        height: 60px;
        background: var(--accent-orange);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #1e293b;
    }

    .status-indicator {
        background: rgba(34, 197, 94, 0.15);
        color: #4ade80;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .dot {
        width: 8px;
        height: 8px;
        background: #4ade80;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }

    /* Form & Input */
    .input-modern {
        display: flex;
        background: #f8fafc;
        transition: 0.3s;
    }

    .input-modern:focus-within {
        border-color: #4361ee !important;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .system-report {
        background: #fff5f5;
        border: 1px dashed #feb2b2;
    }

    /* Buttons */
    .btn-primary-modern {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 10px;
    }

    .btn-dark-modern {
        background: #1e293b;
        border: none;
        color: white;
        border-radius: 10px;
    }

    .radius-10 { border-radius: 10px; }
    .modal-custom-radius { border-radius: 20px; }

    /* Animations */
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.3); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }

    .modal-content { animation: slideUp 0.3s ease-out; }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Helper Classes */
    .font-khmer { font-family: 'Kantumruy Pro', sans-serif; }
    .x-small { font-size: 0.7rem; }
    .italic { font-style: italic; }
    .hover-primary:hover { color: #4361ee !important; }
</style>
@endsection
