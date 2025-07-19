@extends('layouts.master')

@section('title', __('My Orders'))

@push('styles')
<style>
/* Recommendation Navigation */
.recommended-products-container {
    overflow: hidden;
}

.recommended-products {
    display: flex;
    flex-wrap: nowrap;
    transition: transform 0.5s ease;
}

.recommended-item {
    flex: 0 0 auto;
    width: 50%;
}

@media (min-width: 768px) {
    .recommended-item {
        width: 25%;
    }
}

.nav-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: var(--surface);
    border: 1px solid var(--border);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all var(--transition-normal);
}

.nav-btn:hover {
    background-color: var(--primary);
    color: white;
    transform: translateY(-2px);
}

.nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
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
                            <p class="mb-0" >{{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Payment Method') }}:</strong>
                            <p class="mb-0">{{ ucfirst($order->payment_method) }}</p>
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
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->color ? $item->color->name : 'N/A' }}</td>
                                        <td>{{ $item->size ? $item->size->name : 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ app(\App\Services\CurrencyService::class)->formatPrice($item->price) }}</td>
                                        <td>{{ app(\App\Services\CurrencyService::class)->formatPrice($item->price * $item->quantity) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>{{ __('Total Amount') }}:</strong></td>
                                    <td><strong>{{ app(\App\Services\CurrencyService::class)->formatPrice($order->total_amount) }}</strong></td>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">{{ __('You might also like') }}</h3>
            <div class="recommendation-nav">
                <button class="btn btn-sm btn-icon nav-btn prev-btn me-2" id="orderRecommendPrev">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm btn-icon nav-btn next-btn" id="orderRecommendNext">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="recommended-products-container position-relative">
            <div class="recommended-products row g-4" id="orderRecommendedProductsRow">
                @php
                    // Get product categories from order items
                    $categoryIds = [];
                    $purchasedProductIds = [];
                    
                    foreach($orders as $order) {
                        foreach($order->items as $item) {
                            if($item->product && $item->product->categories) {
                                $categoryIds = array_merge($categoryIds, $item->product->categories->pluck('id')->toArray());
                                $purchasedProductIds[] = $item->product_id;
                            }
                        }
                    }
                    $categoryIds = array_unique($categoryIds);
                    $purchasedProductIds = array_unique($purchasedProductIds);
                    
                    // Get recommended products based on order categories
                    $recommendedProducts = \App\Models\Product::with(['categories', 'colors', 'sizes', 'images'])
                        ->whereHas('categories', function($query) use ($categoryIds) {
                            $query->whereIn('categories.id', $categoryIds);
                        })
                        ->whereNotIn('id', $purchasedProductIds)
                        ->inRandomOrder()
                        ->limit(8)
                        ->get();
                        
                    // If not enough products, add some random ones
                    if($recommendedProducts->count() < 8) {
                        $existingIds = $recommendedProducts->pluck('id')
                            ->merge($purchasedProductIds)
                            ->toArray();
                            
                        $moreProducts = \App\Models\Product::with(['categories', 'colors', 'sizes', 'images'])
                            ->whereNotIn('id', $existingIds)
                            ->inRandomOrder()
                            ->limit(8 - $recommendedProducts->count())
                            ->get();
                            
                        $recommendedProducts = $recommendedProducts->concat($moreProducts);
                    }
                @endphp
                
                @foreach($recommendedProducts as $product)
                <div class="col-6 col-md-3 recommended-item">
                    <x-product-card :product="$product" />
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Recommended products navigation
        const recommendRow = document.getElementById('orderRecommendedProductsRow');
        const prevBtn = document.getElementById('orderRecommendPrev');
        const nextBtn = document.getElementById('orderRecommendNext');
        
        if (recommendRow && prevBtn && nextBtn) {
            let currentPosition = 0;
            const itemWidth = document.querySelector('.recommended-item')?.offsetWidth || 0;
            const visibleItems = window.innerWidth >= 768 ? 4 : 2;
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
            
            function updateSliderPosition() {
                recommendRow.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
                updateNavButtons();
            }
            
            function updateNavButtons() {
                prevBtn.disabled = currentPosition === 0;
                nextBtn.disabled = currentPosition >= maxPosition;
            }
            
            // Update on window resize
            window.addEventListener('resize', function() {
                const newItemWidth = document.querySelector('.recommended-item')?.offsetWidth || 0;
                const newVisibleItems = window.innerWidth >= 768 ? 4 : 2;
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