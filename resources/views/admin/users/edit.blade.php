@extends('layouts.admin')

@section('title', isset($user->id) ? 'Edit User' : 'Add New User')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.list') }}">Users</a></li>
    <li class="breadcrumb-item active">{{ isset($user->id) ? 'Edit User' : 'Add New User' }}</li>
@endsection

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">{{ isset($user->id) ? 'Edit User' : 'Add New User' }}</h1>
            <p class="text-secondary mb-0">{{ isset($user->id) ? 'Update user information' : 'Create a new user account' }}</p>
        </div>
        <div>
            <a href="{{ route('admin.users.list') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <span>User Information</span>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="{{ isset($user->id) ? route('admin.users.save', $user) : route('admin.users.save', 0) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">{{ isset($user->id) ? 'New Password (leave blank to keep current)' : 'Password' }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{ isset($user->id) ? '' : 'required' }}>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ isset($user->id) ? '' : 'required' }}>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="roles" class="form-label">User Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" 
                                            {{ (isset($user) && $user->roles->contains($role->id)) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i> {{ isset($user->id) ? 'Update User' : 'Save User' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>Profile Photo</span>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="{{ isset($user->id) ? route('admin.users.save', $user) : route('admin.users.save', 0) }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="text-center mb-4">
                            @if(isset($user) && $user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="rounded-circle img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="avatar-placeholder mb-3">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="dropzone-container" id="profilePhotoDropzone">
                            <div class="dropzone-prompt">
                                <i class="bi bi-cloud-arrow-up fs-3 mb-2"></i>
                                <p class="mb-0">Drag and drop your image here, or click to browse</p>
                                <p class="small text-muted">Supports: JPG, PNG, GIF (Max 2MB)</p>
                            </div>
                            <div class="dropzone-preview" style="display: none;">
                                <div class="image-preview"></div>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-image">
                                    <i class="bi bi-trash"></i> Remove Image
                                </button>
                            </div>
                            <input type="file" class="form-control d-none @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" accept="image/*">
                        </div>
                        @error('profile_photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-upload me-2"></i> Upload Photo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .dropzone-container {
        border: 2px dashed var(--border);
        border-radius: var(--radius-md);
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: var(--surface-alt);
    }
    
    .dropzone-container:hover, .dropzone-container.dragover {
        border-color: var(--primary);
        background-color: rgba(127, 90, 240, 0.05);
    }
    
    .dropzone-prompt {
        color: var(--text-secondary);
    }
    
    .image-preview {
        max-width: 100%;
        height: 150px;
        margin: 0 auto;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .avatar-placeholder {
        width: 150px;
        height: 150px;
        background-color: var(--surface-alt);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .avatar-placeholder i {
        font-size: 4rem;
        color: var(--text-secondary);
    }
</style>
@endpush 

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dropzone functionality
        const dropzone = document.getElementById('profilePhotoDropzone');
        const fileInput = dropzone.querySelector('input[type="file"]');
        const preview = dropzone.querySelector('.dropzone-preview');
        const imagePreview = preview.querySelector('.image-preview');
        const prompt = dropzone.querySelector('.dropzone-prompt');
        const removeButton = dropzone.querySelector('.remove-image');
        
        // Handle click on dropzone
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Handle file selection
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Check file type
                if (!file.type.match('image.*')) {
                    alert('Please select an image file');
                    return;
                }
                
                // Check file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should not exceed 2MB');
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.style.backgroundImage = `url(${e.target.result})`;
                    prompt.style.display = 'none';
                    preview.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        // Handle drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, function() {
                this.classList.add('dragover');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, function() {
                this.classList.remove('dragover');
            }, false);
        });
        
        dropzone.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            if (files && files.length) {
                fileInput.files = files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }, false);
        
        // Handle remove button
        removeButton.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.value = '';
            imagePreview.style.backgroundImage = '';
            prompt.style.display = 'block';
            preview.style.display = 'none';
        });
    });
</script>
@endpush 