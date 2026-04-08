
@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('performance', 'active')
@section('content')
<div class="filter-box shadow-sm mb-4 bg-white p-4 no-print"
     style="border-radius: 16px; border: 1px solid #eef2f7;">

    <div class="d-flex align-items-center mb-3">
        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
            <i class="bi bi-filter-left text-primary fs-4"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold text-dark">ចម្រាញ់របាយការណ៍លក់</h5>
            <small class="text-muted">ជ្រើសរើសកាលបរិច្ឆេទដើម្បីមើលទិន្នន័យលក់ដាច់ និងលក់យឺត</small>
        </div>
    </div>

    <form action="{{ route('reports.performance') }}" method="GET" class="row g-3 align-items-end">

        <div class="col-md-3">
            <label class="form-label small fw-bold text-secondary mb-2">
                <i class="bi bi-calendar-date me-1"></i> ចាប់ពីថ្ងៃ
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                <input type="date" name="start_date" class="form-control border-start-0 ps-0 bg-light"
                       value="{{ request('start_date', date('Y-m-01')) }}">
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label small fw-bold text-secondary mb-2">
                <i class="bi bi-calendar-check me-1"></i> ដល់ថ្ងៃ
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-calendar3"></i></span>
                <input type="date" name="end_date" class="form-control border-start-0 ps-0 bg-light"
                       value="{{ request('end_date', date('Y-m-t')) }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary shadow-sm flex-grow-1 d-flex align-items-center justify-content-center"
                        style="height: 42px; border-radius: 10px; font-weight: 600;">
                    <i class="bi bi-search me-2"></i> បង្ហាញរបាយការណ៍
                </button>

                <a href="{{ route('reports.performance') }}" class="btn btn-light border shadow-sm d-flex align-items-center justify-content-center"
                   style="height: 42px; width: 42px; border-radius: 10px;" title="Reset">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </div>
    </form>
</div>
@endsection()
