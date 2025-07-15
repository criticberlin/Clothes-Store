@extends('layouts.master')

@section('title', __('general.your_cart'))

@push('styles')
<style>
.color-swatch {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1px solid var(--border);
    margin-right: 5px;
    vertical-align: middle;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.cart-item {
    transition: all var(--transition-normal);
    border-radius: var(--radius-md);
}

.cart-item:hover {
    background-color: var(--surface-alt);
    transform: translateX(5px);
}

.cart-product-img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: var(--radius-md);
    transition: all var(--transition-normal);
    border: 1px solid var(--border);
}

.cart-item:hover .cart-product-img {
    transform: scale(1.05);
    box-shadow: var(--shadow-md);
}

.size-badge {
    background-color: var(--surface-alt);
    color: var(--text-secondary);
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-weight: 600;
    display: inline-block;
}

.quantity-control {
    display: flex;
    align-items: center;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
    width: 120px;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--surface-alt);
    border: none;
    color: var(--text-primary);
    font-weight: bold;
    cursor: pointer;
    transition: all var(--transition-normal);
}

.quantity-btn:hover {
    background-color: var(--primary);
    color: white;
}

.quantity-input {
    width: 56px;
    border: none;
    text-align: center;
    font-weight: 600;
    background-color: var(--surface);
    color: var(--text-primary);
}

.cart-summary-card {
    position: sticky;
    top: 100px;
}

.recommended-product {
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
    transition: all var(--transition-normal);
}

.recommended-product:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
}

.recommended-product-img {
    height: 120px;
    object-fit: cover;
}

.coupon-form {
    position: relative;
}

.coupon-input {
    padding-right: 100px;
}

.coupon-btn {
    position: absolute;
    right: 3px;
    top: 3px;
    bottom: 3px;
    border-radius: var(--radius-sm);
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ __('general.your_cart') }}</h1>
        <a href="{{ route('products.list') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> {{ __('general.continue_shopping') }}
        </a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(!empty($cartItems))
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ count($cartItems) }} {{ __('general.items_in_your_cart') }}</h5>
                            <a href="{{ route('cart.clear') }}" class="btn btn-sm btn-outline-danger clear-cart-btn" data-method="DELETE">
                                <i class="bi bi-trash me-1"></i> {{ __('general.clear_cart') }}
                            </a>
                        </div>
                    </div>
                    
                    @foreach($cartItems as $item)
                    <div class="cart-item p-4 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-2 mb-3 mb-md-0">
                                <img src="{{ asset('img/products/' . $item->product->photo) }}" alt="{{ $item->product->name }}" class="cart-product-img">
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h5 class="mb-1">{{ $item->product->name }}</h5>
                                <div class="d-flex align-items-center mb-2">
                                    @if($item->color)
                                        <span class="color-swatch me-2" data-color="{{ $item->color->hex_code }}"></span>
                                        <span class="text-secondary me-3">{{ $item->color->name }}</span>
                                    @endif
                                    
                                    @if($item->size)
                                        <span class="size-badge">{{ $item->size->name }}</span>
                                    @endif
                                </div>
                                <div class="text-secondary small">
                                    <i class="bi bi-box-seam me-1"></i> 
                                    @if($item->product->quantity > 10)
                                        <span class="text-success">{{ __('general.in_stock') }}</span>
                                    @elseif($item->product->quantity > 0)
                                        <span class="text-warning">{{ __('general.low_stock') }}: {{ $item->product->quantity }} {{ __('general.left') }}</span>
                                    @else
                                        <span class="text-danger">{{ __('general.out_of_stock') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                                <span class="price-value" data-base-price="{{ $item->product->price }}">{{ format_price($item->product->price) }}</span>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="cart-quantity-form">
                                    @csrf
                                    @method('PATCH')
                                    <div class="quantity-control mb-2">
                                        <button type="button" class="quantity-btn quantity-decrease" data-action="decrease">-</button>
                                        <input type="number" class="quantity-input" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}">
                                        <button type="button" class="quantity-btn quantity-increase" data-action="increase">+</button>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary update-cart-btn">{{ __('general.update') }}</button>
                                </form>
                            </div>
                            <div class="col-md-1 text-end mb-3 mb-md-0">
                                <span class="fw-bold item-total">{{ number_format($item->quantity * $item->product->price, 2) }} {{ config('app.currency_symbol', '$') }}</span>
                            </div>
                            <div class="col-md-1 text-end">
                                <a href="{{ route('cart.remove', $item->id) }}" class="btn btn-sm btn-outline-danger rounded-circle remove-from-cart-btn" data-method="DELETE">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Recommended Products -->
            <h5 class="mb-3">{{ __('general.you_might_also_like') }}</h5>
            <div class="row g-3">
                <!-- This would be populated with actual recommended products -->
                @for($i = 0; $i < 3; $i++)
                <div class="col-md-4">
                    <div class="recommended-product">
                        <img src="{{ asset('images/products/default.jpg') }}" class="w-100 recommended-product-img" alt="Recommended product">
                        <div class="p-3">
                            <h6 class="mb-1">{{ __('general.recommended_product') }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">$49.99</span>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> {{ __('general.add') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm cart-summary-card">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">{{ __('general.order_summary') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Coupon Code -->
                    <div class="mb-4">
                        <label class="form-label">{{ __('general.coupon_code') }}</label>
                        <div class="coupon-form">
                            <input type="text" class="form-control coupon-input" placeholder="{{ __('general.enter_coupon') }}">
                            <button class="btn btn-sm btn-primary coupon-btn">{{ __('general.apply') }}</button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('general.subtotal') }}</span>
                        <span class="price-value" data-base-price="{{ $subTotal }}">{{ format_price($subTotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('general.shipping') }}</span>
                        <span class="price-value" data-base-price="{{ $shippingCost }}">{{ format_price($shippingCost) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('general.tax') }}</span>
                        <span class="price-value" data-base-price="{{ $tax }}">{{ format_price($tax) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>{{ __('general.total') }}</strong>
                        <strong class="price-value" data-base-price="{{ $total }}">{{ format_price($total) }}</strong>
                    </div>
                    
                    <!-- Shipping Estimate -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-truck me-2 text-primary"></i>
                            <span>{{ __('general.estimated_delivery') }}</span>
                        </div>
                        <div class="text-success fw-medium">{{ date('M d', strtotime('+3 days')) }} - {{ date('M d', strtotime('+7 days')) }}</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                            {{ __('general.proceed_to_checkout') }}
                        </a>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="mt-4 text-center">
                        <small class="text-secondary d-block mb-2">{{ __('general.secure_payment') }}</small>
                        <div class="d-flex justify-content-center gap-2">
                            <i class="bi bi-credit-card fs-4"></i>
                            <i class="bi bi-paypal fs-4"></i>
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-cart-x" style="font-size: 5rem; color: var(--primary-light);"></i>
        </div>
        <h2 class="mb-3">{{ __('general.cart_empty') }}</h2>
        <p class="text-secondary mb-4 mx-auto" style="max-width: 500px;">{{ __('general.cart_empty_message') }}</p>
        <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
            {{ __('general.start_shopping') }}
        </a>
        
        <!-- Featured Categories -->
        <div class="mt-5">
            <h5 class="mb-4">{{ __('general.popular_categories') }}</h5>
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-standing fs-1 mb-3 text-primary"></i>
                                <h6>{{ __('general.tops') }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-standing fs-1 mb-3 text-primary"></i>
                                <h6>{{ __('general.dresses') }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-standing fs-1 mb-3 text-primary"></i>
                                <h6>{{ __('general.accessories') }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-standing fs-1 mb-3 text-primary"></i>
                                <h6>{{ __('general.footwear') }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set color swatches
        document.querySelectorAll('.color-swatch').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
        
        // Quantity buttons
        document.querySelectorAll('.quantity-decrease').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                if (currentValue > parseInt(input.min)) {
                    input.value = currentValue - 1;
                }
            });
        });
        
        document.querySelectorAll('.quantity-increase').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                if (currentValue < parseInt(input.max)) {
                    input.value = currentValue + 1;
                }
            });
        });
        
        // Cart item hover animation
        document.querySelectorAll('.cart-item').forEach(function(item) {
            item.addEventListener('mouseenter', function() {
                this.querySelector('.cart-product-img').style.transform = 'scale(1.05)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.querySelector('.cart-product-img').style.transform = 'scale(1)';
            });
        });
    });
</script>
@endpush