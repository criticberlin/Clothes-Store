@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Edit User</h1>
            <p class="text-secondary mb-0">Modify user account details</p>
        </div>
        <div>
            <a href="{{ route('admin.users.list') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Users
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="admin-card">
        <div class="admin-card-header">
            <span>User Information</span>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.users.save', $user) }}">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                @can('admin_users')
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Roles</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        id="role_{{ $role->name }}"
                                        {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->name }}">
                                        <span class="badge bg-{{ $role->name === 'admin' ? 'danger' : 'primary' }}">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Direct Permissions</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($permissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        id="permission_{{ $permission->name }}"
                                        {{ in_array($permission->name, old('permissions', $user->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->name }}">
                                        {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endcan
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('edit_password', $user->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-key me-2"></i> Change Password
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 