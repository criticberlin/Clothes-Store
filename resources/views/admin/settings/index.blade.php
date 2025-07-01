@extends('layouts.admin')

@section('title', __('Store Settings'))

@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('Settings') }}</li>
@endsection

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">{{ __('Store Settings') }}</h1>
            <p class="text-secondary mb-0">{{ __('Configure your store settings and preferences') }}</p>
        </div>
    </div>

    <!-- Settings Tabs -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('section') != 'currencies' ? 'active' : '' }}" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="true">
                <i class="bi bi-gear me-2"></i>{{ __('General Settings') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('section') == 'currencies' ? 'active' : '' }}" id="currencies-tab" data-bs-toggle="tab" data-bs-target="#currencies" type="button" role="tab" aria-selected="false">
                <i class="bi bi-currency-exchange me-2"></i>{{ __('Currencies') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-selected="false">
                <i class="bi bi-credit-card me-2"></i>{{ __('Payment') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-selected="false">
                <i class="bi bi-truck me-2"></i>{{ __('Shipping') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-selected="false">
                <i class="bi bi-envelope me-2"></i>{{ __('Email') }}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="settingsTabsContent">
        <!-- General Settings Tab -->
        <div class="tab-pane fade {{ request('section') != 'currencies' ? 'show active' : '' }}" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>{{ __('General Settings') }}</span>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_name" class="form-label">{{ __('Store Name') }}</label>
                                    <input type="text" class="form-control" id="store_name" name="store_name" value="MyClothes Store">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_email" class="form-label">{{ __('Store Email') }}</label>
                                    <input type="email" class="form-control" id="store_email" name="store_email" value="info@myclothes.com">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_phone" class="form-label">{{ __('Store Phone') }}</label>
                                    <input type="text" class="form-control" id="store_phone" name="store_phone" value="+1 (555) 123-4567">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_logo" class="form-label">{{ __('Store Logo') }}</label>
                                    <input type="file" class="form-control" id="store_logo" name="store_logo">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="store_address" class="form-label">{{ __('Store Address') }}</label>
                            <textarea class="form-control" id="store_address" name="store_address" rows="3">123 Fashion Street, New York, NY 10001</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>{{ __('Save General Settings') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Currencies Tab -->
        <div class="tab-pane fade {{ request('section') == 'currencies' ? 'show active' : '' }}" id="currencies" role="tabpanel" aria-labelledby="currencies-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="mb-0">{{ __('Manage Currencies and Exchange Rates') }}</h5>
                </div>
                <div class="admin-card-body">
                    <p class="mb-4">
                        {{ __('Update exchange rates or set the default currency for your store. The base currency (EGP) has an exchange rate of 1.') }}
                    </p>

                    <form action="{{ route('admin.currencies.update') }}" method="POST">
                        @csrf
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Default') }}</th>
                                        <th>{{ __('Code') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Symbol') }}</th>
                                        <th>{{ __('Exchange Rate') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currencies as $currency)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="radio" name="default_currency" value="{{ $currency->id }}" class="form-check-input" {{ $currency->is_default ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="hidden" name="currencies[{{ $loop->index }}][id]" value="{{ $currency->id }}">
                                                <strong>{{ $currency->code }}</strong>
                                            </td>
                                            <td>{{ $currency->name }}</td>
                                            <td>{{ $currency->symbol }}</td>
                                            <td>
                                                <input type="number" name="currencies[{{ $loop->index }}][exchange_rate]" value="{{ $currency->exchange_rate }}" step="0.000001" min="0.000001" class="form-control form-control-sm" {{ $currency->is_default ? 'readonly' : '' }}>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" name="currencies[{{ $loop->index }}][is_active]" value="1" class="form-check-input" {{ $currency->is_active ? 'checked' : '' }} {{ $currency->is_default ? 'disabled' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>{{ __('Update Currency Settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="admin-card mt-4">
                <div class="admin-card-header">
                    <h5 class="mb-0">{{ __('Currency Display Guide') }}</h5>
                </div>
                <div class="admin-card-body">
                    <p class="mb-2">{{ __('For your reference, here is how prices will be displayed in different currencies:') }}</p>
                    
                    <div class="row">
                        @foreach($currencies as $currency)
                            @if($currency->is_active)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6>{{ $currency->name }} ({{ $currency->code }})</h6>
                                            <p class="mb-1">{{ __('Symbol') }}: <strong>{{ $currency->symbol }}</strong></p>
                                            <p class="mb-1">{{ __('Exchange Rate') }}: <strong>1 EGP = {{ number_format(1 / $currency->exchange_rate, 4) }} {{ $currency->code }}</strong></p>
                                            <p class="mb-0">{{ __('Example') }}: <strong>{{ $currency->symbol }}{{ number_format(1000 * $currency->exchange_rate, 2) }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Settings Tab -->
        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>{{ __('Payment Settings') }}</span>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="#">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">{{ __('Payment Methods') }}</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="payment_stripe" name="payment_methods[]" value="stripe" checked>
                                <label class="form-check-label" for="payment_stripe">
                                    <i class="bi bi-credit-card me-2"></i> {{ __('Credit Card (Stripe)') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="payment_paypal" name="payment_methods[]" value="paypal" checked>
                                <label class="form-check-label" for="payment_paypal">
                                    <i class="bi bi-paypal me-2"></i> {{ __('PayPal') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="payment_cod" name="payment_methods[]" value="cod">
                                <label class="form-check-label" for="payment_cod">
                                    <i class="bi bi-cash me-2"></i> {{ __('Cash on Delivery') }}
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tax_rate" class="form-label">{{ __('Tax Rate (%)') }}</label>
                            <input type="number" class="form-control" id="tax_rate" name="tax_rate" value="7.5" step="0.01" min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>{{ __('Save Payment Settings') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Shipping Settings Tab -->
        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>{{ __('Shipping Settings') }}</span>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="#">
                        @csrf
                        <div class="mb-3">
                            <label for="shipping_method" class="form-label">{{ __('Default Shipping Method') }}</label>
                            <select class="form-select" id="shipping_method" name="shipping_method">
                                <option value="flat_rate" selected>{{ __('Flat Rate') }}</option>
                                <option value="free_shipping">{{ __('Free Shipping') }}</option>
                                <option value="local_pickup">{{ __('Local Pickup') }}</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="flat_rate_cost" class="form-label">{{ __('Flat Rate Cost') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ session('currency_code', 'EGP') }}</span>
                                <input type="number" class="form-control" id="flat_rate_cost" name="flat_rate_cost" value="5.99" step="0.01" min="0">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="free_shipping_min" class="form-label">{{ __('Free Shipping Minimum Order') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ session('currency_code', 'EGP') }}</span>
                                <input type="number" class="form-control" id="free_shipping_min" name="free_shipping_min" value="50.00" step="0.01" min="0">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>{{ __('Save Shipping Settings') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Email Settings Tab -->
        <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <span>{{ __('Email Settings') }}</span>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="#">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_from" class="form-label">{{ __('From Email Address') }}</label>
                                    <input type="email" class="form-control" id="email_from" name="email_from" value="noreply@myclothes.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_from_name" class="form-label">{{ __('From Name') }}</label>
                                    <input type="text" class="form-control" id="email_from_name" name="email_from_name" value="MyClothes Store">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">{{ __('Email Notifications') }}</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_new_order" name="email_notifications[]" value="new_order" checked>
                                <label class="form-check-label" for="notify_new_order">
                                    {{ __('New Order Notification') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_new_customer" name="email_notifications[]" value="new_customer" checked>
                                <label class="form-check-label" for="notify_new_customer">
                                    {{ __('New Customer Registration') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_low_stock" name="email_notifications[]" value="low_stock">
                                <label class="form-check-label" for="notify_low_stock">
                                    {{ __('Low Stock Alert') }}
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>{{ __('Save Email Settings') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 