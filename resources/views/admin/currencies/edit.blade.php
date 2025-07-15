@extends('layouts.admin')

@section('title', 'Edit Currency')
@section('description', 'Update currency settings')

@section('content')
    <div class="admin-header">
        <div>
            <a href="{{ route('admin.currencies.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Currencies
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">
                <i class="bi bi-currency-exchange me-2"></i>
                Edit {{ $currency->name }} ({{ $currency->code }})
            </h5>
        </div>
        <div class="admin-card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('admin.currencies.update', $currency) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">Currency Code</label>
                            <input type="text" class="form-control" id="code" value="{{ $currency->code }}" disabled>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Currency code cannot be changed.
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Currency Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $currency->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="symbol_en" class="form-label">Symbol (English)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-translate"></i>
                                </span>
                                <input type="text" class="form-control @error('symbol_en') is-invalid @enderror" 
                                       id="symbol_en" name="symbol_en" value="{{ old('symbol_en', $currency->symbol_en) }}" required>
                            </div>
                            @error('symbol_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="symbol_ar" class="form-label">Symbol (Arabic)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-translate"></i>
                                </span>
                                <input type="text" class="form-control @error('symbol_ar') is-invalid @enderror" 
                                       id="symbol_ar" name="symbol_ar" value="{{ old('symbol_ar', $currency->symbol_ar) }}" required>
                            </div>
                            @error('symbol_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rate" class="form-label">Exchange Rate</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-calculator"></i>
                                </span>
                                <input type="number" class="form-control @error('rate') is-invalid @enderror" 
                                       id="rate" name="rate" value="{{ old('rate', $currency->rate) }}" 
                                       step="0.000001" min="0.000001" {{ $currency->is_default ? 'readonly' : '' }} required>
                                <span class="input-group-text">per 1 EGP</span>
                            </div>
                            @if($currency->is_default)
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Default currency rate is always 1.00.
                                </div>
                            @else
                                <div class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Enter how much 1 unit of base currency (EGP) equals in this currency.
                                </div>
                            @endif
                            @error('rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-control-plaintext">
                                @if($currency->is_default)
                                    <span class="status-badge completed">
                                        <i class="bi bi-star-fill"></i> Default Currency
                                    </span>
                                @elseif($currency->is_active)
                                    <span class="status-badge completed">
                                        <i class="bi bi-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="status-badge cancelled">
                                        <i class="bi bi-x-circle"></i> Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Update Currency
                    </button>
                    <a href="{{ route('admin.currencies.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-x me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    @if(!$currency->is_default)
        <div class="admin-card mt-4">
            <div class="admin-card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Danger Zone
                </h5>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.currencies.toggle-status', $currency) }}" method="POST" class="mb-0">
                    @csrf
                    @method('PATCH')
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $currency->is_active ? 'Deactivate' : 'Activate' }} Currency</h6>
                            <p class="mb-0 text-muted">
                                {{ $currency->is_active 
                                   ? 'Deactivating will hide this currency from the currency switcher. Prices will no longer be shown in this currency.' 
                                   : 'Activating will make this currency available in the currency switcher. Users will be able to view prices in this currency.' }}
                            </p>
                        </div>
                        <button type="submit" class="btn {{ $currency->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                            <i class="bi {{ $currency->is_active ? 'bi-toggle-off' : 'bi-toggle-on' }} me-2"></i>
                            {{ $currency->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection 