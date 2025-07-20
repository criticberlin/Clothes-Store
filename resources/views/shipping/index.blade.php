@extends('layouts.master')

@section('title', 'My Shipping Addresses')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Shipping Addresses</h2>
        <a href="{{ route('shipping.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Add New Address
        </a>
    </div>

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

    @if($addresses->isEmpty())
        <div class="alert alert-info">
            <p>You don't have any saved addresses yet.</p>
            <a href="{{ route('shipping.create') }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle me-2"></i> Add New Address
            </a>
        </div>
    @else
        <div class="row">
            @foreach($addresses as $address)
                <div class="col-md-6 mb-4">
                    <div class="card bg-dark h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">{{ $address->full_name }}</h5>
                                @if($address->is_default)
                                    <span class="badge bg-primary">Default</span>
                                @endif
                            </div>
                            <p class="card-text mb-1">{{ $address->mobile_number }}</p>
                            <p class="card-text mb-1">{{ $address->street_address }}</p>
                            <p class="card-text mb-1">
                                @if($address->building_number)
                                    Building: {{ $address->building_number }},
                                @endif
                                @if($address->floor_number)
                                    Floor: {{ $address->floor_number }},
                                @endif
                                @if($address->apartment_number)
                                    Apartment: {{ $address->apartment_number }}
                                @endif
                            </p>
                            <p class="card-text">
                                {{ $address->city->name }}, {{ $address->governorate->name }}
                            </p>
                            @if($address->delivery_instructions)
                                <p class="card-text text-muted">
                                    <small>{{ $address->delivery_instructions }}</small>
                                </p>
                            @endif
                        </div>
                        <div class="card-footer bg-dark border-top">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('shipping.edit', $address) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('shipping.destroy', $address) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this address?')">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                                @if(!$address->is_default)
                                    <form action="{{ route('shipping.default', $address) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-check-circle me-1"></i> Set as Default
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection 

@section('title', 'My Shipping Addresses')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Shipping Addresses</h2>
        <a href="{{ route('shipping.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Add New Address
        </a>
    </div>

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

    @if($addresses->isEmpty())
        <div class="alert alert-info">
            <p>You don't have any saved addresses yet.</p>
            <a href="{{ route('shipping.create') }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle me-2"></i> Add New Address
            </a>
        </div>
    @else
        <div class="row">
            @foreach($addresses as $address)
                <div class="col-md-6 mb-4">
                    <div class="card bg-dark h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">{{ $address->full_name }}</h5>
                                @if($address->is_default)
                                    <span class="badge bg-primary">Default</span>
                                @endif
                            </div>
                            <p class="card-text mb-1">{{ $address->mobile_number }}</p>
                            <p class="card-text mb-1">{{ $address->street_address }}</p>
                            <p class="card-text mb-1">
                                @if($address->building_number)
                                    Building: {{ $address->building_number }},
                                @endif
                                @if($address->floor_number)
                                    Floor: {{ $address->floor_number }},
                                @endif
                                @if($address->apartment_number)
                                    Apartment: {{ $address->apartment_number }}
                                @endif
                            </p>
                            <p class="card-text">
                                {{ $address->city->name }}, {{ $address->governorate->name }}
                            </p>
                            @if($address->delivery_instructions)
                                <p class="card-text text-muted">
                                    <small>{{ $address->delivery_instructions }}</small>
                                </p>
                            @endif
                        </div>
                        <div class="card-footer bg-dark border-top">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('shipping.edit', $address) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('shipping.destroy', $address) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this address?')">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                                @if(!$address->is_default)
                                    <form action="{{ route('shipping.default', $address) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-check-circle me-1"></i> Set as Default
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection 