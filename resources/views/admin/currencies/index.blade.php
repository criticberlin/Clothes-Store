@extends('layouts.admin')

@section('title', 'Currency Management')
@section('description', 'Manage all currencies in your store')

@section('content')
    <div class="admin-header">
        <div>
            <a href="{{ route('admin.settings', ['section' => 'currencies']) }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Settings
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
        </div>
    @endif
    
    <div class="admin-card">
        <div class="admin-card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-currency-exchange me-2"></i>
                Currency Management
            </h5>
        </div>
        <div class="admin-card-body">
            <div class="alert alert-info mb-4">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="bi bi-info-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading">How Currency Exchange Works</h5>
                        <ul class="mb-0">
                            <li>{{ $defaultCurrency->code ?? 'EGP' }} is the base currency - all prices in the system are stored in {{ $defaultCurrency->code ?? 'EGP' }}.</li>
                            <li>Rates represent how many units of base currency equal 1 unit of the target currency.</li>
                            <li>Prices displayed to users are always converted using the current exchange rates.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Symbol (EN)</th>
                            <th>Symbol (AR)</th>
                            <th>Rate</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencies as $currency)
                            <tr>
                                <td>
                                    <strong>{{ $currency->code }}</strong>
                                    @if($currency->is_default)
                                        <span class="status-badge completed">
                                            <i class="bi bi-star-fill"></i> Default
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $currency->name }}</td>
                                <td>{{ $currency->symbol_en }}</td>
                                <td>{{ $currency->symbol_ar }}</td>
                                <td>
                                    @if($currency->is_default)
                                        <span class="badge bg-light text-dark">1.00</span>
                                    @else
                                        {{ number_format($currency->rate, 6) }}
                                    @endif
                                </td>
                                <td>
                                    @if($currency->is_active)
                                        <span class="status-badge completed">
                                            <i class="bi bi-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="status-badge cancelled">
                                            <i class="bi bi-x-circle"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.currencies.edit', $currency) }}" class="action-btn" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        @if(!$currency->is_default)
                                            <form action="{{ route('admin.currencies.toggle-status', $currency) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn {{ $currency->is_active ? 'delete' : '' }}" title="{{ $currency->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi {{ $currency->is_active ? 'bi-toggle-off' : 'bi-toggle-on' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 