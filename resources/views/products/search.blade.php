@extends('layouts.master')
@section('title', __('general.search_results'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 fw-bold mb-1">{{ __('general.search_results') }}</h1>
                    @if(!empty($query))
                        <p class="text-secondary">{{ __('general.search_results_for', ['query' => $query]) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Clean Sort By Dropdown -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-secondary">{{ count($products) }} {{ __('general.products_found') }}</span>
            @if($selectedCategory)
                <span class="ms-2 badge bg-primary">{{ $selectedCategory->name }}</span>
            @endif
        </div>
        
        <div class="sort-dropdown">
            <form action="{{ route('products.search') }}" method="GET" class="d-flex align-items-center">
                <!-- Preserve existing query parameters -->
                <input type="hidden" name="q" value="{{ $query }}">
                @if(request()->has('category_id'))
                    <input type="hidden" name="category_id" value="{{ request()->get('category_id') }}">
                @endif
                
                <label for="sortOrder" class="me-2 text-nowrap">{{ __('general.sort_by') }}:</label>
                <select class="form-select form-select-sm" id="sortOrder" name="sort" onchange="this.form.submit()">
                    <option value="" {{ request()->get('sort') == '' ? 'selected' : '' }}>{{ __('general.relevance') }}</option>
                    <option value="price_low" {{ request()->get('sort') == 'price_low' ? 'selected' : '' }}>{{ __('general.price_low_to_high') }}</option>
                    <option value="price_high" {{ request()->get('sort') == 'price_high' ? 'selected' : '' }}>{{ __('general.price_high_to_low') }}</option>
                    <option value="newest" {{ request()->get('sort') == 'newest' ? 'selected' : '' }}>{{ __('general.newest_arrivals') }}</option>
                    <option value="name" {{ request()->get('sort') == 'name' ? 'selected' : '' }}>{{ __('general.name') }}</option>
                </select>
            </form>
        </div>
    </div>

    @if(isset($products) && count($products) > 0)
        <!-- Products grid with category grouping -->
        @php
            $productsByCategory = $products->groupBy(function($product) {
                return $product->categories->first() ? $product->categories->first()->name : 'Uncategorized';
            });
        @endphp

        @foreach($productsByCategory as $category => $categoryProducts)
            <div class="category-section mb-5">
                <h3 class="h5 mb-3 border-bottom pb-2">{{ $category }}</h3>
                <div class="row g-4">
                    @foreach($categoryProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3 product-card-wrapper" 
                             data-price="{{ $product->price }}" 
                             data-date="{{ $product->created_at->timestamp }}">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        
        <div class="d-flex justify-content-center mt-5">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-search display-4 text-secondary mb-4"></i>
                    <h2 class="h4 mb-3">{{ __('general.no_search_results') }}</h2>
                    <p class="text-secondary mb-4">{{ __('general.try_different_keywords') }}</p>
                    <a href="{{ route('products.list') }}" class="btn btn-primary">{{ __('general.browse_all_products') }}</a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="quickViewContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Apply color swatches background
        document.querySelectorAll('.color-swatch[data-color]').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Sorting functionality
        const sortSelect = document.getElementById('sortOrder');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value;
                const productCards = document.querySelectorAll('.product-card-wrapper');
                const productsArray = Array.from(productCards);
                
                // Sort the products based on the selected option
                productsArray.sort(function(a, b) {
                    switch(sortValue) {
                        case 'price_low':
                            return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                        case 'price_high':
                            return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                        case 'newest':
                            return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                        default: // relevance - keep original order
                            return 0;
                    }
                });
                
                // Reorder the DOM elements
                const categoryContainers = document.querySelectorAll('.category-section .row');
                categoryContainers.forEach(container => {
                    const categoryProducts = productsArray.filter(product => 
                        container.contains(product)
                    );
                    
                    categoryProducts.forEach(product => {
                        container.appendChild(product);
                    });
                });
            });
        }
        
        // Quick View functionality
        const quickViewBtns = document.querySelectorAll('.quick-view-btn');
        const quickViewModal = new bootstrap.Modal(document.getElementById('quickViewModal'));
        const quickViewContent = document.getElementById('quickViewContent');
        
        quickViewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                quickViewContent.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
                quickViewModal.show();
                
                // Fetch product details
                fetch(`/api/products/${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.product) {
                            const product = data.product;
                            quickViewContent.innerHTML = `
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="${product.image}" alt="${product.name}" class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-6">
                                        <h2 class="h4 mb-2">${product.name}</h2>
                                        <div class="mb-3">
                                            <span class="h5 text-primary">${product.formatted_price}</span>
                                        </div>
                                        <p class="mb-4">${product.description}</p>
                                        <div class="d-flex gap-2 mb-4">
                                            <a href="/product/${product.id}" class="btn btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i> View Details
                                            </a>
                                            <a href="/cart/add/${product.id}" class="btn btn-primary add-to-cart-btn" data-method="post">
                                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                            </a>
                                        </div>
                                        <div class="product-meta">
                                            <div class="mb-2"><strong>Category:</strong> ${product.category_name}</div>
                                            <div class="mb-2"><strong>Code:</strong> ${product.code}</div>
                                            <div><strong>Availability:</strong> 
                                                <span class="${product.quantity > 0 ? 'text-success' : 'text-danger'}">
                                                    ${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            // Reinitialize add to cart buttons
                            initializeCartButtons();
                        } else {
                            quickViewContent.innerHTML = `
                                <div class="text-center py-4">
                                    <div class="alert alert-warning">
                                        Product not found
                                    </div>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching product:', error);
                        quickViewContent.innerHTML = `
                            <div class="text-center py-4">
                                <div class="alert alert-danger">
                                    Error loading product details
                                </div>
                            </div>
                        `;
                    });
            });
        });
        
        // Wishlist functionality
        const wishlistBtns = document.querySelectorAll('.wishlist-toggle');
        wishlistBtns.forEach(btn => {
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

@push('styles')
<style>
/* Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    max-width: 300px;
    padding: 15px;
    border-radius: var(--radius-md);
    background-color: var(--surface);
    box-shadow: var(--shadow-lg);
    border-left: 4px solid var(--primary);
    transform: translateX(120%);
    opacity: 0;
    transition: all 0.3s ease;
}

.notification.show {
    transform: translateX(0);
    opacity: 1;
}

.notification-content {
    display: flex;
    align-items: center;
}

.notification-content i {
    margin-right: 10px;
    font-size: 1.2rem;
}

.notification-success {
    border-left-color: var(--secondary);
}

.notification-success i {
    color: var(--secondary);
}

.notification-error {
    border-left-color: #dc3545;
}

.notification-error i {
    color: #dc3545;
}

.notification-info {
    border-left-color: var(--primary);
}

.notification-info i {
    color: var(--primary);
}
</style>
@endpush 