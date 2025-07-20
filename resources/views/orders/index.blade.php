@extends('layouts.master')

@section('title', __('My Orders'))

@push('styles')
<style>
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
</style>
@endpush

@section('content')
<div class="container py-4">
    <h2 class="mb-4">{{ __('My Orders') }}</h2>

    @if($orders->isEmpty())
        <div class="alert alert-info">
            {{ __('You haven\'t placed any orders yet.') }}
        </div>
    @else
        @foreach($orders as $order)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ __('Order') }} #{{ $order->id }}</h5>
                        <small class="text-secondary">{{ __('Placed on') }} {{ $order->created_at->format('F j, Y') }}</small>
                    </div>
                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'info') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('Shipping Address') }}:</strong>
                            <p class="mb-0">
                                @php
                                    $shippingAddress = $order->shipping_address;
                                    if (is_array($shippingAddress)) {
                                        $shippingAddress = json_encode($shippingAddress);
                                    }
                                @endphp
                                {{ $shippingAddress }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Payment Method') }}:</strong>
                            <p class="mb-0">
                                @php
                                    $paymentMethod = $order->payment_method;
                                    if (is_array($paymentMethod)) {
                                        $paymentMethod = json_encode($paymentMethod);
                                    } else {
                                        $paymentMethod = ucfirst($paymentMethod);
                                    }
                                @endphp
                                {{ $paymentMethod }}
                            </p>
                        </div>
                    </div>

                    <h6>{{ __('Order Items') }}:</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Color') }}</th>
                                    <th>{{ __('Size') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            @php
                                                $productName = 'N/A';
                                                if ($item->product && isset($item->product->name)) {
                                                    if (is_string($item->product->name)) {
                                                        $productName = $item->product->name;
                                                    } elseif (is_array($item->product->name)) {
                                                        $productName = json_encode($item->product->name);
                                                    }
                                                }
                                            @endphp
                                            {{ $productName }}
                                        </td>
                                        <td>
                                            @php
                                                $colorName = 'N/A';
                                                if ($item->color && isset($item->color->name)) {
                                                    if (is_string($item->color->name)) {
                                                        $colorName = $item->color->name;
                                                    } elseif (is_array($item->color->name)) {
                                                        $colorName = json_encode($item->color->name);
                                                    }
                                                }
                                            @endphp
                                            {{ $colorName }}
                                        </td>
                                        <td>
                                            @php
                                                $sizeName = 'N/A';
                                                if ($item->size && isset($item->size->name)) {
                                                    if (is_string($item->size->name)) {
                                                        $sizeName = $item->size->name;
                                                    } elseif (is_array($item->size->name)) {
                                                        $sizeName = json_encode($item->size->name);
                                                    }
                                                }
                                            @endphp
                                            {{ $sizeName }}
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            @php
                                                $price = $item->price;
                                                if (is_array($price)) {
                                                    $price = 0;
                                                }
                                            @endphp
                                            {{ app(\App\Services\CurrencyService::class)->formatPrice($price) }}
                                        </td>
                                        <td>
                                            @php
                                                $totalPrice = $item->price * $item->quantity;
                                                if (is_array($totalPrice)) {
                                                    $totalPrice = 0;
                                                }
                                            @endphp
                                            {{ app(\App\Services\CurrencyService::class)->formatPrice($totalPrice) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>{{ __('Total Amount') }}:</strong></td>
                                    <td>
                                        <strong>
                                            @php
                                                $totalAmount = $order->total_amount;
                                                if (is_array($totalAmount)) {
                                                    $totalAmount = 0;
                                                }
                                            @endphp
                                            {{ app(\App\Services\CurrencyService::class)->formatPrice($totalAmount) }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    
    <!-- You might also like section -->
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
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Apply color swatches background for product cards
        document.querySelectorAll('.color-swatch[data-color]').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
        
        // Initialize wishlist buttons
        document.querySelectorAll('.wishlist-toggle').forEach(btn => {
            const productId = btn.getAttribute('data-product-id');
            
            // Check if product is in wishlist
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
                }
            })
            .catch(error => {
                console.error('Error checking wishlist status:', error);
            });
            
            // Toggle wishlist on click
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