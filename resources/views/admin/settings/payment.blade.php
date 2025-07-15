@extends('layouts.admin')

@section('title', __('general.payment_settings'))
@section('description', __('general.payment_settings_description'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('general.payment_settings') }}</h5>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="#">
                @csrf
                <div class="mb-4">
                    <label class="form-label">{{ __('general.payment_methods') }}</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="payment_stripe" name="payment_methods[]" value="stripe" checked>
                        <label class="form-check-label" for="payment_stripe">
                            <i class="bi bi-credit-card me-2"></i> {{ __('general.credit_card_stripe') }}
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="payment_paypal" name="payment_methods[]" value="paypal" checked>
                        <label class="form-check-label" for="payment_paypal">
                            <i class="bi bi-paypal me-2"></i> {{ __('general.paypal') }}
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="payment_cod" name="payment_methods[]" value="cod">
                        <label class="form-check-label" for="payment_cod">
                            <i class="bi bi-cash me-2"></i> {{ __('general.cash_on_delivery') }}
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="tax_rate" class="form-label">{{ __('general.tax_rate') }}</label>
                    <input type="number" class="form-control" id="tax_rate" name="tax_rate" value="7.5" step="0.01" min="0">
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>{{ __('general.save_payment_settings') }}
                </button>
            </form>
        </div>
    </div>
@endsection 