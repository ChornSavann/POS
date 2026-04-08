@extends('layout.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">គ្រប់គ្រងតួនាទី</h4>
                <p class="text-muted small mb-0">គ្រប់គ្រងកម្រិតសិទ្ធិប្រើប្រាស់របស់បុគ្គលិកក្នុងប្រព័ន្ធ</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('roles.create') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
                    <i class="fas fa-plus-circle me-2"></i>បង្កើតតួនាទីថ្មី
                </a>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-list me-2"></i>បញ្ជីតួនាទី</h6>
                <div class="input-group input-group-sm" style="max-width: 250px;">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="roleSearch" class="form-control bg-light border-start-0"
                        placeholder="ស្វែងរកតួនាទី...">
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="roleTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-bold text-uppercase" style="width: 30%">ឈ្មោះតួនាទី
                                </th>
                                <th class="py-3 text-muted small fw-bold text-uppercase" style="width: 25%">
                                    ឈ្មោះក្នុងប្រព័ន្ធ</th>
                                <th class="py-3 text-muted small fw-bold text-uppercase text-center" style="width: 20%">
                                    ចំនួនសិទ្ធិ</th>
                                <th class="pe-4 py-3 text-muted small fw-bold text-uppercase text-end" style="width: 25%">
                                    សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 35px; height: 35px;">
                                                <i class="fas fa-user-shield"></i>
                                            </div>
                                            <span class="fw-bold text-dark">{{ $role->label_kh }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border px-2 py-1 font-monospace">
                                            {{ $role->name }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-3">
                                            {{ $role->permissions_count ?? 0 }} សិទ្ធិ
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group shadow-sm rounded-2 overflow-hidden" role="group">
                                            <button type="button"
                                                class="btn btn-sm btn-white border-end view-role-btn text-info"
                                                data-id="{{ $role->id }}" data-bs-toggle="tooltip" title="មើលលម្អិត">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <a href="{{ route('roles.edit', $role->id) }}"
                                                class="btn btn-sm btn-white border-end text-warning"
                                                data-bs-toggle="tooltip" title="កែសម្រួល">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- ការពារមិនឱ្យលុប Role Admin --}}
                                            @if ($role->name !== 'admin')
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="btn btn-sm btn-white text-danger delete-btn"
                                                        data-bs-toggle="tooltip" title="លុប">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-white text-muted" disabled
                                                    data-bs-toggle="tooltip" title="មិនអាចលុបតួនាទីប្រព័ន្ធបានទេ">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-5 text-center">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">មិនទាន់មានទិន្នន័យតួនាទីនៅឡើយទេ</p>
                                            <a href="{{ route('roles.create') }}"
                                                class="btn btn-sm btn-primary mt-2">បង្កើតឥឡូវនេះ</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($roles->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal View Detail --}}
    <div class="modal fade" id="viewRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white border-bottom-0 py-3">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-shield me-2"></i>ព័ត៌មានតួនាទីលម្អិត</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="roleDetailsContent">
                        {{-- Content នឹងត្រូវបញ្ចូលតាមរយៈ AJAX --}}
                    </div>
                </div>
                <div class="modal-footer border-top-0 py-3">
                    <button type="button" class="btn btn-light px-4 fw-bold rounded-pill shadow-sm"
                        data-bs-dismiss="modal">បិទ</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-white {
            background: #fff;
            border: 1px solid #eee;
        }

        .btn-white:hover {
            background: #f8f9fa;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.5rem;
        }

        tbody tr {
            transition: all 0.2s;
        }

        tbody tr:hover {
            background-color: #f8f9ff !important;
        }

        .bg-primary-subtle {
            background-color: #eef2ff !important;
        }

        /* Custom Scrollbar for Permissions List */
        .role-permissions-list::-webkit-scrollbar {
            width: 6px;
        }

        .role-permissions-list::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ១. មុខងារស្វែងរក (Smart Search)
            const searchInput = document.getElementById('roleSearch');
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#roleTable tbody tr');
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });

            // ២. SweetAlert2 Delete
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'តើបងពិតជាចង់លុបមែនទេ?',
                        text: "រាល់សិទ្ធិទាំងអស់ក្នុងតួនាទីនេះនឹងត្រូវលុបចេញ!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'បាទ, លុបវា!',
                        cancelButtonText: 'បោះបង់',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            // ៣. AJAX View Role
            $(document).on('click', '.view-role-btn', function() {
                const id = $(this).data('id');
                const modal = new bootstrap.Modal(document.getElementById('viewRoleModal'));

                $('#roleDetailsContent').html(
                    '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>'
                    );
                modal.show();

                $.get(`/roles/${id}`, function(data) {
                    let grouped = {};
                    data.permissions.forEach(p => {
                        let g = p.group_name || 'ផ្សេងៗ';
                        if (!grouped[g]) grouped[g] = [];
                        grouped[g].push(p);
                    });

                    let permHtml = '';
                    for (let g in grouped) {
                        permHtml += `<div class="mb-3">
                            <h6 class="fw-bold small text-primary mb-2"><i class="fas fa-folder me-2"></i>ផ្នែក៖ ${g}</h6>
                            <div class="d-flex flex-wrap gap-2 ps-3">
                                ${grouped[g].map(p => `<span class="badge bg-white text-dark border shadow-sm px-2 py-2 fw-normal rounded-2"><i class="fas fa-check-circle text-success me-1"></i>${p.label_kh}</span>`).join('')}
                            </div>
                        </div>`;
                    }

                    $('#roleDetailsContent').html(`
                        <div class="bg-light p-4 border-bottom mb-4">
                            <div class="row">
                                <div class="col-6"><label class="small text-muted fw-bold d-block">ឈ្មោះខ្មែរ</label><span class="h5 fw-bold">${data.label_kh}</span></div>
                                <div class="col-6 text-end"><label class="small text-muted fw-bold d-block">ឈ្មោះក្នុងប្រព័ន្ធ</label><span class="badge bg-white text-primary border border-primary font-monospace">${data.name}</span></div>
                            </div>
                        </div>
                        <div class="px-4 pb-4">
                            <label class="small text-muted fw-bold text-uppercase mb-3"><i class="fas fa-shield-alt me-1 text-info"></i>សិទ្ធិប្រើប្រាស់ (${data.permissions.length})</label>
                            <div class="role-permissions-list" style="max-height: 350px; overflow-y: auto;">
                                ${permHtml || '<p class="text-center py-4 text-muted">មិនទាន់មានសិទ្ធិ</p>'}
                            </div>
                        </div>
                    `);
                }).fail(() => {
                    $('#roleDetailsContent').html(
                        '<div class="alert alert-danger m-4">មិនអាចទាញទិន្នន័យបានទេ!</div>');
                });
            });

            // ៤. Tooltip
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(el => new bootstrap.Tooltip(
                el));
        });
    </script>
@endpush
