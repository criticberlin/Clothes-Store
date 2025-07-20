@extends('layouts.admin')

@section('title', __('Edit Shipping Method'))
@section('description', __('Update shipping method details'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
        <div>
            <a href="{{ route('admin.shipping.methods') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Shipping Methods') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('Edit Shipping Method') }}</h5>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.shipping.methods.update', $method) }}">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $method->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="cost" class="form-label">{{ __('Cost') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">{{ session('currency_code', 'EGP') }}</span>
                            <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{ old('cost', $method->cost) }}" step="0.01" min="0" required>
                        </div>
                        @error('cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $method->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="estimated_delivery_time" class="form-label">{{ __('Estimated Delivery Time') }}</label>
                    <input type="text" class="form-control @error('estimated_delivery_time') is-invalid @enderror" id="estimated_delivery_time" name="estimated_delivery_time" value="{{ old('estimated_delivery_time', $method->estimated_delivery_time) }}" placeholder="e.g. 2-3 business days">
                    @error('estimated_delivery_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $method->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            {{ __('Active') }}
                        </label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.shipping.methods') }}" class="btn btn-outline-secondary me-2">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> {{ __('Update Shipping Method') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 
 