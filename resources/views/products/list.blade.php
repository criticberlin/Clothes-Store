@extends('layouts.master')

@section('title', isset($category) ? ucfirst($category) . ' Category' : 'Products')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-capitalize">{{ isset($category) ? $category . ' Collection' : 'All Products' }}</h2>

    <!-- Filters and Sort Bar -->
    <div class="filters-bar mb-4">
        <div class="row g-3 align-items-center">
            <!-- Size Filters -->
            <div class="col-md-4">
                <div class="filter-section mb-0">
                    <h5 class="filter-heading">{{ __('Size') }}</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $sizes = \App\Models\Size::orderBy('name')->get();
                        @endphp
                        @foreach($sizes as $size)
                            <button class="size-filter" 
                                    data-filter="{{ $size->id }}" 
                                    data-type="size">
                                {{ $size->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Color Filters -->
            <div class="col-md-4">
                <div class="filter-section mb-0">
                    <h5 class="filter-heading">{{ __('Color') }}</h5>
                    <div class="d-flex flex-wrap gap-3">
                        @php
                            $colors = \App\Models\Color::orderBy('name')->get();
                        @endphp
                        @foreach($colors as $color)
                            <button class="color-filter" 
                                    data-filter="{{ $color->id }}" 
                                    data-type="color"
                                    data-color="{{ $color->hex_code }}"
                                    title="{{ $color->name }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Sort By Dropdown -->
            <div class="col-md-4">
                <div class="d-flex justify-content-md-end">
                    <div class="sort-dropdown">
                        <form action="{{ route('products.list') }}" method="GET" class="d-flex align-items-center" id="sortForm">
                            <!-- Preserve any existing query parameters -->
                            @if(request()->has('query'))
                                <input type="hidden" name="query" value="{{ request()->get('query') }}">
                            @endif
                            @if(request()->has('category_id'))
                                <input type="hidden" name="category_id" value="{{ request()->get('category_id') }}">
                            @endif
                            
                            <!-- Hidden filter inputs that will be populated by JS -->
                            <input type="hidden" name="sizes" id="sizesInput" value="">
                            <input type="hidden" name="colors" id="colorsInput" value="">
                            
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
            </div>
            
            <!-- Active Filters -->
            <div class="col-12 mt-2" id="activeFiltersContainer" style="display: none;">
                <div class="filter-section mb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="filter-heading mb-0">{{ __('Active Filters') }}</h5>
                        <button class="btn btn-sm btn-outline-danger" id="clearAllFilters">
                            <i class="bi bi-x-circle me-1"></i> {{ __('Clear All Filters') }}
                        </button>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-2" id="activeFilters">
                        <!-- Active filters will be added here dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> {{ __('general.no_products_found') }}
        </div>
    @else
        <!-- Products Grid -->
        <div class="row g-4" id="productsGrid">
            @foreach($products as $product)
                <div class="col-6 col-md-4 col-lg-3 product-item" 
                     data-sizes="{{ $product->sizes->pluck('id')->implode(',') }}"
                     data-colors="{{ $product->colors->pluck('id')->implode(',') }}">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->appends(request()->query())->links() }}
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

@push('styles')
<style>
/* Product Card Styles */
.product-card {
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    background-color: var(--surface);
    overflow: hidden;
    transition: all var(--transition-normal);
    position: relative;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
}

.product-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

.product-image-container {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1 / 1;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-normal);
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 1;
}

.product-badge.out-of-stock {
    background-color: var(--bs-danger);
    color: white;
}

.product-badge.new {
    background-color: var(--primary);
    color: white;
}

.color-swatches {
    position: absolute;
    bottom: 10px;
    left: 10px;
    display: flex;
    gap: 5px;
    z-index: 1;
}

.color-swatch {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    cursor: pointer;
}

.color-swatch.more-colors {
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: bold;
}

.product-title {
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.available-sizes {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.size-badge {
    font-size: 0.7rem;
    padding: 0.15rem 0.4rem;
    background-color: var(--surface-alt);
    color: var(--text-secondary);
    border-radius: var(--radius-sm);
}

.product-price {
    color: var(--primary);
    font-size: 1.1rem;
}

/* Filter Styles */
.filters-bar {
    background-color: var(--surface);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    border: 1px solid var(--border);
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.filter-section {
    margin-bottom: 1.5rem;
}

.filter-heading {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
}

.size-filter {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border);
    background-color: var(--surface);
    color: var(--text-secondary);
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-weight: 500;
    transition: all var(--transition-normal);
    cursor: pointer;
}

.size-filter:hover {
    background-color: var(--surface-alt);
}

.size-filter.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Color Filter */
.color-filter {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid var(--border);
    cursor: pointer;
    transition: all var(--transition-normal);
    position: relative;
}

.color-filter:hover {
    transform: scale(1.1);
    box-shadow: 0 0 0 2px var(--primary-light);
}

.color-filter.active {
    box-shadow: 0 0 0 2px var(--primary);
    transform: scale(1.1);
}

.color-filter.active::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    text-shadow: 0 0 2px rgba(0, 0, 0, 0.8);
    font-size: 16px;
    font-weight: bold;
}

/* Active Filters */
.active-filter-tag {
    background-color: var(--surface-alt);
    color: var(--text-primary);
    border-radius: 30px;
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all var(--transition-normal);
}

.active-filter-tag .remove-filter {
    color: var(--text-tertiary);
    cursor: pointer;
    transition: all var(--transition-normal);
}

.active-filter-tag .remove-filter:hover {
    color: var(--primary);
}

.sort-dropdown .form-select {
    min-width: 180px;
}

.btn-icon {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    transition: all var(--transition-normal);
    z-index: 2;
    position: relative;
}

.btn-icon:hover {
    color: var(--primary);
}

.btn-icon.active {
    color: var(--primary);
}

.wishlist-toggle .bi-heart-fill {
    color: var(--primary);
}

.color-swatch-lg {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid var(--border);
    cursor: pointer;
}

.size-option {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
}

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

/* Responsive adjustments */
@media (max-width: 767px) {
    .filter-section {
        margin-bottom: 1rem;
    }
    
    .filter-heading {
        font-size: 0.9rem;
    }
    
    .size-filter {
        min-width: 35px;
        height: 35px;
    }
    
    .filters-bar {
        padding: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Apply color swatches background
        document.querySelectorAll('.color-swatch[data-color]').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
        
        // Apply color filter background
        document.querySelectorAll('.color-filter[data-color]').forEach(function(filter) {
            filter.style.backgroundColor = filter.dataset.color;
        });
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Filter state
        const filterState = {
            sizes: [],
            colors: []
        };
        
        // DOM elements
        const productsGrid = document.getElementById('productsGrid');
        const activeFiltersContainer = document.getElementById('activeFiltersContainer');
        const activeFiltersDiv = document.getElementById('activeFilters');
        const clearAllFiltersBtn = document.getElementById('clearAllFilters');
        const sizesInput = document.getElementById('sizesInput');
        const colorsInput = document.getElementById('colorsInput');
        const sortForm = document.getElementById('sortForm');
        
        // Size filter functionality
        const sizeFilters = document.querySelectorAll('.size-filter');
        sizeFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                const filterId = this.dataset.filter;
                const filterType = this.dataset.type;
                
                // Toggle this filter
                const filterIndex = filterState.sizes.indexOf(filterId);
                if (filterIndex === -1) {
                    // Add filter
                    filterState.sizes.push(filterId);
                    this.classList.add('active');
                } else {
                    // Remove filter
                    filterState.sizes.splice(filterIndex, 1);
                    this.classList.remove('active');
                }
                
                // Update active filters display
                updateActiveFilters();
                
                // Apply filters
                applyFilters();
            });
        });
        
        // Color filter functionality
        const colorFilters = document.querySelectorAll('.color-filter');
        colorFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                const filterId = this.dataset.filter;
                const filterType = this.dataset.type;
                
                // Toggle this filter
                const filterIndex = filterState.colors.indexOf(filterId);
                if (filterIndex === -1) {
                    // Add filter
                    filterState.colors.push(filterId);
                    this.classList.add('active');
                } else {
                    // Remove filter
                    filterState.colors.splice(filterIndex, 1);
                    this.classList.remove('active');
                }
                
                // Update active filters display
                updateActiveFilters();
                
                // Apply filters
                applyFilters();
            });
        });
        
        // Clear all filters
        clearAllFiltersBtn.addEventListener('click', function() {
            // Reset filter state
            filterState.sizes = [];
            filterState.colors = [];
            
            // Reset UI
            sizeFilters.forEach(f => f.classList.remove('active'));
            colorFilters.forEach(f => f.classList.remove('active'));
            
            // Update active filters display
            updateActiveFilters();
            
            // Apply filters
            applyFilters();
        });
        
        // Function to update active filters display
        function updateActiveFilters() {
            // Clear current active filters
            activeFiltersDiv.innerHTML = '';
            
            // Add size filters
            filterState.sizes.forEach(sizeId => {
                const filter = document.querySelector(`.size-filter[data-filter="${sizeId}"]`);
                if (filter) {
                    const filterName = filter.textContent.trim();
                    addActiveFilterTag(sizeId, filterName, 'size');
                }
            });
            
            // Add color filters
            filterState.colors.forEach(colorId => {
                const filter = document.querySelector(`.color-filter[data-filter="${colorId}"]`);
                if (filter) {
                    const filterName = filter.title;
                    const filterColor = filter.dataset.color;
                    addActiveFilterTag(colorId, filterName, 'color', filterColor);
                }
            });
            
            // Show/hide active filters container
            const hasActiveFilters = filterState.sizes.length > 0 || filterState.colors.length > 0;
                                    
            activeFiltersContainer.style.display = hasActiveFilters ? 'block' : 'none';
            
            // Update hidden inputs for form submission
            sizesInput.value = filterState.sizes.join(',');
            colorsInput.value = filterState.colors.join(',');
        }
        
        // Function to add an active filter tag
        function addActiveFilterTag(id, name, type, color = null) {
            const tag = document.createElement('div');
            tag.className = 'active-filter-tag';
            tag.dataset.id = id;
            tag.dataset.type = type;
            
            let tagContent = '';
            
            if (type === 'color' && color) {
                tagContent = `
                    <span class="d-flex align-items-center">
                        <span class="color-swatch me-1" style="background-color: ${color}; width: 16px; height: 16px;"></span>
                        ${name}
                    </span>
                `;
            } else {
                tagContent = `<span>${name}</span>`;
            }
            
            tagContent += `<i class="bi bi-x remove-filter"></i>`;
            tag.innerHTML = tagContent;
            
            // Add click handler to remove filter
            tag.querySelector('.remove-filter').addEventListener('click', function() {
                removeFilter(id, type);
            });
            
            activeFiltersDiv.appendChild(tag);
        }
        
        // Function to remove a filter
        function removeFilter(id, type) {
            switch (type) {
                case 'size':
                    const sizeIndex = filterState.sizes.indexOf(id);
                    if (sizeIndex !== -1) {
                        filterState.sizes.splice(sizeIndex, 1);
                        document.querySelector(`.size-filter[data-filter="${id}"]`).classList.remove('active');
                    }
                    break;
                    
                case 'color':
                    const colorIndex = filterState.colors.indexOf(id);
                    if (colorIndex !== -1) {
                        filterState.colors.splice(colorIndex, 1);
                        document.querySelector(`.color-filter[data-filter="${id}"]`).classList.remove('active');
                    }
                    break;
            }
            
            // Update active filters display
            updateActiveFilters();
            
            // Apply filters
            applyFilters();
        }
        
        // Function to apply filters
        function applyFilters() {
            const productItems = document.querySelectorAll('.product-item');
            let visibleCount = 0;
            
            productItems.forEach(item => {
                let showItem = true;
                
                // Check size filters
                if (showItem && filterState.sizes.length > 0) {
                    const productSizes = item.dataset.sizes.split(',');
                    const hasMatchingSize = filterState.sizes.some(sizeId => 
                        productSizes.includes(sizeId)
                    );
                    
                    if (!hasMatchingSize) {
                        showItem = false;
                    }
                }
                
                // Check color filters
                if (showItem && filterState.colors.length > 0) {
                    const productColors = item.dataset.colors.split(',');
                    const hasMatchingColor = filterState.colors.some(colorId => 
                        productColors.includes(colorId)
                    );
                    
                    if (!hasMatchingColor) {
                        showItem = false;
                    }
                }
                
                // Show/hide item
                if (showItem) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Check if any products are visible
            if (visibleCount === 0) {
                // No products match filters
                if (!document.getElementById('no-products-message')) {
                    const noProductsMessage = document.createElement('div');
                    noProductsMessage.id = 'no-products-message';
                    noProductsMessage.className = 'alert alert-info col-12 text-center';
                    noProductsMessage.innerHTML = '<i class="bi bi-info-circle me-2"></i> {{ __("No products match your selected filters") }}';
                    productsGrid.appendChild(noProductsMessage);
                }
            } else {
                // Remove no products message if it exists
                const noProductsMessage = document.getElementById('no-products-message');
                if (noProductsMessage) {
                    noProductsMessage.remove();
                }
            }
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
                    if (data.success) {
                        if (data.in_wishlist) {
                            btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
                            btn.classList.add('active');
                            showNotification('Product added to wishlist', 'success');
                        } else {
                            btn.innerHTML = '<i class="bi bi-heart"></i>';
                            btn.classList.remove('active');
                            showNotification('Product removed from wishlist', 'info');
                        }
                    } else {
                        // If not authenticated, redirect to login
                        if (data.message.includes('login')) {
                            window.location.href = '/login';
                        }
                        }
                    })
                    .catch(error => {
                    console.error('Error toggling wishlist:', error);
                    });
            });
        });
        
        // Initialize filters from URL parameters
        function initializeFiltersFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // Initialize sizes
            if (urlParams.has('sizes')) {
                const sizeIds = urlParams.get('sizes').split(',').filter(id => id.trim() !== '');
                sizeIds.forEach(id => {
                    const filter = document.querySelector(`.size-filter[data-filter="${id}"]`);
                    if (filter) {
                        filter.classList.add('active');
                        filterState.sizes.push(id);
                    }
                });
            }
            
            // Initialize colors
            if (urlParams.has('colors')) {
                const colorIds = urlParams.get('colors').split(',').filter(id => id.trim() !== '');
                colorIds.forEach(id => {
                    const filter = document.querySelector(`.color-filter[data-filter="${id}"]`);
                    if (filter) {
                        filter.classList.add('active');
                        filterState.colors.push(id);
                    }
                });
            }
            
            // Update active filters display
            updateActiveFilters();
            
            // Apply filters
            applyFilters();
        }
        
        // Initialize filters from URL
        initializeFiltersFromUrl();
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
</script>
@endpush
