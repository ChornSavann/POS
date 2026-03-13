@extends('layout.app')
@section('dashboard', 'active')

@section('content')
<style>
    body { background-color: #f0f2f5;  }
    .content-header h2 { font-weight: 700; color: #1e293b; letter-spacing: -0.5px; }

    /* Quick Links Modern Style */
    .quick-link-item {
        background: #ffffff; border: none; border-radius: 12px; padding: 15px;
        text-align: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none; color: #475569; display: block; min-width: 100px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .quick-link-item:hover {
        transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        background: #3c8dbc; color: #fff;
    }
    .quick-link-item:hover i { color: #fff; }
    .quick-link-item i { display: block; font-size: 28px; margin-bottom: 8px; color: #3c8dbc; }
    .quick-link-item span { font-size: 13px; font-weight: 600; }

    /* Modern Card Custom */
    .card-custom { background: #ffffff; border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 25px; overflow: hidden; }
    .card-header-custom {
        background: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px;
        color: #1e293b; font-size: 18px; font-weight: 700; display: flex; align-items: center; gap: 10px;
    }
    .card-sales { border-top: 4px solid #10b981; transition: transform 0.3s ease; }
    .card-products { border-top: 4px solid #3b82f6; }
    .card-quick { border-top: 4px solid #6366f1; }
    .card-sales:hover { transform: translateY(-5px); }

    /* Toast Custom Style */
    #toast-container > .toast {
        background-color: #ffffff !important; color: #334155 !important;
        border-radius: 16px !important; border: 1px solid #f1f5f9 !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
        font-family: 'Kantumruy Pro', sans-serif !important; opacity: 1 !important;
    }
</style>
<style>
    .quick-link-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100px;
        height: 100px;
        background: #f8fafc;
        border-radius: 16px;
        text-decoration: none;
        color: #475569;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .quick-link-item i {
        font-size: 1.5rem;
        margin-bottom: 8px;
        color: #080cf4; /* Indigo color */
    }

    .quick-link-item span {
        font-size: 0.85rem;
        font-weight: 500;
        text-align: center;
    }

    .quick-link-item:hover {
        background: #6366f1;
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }

    .quick-link-item:hover i {
        color: white;
    }

    .bg-success-soft {
        background-color: #f0fdf4;
    }

    .card-custom {
        transition: transform 0.2s;
    }
</style>
<div class="container-fluid py-3">
    <div class="content-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-chart-pie me-2 text-primary"></i>Dashboard Summary</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Home</a></li>
                <li class="breadcrumb-item active fw-bold text-primary">Dashboard</li>
            </ol>
        </nav>
    </div>

    <div class="card-custom card-quick mb-4 border-0 shadow-sm rounded-4">
        <div class="card-header-custom bg-white border-0 py-3">
            <i class="fas fa-rocket text-indigo me-2"></i> <span class="fw-bold">Quick Management</span>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-3 justify-content-center mb-3">
                <a href="#" class="quick-link-item"><i class="fa fa-desktop"></i><span>POS System</span></a>
                <a href="#" class="quick-link-item"><i class="fa fa-box-open"></i><span>Inventory</span></a>
                <a href="#" class="quick-link-item"><i class="fa fa-file-invoice-dollar"></i><span>List Sales</span></a>
                <a href="#" class="quick-link-item"><i class="fa fa-receipt"></i><span>Orders</span></a>
                <a href="#" class="quick-link-item"><i class="fa fa-tags"></i><span>Categories</span></a>
                <a href="#" class="quick-link-item"><i class="fa fa-users-cog"></i><span>Users</span></a>
                <a href="#" class="quick-link-item"><i class="fa fa-truck"></i><span>Suppliers</span></a>
                <a href="#" class="quick-link-item"><i class="fa fa-shopping-cart"></i><span>Purchase</span></a>
                <a href="#" class="quick-link-item"><i class="fa fa-chart-line"></i><span>Analysis</span></a>
                <a href="{{ route('units.index') }}" class="quick-link-item"><i class="fa fa-sliders-h"></i><span>Settings</span></a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card-custom card-sales border-0 shadow-sm rounded-4 h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center bg-white border-0 py-3">
                    <span class="fw-bold"><i class="fas fa-chart-bar text-success me-2"></i>Revenue Analysis (2026)</span>
                    <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill fs-6">
                        ${{ number_format(array_sum($SalesValues ?? []), 2) }}
                    </span>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card-custom card-products border-0 shadow-sm rounded-4 h-100" style="background: white;">
                <div class="card-header-custom d-flex align-items-center gap-2 border-0 bg-white py-3">
                    <i class="fas fa-star text-warning"></i> <span class="fw-bold" style="color: #1e293b; font-family: 'Kantumruy Pro', sans-serif;">Best Sellers</span>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 320px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* រចនា Legend ឱ្យដូចចំណុចមូលក្នុងរូបគំរូ */
    .low-stock-modal-wrapper .stock-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
    }

    #pie-legend ul {
        list-style: none;
        padding: 0;
        margin-top: 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    }

    #pie-legend li {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #475569;
        font-family: 'Inter', sans-serif;
    }

    #pie-legend span {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%; /* ចំណុចមូល */
        margin-right: 8px;
    }
</style>

{{-- រួមបញ្ចូល Low Stock Modal --}}
{{-- @include('partail.lowStock') --}}

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Sales Bar Chart ---
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        const monthColors = ['#4caf50', '#2196f3', '#ff9800', '#e91e63', '#9c27b0', '#00bcd4', '#ffeb3b', '#ff5722', '#795548', '#607d8b', '#009688', '#673ab7'];

        new Chart(ctxSales, {
            type: 'bar',
            data: {
                labels: {!! json_encode($SalesLabels ?? []) !!},
                datasets: [{
                    label: 'ចំណូលសរុប ($)',
                    data: {!! json_encode($SalesValues ?? []) !!},
                    backgroundColor: monthColors,
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { padding: 10 }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5] },
                        ticks: { callback: value => '$' + value.toLocaleString() }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // --- Best Sellers Doughnut Chart ---
       const ctxPie = document.getElementById('pieChart').getContext('2d');

        // ពណ៌ Pastel/Modern ដូចក្នុងរូបគំរូ (បៃតងខ្ចី, បៃតងស្រស់, ស្វាយ, លឿង)
        const customColors = ['#4ade80', '#2dd4bf', '#a78bfa', '#facc15'];

        new Chart(ctxPie, {
            type: 'pie', // ប្រើ Pie Chart ធម្មតា
            data: {
                labels: {!! json_encode($TopProductNames ?? []) !!},
                datasets: [{
                    data: {!! json_encode($TopProductQty ?? []) !!},
                    backgroundColor: customColors,

                    // --- គន្លឹះដើម្បីបង្កើតព្រំដែនពណ៌ស (ដូចក្នុងរូប) ---
                    borderColor: '#ffffff', // ពណ៌ព្រំដែនជាពណ៌ស
                    borderWidth: 2,        // កម្រាស់ព្រំដែន

                    hoverOffset: 15        // ឱ្យវារីកធំពេល Hover
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom', // ដាក់ Legend នៅខាងក្រោម
                        labels: {
                            usePointStyle: true, // ប្រើចំណុចមូល
                            pointStyle: 'circle',
                            padding: 20,
                            font: { family: 'Inter', size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.9)',
                        titleFont: { family: 'Inter', size: 13 },
                        bodyFont: { family: 'Inter', size: 12 },
                        cornerRadius: 8
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500
                }
            }
        });

        // --- Toastr & Auto Modal Logic ---
        toastr.options = { "closeButton": true, "progressBar": true, "positionClass": "toast-top-right", "timeOut": "4000" };

        @if(session('FullName') || Auth::check())
            toastr.success('រីករាយដែលបានជួបអ្នកម្តងទៀត {{ Auth::user()->name ?? session("FullName") }}!', 'ជម្រាបសួរ!');
        @endif

       // លោត Modal ស្វ័យប្រវត្តិបើមានទំនិញអស់ស្តុក
        @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
            setTimeout(function() {
                var lowStockEl = document.getElementById('lowStockModal');
                if(lowStockEl) {
                    var myModal = new bootstrap.Modal(lowStockEl);

                    // ១. បង្ហាញ Modal
                    myModal.show();

                    // ២. កំណត់ឱ្យវាបិទទៅវិញដោយខ្លួនឯង បន្ទាប់ពី ៣ វិនាទី (3000ms)
                    setTimeout(function() {
                        myModal.hide();
                    }, 1000); // បងអាចដូរលេខ 3000 ទៅតាមការចង់បាន
                }
            }, 1200);
        @endif
    });
</script>
@endpush


