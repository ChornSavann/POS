@extends('layout.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid">
            {{-- 1. Header Section --}}
            <div class="header mb-4 mt-3">
                <div
                    class="card-header bg-white py-3 d-flex justify-content-between align-items-center border shadow-sm rounded-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-dark text-white me-3 shadow-sm">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark text-khmer">របាយការណ៍លក់ប្រចាំខែ</h5>
                            <small class="text-muted italic">Monthly Revenue Overview - Year {{ $year }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <form action="{{ route('reports.monthly') }}" method="GET"
                            class="d-flex align-items-center bg-light p-1 rounded-2 border">
                            <label class="ms-2 me-2 small text-muted fw-bold text-uppercase">ឆ្នាំ:</label>
                            <select name="year" class="form-select form-select-sm border-0 bg-light shadow-none"
                                onchange="this.form.submit()" style="width: 100px; cursor: pointer;">
                                @foreach ($years_list as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endforeach
                            </select>
                        </form>
                        <div class="btn-group shadow-sm">
                            <button class="btn btn-sm btn-white border" title="Print"><i
                                    class="fas fa-print text-muted"></i></button>
                            <button class="btn btn-sm btn-white border" title="Export Excel"><i
                                    class="fas fa-file-excel text-success"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Summary Cards (Compact Style) --}}
            <div class="row g-3 mb-4">
                {{-- Card 1: Total Revenue --}}
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden border-bottom-success">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="stats-icon-small bg-soft-success text-success">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-uppercase mb-0 text-muted fw-bold text-khmer" style="font-size: 11px;">
                                    ចំណូលសរុប ({{ $year }})</p>
                                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.5px;">
                                    ${{ number_format($yearly_total, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Average Revenue --}}
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden border-bottom-primary">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="stats-icon-small bg-soft-primary text-primary">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-uppercase mb-0 text-muted fw-bold text-khmer" style="font-size: 11px;">
                                    ចំណូលមធ្យមភាគ</p>
                                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.5px;">
                                    ${{ number_format($avg_monthly, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Total Invoices --}}
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden border-bottom-warning">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="stats-icon-small bg-soft-warning text-warning">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-uppercase mb-0 text-muted fw-bold text-khmer" style="font-size: 11px;">
                                    ចំនួនវិក្កយបត្រសរុប</p>
                                <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.5px;">
                                    {{ number_format($monthly_stats->sum('orders'), 0) }}
                                    <small class="fw-normal text-muted" style="font-size: 12px;">Inv</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Monthly Data Table (Enterprise Grid Style) --}}
            <div class="card border-0  rounded-0 mt-3">
                <div class=" p-0">
                    <div class="">
                        <table class="table mb-0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="ps-3 py-2 text-khmer">ឈ្មោះខែ (MONTH) <i
                                            class="fas fa-sort float-end mt-1 opacity-50"></i></th>
                                    <th class="text-center py-2 text-khmer">ប្រតិបត្តិការ (TRANSACTIONS) <i
                                            class="fas fa-sort float-end mt-1 opacity-50"></i></th>
                                    <th class="text-end pe-3 py-2 text-khmer">ចំណូលសរុប (REVENUE) <i
                                            class="fas fa-sort float-end mt-1 opacity-50"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_yearly_revenue = 0;
                                    $total_yearly_orders = 0;
                                @endphp
                                @foreach ($monthly_stats as $index => $row)
                                    @php
                                        $total_yearly_revenue += $row['revenue'];
                                        $total_yearly_orders += $row['orders'];
                                    @endphp
                                    <tr onclick="window.location='{{ route('reports.monthly_details', ['month' => $index + 1, 'year' => $year]) }}'"
                                        style="cursor: pointer;">
                                        {{-- <td class=" fw-bold text-dark text-khmer border-end">
                                            {{ $row['month_name'] }}
                                        </td> --}}
                                        <td class="ps-3 fw-bold text-dark text-khmer border-end">
                                            <div class="d-flex align-items-center">
                                                @php
                                                    // កំណត់ពណ៌ Icon ទៅតាមលំដាប់ខែ (ឧទាហរណ៍៖ ៣ខែដំបូងពណ៌ខៀវ...)
                                                    $iconColor = 'text-muted';
                                                    if ($index < 3) {
                                                        $iconColor = 'text-primary';
                                                    } elseif ($index < 6) {
                                                        $iconColor = 'text-success';
                                                    } elseif ($index < 9) {
                                                        $iconColor = 'text-warning';
                                                    } else {
                                                        $iconColor = 'text-danger';
                                                    }
                                                @endphp

                                                <i class="fas fa-calendar-check me-2 {{ $iconColor }} opacity-75"
                                                    style="font-size: 13px;"></i>
                                                <span>{{ $row['month_name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold text-primary border-end">
                                            {{ number_format($row['orders']) }} <small
                                                class="text-muted fw-normal">Invoices</small>
                                        </td>
                                        <td class="text-end  fw-bold text-dark">
                                            ${{ number_format($row['revenue'], 2) }}
                                            <i class="fas fa-chevron-right ms-2 text-muted opacity-25 small"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light fw-bold text-dark">
                                <tr>
                                    <td class="ps-3 text-khmer py-2 border-end">សរុបឆ្នាំ {{ $year }} (Total)</td>
                                    <td class="text-center py-2 border-end">{{ number_format($total_yearly_orders) }}</td>
                                    <td class="text-end pe-3 py-2 text-success">
                                        ${{ number_format($total_yearly_revenue, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <style>
                /* រៀបចំស្ទីលតារាងឱ្យដូចរូបភាពរបស់បង */
                .custom-enterprise-table {
                    border: 1px solid #343a40 !important;
                    /* បន្ទាត់ក្រៅដិត */
                    border-collapse: collapse !important;
                }

                /* Header ពណ៌ប្រផេះចាស់ (Charcoal) */
                .custom-enterprise-table thead tr {
                    background-color: #343a40 !important;
                    color: #ffffff !important;
                }

                .custom-enterprise-table thead th {
                    border: 1px solid #4b545c !important;
                    /* បន្ទាត់ខណ្ឌក្នុង Header */
                    font-weight: 600;
                    font-size: 13px;
                    padding: 10px 8px !important;
                    text-transform: uppercase;
                }

                /* បន្ទាត់ Grid ច្បាស់ៗក្នុង Body */
                .custom-enterprise-table tbody td {
                    border: 1px solid #dee2e6 !important;
                    padding: 10px 8px !important;
                    vertical-align: middle;
                }

                /* បន្ទាត់ខណ្ឌបញ្ឈរឱ្យដូចរូបភាពទី ៣ */
                .border-end {
                    border-right: 1px solid #dee2e6 !important;
                }

                /* Hover Effect */
                .custom-enterprise-table tbody tr:hover {
                    background-color: #f8fbff !important;
                }

                .text-khmer {
                    font-family: 'Kantumruy Pro', sans-serif;
                }

                /* បន្ថែម Style នេះក្នុងផ្នែក <style> របស់បង */
                .stats-icon-small {
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 10px;
                    font-size: 16px;
                    flex-shrink: 0;
                }

                /* កំណត់ទំហំអក្សរ H4 ឱ្យតូចជាងមុនបន្តិច */
                h4.fw-bold {
                    font-size: 1.30rem;
                }
            </style>
        </div>
    </div>

    <style>
        /* Global Styling */
        .text-khmer {
            font-family: 'Kantumruy Pro', sans-serif;
            font-size: 14px;
        }

        .italic {
            font-style: italic;
            font-size: 12px;
        }

        /* Header Icon Box */
        .icon-box {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 18px;
        }

        /* Stats Cards Customization */
        .stats-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 22px;
        }

        .bg-soft-success {
            background-color: rgba(40, 167, 69, 0.1);
        }

        .bg-soft-primary {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .bg-soft-warning {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .border-bottom-success {
            border-bottom: 4px solid #28a745 !important;
        }

        .border-bottom-primary {
            border-bottom: 4px solid #007bff !important;
        }

        .border-bottom-warning {
            border-bottom: 4px solid #ffc107 !important;
        }

        /* Enterprise Grid Table Styling (ដូចរូបភាពទី១) */
        .custom-grid-table {
            border: 1px solid #2d3436 !important;
        }

        .custom-grid-table thead tr {
            background-color: #343a40 !important;
            color: #ffffff !important;
        }

        .custom-grid-table thead th {
            border: 1px solid #4b545c !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
        }

        .custom-grid-table tbody td {
            border: 1px solid #dee2e6 !important;
            padding: 12px 10px !important;
        }

        .custom-grid-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .custom-grid-table tbody tr:hover {
            background-color: #f1f4f7 !important;
        }

        .border-end {
            border-right: 1px solid #dee2e6 !important;
        }

        .btn-white {
            background: #fff;
            color: #6c757d;
        }
    </style>
@endsection
