@extends('layouts.admin')

@section('title', __('Currency Management'))

@section('header', __('Currency Management'))

@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('Currencies') }}</li>
@endsection

@section('content')
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
                    {{ __('Update Currency Settings') }}
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
                                <p class="mb-1">{{ __('Exchange Rate') }}: <strong>1 EGP = {{ 1 / $currency->exchange_rate }} {{ $currency->code }}</strong></p>
                                <p class="mb-0">{{ __('Example') }}: <strong>{{ $currency->format(1000 / $currency->exchange_rate) }}</strong></p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection 