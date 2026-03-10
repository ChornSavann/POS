@extends('layout.app')
@section('setting_menu_open', 'menu-open')
@section('setting_active', 'active')
@section('unit_active', 'active')
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class=" d-flex justify-content-between align-items-center">
            <h3 class="fw-bold">Unit Management</h3>
            <a href="{{ route('units.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg"></i> Create Unit
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h3 class="card-title text-muted">Available Units</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 20%">Unit Name</th>
                            <th style="width: 20%">Base Unit</th>
                            <th style="width: 15%">Operator</th>
                            <th style="width: 15%">Value</th>
                             <th style="width: 15%">Note</th>
                            <th style="width: 15%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-semibold">{{ $item->name }}</td>
                            <td>
                                @if($item->baseUnit)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $item->baseUnit->name }}</span>
                                @else
                                    <span class="text-muted small">Main Unit</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ $item->operator ?? '-' }}</td>
                            <td>{{ number_format($item->operator_value, 2) }}</td>
                            <td>{{ $item->note ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Edit Button --}}
                                    <a href="{{ route('units.edit', $item->id) }}"
                                       class="btn btn-outline-warning btn-sm border-0"
                                       title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('units.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm border-0 delete-confirm"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->name }}"
                                                title="Delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No units found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
