@extends('layouts.admin')

@section('title', __('general.add_promo_code'))
@section('description', __('general.add_promo_code_description'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
        <div>
            <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> {{ __('general.back_to_promo_codes') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('general.add_promo_code') }}</h5>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.promo-codes.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">{{ __('general.promo_code') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">{{ __('general.promo_code_note') }}</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="description" class="form-label">{{ __('general.description') }}</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">{{ __('general.discount_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>{{ __('general.percentage') }}</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>{{ __('general.fixed_amount') }}</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="value" class="form-label">{{ __('general.discount_value') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" step="0.01" min="0" required>
                            <span class="input-group-text" id="value-addon">
                                <span class="percentage-symbol">%</span>
                                <span class="currency-symbol" style="display: none;">{{ session('currency_code', 'EGP') }}</span>
                            </span>
                        </div>
                        @error('value')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="max_discount_amount" class="form-label">{{ __('general.max_discount_amount') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ session('currency_code', 'EGP') }}</span>
                            <input type="number" class="form-control @error('max_discount_amount') is-invalid @enderror" id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}" step="0.01" min="0">
                        </div>
                        @error('max_discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">{{ __('general.max_discount_amount_note') }}</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="min_order_amount" class="form-label">{{ __('general.min_order_amount') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ session('currency_code', 'EGP') }}</span>
                            <input type="number" class="form-control @error('min_order_amount') is-invalid @enderror" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}" step="0.01" min="0">
                        </div>
                        @error('min_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="usage_limit" class="form-label">{{ __('general.usage_limit') }}</label>
                        <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" min="1">
                        @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">{{ __('general.usage_limit_note') }}</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">{{ __('general.start_date') }}</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">{{ __('general.end_date') }}</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            {{ __('general.active') }}
                        </label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-outline-secondary me-2">
                        {{ __('general.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> {{ __('general.create_promo_code') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const percentageSymbol = document.querySelector('.percentage-symbol');
        const currencySymbol = document.querySelector('.currency-symbol');
        const maxDiscountField = document.getElementById('max_discount_amount').closest('.mb-3');
        
        function updateDiscountType() {
            if (typeSelect.value === 'percentage') {
                percentageSymbol.style.display = 'inline';
                currencySymbol.style.display = 'none';
                maxDiscountField.style.display = 'block';
            } else {
                percentageSymbol.style.display = 'none';
                currencySymbol.style.display = 'inline';
                maxDiscountField.style.display = 'none';
            }
        }
        
        typeSelect.addEventListener('change', updateDiscountType);
        updateDiscountType();
    });
</script>
@endpush 
 