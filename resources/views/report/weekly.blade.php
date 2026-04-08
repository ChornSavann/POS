@extends('layout.app')

@section('content')
    <div class="container-fluid">
        {{-- Section: Header & Filter --}}
        <section class="content-header mb-4 no-print">
            <div class="card border-0 shadow-sm rounded-3 bg-white mb-3">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 d-flex align-items-center gap-3">
                            <div class="bg-dark text-white shadow-sm d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px; border-radius: 12px;">
                                <i class="fas fa-chart-line fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark font-khmer">របាយការណ៍លក់ប្រចាំសប្តាហ៍</h5>
                                <p class="text-muted mb-0 small">ឆ្នាំ {{ $year }} | ខែ {{ $month ?? 'ទាំងអស់' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-md-end mb-0 small bg-transparent p-0">
                                    <li class="breadcrumb-item text-muted"><i class="fas fa-home me-1"></i> Dashboard</li>
                                    <li class="breadcrumb-item active text-primary fw-bold">Weekly Report</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-2">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-3 border-start border-primary border-5">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small fw-bold text-uppercase mb-1">វិក្កយបត្រសរុប</p>
                                    <h4 class="mb-0 fw-bold">{{ number_format($invoices->count()) }}</h4>
                                </div>
                                <div class="text-primary opacity-50"><i class="fas fa-receipt fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-3 border-start border-success border-5">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small fw-bold text-uppercase mb-1">ចំណូលសរុប</p>
                                    <h4 class="mb-0 fw-bold text-success">
                                        ${{ number_format($invoices->sum('grand_total'), 2) }}</h4>
                                </div>
                                <div class="text-success opacity-50"><i class="fas fa-money-bill-wave fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-3 border-start border-info border-5">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small fw-bold text-uppercase mb-1">បរិមាណលក់សរុប</p>
                                    <h4 class="mb-0 fw-bold text-info">
                                        {{ number_format($invoices->sum('total_quantity_sold'), 0) }} ឈុត
                                    </h4>
                                </div>
                                <div class="text-info opacity-50"><i class="fas fa-shopping-basket fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-3 border-start border-warning border-5">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small fw-bold text-uppercase mb-1">ប្រភេទជម្រើសលក់</p>
                                    <h4 class="mb-0 fw-bold text-warning">
                                        {{ number_format($reports->sum('total_invoices')) }} ថ្ងៃ</h4>
                                </div>
                                <div class="text-warning opacity-50"><i class="fas fa-boxes fa-2x"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 bg-light-subtle">
                    <form action="{{ route('reports.weekly') }}" method="GET" id="searchForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-secondary">ឆ្នាំ</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-secondary border-end-0 rounded-start-2">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </span>
                                    <select name="year" class="form-select border-secondary shadow-none rounded-end-2">
                                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                                {{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-secondary">ជ្រើសរើសខែ</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-secondary border-end-0 rounded-start-2">
                                        <i class="fas fa-filter text-success"></i>
                                    </span>
                                    <select name="month"
                                        class="form-select border-secondary shadow-none rounded-end-2 font-khmer">
                                        <option value="">គ្រប់ខែទាំងអស់</option>
                                        @php
                                            $months = [
                                                'មករា',
                                                'កុម្ភៈ',
                                                'មីនា',
                                                'មេសា',
                                                'ឧសភា',
                                                'មិថុនា',
                                                'កក្កដា',
                                                'សីហា',
                                                'កញ្ញា',
                                                'តុលា',
                                                'វិច្ឆិកា',
                                                'ធ្នូ',
                                            ];
                                        @endphp
                                        @foreach ($months as $index => $mName)
                                            <option value="{{ $index + 1 }}"
                                                {{ $month == $index + 1 ? 'selected' : '' }}>{{ $mName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-secondary">ថ្ងៃទី</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-secondary border-end-0 rounded-start-2">
                                        <i class="fas fa-calendar-day text-info"></i>
                                    </span>
                                    <input type="number" name="day"
                                        class="form-control border-secondary shadow-none rounded-end-2" placeholder="Ex: 15"
                                        value="{{ $day }}" min="1" max="31">
                                </div>
                            </div>

                            <div class="col-md-5 text-end d-flex gap-2">
                                <button type="submit"
                                    class="btn btn-primary rounded-2 flex-grow-1 d-flex align-items-center justify-content-center gap-2 fw-bold">
                                    <i class="fas fa-search"></i> ស្វែងរក
                                </button>

                                <a href="{{ route('reports.weekly') }}"
                                    class="btn btn-outline-secondary rounded-2 d-flex align-items-center justify-content-center px-3"
                                    title="Reset">
                                    <i class="fas fa-sync-alt"></i>
                                </a>

                                <button type="button"
                                    class="btn btn-outline-dark rounded-2 d-flex align-items-center justify-content-center gap-2 px-3"
                                    id="btnPrint">
                                    <i class="fas fa-print text-danger"></i> បោះពុម្ព
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        {{-- Table Section --}}
        <div class=" border shadow-sm rounded-3 overflow-hidden print-section">
            <div class="">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle border-collapse">
                        <thead class="bg-dark text-white text-center" style="font-size: 0.75rem;">
                            <tr class="border-bottom border-secondary">
                                <th class="py-2 px-3 border-end border-secondary fw-bold" style="min-width: 100px;">
                                    <i class="fas fa-calendar-alt me-1" style="font-size: 0.7rem;"></i> សប្តាហ៍ទី
                                </th>

                                @php
                                    $daysKhmer = [
                                        'Monday' => 'ច័ន្ទ',
                                        'Tuesday' => 'អង្គារ',
                                        'Wednesday' => 'ពុធ',
                                        'Thursday' => 'ព្រហស្បតិ៍',
                                        'Friday' => 'សុក្រ',
                                        'Saturday' => 'សៅរ៍',
                                        'Sunday' => 'អាទិត្យ',
                                    ];
                                @endphp

                                @foreach ($daysKhmer as $en => $kh)
                                    <th class="py-2 border-end border-secondary fw-normal">
                                        {{ $kh }}
                                    </th>
                                @endforeach

                                <th class="py-2 bg-primary text-white fw-bold" style="min-width: 110px;">
                                    <i class="fas fa-layer-group me-1" style="font-size: 0.7rem;"></i> សរុប
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($reports->count() > 0)
                                @foreach ($reports->groupBy('week_number') as $week => $days)
                                    <tr>
                                        <td class="text-center bg-light border-end border-bottom fw-bold text-dark">
                                            សប្តាហ៍ទី {{ $week }}
                                        </td>

                                        @php
                                            $weekDays = [
                                                'Monday',
                                                'Tuesday',
                                                'Wednesday',
                                                'Thursday',
                                                'Friday',
                                                'Saturday',
                                                'Sunday',
                                            ];
                                            $wInv = 0;
                                            $wQty = 0;
                                            $wPrice = 0;
                                        @endphp

                                        @foreach ($weekDays as $dayName)
                                            @php
                                                $data = $days->where('day_name', $dayName)->first();
                                                $inv = $data ? $data->total_invoices : 0;
                                                $qty = $data ? $data->total_qty ?? 0 : 0;
                                                $price = $data ? $data->total_amount : 0;
                                                $dateNum = $data ? date('d', strtotime($data->exact_date)) : null;

                                                $wInv += $inv;
                                                $wQty += $qty;
                                                $wPrice += $price;
                                            @endphp

                                            <td class="p-2 border-end border-bottom text-center position-relative report-cell"
                                                style="min-width: 110px; height: 100px;">
                                                @if ($dateNum)
                                                    <div class="position-absolute top-0 end-0 pe-1">
                                                        <small class="fw-bold text-muted"
                                                            style="font-size: 0.65rem;">{{ $dateNum }}</small>
                                                    </div>
                                                @endif

                                                @if ($inv > 0)
                                                    <div class="d-flex flex-column gap-1 mt-2">
                                                        <span
                                                            class="badge bg-info-subtle text-info border border-info-subtle rounded-1"
                                                            style="font-size: 0.7rem;">{{ $inv }} Inv</span>
                                                        <span class="small fw-bold text-dark">{{ number_format($qty) }}
                                                            Qty</span>
                                                        <span class="text-danger fw-bold" style="font-size: 0.85rem;">
                                                            ${{ number_format($price, 2) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="mt-3 text-muted opacity-25">-</div>
                                                @endif
                                            </td>
                                        @endforeach

                                        <td
                                            class="bg-primary-subtle text-center p-2 border-bottom border-start border-primary-subtle">
                                            <div class="d-flex flex-column gap-1">
                                                <span class="badge bg-primary text-white rounded-1"
                                                    style="font-size: 0.7rem;">
                                                    {{ $wInv }} Inv
                                                </span>
                                                <span class="small fw-bold text-dark">{{ number_format($wQty) }}
                                                    Qty</span>
                                                <span
                                                    class="text-danger fw-bolder">${{ number_format($wPrice, 2) }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                {{-- បង្ហាញនៅពេលគ្មានទិន្នន័យ --}}
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center opacity-50">
                                            <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
                                            <h5 class="fw-bold text-muted">មិនមានទិន្នន័យសម្រាប់បង្ហាញឡើយ</h5>
                                            <p class="small text-muted">សូមជ្រើសរើសការបរិច្ឆេទផ្សេងដើម្បីពិនិត្យឡើងវិញ</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Global Rounding */
        .card,
        .btn,
        .badge {
            border-radius: 8px !important;
        }

        .rounded-start-2 {
            border-top-left-radius: 8px !important;
            border-bottom-left-radius: 8px !important;
        }

        .rounded-end-2 {
            border-top-right-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }

        /* Input Group Styling */
        .input-group-text {
            font-size: 0.9rem;
            padding: 0 12px;
        }

        .input-group>.form-select,
        .input-group>.form-control {
            border-left: none !important;
            cursor: pointer;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #343a40 !important;
            box-shadow: none !important;
        }

        /* Table Effects */
        .report-cell:hover {
            background-color: #f8fafc !important;
        }

        .bg-info-subtle {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
        }

        .bg-primary-subtle {
            background-color: #f0f7ff !important;
        }

        /* បង្ខំឱ្យ Header ខ្មៅដិត */
        .table thead tr {
            background-color: #212529 !important;
        }

        .table thead th {
            background-color: inherit !important;
            /* ឱ្យវាទាញពណ៌ពីជួរដេក (TR) */
            color: white !important;
            vertical-align: middle;
        }

        /* Print Logic */
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }

            .no-print,
            .btn,
            form,
            .sidebar,
            .navbar {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .table {
                border: 1px solid #000 !important;
                width: 100% !important;
            }

            .table th,
            .table td {
                border: 1px solid #ccc !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>

    <script>
        document.getElementById('btnPrint').addEventListener('click', function() {
            window.print();
        });
    </script>
@endsection
