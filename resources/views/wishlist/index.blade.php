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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">{{ __('You might also like') }}</h3>
            <div class="recommendation-nav">
                <button class="btn btn-sm btn-icon nav-btn prev-btn me-2" id="wishlistRecommendPrev">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm btn-icon nav-btn next-btn" id="wishlistRecommendNext">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="recommended-products-container position-relative">
            <div class="recommended-products row g-4" id="wishlistRecommendedProductsRow">
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
                <div class="col-6 col-md-3 recommended-item">
                    <x-product-card :product="$product" />
                </div>
                @endforeach
            </div>
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
        
        // Remove from wishlist
        document.querySelectorAll('.remove-from-wishlist-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const id = this.dataset.id;
                const container = this.closest('.wishlist-card-container');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Show loading state
                this.innerHTML = '<i class="spinner-border spinner-border-sm"></i>';
                this.disabled = true;
                
                fetch(`{{ url('wishlist/remove') }}/${id}`, {
                    method: 'DELETE',
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
                    if (data.success) {
                        // Update wishlist count in header
                        const wishlistCounter = document.querySelector('.wishlist-counter');
                        if (wishlistCounter) {
                            wishlistCounter.textContent = data.wishlist_count;
                            if (data.wishlist_count === 0) {
                                wishlistCounter.style.display = 'none';
                            }
                        }
                        
                        // Remove container with animation
                        container.style.opacity = '0';
                        setTimeout(() => {
                            container.remove();
                            
                            // Check if wishlist is now empty
                            const wishlistGrid = document.querySelector('.wishlist-grid');
                            if (wishlistGrid && wishlistGrid.children.length === 0) {
                                // Reload page to show empty state
                                window.location.reload();
                            } else {
                                // Update count in header
                                const countHeader = document.querySelector('.card-header h5');
                                if (countHeader) {
                                    const remainingItems = wishlistGrid.children.length;
                                    countHeader.textContent = `${remainingItems} Items in your wishlist`;
                                }
                            }
                        }, 300);
                    } else {
                        alert(data.message || 'Failed to remove item');
                        this.innerHTML = '<i class="bi bi-trash me-1"></i> {{ __("Remove") }}';
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the item');
                    this.innerHTML = '<i class="bi bi-trash me-1"></i> {{ __("Remove") }}';
                    this.disabled = false;
                });
            });
        });
        
        // Initialize wishlist buttons
        document.querySelectorAll('.wishlist-toggle').forEach(function(btn) {
            btn.classList.add('active');
            btn.querySelector('i').classList.remove('bi-heart');
            btn.querySelector('i').classList.add('bi-heart-fill', 'text-danger');
        });
    });
</script>
@endpush 