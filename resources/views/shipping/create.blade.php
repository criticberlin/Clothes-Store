@extends('layouts.master')

@section('title', 'Add New Address')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add New Shipping Address</h5>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('shipping.store') }}" method="POST">
                        @csrf
                        
                        @if(request()->has('is_checkout'))
                            <input type="hidden" name="is_checkout" value="1">
                        @endif

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control bg-dark text-white" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control bg-dark text-white" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" required>
                            @error('mobile_number')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="governorate_id" class="form-label">Governorate</label>
                            <select class="form-select bg-dark text-white" id="governorate_id" name="governorate_id" required>
                                <option value="">Select Governorate</option>
                                @foreach($governorates as $governorate)
                                    <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                        {{ $governorate->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('governorate_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="city_id" class="form-label">City</label>
                            <select class="form-select bg-dark text-white" id="city_id" name="city_id" required disabled>
                                <option value="">Select City</option>
                            </select>
                            @error('city_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="street_address" class="form-label">Street Address</label>
                            <input type="text" class="form-control bg-dark text-white" id="street_address" name="street_address" value="{{ old('street_address') }}" required>
                            @error('street_address')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="building_number" class="form-label">Building Number</label>
                                <input type="text" class="form-control bg-dark text-white" id="building_number" name="building_number" value="{{ old('building_number') }}">
                                @error('building_number')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="floor_number" class="form-label">Floor Number</label>
                                <input type="text" class="form-control bg-dark text-white" id="floor_number" name="floor_number" value="{{ old('floor_number') }}">
                                @error('floor_number')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="apartment_number" class="form-label">Apartment Number</label>
                                <input type="text" class="form-control bg-dark text-white" id="apartment_number" name="apartment_number" value="{{ old('apartment_number') }}">
                                @error('apartment_number')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="delivery_instructions" class="form-label">Delivery Instructions (Optional)</label>
                            <textarea class="form-control bg-dark text-white" id="delivery_instructions" name="delivery_instructions" rows="3">{{ old('delivery_instructions') }}</textarea>
                            @error('delivery_instructions')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Set as Default Address</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const governorateSelect = document.getElementById('governorate_id');
        const citySelect = document.getElementById('city_id');
        
        // Function to load cities based on selected governorate
        function loadCities(governorateId) {
            if (!governorateId) {
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = true;
                return;
            }
            
            // Show loading state
            citySelect.innerHTML = '<option value="">Loading cities...</option>';
            citySelect.disabled = true;
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Make AJAX request to get cities
            fetch('{{ route("shipping.cities") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    governorate_id: governorateId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Clear and populate cities dropdown
                citySelect.innerHTML = '<option value="">Select City</option>';
                
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name_en;
                    citySelect.appendChild(option);
                });
                
                citySelect.disabled = false;
                
                // If there's a previously selected city, try to select it
                const oldCityId = '{{ old("city_id") }}';
                if (oldCityId) {
                    const option = citySelect.querySelector(`option[value="${oldCityId}"]`);
                    if (option) {
                        option.selected = true;
                    }
                }
            })
            .catch(error => {
                console.error('Error loading cities:', error);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
            });
        }
        
        // Load cities when governorate changes
        governorateSelect.addEventListener('change', function() {
            loadCities(this.value);
        });
        
        // Load cities on page load if governorate is selected
        if (governorateSelect.value) {
            loadCities(governorateSelect.value);
        }
    });
</script>
@endpush
@endsection 

@section('title', 'Add New Address')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add New Shipping Address</h5>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('shipping.store') }}" method="POST">
                        @csrf
                        
                        @if(request()->has('is_checkout'))
                            <input type="hidden" name="is_checkout" value="1">
                        @endif

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control bg-dark text-white" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control bg-dark text-white" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" required>
                            @error('mobile_number')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="governorate_id" class="form-label">Governorate</label>
                            <select class="form-select bg-dark text-white" id="governorate_id" name="governorate_id" required>
                                <option value="">Select Governorate</option>
                                @foreach($governorates as $governorate)
                                    <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                        {{ $governorate->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('governorate_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="city_id" class="form-label">City</label>
                            <select class="form-select bg-dark text-white" id="city_id" name="city_id" required disabled>
                                <option value="">Select City</option>
                            </select>
                            @error('city_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="street_address" class="form-label">Street Address</label>
                            <input type="text" class="form-control bg-dark text-white" id="street_address" name="street_address" value="{{ old('street_address') }}" required>
                            @error('street_address')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="building_number" class="form-label">Building Number</label>
                                <input type="text" class="form-control bg-dark text-white" id="building_number" name="building_number" value="{{ old('building_number') }}">
                                @error('building_number')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="floor_number" class="form-label">Floor Number</label>
                                <input type="text" class="form-control bg-dark text-white" id="floor_number" name="floor_number" value="{{ old('floor_number') }}">
                                @error('floor_number')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="apartment_number" class="form-label">Apartment Number</label>
                                <input type="text" class="form-control bg-dark text-white" id="apartment_number" name="apartment_number" value="{{ old('apartment_number') }}">
                                @error('apartment_number')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="delivery_instructions" class="form-label">Delivery Instructions (Optional)</label>
                            <textarea class="form-control bg-dark text-white" id="delivery_instructions" name="delivery_instructions" rows="3">{{ old('delivery_instructions') }}</textarea>
                            @error('delivery_instructions')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Set as Default Address</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Address</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const governorateSelect = document.getElementById('governorate_id');
        const citySelect = document.getElementById('city_id');
        
        // Function to load cities based on selected governorate
        function loadCities(governorateId) {
            if (!governorateId) {
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = true;
                return;
            }
            
            // Show loading state
            citySelect.innerHTML = '<option value="">Loading cities...</option>';
            citySelect.disabled = true;
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Make AJAX request to get cities
            fetch('{{ route("shipping.cities") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    governorate_id: governorateId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Clear and populate cities dropdown
                citySelect.innerHTML = '<option value="">Select City</option>';
                
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name_en;
                    citySelect.appendChild(option);
                });
                
                citySelect.disabled = false;
                
                // If there's a previously selected city, try to select it
                const oldCityId = '{{ old("city_id") }}';
                if (oldCityId) {
                    const option = citySelect.querySelector(`option[value="${oldCityId}"]`);
                    if (option) {
                        option.selected = true;
                    }
                }
            })
            .catch(error => {
                console.error('Error loading cities:', error);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
            });
        }
        
        // Load cities when governorate changes
        governorateSelect.addEventListener('change', function() {
            loadCities(this.value);
        });
        
        // Load cities on page load if governorate is selected
        if (governorateSelect.value) {
            loadCities(governorateSelect.value);
        }
    });
</script>
@endpush
@endsection 