@extends('layouts.admin')

@section('title', __('Edit City'))
@section('description', __('Update city details'))

@section('content')
    <div class="admin-header">
        <div>
            <!-- Empty div for layout consistency -->
        </div>
        <div>
            <a href="{{ route('admin.shipping.cities') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Cities') }}
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5 class="mb-0">{{ __('Edit City') }}</h5>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.shipping.cities.update', $city) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="governorate_id" class="form-label">{{ __('Governorate') }} <span class="text-danger">*</span></label>
                    <select class="form-select @error('governorate_id') is-invalid @enderror" id="governorate_id" name="governorate_id" required>
                        <option value="">{{ __('Select Governorate') }}</option>
                        @foreach($governorates as $governorate)
                            <option value="{{ $governorate->id }}" {{ (old('governorate_id', $city->governorate_id) == $governorate->id) ? 'selected' : '' }}>
                                {{ $governorate->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('governorate_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">{{ __('Name (English)') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $city->name_en) }}" required>
                        @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">{{ __('Name (Arabic)') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $city->name_ar) }}" required>
                        @error('name_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $city->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            {{ __('Active') }}
                        </label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.shipping.cities') }}" class="btn btn-outline-secondary me-2">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> {{ __('Update City') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 
 