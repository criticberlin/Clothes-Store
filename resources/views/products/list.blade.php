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
                        <div class="product-image-container">
                            @if($product->photo)
                                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="img-fluid product-image">
                            @else
                                <img src="{{ asset('images/products/default.jpg') }}" alt="{{ $product->name }}" class="img-fluid product-image">
                            @endif
                            <div class="product-actions">
                                <a href="{{ route('cart.add', $product->id) }}" class="btn btn-sm btn-primary rounded-circle add-to-cart-btn" data-method="post" title="Add to Cart">
                                    <i class="bi bi-cart-plus"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-light rounded-circle quick-view-btn" data-product-id="{{ $product->id }}" title="Quick View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-light rounded-circle add-to-wishlist-btn" title="Add to Wishlist">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
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
                            <h3 class="product-title h6 mb-1">
                                <a href="{{ route('products.details', $product->id) }}" class="text-reset text-decoration-none">
                                    {{ $product->name }}
                                </a>
                            </h3>
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
                                    {{ number_format($product->price, 2) }} {{ config('app.currency_symbol', '$') }}
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Apply color swatches background
        document.querySelectorAll('.color-swatch[data-color]').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
        
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
                            
                            // Generate color swatches HTML
                            let colorSwatches = '';
                            if (product.colors && product.colors.length > 0) {
                                colorSwatches = `
                                    <div class="mb-4">
                                        <h6 class="mb-2">Available Colors:</h6>
                                        <div class="d-flex gap-2">
                                            ${product.colors.map(color => 
                                                `<div class="color-swatch-lg" data-color="${color.hex_code}" title="${color.name}"></div>`
                                            ).join('')}
                                        </div>
                                    </div>
                                `;
                            }
                            
                            // Generate sizes HTML
                            let sizesHtml = '';
                            if (product.sizes && product.sizes.length > 0) {
                                sizesHtml = `
                                    <div class="mb-4">
                                        <h6 class="mb-2">Available Sizes:</h6>
                                        <div class="d-flex gap-2">
                                            ${product.sizes.map(size => 
                                                `<div class="size-option">${size.name}</div>`
                                            ).join('')}
                                        </div>
                                    </div>
                                `;
                            }
                            
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
                                        
                                        ${colorSwatches}
                                        ${sizesHtml}
                                        
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
                                            <div class="mt-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2"><strong>Rating:</strong></div>
                                                    <div class="rating-stars">
                                                        ${Array(5).fill().map((_, i) => 
                                                            `<i class="bi bi-star${i < Math.round(product.average_rating || 0) ? '-fill' : ''} text-warning"></i>`
                                                        ).join('')}
                                                    </div>
                                                    <div class="ms-2">
                                                        <span class="fw-bold">${(product.average_rating || 0).toFixed(1)}</span>
                                                        ${product.ratings_count ? `<span class="text-tertiary small">(${product.ratings_count})</span>` : ''}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            // Reinitialize add to cart buttons
                            initializeCartButtons();
                            
                            // Apply color to swatches
                            quickViewContent.querySelectorAll('.color-swatch-lg[data-color]').forEach(function(swatch) {
                                swatch.style.backgroundColor = swatch.dataset.color;
                            });
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
