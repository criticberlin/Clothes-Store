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

/* Recommendation Navigation */
.recommended-section {
    position: relative;
    padding: 0 15px;
}

.recommended-products-container {
    overflow: hidden;
    border-radius: 8px;
    padding: 0 30px;
    touch-action: pan-x; /* Enable horizontal touch scrolling */
}

.recommended-products {
    display: flex;
    flex-wrap: nowrap;
    transition: transform 0.5s ease;
}

.recommended-item {
    flex: 0 0 auto;
    width: 85%; /* Wider cards on mobile */
    padding: 10px;
}

@media (min-width: 768px) {
    .recommended-item {
        width: 25%;
    }
    .recommended-section {
        padding: 0 25px;
    }
}

/* Make product cards bigger on mobile */
@media (max-width: 767px) {
    .product-card {
        transform: scale(1.05);
    }
    
    .product-image-container {
        height: auto;
        min-height: 180px;
    }
}

.nav-btn {
    position: absolute;
    top: 44%;
    transform: translateY(-50%);
    width: 40px;
    height: 30px;
    border-radius: 30px;
    background-color: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}

.nav-btn.prev-btn {
    left: -15px;
}

.nav-btn.next-btn {
    right: -15px;
}

.nav-btn:hover {
    background-color: var(--primary);
    color: white;
}

.nav-btn:disabled {
    opacity: 0;
    cursor: not-allowed;
    pointer-events: none;
}

@media (max-width: 767px) {
    .nav-btn {
        width: 36px;
        height: 28px;
    }
    
    /* Hide arrows on touch devices */
    @media (pointer: coarse) {
        .nav-btn {
            display: none;
        }
    }
}

/* Fix for "You Might Also Like" section */
.container.py-5 {
    margin-bottom: 2rem;
}

.row.g-3.mb-5 {
    margin-bottom: 3rem;
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
    
    <!-- You might also like section -->
    @if(!empty($cartItems) || true)
    <div class="mt-5">
        <h3 class="mb-4">{{ __('You might also like') }}</h3>
        
        <div class="recommended-section">
            <div class="recommended-products-container">
                <div class="recommended-products row g-0" id="cartRecommendedProductsRow">
                    @php
                        // Get product categories from cart items
                        $categoryIds = [];
                        if(!empty($cartItems)) {
                            foreach($cartItems as $item) {
                                if($item->product && $item->product->categories) {
                                    $categoryIds = array_merge($categoryIds, $item->product->categories->pluck('id')->toArray());
                                }
                            }
                            $categoryIds = array_unique($categoryIds);
                        }
                        
                        // Get recommended products based on cart categories
                        if(!empty($categoryIds)) {
                            $recommendedProducts = \App\Models\Product::with(['categories', 'colors', 'sizes', 'images'])
                                ->whereHas('categories', function($query) use ($categoryIds) {
                                    $query->whereIn('categories.id', $categoryIds);
                                })
                                ->inRandomOrder()
                                ->limit(8)
                                ->get();
                                
                            // If not enough products, add some random ones
                            if($recommendedProducts->count() < 8) {
                                $existingIds = $recommendedProducts->pluck('id')->toArray();
                                $moreProducts = \App\Models\Product::with(['categories', 'colors', 'sizes', 'images'])
                                    ->whereNotIn('id', $existingIds)
                                    ->inRandomOrder()
                                    ->limit(8 - $recommendedProducts->count())
                                    ->get();
                                    
                                $recommendedProducts = $recommendedProducts->concat($moreProducts);
                            }
                        } else {
                            // If no cart items or categories, just get random products
                            $recommendedProducts = \App\Models\Product::with(['categories', 'colors', 'sizes', 'images'])
                                ->inRandomOrder()
                                ->limit(8)
                                ->get();
                        }
                    @endphp
                    
                    @foreach($recommendedProducts as $product)
                    <div class="recommended-item">
                        <x-product-card :product="$product" />
                    </div>
                    @endforeach
                </div>
            </div>
            
            <button class="btn nav-btn prev-btn" id="cartRecommendPrev" aria-label="Previous">
                <i class="bi bi-chevron-left"></i>
            </button>
            
            <button class="btn nav-btn next-btn" id="cartRecommendNext" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </button>
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
            if (swatch.dataset.color) {
                swatch.style.backgroundColor = swatch.dataset.color;
            }
        });
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
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
        
        // Recommended products navigation
        const recommendRow = document.getElementById('cartRecommendedProductsRow');
        const prevBtn = document.getElementById('cartRecommendPrev');
        const nextBtn = document.getElementById('cartRecommendNext');
        
        if (recommendRow && prevBtn && nextBtn) {
            let currentPosition = 0;
            const itemWidth = document.querySelector('.recommended-item')?.offsetWidth || 0;
            const visibleItems = window.innerWidth >= 768 ? 4 : 1; // Show only 1 item at a time on mobile
            const totalItems = recommendRow.querySelectorAll('.recommended-item').length;
            const maxPosition = Math.max(0, totalItems - visibleItems);
            
            // Initialize button states
            updateNavButtons();
            
            prevBtn.addEventListener('click', function() {
                if (currentPosition > 0) {
                    currentPosition--;
                    updateSliderPosition();
                }
            });
            
            nextBtn.addEventListener('click', function() {
                if (currentPosition < maxPosition) {
                    currentPosition++;
                    updateSliderPosition();
                }
            });
            
            // Improved touch swipe support
            let touchStartX = 0;
            let touchEndX = 0;
            let isSwiping = false;
            
            recommendRow.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                isSwiping = true;
            }, { passive: true });
            
            recommendRow.addEventListener('touchmove', function(e) {
                if (!isSwiping) return;
                
                const currentX = e.changedTouches[0].screenX;
                const diff = touchStartX - currentX;
                
                // Add resistance at the edges
                if ((currentPosition === 0 && diff < 0) || 
                    (currentPosition >= maxPosition && diff > 0)) {
                    return;
                }
                
                // Prevent default to avoid page scrolling
                e.preventDefault();
            }, { passive: false });
            
            recommendRow.addEventListener('touchend', function(e) {
                if (!isSwiping) return;
                
                touchEndX = e.changedTouches[0].screenX;
                const swipeDistance = touchStartX - touchEndX;
                handleSwipe(swipeDistance);
                isSwiping = false;
            }, { passive: true });
            
            function handleSwipe(swipeDistance) {
                const swipeThreshold = 50;
                if (swipeDistance > swipeThreshold) {
                    // Swipe left
                    if (currentPosition < maxPosition) {
                        currentPosition++;
                        updateSliderPosition();
                    }
                } else if (swipeDistance < -swipeThreshold) {
                    // Swipe right
                    if (currentPosition > 0) {
                        currentPosition--;
                        updateSliderPosition();
                    }
                }
            }
            
            function updateSliderPosition() {
                recommendRow.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
                updateNavButtons();
            }
            
            function updateNavButtons() {
                prevBtn.disabled = currentPosition === 0;
                nextBtn.disabled = currentPosition >= maxPosition;
                
                // Show/hide buttons based on position
                prevBtn.style.opacity = currentPosition === 0 ? '0' : '1';
                nextBtn.style.opacity = currentPosition >= maxPosition ? '0' : '1';
            }
            
            // Update on window resize
            window.addEventListener('resize', function() {
                const newItemWidth = document.querySelector('.recommended-item')?.offsetWidth || 0;
                const newVisibleItems = window.innerWidth >= 768 ? 4 : 1; // Show only 1 item at a time on mobile
                const newMaxPosition = Math.max(0, totalItems - newVisibleItems);
                
                // Reset position if needed
                if (currentPosition > newMaxPosition) {
                    currentPosition = newMaxPosition;
                }
                
                // Update with new dimensions
                updateSliderPosition();
            });
        }
        
        // Wishlist toggle functionality
        const wishlistBtns = document.querySelectorAll('.wishlist-toggle');
        wishlistBtns.forEach(btn => {
            // Check if product is in wishlist
            const productId = btn.getAttribute('data-product-id');
            
            // Make AJAX request to check if in wishlist
            fetch(`{{ url('wishlist/check') }}/${productId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
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
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Show loading state
                this.innerHTML = '<i class="bi bi-hourglass-split"></i>';
                this.disabled = true;
                
                // Toggle wishlist status
                fetch(`{{ url('wishlist/toggle') }}/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
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
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'notification notification-' + type;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.maxWidth = '300px';
            notification.style.padding = '15px';
            notification.style.borderRadius = 'var(--radius-md)';
            notification.style.backgroundColor = 'var(--surface)';
            notification.style.boxShadow = 'var(--shadow-lg)';
            notification.style.borderLeft = '4px solid var(--primary)';
            notification.style.transform = 'translateX(120%)';
            notification.style.opacity = '0';
            notification.style.transition = 'all 0.3s ease';
            
            if (type === 'success') {
                notification.style.borderLeftColor = 'var(--secondary)';
            } else if (type === 'error') {
                notification.style.borderLeftColor = '#dc3545';
            }
            
            let icon = 'check-circle';
            if (type === 'error') icon = 'exclamation-triangle';
            if (type === 'info') icon = 'info-circle';
            
            notification.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <i class="bi bi-${icon}" style="margin-right: 10px; font-size: 1.2rem;"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Add to DOM
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
                notification.style.opacity = '1';
            }, 10);
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(120%)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    });
</script>
@endpush