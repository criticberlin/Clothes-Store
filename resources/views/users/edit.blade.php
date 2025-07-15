@extends('layouts.master')
@section('title', 'Edit User Profile')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit User Profile</h2>
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

    <div class="card">
        <div class="card-body">
            <form action="{{ route('users_save', $user->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="row g-3">
                    <!-- Profile Photo -->
                    <div class="col-12 text-center mb-3">
                        <div class="profile-photo-container mb-3">
                            @if($user->profile_photo)
                                <img src="{{ asset('images/users/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="profile-photo rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/users/default-avatar.png') }}" alt="Default Avatar" class="profile-photo rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" accept="image/*">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a square image for best results. Max 2MB.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Full name"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            placeholder="Email address"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address"
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            rows="3"
                            placeholder="Your address here...">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Change Section -->
                    <div class="col-12 mt-4">
                        <h5>Password Management</h5>
                        <hr>
                        <div class="d-grid gap-2 col-md-6 mx-auto">
                            <a href="{{ route('edit_password', $user->id) }}" class="btn btn-primary">
                                <i class="bi bi-lock me-2"></i> Change Password
                            </a>
                            <p class="text-center text-muted small mt-2">
                                Click the button above to change your password securely
                            </p>
                        </div>
                    </div>

                    @can('admin_users')
                    <div class="col-12 mt-3">
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
                        @error('roles')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    @endcan
                </div>

                <div class="d-flex justify-content-end mt-4 gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save Changes
                    </button>

                    <a href="{{ url()->previous() }}" class="btn btn-outline-light">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </form>
            
            <!-- Account Deletion Section -->
            @if(auth()->id() == $user->id || auth()->user()->hasPermissionTo('delete_users'))
                <div class="mt-5">
                    <h5 class="text-danger">Delete Account</h5>
                    <hr>
                    <p>This action cannot be undone. All your data will be permanently removed.</p>
                    
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash me-1"></i> Delete Account
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users_delete', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this account? This action cannot be undone.</p>
                    
                    @if(auth()->id() == $user->id)
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Enter your password to confirm</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
