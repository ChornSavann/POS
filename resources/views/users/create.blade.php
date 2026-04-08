@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card card-info card-outline shadow-sm border-0 mt-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus-fill fs-4 text-info me-2"></i>
                            <h5 class="card-title mb-0 fw-bold text-dark">បង្កើតអ្នកប្រើប្រាស់ថ្មី (Create New User)</h5>
                        </div>
                    </div>

                    {{-- ១. បន្ថែម enctype ដើម្បីអាច upload file បាន --}}
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="card-body p-4">
                            <div class="row g-4">

                                {{-- ផ្នែក Upload រូបភាព (Profile Picture) --}}
                                <div class="col-md-12 text-center mb-3">
                                    <label class="form-label fw-semibold d-block text-start">រូបថតផ្ទាល់ខ្លួន</label>
                                    <div class="d-inline-block position-relative">
                                        <img id="preview" src="{{ asset('assets/img/user2-160x160.jpg') }}"
                                             class="rounded-circle shadow-sm border p-1"
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                        <label for="profile_picture" class="btn btn-sm btn-info position-absolute bottom-0 end-0 rounded-circle text-white shadow">
                                            <i class="bi bi-camera-fill"></i>
                                            <input type="file" name="profile_picture" id="profile_picture" class="d-none" accept="image/*">
                                        </label>
                                    </div>
                                    @error('profile_picture')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                    <div class="text-muted small mt-1">ចុចលើរូបតំណាងកាមេរ៉ាដើម្បីប្តូររូបភាព</div>
                                </div>

                                {{-- Full Name --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">ឈ្មោះពេញ</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="បញ្ចូលឈ្មោះពេញ" value="{{ old('name') }}" required />
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">អ៊ីមែល</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="example@mail.com" value="{{ old('email') }}" required />
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">លេខទូរស័ព្ទ</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                        <input type="tel" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="012 345 678" value="{{ old('phone') }}" required />
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Role --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">តួនាទី (Role)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                                        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                            <option selected disabled value="">ជ្រើសរើសតួនាទី...</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->label_kh ?? ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Address --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">អាសយដ្ឋាន</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                                        <input type="text" name="address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            placeholder="បញ្ចូលអាសយដ្ឋានបច្ចុប្បន្ន" value="{{ old('address') }}" required />
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-3 opacity-10">

                                {{-- Password --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">លេខសម្ងាត់</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="••••••••" required />
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Confirm Password --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">បញ្ជាក់លេខសម្ងាត់</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-key-fill"></i></span>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" placeholder="••••••••" required />
                                        <div class="invalid-feedback">សូមបញ្ចូលលេខសម្ងាត់ឱ្យដូចគ្នា។</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-top py-3 d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i> បោះបង់
                            </a>
                            <button class="btn btn-info text-white px-5 shadow-sm" type="submit">
                                <i class="bi bi-save me-1"></i> រក្សាទុកទិន្នន័យ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-outline.card-info { border-top: 4px solid #0dcaf0; }
        .input-group-text { border-right: none; }
        .form-control:focus, .form-select:focus { border-color: #0dcaf0; box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.15); }
        .form-control { border-left: none; }
        #preview { transition: 0.3s; }
        #preview:hover { opacity: 0.8; }
    </style>

    <script>
        // មុខងារ Preview រូបភាព
        document.getElementById('profile_picture').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview');
                output.src = reader.result;
            };
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        });

        // Validation logic
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach((form) => {
                form.addEventListener('submit', (event) => {
                    const password = document.getElementById('password');
                    const confirm = document.getElementById('password_confirmation');

                    if (password.value !== confirm.value) {
                        confirm.setCustomValidity("Passwords do not match");
                    } else {
                        confirm.setCustomValidity("");
                    }

                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endsection
