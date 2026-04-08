@extends('layout.app')

{{-- ការកំណត់ Sidebar Active State --}}
@section('user_menu_open', 'menu-open')
@section('user_parent_active', 'active')
@section('user_list_active', 'active') {{-- ឧបមាថាបងប្រើ list_active សម្រាប់ទំព័រនេះ --}}

@section('content')
    <div class="app-content mb-3 p-0">
        <div class="container-fluid">
            <div class="header-card d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 bg-white rounded shadow-sm"
                style="border-left: 5px solid #f59e0b;">

                <div class="d-flex align-items-center">
                    <div class="icon-wrapper me-3 bg-warning bg-opacity-10 p-3 rounded-circle"
                        style="background-color: rgba(245, 158, 11, 0.1);">
                        <i class="bi bi-person-gear text-warning fs-4" style="color: #f59e0b;"></i>
                    </div>
                    <div>
                        <h4 class="mb-1 fw-bold text-dark">គ្រប់គ្រងអ្នកប្រើប្រាស់ (User Management)</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb small mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                                        class="text-decoration-none text-muted">Dashboard</a></li>
                                <li class="breadcrumb-item active fw-semibold" style="color: #f59e0b;">User List</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('users.create') }}" class="btn px-4 fw-bold shadow-sm py-2 text-white"
                        style="border-radius: 10px; background-color: #f59e0b; border: none;">
                        <i class="bi bi-person-plus-fill me-1"></i> Add New User
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid ">

        {{-- Main Table Card --}}
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold">User List</h5>
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" class="form-control" placeholder="Search users...">
                        <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted uppercase small">
                            <tr>
                                <th class="ps-4" style="width: 50px">#</th>
                                <th>User Info</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $key => $user)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $key + 1 }}.</td>
                                 
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- បង្ហាញរូបភាព Profile --}}
                                            <div class="avatar-sm me-3">
                                                @if ($user->profile_picture)
                                                    <img src="{{ asset('Image/users-image/' . $user->profile_picture) }}"
                                                        class="rounded-circle shadow-sm border"
                                                        style="width: 40px; height: 40px; object-fit: cover;"
                                                        alt="{{ $user->name }}">
                                                @else
                                                    {{-- បើគ្មានរូបភាព ឱ្យបង្ហាញអក្សរដំបូងនៃឈ្មោះជំនួសវិញ (Style ចាស់របស់បង) --}}
                                                    <div class="bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center border"
                                                        style="width: 40px; height: 40px;">
                                                        <span class="text-primary fw-bold">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div>
                                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{-- កែពី $user->role ទៅជា $user->userRole --}}
                                        @if ($user->userRole)
                                            @php
                                                $badgeClass = match ($user->userRole->name) {
                                                    'admin' => 'bg-danger-subtle text-danger',
                                                    'cashier' => 'bg-info-subtle text-info',
                                                    default => 'bg-secondary-subtle text-secondary',
                                                };
                                            @endphp

                                            <span class="badge rounded-pill {{ $badgeClass }} px-3">
                                                <i class="bi bi-shield-lock me-1"></i>
                                                {{ $user->userRole->label_kh ?? ucfirst($user->userRole->name) }}
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-light text-dark px-3">
                                                <i class="bi bi-person me-1"></i> មិនទាន់កំណត់
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- កែពី $users មក $user វិញ --}}
                                        @if ($user->is_active)
                                            <span class="badge bg-success-subtle text-success px-3">
                                                <i class="bi bi-check-circle-fill me-1"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger px-3">
                                                <i class="bi bi-x-circle-fill me-1"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-light btn-sm text-warning border" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                id="delete-form-{{ $user->id }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-light btn-sm text-danger border delete-confirm"
                                                    data-id="{{ $user->id }}" title="Delete">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-people mb-2 d-block fs-1"></i>
                                        No users found in the database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Placeholder --}}
            {{-- @if ($users->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $users->links() }}
        </div>
        @endif --}}
        </div>
    </div>

    {{-- Inline Style សម្រាប់បន្ថែមភាព Soft --}}
    <style>
        .avatar-sm {
            font-size: 14px;
        }

        .table thead th {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .bg-primary-subtle {
            background-color: #e7f1ff;
        }

        .bg-success-subtle {
            background-color: #d1e7dd;
        }

        .bg-danger-subtle {
            background-color: #f8d7da;
        }

        .bg-secondary-subtle {
            background-color: #e2e3e5;
        }

        .btn-light:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
    </style>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // ប្រើ Event Delegation ដើម្បីឱ្យវាដើរទោះបីជា Table ត្រូវបាន Reload ដោយ Ajax ក៏ដោយ
            $(document).on('click', '.delete-confirm', function(e) {
                e.preventDefault();

                let id = $(this).data('id');
                let form = $('#delete-form-' + id);

                Swal.fire({
                    title: 'តើអ្នកប្រាកដទេ?',
                    text: "ទិន្នន័យនេះនឹងត្រូវលុប ហើយមិនអាចទាញយកមកវិញបានឡើយ!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // ពណ៌ក្រហមសម្រាប់ប៊ូតុងលុប
                    cancelButtonColor: '#6c757d', // ពណ៌ប្រផេះសម្រាប់ប៊ូតុងបោះបង់
                    confirmButtonText: '<i class="bi bi-trash"></i> លុបចេញ',
                    cancelButtonText: 'បោះបង់',
                    reverseButtons: true, // ដាក់ប៊ូតុងបោះបង់នៅខាងឆ្វេង
                    customClass: {
                        confirmButton: 'btn btn-danger px-4',
                        cancelButton: 'btn btn-light px-4 border'
                    },
                    buttonsStyling: false // ប្រើ Class របស់ Bootstrap ជំនួសវិញ
                }).then((result) => {
                    if (result.isConfirmed) {
                        // បង្ហាញ Loading ពេលកំពុង Submit
                        Swal.fire({
                            title: 'កំពុងលុប...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
