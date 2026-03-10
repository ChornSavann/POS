@extends('layout.app')
@section('content')
<div class="card card-info card-outline mb-4">
    <div class="card-header"><div class="card-title">Edit User: {{ $user->name }}</div></div>
    
    <form action="{{ route('users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Password (ទុកទទេបើមិនចង់ប្តូរ)</label>
                    <input type="password" name="password" class="form-control" placeholder="******" />
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-info text-white" type="submit">រក្សាទុកការផ្លាស់ប្តូរ</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">បោះបង់</a>
        </div>
    </form>
</div>
@endsection