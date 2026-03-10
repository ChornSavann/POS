@extends('layout.app')

@section('content')
<style>
    /* បន្ថែម Gradient និង Hover Effect លើប៊ូតុង */
    .btn-create {
        background: linear-gradient(45deg, #0d6efd, #004dc7);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    /* រចនាប៊ូតុងសកម្មភាពឱ្យមូល និងស្អាត */
    .btn-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }
    .btn-edit-soft { background-color: #fff9db; color: #f59f00; }
    .btn-edit-soft:hover { background-color: #f59f00; color: white; }
    
    .btn-delete-soft { background-color: #fff5f5; color: #fa5252; }
    .btn-delete-soft:hover { background-color: #fa5252; color: white; }

    /* Badge ស្ទីលស្រាល (Subtle) */
    .badge-soft {
        padding: 0.5em 0.8em;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .bg-active { background-color: #ebfbee; color: #2b8a3e; border: 1px solid #d3f9d8; }
    .bg-inactive { background-color: #fff5f5; color: #c92a2a; border: 1px solid #ffe3e3; }

    /* តារាង */
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #495057;
        padding: 15px;
    }
</style>

<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row align-items-center mb-4">
        <div class="col-sm-6">
            <h4 class="fw-bold mb-1"><i class="bi bi-grid-fill me-2 text-primary"></i>ការគ្រប់គ្រងប្រភេទទំនិញ</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Category List</li>
                </ol>
            </nav>
        </div>
        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <a href="{{ route('category.create') }}" class="btn btn-create text-white px-4 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមប្រភេទថ្មី
            </a>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold text-muted small text-uppercase">បញ្ជីប្រភេទទំនិញដែលមានស្រាប់</h5>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control bg-light border-start-0" placeholder="ស្វែងរក...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Category Identity</th>
                            <th>Description</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $key => $item)
                        <tr>
                            <td class="text-center text-muted small">{{ $key + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">ID: #CAT-00{{ $item->id }}</div>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ Str::limit($item->description, 60, '...') ?: 'មិនមានការពិពណ៌នាឡើយ' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->status)
                                    <span class="badge-soft bg-active">
                                        <i class="bi bi-check-circle-fill me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge-soft bg-inactive">
                                        <i class="bi bi-x-circle-fill me-1"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Edit --}}
                                    <a href="{{ route('category.edit', $item->id) }}" 
                                       class="btn-circle btn-edit-soft shadow-sm" 
                                       title="កែសម្រួល">
                                        <i class="bi bi-pencil-fill small"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('category.destroy', $item->id) }}" 
                                          method="POST" 
                                          id="delete-form-{{ $item->id }}" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn-circle btn-delete-soft shadow-sm delete-confirm" 
                                                data-id="{{ $item->id }}" 
                                                data-name="{{ $item->name }}"
                                                title="លុប">
                                            <i class="bi bi-trash-fill small"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-folder2-open display-4 text-light"></i>
                                <p class="text-muted mt-2">មិនមានទិន្នន័យប្រភេទទំនិញឡើយ។</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Footer/Pagination --}}
        <div class="card-footer bg-white border-top py-3">
            <div class="row align-items-center">
                <div class="col-6 small text-muted">
                    បង្ហាញសរុប <strong>{{ count($categories) }}</strong> ប្រភេទ
                </div>
                <div class="col-6 text-end">
                    </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript សម្រាប់ SweetAlert2 (ប្រើ Vanilla JS ដើម្បីការពារ error $) --}}
<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-confirm');
        if (btn) {
            e.preventDefault();
            const name = btn.getAttribute('data-name');
            const id = btn.getAttribute('data-id');
            const form = document.getElementById('delete-form-' + id);

            Swal.fire({
                title: 'លុបប្រភេទនេះ?',
                text: `តើអ្នកប្រាកដថាចង់លុប "${name}" មែនទេ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fa5252',
                cancelButtonColor: '#868e96',
                confirmButtonText: 'យល់ព្រមលុប',
                cancelButtonText: 'បោះបង់',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
</script>
@endsection