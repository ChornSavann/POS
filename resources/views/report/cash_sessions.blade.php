@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2"></i> របាយការណ៍បើក-បិទបញ្ជីលក់</h5>
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-file-export me-1"></i> Export</button>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-uppercase small fw-bold text-muted bg-dark border-bottom">
                        <tr style="font-family: 'Kantumruy Pro', sans-serif; font-size: 0.85rem; color: #e9ecef;">
                            <th class="ps-4 py-3">អ្នកលក់/Session</th>
                            <th class="py-3">ស្ថានភាព</th>
                            <th class="py-3">ពេលបើក (Opening)</th>
                            <th class="py-3 text-end">លុយដើមគ្រា</th>
                            <th class="py-3">ពេលបិទ (Closing)</th>
                            <th class="py-3 text-end">លុយក្នុងប្រព័ន្ធ</th>
                            <th class="py-3 text-end">លុយរាប់ជាក់ស្តែង</th>
                            <th class="py-3 text-end pe-4">លើស/ខ្វះ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $item->user->name }}</div>
                                <small class="text-muted">ID: #{{ $item->id }}</small>
                            </td>
                            <td>
                                @if($item->status == 'open')
                                    <span class="badge bg-success-soft text-success px-2 py-1">កំពុងលក់</span>
                                @else
                                    <span class="badge bg-secondary-soft text-secondary px-2 py-1">បិទបញ្ជីហើយ</span>
                                @endif
                            </td>
                            <td class="small">{{ $item->opening_time->format('d/m/Y h:i A') }}</td>
                            <td class="text-end fw-bold text-primary">${{ number_format($item->opening_balance, 2) }}</td>
                            <td class="small">
                                {{ $item->closing_time ? $item->closing_time->format('d/m/Y h:i A') : '-' }}
                            </td>
                            <td class="text-end text-muted">
                                ${{ number_format($item->system_cash + $item->opening_balance, 2) }}
                            </td>
                            <td class="text-end fw-bold text-dark">
                                {{ $item->actual_cash ? '$' . number_format($item->actual_cash, 2) : '-' }}
                            </td>
                            <td class="text-end pe-4">
                                @if($item->status == 'closed')
                                    @if($item->difference < 0)
                                        <span class="text-danger fw-bold"><i class="fas fa-arrow-down me-1"></i> ${{ number_format(abs($item->difference), 2) }}</span>
                                    @elseif($item->difference > 0)
                                        <span class="text-success fw-bold"><i class="fas fa-arrow-up me-1"></i> ${{ number_format($item->difference, 2) }}</span>
                                    @else
                                        <span class="text-muted fw-bold">$0.00</span>
                                    @endif
                                @else
                                    <span class="text-muted italic small">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0 py-3">
            {{ $sessions->links() }}
        </div>
    </div>
</div>

<style>
    /* បន្ថែមពណ៌ស្រាលៗសម្រាប់ Badge */
    .bg-success-soft { background-color: #e8fadf; }
    .bg-secondary-soft { background-color: #f1f3f5; }
</style>
@endsection
