@extends('layouts.master')

@section('title', 'User Profile')

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="mb-2 fw-bold">👤 Profile</h2>
            <p class="text-info mb-0">User Profile Information</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('orders.index') }}" class="btn btn-info px-4">
                <i class="bi bi-bag me-2"></i> My Orders
            </a>
            <a href="{{ route('users_edit', $user->id) }}" class="btn btn-primary px-4">
                <i class="bi bi-pencil-square me-2"></i> Edit Profile
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- User Info Card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 profile-card">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <div class="profile-photo-container mx-auto mb-4">
                            @if($user->profile_photo)
                                <img src="{{ asset('images/users/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="profile-photo">
                            @else
                                <div class="avatar-circle mx-auto">
                            <span class="avatar-text">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                        <p class="text-secondary mb-3">{{ $user->email }}</p>

                        @if($user->email_verified_at)
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i> Verified Account
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                <i class="bi bi-clock me-1"></i> Pending Verification
                            </span>
                        @endif
                    </div>

                    <hr class="my-4">

                    <div class="text-secondary">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-calendar me-2"></i>
                            <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($user->last_login_at)
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-clock-history me-2"></i>
                                <span>Last login {{ $user->last_login_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- User Details Card --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100 profile-card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4 fw-bold">
                        <i class="bi bi-person-lines-fill me-2"></i>User Details
                    </h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-secondary mb-2">Full Name</label>
                                <div class="form-control-plaintext text-info fw-medium">{{ $user->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-secondary mb-2">Email Address</label>
                                <div class="form-control-plaintext text-info fw-medium">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-secondary mb-2">Address</label>
                                <div class="form-control-plaintext text-info fw-medium">{{ $user->address ?? 'Not specified' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="form-label text-secondary mb-2">Phone</label>
                                <div class="form-control-plaintext text-info fw-medium">{{ $user->phone ?? 'Not specified' }}</div>
                            </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-card {
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.profile-photo-container {
    width: 120px;
    height: 120px;
    position: relative;
    margin-bottom: 1.5rem;
}

.profile-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.avatar-circle {
    width: 120px;
    height: 120px;
    background: linear-gradient(45deg, var(--primary), var(--primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.avatar-text {
    color: white;
    font-size: 48px;
    font-weight: bold;
}

.detail-item {
    margin-bottom: 1rem;
}

.detail-item label {
    font-size: 0.9rem;
    font-weight: 500;
}

.form-control-plaintext {
    padding: 0.5rem 0;
    font-size: 1.1rem;
}

/* Dark mode adjustments */
html.theme-dark .profile-card {
    background-color: var(--surface);
    border: 1px solid var(--border);
}

html.theme-dark .form-control-plaintext {
    color: var(--text-primary);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .profile-photo-container, .profile-photo, .avatar-circle {
        width: 100px;
        height: 100px;
    }
    
    .avatar-text {
        font-size: 40px;
    }
}
</style>
@endsection
