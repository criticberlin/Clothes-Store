@extends('layouts.admin')

@section('title', __('Add Payment Method'))
@section('description', __('Add a new payment method to the system'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
        <div>
            <a href="{{ route('admin.payment.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Payment Methods') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('Add New Payment Method') }}</h5>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.payment.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="icon" class="form-label">{{ __('Icon') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">bi bi-</span>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', 'credit-card') }}">
                        </div>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">{{ __('Bootstrap icon name (e.g. credit-card, paypal, cash)') }}</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="fee" class="form-label">{{ __('Fee') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ session('currency_code', 'EGP') }}</span>
                        <input type="number" class="form-control @error('fee') is-invalid @enderror" id="fee" name="fee" value="{{ old('fee', 0) }}" step="0.01" min="0">
                    </div>
                    @error('fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">{{ __('Fee charged for using this payment method (0 for free)') }}</small>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            {{ __('Active') }}
                        </label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.payment.index') }}" class="btn btn-outline-secondary me-2">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> {{ __('Save Payment Method') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview icon
        const iconInput = document.getElementById('icon');
        const iconPreview = document.createElement('i');
        iconPreview.className = 'bi bi-' + iconInput.value + ' ms-2 fs-4';
        iconInput.parentNode.appendChild(iconPreview);
        
        iconInput.addEventListener('input', function() {
            iconPreview.className = 'bi bi-' + this.value + ' ms-2 fs-4';
        });
    });
</script>
@endpush 