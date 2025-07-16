@extends('layouts.master')

@section('title', __('Your Cart'))

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
        <h1 class="mb-0">{{ __('Your Cart') }}</h1>
        <a href="{{ route('products.list') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Continue Shopping') }}
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
                            <h5 class="mb-0">{{ count($cartItems) }} {{ __('Items in your cart') }}</h5>
                            <a href="{{ route('cart.clear') }}" class="btn btn-sm btn-outline-danger clear-cart-btn" data-method="DELETE">
                                <i class="bi bi-trash me-1"></i> {{ __('Clear Cart') }}
                            </a>
                        </div>
                    </div>
                    
                    @foreach($cartItems as $item)
                    <div class="cart-item p-4 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-2 mb-3 mb-md-0">
                                <img src="{{ $item->product->imageUrl }}" alt="{{ $item->product->name }}" class="cart-product-img">
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
                                        <span class="text-success">{{ __('In Stock') }}</span>
                                    @elseif($item->product->quantity > 0)
                                        <span class="text-warning">{{ __('Low Stock') }}: {{ $item->product->quantity }} {{ __('Left') }}</span>
                                    @else
                                        <span class="text-danger">{{ __('Out of Stock') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 mb-3 mb-md-0">
                                <span class="price-value" data-base-price="{{ $item->product->price }}">{{ app(\App\Services\CurrencyService::class)->formatPrice($item->product->price) }}</span>
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
                                    <button type="submit" class="btn btn-sm btn-primary update-cart-btn">{{ __('Update') }}</button>
                                </form>
                            </div>
                            <div class="col-md-1 text-end mb-3 mb-md-0">
                                <span class="fw-bold item-total price-value" data-base-price="{{ $item->quantity * $item->product->price }}">
                                    {{ app(\App\Services\CurrencyService::class)->formatPrice($item->quantity * $item->product->price) }}
                                </span>
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
            <h5 class="mb-3">{{ __('You might also like') }}</h5>
            <div class="row g-3">
                @if($recommendedProducts->count() > 0)
                    @foreach($recommendedProducts as $product)
                    <div class="col-md-4">
                        <div class="product-card recommended-product h-100">
                            <a href="{{ route('products.details', $product->id) }}" class="product-card-link">
                                <div class="product-image-container" style="height: 150px;">
                                    <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="img-fluid product-image">
                                    
                                    <!-- Product Badges -->
                                    @if($product->quantity <= 0)
                                        <div class="product-badge out-of-stock">{{ __('general.out_of_stock') }}</div>
                                    @elseif($product->created_at && $product->created_at->diffInDays(now()) <= 7)
                                        <div class="product-badge new">{{ __('general.new') }}</div>
                                    @endif
                                </div>
                                <div class="product-info p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h3 class="product-title h6 mb-1">{{ $product->name }}</h3>
                                        <button type="button" class="btn btn-sm btn-icon wishlist-toggle p-0 m-0" 
                                                data-product-id="{{ $product->id }}" 
                                                title="{{ __('Add to Wishlist') }}">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="product-price fw-bold price-value" data-base-price="{{ $product->price }}">
                                            {{ app(\App\Services\CurrencyService::class)->formatPrice($product->price) }}
                                        </div>
                                        <div class="product-rating">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <span class="ms-1 small">{{ number_format($product->average_rating ?? 0, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12 text-center text-secondary">
                        <p>{{ __('No recommendations available at this time.') }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm cart-summary-card">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">{{ __('Order Summary') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Coupon Code -->
                    <div class="mb-4">
                        <label class="form-label">{{ __('Coupon Code') }}</label>
                        <div class="coupon-form">
                            <input type="text" class="form-control coupon-input" placeholder="{{ __('Enter Coupon') }}">
                            <button class="btn btn-sm btn-primary coupon-btn">{{ __('Apply') }}</button>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('Subtotal') }}</span>
                        <span class="price-value" data-base-price="{{ $subTotal }}">{{ app(\App\Services\CurrencyService::class)->formatPrice($subTotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('Shipping') }}</span>
                        <span class="price-value" data-base-price="{{ $shippingCost }}">{{ app(\App\Services\CurrencyService::class)->formatPrice($shippingCost) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('Tax') }}</span>
                        <span class="price-value" data-base-price="{{ $tax }}">{{ app(\App\Services\CurrencyService::class)->formatPrice($tax) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>{{ __('Total') }}</strong>
                        <strong class="price-value" data-base-price="{{ $total }}">{{ app(\App\Services\CurrencyService::class)->formatPrice($total) }}</strong>
                    </div>
                    
                    <!-- Shipping Estimate -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-truck me-2 text-primary"></i>
                            <span>{{ __('Estimated Delivery') }}</span>
                        </div>
                        <div class="text-success fw-medium">{{ date('M d', strtotime('+3 days')) }} - {{ date('M d', strtotime('+7 days')) }}</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                            {{ __('Proceed to Checkout') }}
                        </a>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="mt-4 text-center">
                        <small class="text-secondary d-block mb-2">{{ __('Secure Payment') }}</small>
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
        <h2 class="mb-3">{{ __('Cart Empty') }}</h2>
        <p class="text-secondary mb-4 mx-auto" style="max-width: 500px;">{{ __('Cart Empty Message') }}</p>
        <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
            {{ __('Start Shopping') }}
        </a>
        
        <!-- Featured Categories -->
        <div class="mt-5">
            <h5 class="mb-4">{{ __('Popular Categories') }}</h5>
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-standing fs-1 mb-3 text-primary"></i>
                                <h6>{{ __('Tops') }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-standing fs-1 mb-3 text-primary"></i>
                                <h6>{{ __('Dresses') }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-standing fs-1 mb-3 text-primary"></i>
                                <h6>{{ __('Accessories') }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-standing fs-1 mb-3 text-primary"></i>
                                <h6>{{ __('Footwear') }}</h6>
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
        
        // Wishlist toggle functionality
        const wishlistBtns = document.querySelectorAll('.wishlist-toggle');
        wishlistBtns.forEach(btn => {
            // Check if product is in wishlist
            const productId = btn.getAttribute('data-product-id');
            
            // Make AJAX request to check if in wishlist
            fetch(`/wishlist/check/${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.in_wishlist) {
                        btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
                        btn.classList.add('active');
                    } else {
                        btn.innerHTML = '<i class="bi bi-heart"></i>';
                        btn.classList.remove('active');
                    }
                })
                .catch(error => {
                    console.error('Error checking wishlist status:', error);
                });
            
            // Add click event listener
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const productId = this.getAttribute('data-product-id');
                
                // Show loading state
                this.innerHTML = '<i class="bi bi-hourglass-split"></i>';
                this.disabled = true;
                
                // Toggle wishlist status
                fetch(`/wishlist/toggle/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Enable button
                    this.disabled = false;
                    
                    if (data.success) {
                        if (data.in_wishlist) {
                            this.innerHTML = '<i class="bi bi-heart-fill"></i>';
                            this.classList.add('active');
                            showNotification('Product added to wishlist', 'success');
                        } else {
                            this.innerHTML = '<i class="bi bi-heart"></i>';
                            this.classList.remove('active');
                            showNotification('Product removed from wishlist', 'info');
                        }
                    } else {
                        // If not authenticated, redirect to login
                        if (data.message.includes('login')) {
                            window.location.href = '/login';
                        } else {
                            showNotification(data.message || 'Error updating wishlist', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error toggling wishlist:', error);
                    this.disabled = false;
                    this.innerHTML = '<i class="bi bi-heart"></i>';
                    showNotification('Error updating wishlist', 'error');
                });
            });
        });
        
        // Show notification function
        function showNotification(message, type = 'success') {
            // If there's an existing notification, remove it
            const existingNotification = document.querySelector('.notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            
            let icon = 'check-circle';
            if (type === 'error') icon = 'exclamation-triangle';
            if (type === 'info') icon = 'info-circle';
            
            notification.innerHTML = `
                <div class="notification-content">
                    <i class="bi bi-${icon}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Add to DOM
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    });
</script>
@endpush