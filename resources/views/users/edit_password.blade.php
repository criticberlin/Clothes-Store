@extends('layouts.master')

@section('title', 'Change Password')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm profile-card">
        <div class="card-header bg-transparent border-0 pt-4 pb-0">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <div>
              <h3 class="fw-bold mb-1">Change Password</h3>
              <p class="text-secondary mb-0">Update your account password securely</p>
            </div>
            <div class="mt-3 mt-md-0">
              <a href="{{ route('profile', $user->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Profile
              </a>
            </div>
          </div>
        </div>
        
        <div class="card-body p-4">
          <!-- Display General Errors -->
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          
          <form action="{{ route('save_password', $user->id) }}" method="post" class="password-form">
            {{ csrf_field() }}

          @php
            $isAdmin = auth()->check() && method_exists(auth()->user(), 'hasPermissionTo') ? auth()->user()->hasPermissionTo('admin_users') : false;
            $isSameUser = auth()->id() == $user->id;
          @endphp

          @if(!$isAdmin || $isSameUser)
              <div class="mb-4">
                <label for="old_password" class="form-label">Current Password</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0">
                    <i class="bi bi-key"></i>
                  </span>
                  <input type="password" id="old_password" class="form-control border-start-0 @error('old_password') is-invalid @enderror" 
                    placeholder="Enter your current password" name="old_password" required>
                </div>
              @error('old_password')
                  <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
          @endif

            <div class="mb-4">
              <label for="password" class="form-label">New Password</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0">
                  <i class="bi bi-lock"></i>
                </span>
                <input type="password" id="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                  placeholder="Enter new password" name="password" required>
              </div>
              <div class="form-text">
                Password must be at least 8 characters and include uppercase, lowercase, numbers, and symbols.
              </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

            <div class="mb-4">
              <label for="password_confirmation" class="form-label">Confirm New Password</label>
              <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0">
                  <i class="bi bi-lock-fill"></i>
                </span>
                <input type="password" id="password_confirmation" class="form-control border-start-0 @error('password_confirmation') is-invalid @enderror" 
                  placeholder="Confirm your new password" name="password_confirmation" required>
              </div>
            @error('password_confirmation')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

            <div class="d-grid gap-2 col-lg-6 mx-auto mt-4">
              <button type="submit" class="btn btn-primary py-2">
                <i class="bi bi-check-circle me-2"></i>Update Password
              </button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .profile-card {
    border-radius: 15px;
    background-color: var(--surface);
    border: 1px solid var(--border);
  }
  
  .input-group-text {
    color: var(--text-secondary);
    border-color: var(--border);
  }
  
  .form-control {
    background-color: var(--surface);
    border-color: var(--border);
    color: var(--text-primary);
  }
  
  .form-text {
    color: var(--text-secondary);
  }
</style>
@endsection
