@extends('layouts.master')

@section('title', __('My Wishlist'))

@push('styles')
<style>
.empty-wishlist-icon {
    font-size: 5rem;
    color: var(--primary-light);
}

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
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
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ __('My Wishlist') }}</h1>
        <a href="{{ route('products.list') }}" class="btn btn-outline-primary">
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

    @if($wishlistItems->count() > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $wishlistItems->count() }} {{ __('Items in your wishlist') }}</h5>
                <a href="{{ route('wishlist.clear') }}" class="btn btn-sm btn-outline-danger" 
                   onclick="return confirm('Are you sure you want to clear your wishlist?')">
                    <i class="bi bi-trash me-1"></i> {{ __('Clear All') }}
                </a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="wishlist-grid">
                @foreach($wishlistItems as $item)
                <div class="wishlist-card-container" data-wishlist-id="{{ $item->id }}">
                    <div class="position-relative">
                        <x-product-card :product="$item->product" />
                        <div class="d-flex justify-content-between mt-2">
                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger remove-from-wishlist-btn" 
                                        data-id="{{ $item->id }}">
                                    <i class="bi bi-trash me-1"></i> {{ __('Remove') }}
                                </button>
                            </form>
                            
                            @if($item->product->quantity > 0)
                            <form action="{{ route('cart.add', $item->product->id) }}" method="POST" class="d-inline ms-2">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-cart-plus me-1"></i> {{ __('Add to Cart') }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- You might also like section -->
    <div class="mt-5">
        <h3 class="mb-4">{{ __('You might also like') }}</h3>
        
        <div class="recommended-section">
            <div class="recommended-products-container">
                <div class="recommended-products row g-0" id="wishlistRecommendedProductsRow">
                    @php
                        // Get product categories from wishlist items
                        $categoryIds = [];
                        foreach($wishlistItems as $item) {
                            if($item->product && $item->product->categories) {
                                $categoryIds = array_merge($categoryIds, $item->product->categories->pluck('id')->toArray());
                            }
                        }
                        $categoryIds = array_unique($categoryIds);
                        
                        // Get recommended products based on wishlist categories
                        $wishlistProductIds = $wishlistItems->pluck('product_id')->toArray();
                        $recommendedProducts = \App\Models\Product::with(['categories', 'colors', 'sizes', 'images'])
                            ->whereHas('categories', function($query) use ($categoryIds) {
                                $query->whereIn('categories.id', $categoryIds);
                            })
                            ->whereNotIn('id', $wishlistProductIds)
                            ->inRandomOrder()
                            ->limit(8)
                            ->get();
                            
                        // If not enough products, add some random ones
                        if($recommendedProducts->count() < 8) {
                            $existingIds = $recommendedProducts->pluck('id')
                                ->merge($wishlistProductIds)
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
                    <div class="recommended-item">
                        <x-product-card :product="$product" />
                    </div>
                    @endforeach
                </div>
            </div>
            
            <button class="btn nav-btn prev-btn" id="wishlistRecommendPrev" aria-label="Previous">
                <i class="bi bi-chevron-left"></i>
            </button>
            
            <button class="btn nav-btn next-btn" id="wishlistRecommendNext" aria-label="Next">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-heart empty-wishlist-icon"></i>
        </div>
        <h2 class="mb-3">{{ __('Your wishlist is empty') }}</h2>
        <p class="text-secondary mb-4 mx-auto" style="max-width: 500px;">
            {{ __('Add items to your wishlist by clicking the heart icon on product pages') }}
        </p>
        <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
            {{ __('Explore Products') }}
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Apply color swatches background
        document.querySelectorAll('.color-swatch[data-color]').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
        
        // Recommended products navigation
        const recommendRow = document.getElementById('wishlistRecommendedProductsRow');
        const prevBtn = document.getElementById('wishlistRecommendPrev');
        const nextBtn = document.getElementById('wishlistRecommendNext');
        
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
    });
</script>
@endpush 