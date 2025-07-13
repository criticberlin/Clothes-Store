@extends('layouts.admin')

@section('title', 'Add New User')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Add New User</h1>
            <p class="text-secondary mb-0">Create a new user account</p>
        </div>
        <div>
            <a href="{{ route('admin.users.list') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>User Information</span>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.users.save', ['user' => 0]) }}">
                @csrf
                
                @foreach($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endforeach
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <label for="address" class="form-label">Address (Optional)</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="roles" class="form-label">Assign Roles</label>
                        <select class="form-select @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Hold Ctrl/Cmd to select multiple roles</div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 