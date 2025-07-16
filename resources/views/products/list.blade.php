@extends('layouts.master')

@section('title', isset($category) ? ucfirst($category) . ' Category' : 'Products')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-capitalize">{{ isset($category) ? $category . ' Collection' : 'All Products' }}</h2>

    <!-- Clothing Filters -->
    <div class="clothing-filters mb-4">
        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="text-secondary me-2">{{ __('Filter by') }}:</span>
                    <div class="filter-pills">
                        <button class="filter-pill active" data-filter="all">All</button>
                        <button class="filter-pill" data-filter="tops">Tops</button>
                        <button class="filter-pill" data-filter="bottoms">Bottoms</button>
                        <button class="filter-pill" data-filter="dresses">Dresses</button>
                        <button class="filter-pill" data-filter="outerwear">Outerwear</button>
                        <button class="filter-pill" data-filter="accessories">Accessories</button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="text-secondary me-2">{{ __('Size') }}:</span>
                    <div class="size-filters">
                        <button class="size-filter" data-size="xs">XS</button>
                        <button class="size-filter" data-size="s">S</button>
                        <button class="size-filter" data-size="m">M</button>
                        <button class="size-filter" data-size="l">L</button>
                        <button class="size-filter" data-size="xl">XL</button>
                        <button class="size-filter" data-size="xxl">XXL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean Sort By Dropdown -->
    <div class="d-flex justify-content-end mb-4">
        <div class="sort-dropdown">
            <form action="{{ route('products.list') }}" method="GET" class="d-flex align-items-center">
                <!-- Preserve any existing query parameters -->
                @if(request()->has('query'))
                    <input type="hidden" name="query" value="{{ request()->get('query') }}">
                @endif
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

    @if($products->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> {{ __('general.no_products_found') }}
        </div>
    @else
        <!-- Products Grid -->
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card h-100">
                        <a href="{{ route('products.details', $product->id) }}" class="product-card-link">
                        <div class="product-image-container">
                                <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="img-fluid product-image">
                                
                            <!-- Product Badges -->
                            @if($product->quantity <= 0)
                                <div class="product-badge out-of-stock">{{ __('general.out_of_stock') }}</div>
                            @elseif($product->created_at && $product->created_at->diffInDays(now()) <= 7)
                                <div class="product-badge new">{{ __('general.new') }}</div>
                            @endif
                            
                            <!-- Color Swatches -->
                            @if($product->colors && $product->colors->count() > 0)
                                <div class="color-swatches">
                                    @foreach($product->colors->take(4) as $color)
                                        <div class="color-swatch" data-color="{{ $color->hex_code }}" title="{{ $color->name }}"></div>
                                    @endforeach
                                    @if($product->colors->count() > 4)
                                        <div class="color-swatch more-colors">+{{ $product->colors->count() - 4 }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="product-info p-3">
                            <div class="product-category text-tertiary small mb-1">
                                @if($product->categories->isNotEmpty())
                                    {{ $product->categories->first()->name }}
                                @endif
                            </div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <h3 class="product-title h6 mb-1">{{ $product->name }}</h3>
                                    <button type="button" class="btn btn-sm btn-icon wishlist-toggle p-0 m-0" 
                                            data-product-id="{{ $product->id }}" 
                                            data-bs-toggle="tooltip" 
                                            title="{{ __('Add to Wishlist') }}">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                            <!-- Available Sizes -->
                            @if($product->sizes && $product->sizes->count() > 0)
                                <div class="available-sizes mb-2">
                                    @foreach($product->sizes->take(5) as $size)
                                        <span class="size-badge">{{ $size->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="product-price fw-bold">
                                        {{ app(\App\Services\CurrencyService::class)->formatPrice($product->price) }}
                                </div>
                                <div class="product-rating">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="ms-1 small">{{ number_format($product->average_rating, 1) }}</span>
                                    @if($product->ratings_count > 0)
                                        <span class="text-tertiary small">({{ $product->ratings_count }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
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

.filter-pill {
    border: 1px solid var(--border);
    background-color: var(--surface);
    color: var(--text-secondary);
    padding: 0.5rem 1rem;
    border-radius: 30px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all var(--transition-normal);
    cursor: pointer;
}

.filter-pill:hover {
    background-color: var(--surface-alt);
}

.filter-pill.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
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
</style>
@endpush

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
                    if (data.success) {
                        if (data.in_wishlist) {
                            btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
                            btn.classList.add('active');
                        } else {
                            btn.innerHTML = '<i class="bi bi-heart"></i>';
                            btn.classList.remove('active');
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
        
        // Filter functionality
        const filterPills = document.querySelectorAll('.filter-pill');
        filterPills.forEach(pill => {
            pill.addEventListener('click', function() {
                // Remove active class from all pills
                filterPills.forEach(p => p.classList.remove('active'));
                // Add active class to clicked pill
                this.classList.add('active');
                
                // Filter logic would go here
                // For now, we're just showing the UI
            });
        });
        
        // Size filter functionality
        const sizeFilters = document.querySelectorAll('.size-filter');
        sizeFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                this.classList.toggle('active');
                // Size filter logic would go here
            });
        });
    });
</script>
@endpush
