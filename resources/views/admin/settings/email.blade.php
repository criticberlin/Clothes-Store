@extends('layouts.admin')

@section('title', __('general.email_settings'))
@section('description', __('general.email_settings_description'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
    </div>

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
@endsection 