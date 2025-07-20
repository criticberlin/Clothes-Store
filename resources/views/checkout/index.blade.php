@extends('layouts.master')
@section('title', 'Checkout')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Checkout</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-4 mb-4">
            <div class="card bg-dark">
                <div class="card-body">
                    <h2 class="card-title mb-4">Order Summary</h2>
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('images/' . $item->product->photo) }}"
                                     alt="{{ $item->product->name }}"
                                     class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <p class="text-secondary mb-1">
                                        Quantity: {{ $item->quantity }}
                                        @if($item->color)
                                            | Color: {{ $item->color->name }}
                                        @endif
                                        @if($item->size)
                                            | Size: {{ $item->size->name }}
                                        @endif
                                    </p>
                                    <p class="mb-0">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="border-top border-secondary mt-3 pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        @if($shippingCost > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>${{ number_format($shippingCost, 2) }}</span>
                        </div>
                        @endif
                        
                        @if($paymentFee > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Payment Fee:</span>
                            <span>${{ number_format($paymentFee, 2) }}</span>
                        </div>
                        @endif
                        
                        @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span class="text-success">-${{ number_format($discount, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between mt-2 pt-2 border-top border-secondary">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Promo Code Section -->
                    <div class="mt-4">
                        <h5>Promo Code</h5>
                        @if(!empty($checkoutData['promo_code']))
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-success">{{ $checkoutData['promo_code'] }}</span>
                                    <span class="ms-2">Applied</span>
                                </div>
                                <form action="{{ route('checkout.promo-code.remove') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('checkout.promo-code') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="promo_code" class="form-control bg-dark text-white" placeholder="Enter promo code">
                                    <button type="submit" class="btn btn-outline-primary">Apply</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Steps -->
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-body">
                    <h2 class="card-title mb-4">Checkout</h2>
                    
                    <!-- Step Indicator -->
                    <div class="d-flex justify-content-between mb-4">
                        <div class="text-center">
                            <div class="step-circle {{ isset($checkoutData['step']) && $checkoutData['step'] >= 1 ? 'active' : '' }}">1</div>
                            <div class="step-label">Address</div>
                        </div>
                        <div class="text-center">
                            <div class="step-circle {{ isset($checkoutData['step']) && $checkoutData['step'] >= 2 ? 'active' : '' }}">2</div>
                            <div class="step-label">Shipping</div>
                        </div>
                        <div class="text-center">
                            <div class="step-circle {{ isset($checkoutData['step']) && $checkoutData['step'] >= 3 ? 'active' : '' }}">3</div>
                            <div class="step-label">Payment</div>
                        </div>
                        <div class="text-center">
                            <div class="step-circle {{ isset($checkoutData['step']) && $checkoutData['step'] >= 4 ? 'active' : '' }}">4</div>
                            <div class="step-label">Review</div>
                        </div>
                    </div>

                    <!-- Step 1: Shipping Address -->
                    @if(isset($checkoutData['step']) && $checkoutData['step'] == 1)
                    <div class="step-content">
                        <h3>Select Shipping Address</h3>
                        
                        @if($addresses->isEmpty())
                            <div class="alert alert-info">
                                You don't have any saved addresses. Please add one.
                            </div>
                            <div class="text-center">
                                <a href="{{ route('shipping.create') }}?is_checkout=1" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i> Add New Address
                                </a>
                            </div>
                        @else
                            <form action="{{ route('checkout.address') }}" method="POST">
                                @csrf
                                <div class="row">
                                    @foreach($addresses as $address)
                                        <div class="col-md-6 mb-3">
                                            <div class="card {{ isset($checkoutData['address_id']) && $address->id == $checkoutData['address_id'] ? 'border-primary' : '' }}">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="address_id" 
                                                               id="address{{ $address->id }}" value="{{ $address->id }}"
                                                               {{ isset($checkoutData['address_id']) && $address->id == $checkoutData['address_id'] ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="address{{ $address->id }}">
                                                            <strong>{{ $address->full_name }}</strong>
                                                            @if($address->is_default) 
                                                                <span class="badge bg-primary ms-2">Default</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                    <div class="mt-2">
                                                        <p class="mb-1">{{ $address->mobile_number }}</p>
                                                        <p class="mb-1">{{ $address->street_address }}</p>
                                                        <p class="mb-1">
                                                            {{ $address->city->name }}, 
                                                            {{ $address->governorate->name }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <a href="{{ route('shipping.create') }}?is_checkout=1" class="btn btn-outline-primary">
                                        <i class="bi bi-plus-circle me-2"></i> Add New Address
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Continue <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Step 2: Shipping Method -->
                    @if(isset($checkoutData['step']) && $checkoutData['step'] == 2)
                    <div class="step-content">
                        <h3>Select Shipping Method</h3>
                        
                        <form action="{{ route('checkout.shipping') }}" method="POST">
                            @csrf
                            <div class="row">
                                @foreach($shippingMethods as $method)
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ isset($checkoutData['shipping_method_id']) && $method->id == $checkoutData['shipping_method_id'] ? 'border-primary' : '' }}">
                                            <div class="card-body">
                            <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="shipping_method_id" 
                                                           id="shipping{{ $method->id }}" value="{{ $method->id }}"
                                                           {{ isset($checkoutData['shipping_method_id']) && $method->id == $checkoutData['shipping_method_id'] ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="shipping{{ $method->id }}">
                                                        <strong>{{ $method->name }}</strong>
                                                    </label>
                                                </div>
                                                <div class="mt-2">
                                                    <p class="mb-1">{{ $method->description }}</p>
                                                    <p class="mb-1">{{ $method->estimated_delivery_time }}</p>
                                                    <p class="mb-0 fw-bold">${{ number_format($method->cost, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Continue <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                    
                    <!-- Step 3: Payment Method -->
                    @if(isset($checkoutData['step']) && $checkoutData['step'] == 3)
                    <div class="step-content">
                        <h3>Select Payment Method</h3>
                        
                        <form action="{{ route('checkout.payment') }}" method="POST">
                            @csrf
                            <div class="row">
                                @foreach($paymentMethods as $method)
                                    <div class="col-md-6 mb-3">
                                        <div class="card {{ isset($checkoutData['payment_method_id']) && $method->id == $checkoutData['payment_method_id'] ? 'border-primary' : '' }}">
                                            <div class="card-body">
                            <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method_id" 
                                                           id="payment{{ $method->id }}" value="{{ $method->id }}"
                                                           {{ isset($checkoutData['payment_method_id']) && $method->id == $checkoutData['payment_method_id'] ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="payment{{ $method->id }}">
                                                        <strong>{{ $method->name }}</strong>
                                                    </label>
                                                </div>
                                                <div class="mt-2">
                                                    <p class="mb-1">{{ $method->description }}</p>
                                                    @if($method->fee > 0)
                                                        <p class="mb-0 fw-bold">Fee: ${{ number_format($method->fee, 2) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Continue <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                    
                    <!-- Step 4: Review & Place Order -->
                    @if(isset($checkoutData['step']) && $checkoutData['step'] == 4)
                    <div class="step-content">
                        <h3>Review & Place Order</h3>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-dark">
                                    <div class="card-body">
                                        <h5>Shipping Address</h5>
                                        @if(!empty($checkoutData['address_id']) && $address = $addresses->where('id', $checkoutData['address_id'])->first())
                                            <p class="mb-1"><strong>{{ $address->full_name }}</strong></p>
                                            <p class="mb-1">{{ $address->mobile_number }}</p>
                                            <p class="mb-1">{{ $address->street_address }}</p>
                                            <p class="mb-1">
                                                {{ $address->city->name }}, 
                                                {{ $address->governorate->name }}
                                            </p>
                                            <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Change</a>
                                        @else
                                            <p class="text-danger">Please select a shipping address</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-dark">
                                    <div class="card-body">
                                        <h5>Payment Method</h5>
                                        @if(!empty($checkoutData['payment_method_id']) && $paymentMethod = $paymentMethods->where('id', $checkoutData['payment_method_id'])->first())
                                            <p class="mb-1"><strong>{{ $paymentMethod->name }}</strong></p>
                                            <p class="mb-1">{{ $paymentMethod->description }}</p>
                                            @if($paymentMethod->fee > 0)
                                                <p class="mb-1">Fee: ${{ number_format($paymentMethod->fee, 2) }}</p>
                                            @endif
                                            <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Change</a>
                                        @else
                                            <p class="text-danger">Please select a payment method</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-dark mb-4">
                            <div class="card-body">
                                <h5>Shipping Method</h5>
                                @if(!empty($checkoutData['shipping_method_id']) && $shippingMethod = $shippingMethods->where('id', $checkoutData['shipping_method_id'])->first())
                                    <p class="mb-1"><strong>{{ $shippingMethod->name }}</strong></p>
                                    <p class="mb-1">{{ $shippingMethod->description }}</p>
                                    <p class="mb-1">{{ $shippingMethod->estimated_delivery_time }}</p>
                                    <p class="mb-1">Cost: ${{ number_format($shippingMethod->cost, 2) }}</p>
                                    <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Change</a>
                                @else
                                    <p class="text-danger">Please select a shipping method</p>
                                @endif
                            </div>
                        </div>
                        
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="terms_accepted" id="terms_accepted" required>
                                <label class="form-check-label" for="terms_accepted">
                                    I agree to the <a href="{{ route('pages.terms') }}" target="_blank">Terms & Conditions</a>
                                </label>
                                @error('terms_accepted')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Place Order <i class="bi bi-check-circle ms-2"></i>
                        </button>
                            </div>
                    </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #343a40;
        border: 2px solid #6c757d;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
    }
    
    .step-circle.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .step-label {
        font-size: 14px;
        color: #6c757d;
    }
</style>
@endsection