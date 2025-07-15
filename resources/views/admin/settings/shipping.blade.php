@extends('layouts.admin')

@section('title', __('general.shipping_settings'))
@section('description', __('general.shipping_settings_description'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('general.shipping_settings') }}</h5>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="#">
                @csrf
                <div class="mb-3">
                    <label for="shipping_flat_rate" class="form-label">{{ __('general.flat_rate_shipping') }}</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ session('currency_code', 'EGP') }}</span>
                        <input type="number" class="form-control" id="shipping_flat_rate" name="shipping_flat_rate" value="50" min="0" step="0.01">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="free_shipping_threshold" class="form-label">{{ __('general.free_shipping_threshold') }}</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ session('currency_code', 'EGP') }}</span>
                        <input type="number" class="form-control" id="free_shipping_threshold" name="free_shipping_threshold" value="1000" min="0" step="0.01">
                    </div>
                    <small class="form-text text-muted">{{ __('general.free_shipping_note') }}</small>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>{{ __('general.save_shipping_settings') }}
                </button>
            </form>
        </div>
    </div>
@endsection 