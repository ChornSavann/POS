@extends('layout.app')
@section('content')
  <!--begin::Form Validation-->
  <div class="card card-info card-outline mb-4">
    <div class="card-header"><div class="card-title">Create New User</div></div>
    
    <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required />
                    <div class="invalid-feedback"></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required />
                    <div class="invalid-feedback"></div>
                </div>
                 <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control" required />
                    <div class="invalid-feedback"></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required />
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" required />
                    <div class="invalid-feedback"></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option selected disabled value="">Choose...</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-info text-white" type="submit">Save User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    // JavaScript សម្រាប់ Validation ទុកដដែលដូចដែលអ្នកបានផ្ដល់ឱ្យ
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
                <!--end::Form Validation-->
@endsection


               