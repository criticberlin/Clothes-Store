@extends('layouts.admin')

@section('title', __('general.store_settings'))
@section('description', __('general.store_settings_description'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
    </div>

    <!-- Settings Tabs -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('section') != 'currencies' ? 'active' : '' }}" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-selected="true">
                <i class="bi bi-gear me-2"></i>{{ __('general.general_settings') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request('section') == 'currencies' ? 'active' : '' }}" id="currencies-tab" data-bs-toggle="tab" data-bs-target="#currencies" type="button" role="tab" aria-selected="false">
                <i class="bi bi-currency-exchange me-2"></i>{{ __('general.currencies') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab" aria-selected="false">
                <i class="bi bi-palette me-2"></i>{{ __('general.appearance') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="localization-tab" data-bs-toggle="tab" data-bs-target="#localization" type="button" role="tab" aria-selected="false">
                <i class="bi bi-globe me-2"></i>{{ __('general.localization') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-selected="false">
                <i class="bi bi-credit-card me-2"></i>{{ __('general.payment') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-selected="false">
                <i class="bi bi-truck me-2"></i>{{ __('general.shipping') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-selected="false">
                <i class="bi bi-envelope me-2"></i>{{ __('general.email') }}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="settingsTabsContent">
        <!-- General Settings Tab -->
        <div class="tab-pane fade {{ request('section') != 'currencies' ? 'show active' : '' }}" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="mb-0">{{ __('general.store_information') }}</h5>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_name" class="form-label">{{ __('general.store_name') }}</label>
                                    <input type="text" class="form-control" id="store_name" name="store_name" value="MyClothes Store">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_email" class="form-label">{{ __('general.store_email') }}</label>
                                    <input type="email" class="form-control" id="store_email" name="store_email" value="info@myclothes.com">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_phone" class="form-label">{{ __('general.store_phone') }}</label>
                                    <input type="text" class="form-control" id="store_phone" name="store_phone" value="+1 (555) 123-4567">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="store_logo" class="form-label">{{ __('general.store_logo') }}</label>
                                    <input type="file" class="form-control" id="store_logo" name="store_logo">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="store_address" class="form-label">{{ __('general.store_address') }}</label>
                            <textarea class="form-control" id="store_address" name="store_address" rows="3">123 Fashion Street, New York, NY 10001</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>{{ __('general.save') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Currencies Tab -->
        <div class="tab-pane fade {{ request('section') == 'currencies' ? 'show active' : '' }}" id="currencies" role="tabpanel" aria-labelledby="currencies-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="mb-0">{{ __('general.manage_currencies') }}</h5>
                </div>
                <div class="admin-card-body">
                    <p class="mb-4">
                        {{ __('general.currencies_description') }}
                    </p>

                    <form action="{{ route('admin.currencies.update') }}" method="POST">
                        @csrf
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('general.default') }}</th>
                                        <th>{{ __('general.code') }}</th>
                                        <th>{{ __('general.name') }}</th>
                                        <th>{{ __('general.symbol') }}</th>
                                        <th>{{ __('general.exchange_rate') }}</th>
                                        <th>{{ __('general.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currencies as $currency)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="radio" name="default_currency" value="{{ $currency->id }}" class="form-check-input" {{ $currency->is_default ? 'checked' : '' }}>
                                                    <input type="hidden" name="currencies[{{ $loop->index }}][id]" value="{{ $currency->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $currency->code }}</strong>
                                            </td>
                                            <td>{{ $currency->name }}</td>
                                            <td>{{ $currency->symbol }}</td>
                                            <td>
                                                <input type="number" name="currencies[{{ $loop->index }}][exchange_rate]" value="{{ $currency->exchange_rate }}" step="0.000001" min="0.000001" class="form-control form-control-sm" {{ $currency->is_default ? 'readonly' : '' }}>
                                                @if($currency->is_default)
                                                    <small class="text-muted">{{ __('general.base_currency') }}</small>
                                                @endif
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
                                <i class="bi bi-save me-2"></i>{{ __('general.update_currency_settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="admin-card mt-4">
                <div class="admin-card-header">
                    <h5 class="mb-0">{{ __('general.currency_display_guide') }}</h5>
                </div>
                <div class="admin-card-body">
                    <p class="mb-2">{{ __('general.currency_display_guide_description') }}</p>
                    
                    <div class="row">
                        @foreach($currencies as $currency)
                            @if($currency->is_active)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6>{{ $currency->name }} ({{ $currency->code }})</h6>
                                            <p class="mb-1">{{ __('general.symbol') }}: <strong>{{ $currency->symbol }}</strong></p>
                                            @php
                                                $baseRate = 1;
                                                $baseCurrency = $currencies->where('is_default', true)->first();
                                                
                                                if ($baseCurrency) {
                                                    if ($currency->is_default) {
                                                        $rate = 1;
                                                        $inverseRate = 1;
                                                    } else {
                                                        $rate = $currency->exchange_rate;
                                                        $inverseRate = 1 / $rate;
                                                    }
                                                    
                                                    $baseCode = $baseCurrency->code;
                                                } else {
                                                    $rate = $currency->exchange_rate;
                                                    $inverseRate = 1 / $rate;
                                                    $baseCode = 'EGP';
                                                }
                                            @endphp
                                            <p class="mb-1">{{ __('general.exchange_rate') }}: <strong>1 {{ $baseCode }} = {{ number_format($rate, 4) }} {{ $currency->code }}</strong></p>
                                            <p class="mb-1">{{ __('general.inverse_rate') }}: <strong>1 {{ $currency->code }} = {{ number_format($inverseRate, 4) }} {{ $baseCode }}</strong></p>
                                            <p class="mb-0">{{ __('general.example') }}: <strong>{{ $currency->symbol }}{{ number_format(1000 * $rate, 2) }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Appearance Settings Tab -->
        <div class="tab-pane fade" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="mb-0">{{ __('general.appearance_settings') }}</h5>
                </div>
                <div class="admin-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label">{{ __('general.theme') }}</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="theme_mode" id="theme_light" value="light" {{ session('theme_mode', 'dark') == 'light' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="theme_light">
                                            <i class="bi bi-sun me-2"></i>{{ __('general.light_mode') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="theme_mode" id="theme_dark" value="dark" {{ session('theme_mode', 'dark') == 'dark' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="theme_dark">
                                            <i class="bi bi-moon-stars me-2"></i>{{ __('general.dark_mode') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">{{ __('general.theme_preview') }}</label>
                                <div class="theme-preview p-4 border rounded">
                                    <div class="theme-preview-header mb-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0">{{ __('general.preview_title') }}</h5>
                                            <small class="text-muted">{{ __('general.preview_subtitle') }}</small>
                                        </div>
                                        <button class="btn btn-sm btn-primary">{{ __('general.button') }}</button>
                                    </div>
                                    <div class="theme-preview-content">
                                        <p>{{ __('general.preview_text') }}</p>
                                        <div class="d-flex gap-2">
                                            <div class="theme-preview-card p-2 border rounded text-center">
                                                <div>{{ __('general.item') }} 1</div>
                                            </div>
                                            <div class="theme-preview-card p-2 border rounded text-center">
                                                <div>{{ __('general.item') }} 2</div>
                                            </div>
                                            <div class="theme-preview-card p-2 border rounded text-center">
                                                <div>{{ __('general.item') }} 3</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('preferences.theme') }}" method="POST">
                        @csrf
                        <input type="hidden" name="theme" id="theme_setting_input" value="{{ session('theme_mode', 'dark') }}">
                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                        <button type="submit" class="btn btn-primary" id="save_theme_btn">
                            <i class="bi bi-save me-2"></i>{{ __('general.save_appearance_settings') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Localization Tab -->
        <div class="tab-pane fade" id="localization" role="tabpanel" aria-labelledby="localization-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="mb-0">{{ __('general.localization_settings') }}</h5>
                </div>
                <div class="admin-card-body">
                    <form action="{{ route('preferences.language') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">{{ __('general.default_language') }}</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="language" id="lang_en" value="en" {{ app()->getLocale() == 'en' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lang_en">
                                        <span class="fi fi-gb me-2"></span>English
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="language" id="lang_ar" value="ar" {{ app()->getLocale() == 'ar' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="lang_ar">
                                        <span class="fi fi-eg me-2"></span>العربية
                                    </label>
                                </div>
                            </div>
                            <small class="form-text text-muted">{{ __('general.language_notice') }}</small>
                        </div>
                        
                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>{{ __('general.save_language_settings') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Settings Tab -->
        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
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
        </div>
        
        <!-- Shipping Settings Tab -->
        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
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
        </div>
        
        <!-- Email Settings Tab -->
        <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5 class="mb-0">{{ __('general.email_settings') }}</h5>
                </div>
                <div class="admin-card-body">
                    <form method="POST" action="#">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_from" class="form-label">{{ __('general.from_email_address') }}</label>
                                    <input type="email" class="form-control" id="email_from" name="email_from" value="noreply@myclothes.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_from_name" class="form-label">{{ __('general.from_name') }}</label>
                                    <input type="text" class="form-control" id="email_from_name" name="email_from_name" value="MyClothes Store">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">{{ __('general.email_notifications') }}</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_new_order" name="email_notifications[]" value="new_order" checked>
                                <label class="form-check-label" for="notify_new_order">
                                    {{ __('general.new_order_notification') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_new_customer" name="email_notifications[]" value="new_customer" checked>
                                <label class="form-check-label" for="notify_new_customer">
                                    {{ __('general.new_customer_registration') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_low_stock" name="email_notifications[]" value="low_stock">
                                <label class="form-check-label" for="notify_low_stock">
                                    {{ __('general.low_stock_alert') }}
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>{{ __('general.save_email_settings') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle theme radio buttons
            const themeRadios = document.querySelectorAll('input[name="theme_mode"]');
            const themeInput = document.getElementById('theme_setting_input');
            
            themeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    themeInput.value = this.value;
                    
                    // Update preview in real-time
                    document.documentElement.classList.remove('theme-light', 'theme-dark');
                    document.documentElement.classList.add(`theme-${this.value}`);
                });
            });
        });
    </script>
    @endpush
@endsection 