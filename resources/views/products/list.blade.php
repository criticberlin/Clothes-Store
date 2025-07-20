@extends('layouts.master')

@section('title', isset($category) ? ucfirst($category) . ' Category' : 'Products')

@section('content')
<div class="container py-4">
    <!-- Header with Title -->
    <div class="mb-4">
        <h2 class="text-capitalize">{{ isset($category) ? $category . ' Collection' : 'All Products' }}</h2>
    </div>
    
    <!-- Filter Controls - Horizontal layout -->
    <div class="filter-bar mb-4">
        <div class="d-flex flex-wrap align-items-center">
            <!-- Categories Section - Dropdown -->
            <div class="filter-dropdown me-3 mb-2">
                <div class="dropdown">
                    <button class="btn filter-btn dropdown-toggle" type="button" id="categoriesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>{{ __('Categories') }}</span>
                    </button>
                    <div class="dropdown-menu p-3" aria-labelledby="categoriesDropdown">
                        <!-- Main Categories -->
                        <div class="mb-2">
                            <h6 class="filter-subtitle">{{ __('Main Categories') }}</h6>
                            <div class="category-options">
                                @php
                                    $mainCategories = \App\Models\Category::where('type', 'main')
                                        ->where('status', true)
                                        ->orderBy('name')
                                        ->get();
                                @endphp
                                @foreach($mainCategories as $category)
                                    <button class="category-filter" 
                                            data-filter="{{ $category->id }}" 
                                            data-type="category">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                    </div>
                </div>
                        
                        <!-- Clothing Type -->
                        <div class="mb-2">
                            <h6 class="filter-subtitle">{{ __('Clothing Type') }}</h6>
                            <div class="category-options">
                                @php
                                    $clothingTypes = \App\Models\Category::where('type', 'clothing')
                                        ->where('status', true)
                                        ->orderBy('name')
                                        ->get();
                                @endphp
                                @foreach($clothingTypes as $category)
                                    <button class="category-filter" 
                                            data-filter="{{ $category->id }}" 
                                            data-type="category">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
            </div>
                        </div>
                        
                        <!-- Specific Item Types -->
                        <div>
                            <h6 class="filter-subtitle">{{ __('Specific Items') }}</h6>
                            <div class="category-options">
                                @php
                                    $specificItems = \App\Models\Category::whereNotIn('type', ['main', 'clothing'])
                                        ->where('status', true)
                                        ->orderBy('name')
                                        ->get();
                                @endphp
                                @foreach($specificItems as $category)
                                    <button class="category-filter" 
                                            data-filter="{{ $category->id }}" 
                                            data-type="category">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- Size Filter - Dropdown -->
            <div class="filter-dropdown me-3 mb-2">
                <div class="dropdown">
                    <button class="btn filter-btn dropdown-toggle" type="button" id="sizeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>{{ __('Size') }}</span>
                    </button>
                    <div class="dropdown-menu p-3" aria-labelledby="sizeDropdown">
                        <div class="size-options">
                            @php
                                // Define standard size order
                                $standardSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'];
                                $orderedSizes = [];
                                $otherSizes = [];
                                
                                // Get all sizes from database
                                $allSizes = \App\Models\Size::orderBy('name')->get();
                                
                                // Sort sizes into standard and other categories
                                foreach($allSizes as $size) {
                                    $found = false;
                                    foreach($standardSizes as $index => $stdSize) {
                                        if(strtoupper($size->name) == $stdSize) {
                                            $orderedSizes[$index] = $size;
                                            $found = true;
                                            break;
                                        }
                                    }
                                    if(!$found) {
                                        $otherSizes[] = $size;
                                    }
                                }
                                
                                // Sort standard sizes by predefined order
                                ksort($orderedSizes);
                                
                                // Combine arrays with standard sizes first
                                $displaySizes = array_merge($orderedSizes, $otherSizes);
                            @endphp
                            
                            @foreach($displaySizes as $size)
                                <button class="size-filter" 
                                        data-filter="{{ $size->id }}" 
                                        data-type="size">
                                    {{ $size->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Color Filter - Dropdown -->
            <div class="filter-dropdown me-3 mb-2">
                <div class="dropdown">
                    <button class="btn filter-btn dropdown-toggle" type="button" id="colorDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>{{ __('Color') }}</span>
                    </button>
                    <div class="dropdown-menu p-3 color-dropdown-menu" aria-labelledby="colorDropdown">
                        <div class="color-options">
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
            </div>
            
            <!-- Sort By Dropdown -->
            <div class="filter-dropdown mb-2 ms-auto">
                <div class="dropdown">
                    <button class="btn filter-btn dropdown-toggle sort-button" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div class="sort-text">
                                <span>{{ __('Sort by') }}</span>
                                <span class="selected-sort">
                                    @php
                                        $sortOption = request()->get('sort', '');
                                        $sortText = '';
                                        switch($sortOption) {
                                            case 'price_low':
                                                $sortText = __('general.price_low_to_high');
                                                break;
                                            case 'price_high':
                                                $sortText = __('general.price_high_to_low');
                                                break;
                                            case 'newest':
                                                $sortText = __('general.newest_arrivals');
                                                break;
                                            case 'name':
                                                $sortText = __('general.name');
                                                break;
                                            default:
                                                $sortText = __('general.relevance');
                                        }
                                    @endphp
                                    : {{ $sortText }}
                                </span>
                            </div>
                        </div>
                    </button>
                    <div class="dropdown-menu p-3 sort-dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                        <form action="{{ route('products.list') }}" method="GET" id="sortForm">
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
                            <input type="hidden" name="categories" id="categoriesInput" value="">
                            
                            <div class="sort-options">
                                <button type="button" class="sort-option {{ request()->get('sort') == '' ? 'active' : '' }}" data-value="">
                                    {{ __('general.relevance') }}
                                </button>
                                <button type="button" class="sort-option {{ request()->get('sort') == 'price_low' ? 'active' : '' }}" data-value="price_low">
                                    {{ __('general.price_low_to_high') }}
                                </button>
                                <button type="button" class="sort-option {{ request()->get('sort') == 'price_high' ? 'active' : '' }}" data-value="price_high">
                                    {{ __('general.price_high_to_low') }}
                                </button>
                                <button type="button" class="sort-option {{ request()->get('sort') == 'newest' ? 'active' : '' }}" data-value="newest">
                                    {{ __('general.newest_arrivals') }}
                                </button>
                                <button type="button" class="sort-option {{ request()->get('sort') == 'name' ? 'active' : '' }}" data-value="name">
                                    {{ __('general.name') }}
                                </button>
                            </div>
                            <input type="hidden" name="sort" id="sortInput" value="{{ request()->get('sort', '') }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Filters -->
    <div class="mb-4" id="activeFiltersContainer" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="filter-title mb-0">{{ __('Active Filters') }}</h5>
            <button class="btn btn-sm btn-outline-danger" id="clearAllFilters">
                <i class="bi bi-x-circle me-1"></i> {{ __('Clear All') }}
            </button>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-2" id="activeFilters">
            <!-- Active filters will be added here dynamically -->
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
                     data-categories="{{ $product->categories->pluck('id')->implode(',') }}"
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

/* Filter Bar - Horizontal Layout */
.filter-bar {
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--border);
    padding-bottom: 1rem;
}

/* Filter Dropdown Buttons */
.filter-btn {
    background-color: var(--surface);
    color: var(--text-primary);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all var(--transition-normal);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-btn:hover, 
.filter-btn:focus {
    background-color: var(--surface-alt);
    border-color: var(--primary-light);
    color: var(--primary);
}

.filter-btn::after {
    margin-left: 0.5rem;
    transition: transform var(--transition-normal);
}

.filter-btn[aria-expanded="true"] {
    background-color: var(--surface-alt);
    border-color: var(--primary);
    color: var(--primary);
}

.filter-btn[aria-expanded="true"]::after {
    transform: rotate(180deg);
}

/* Dropdown Menus */
.filter-dropdown .dropdown-menu {
    min-width: 320px;
    max-width: 420px;
    max-height: 400px;
    overflow-y: auto;
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-md);
    margin-top: 0.5rem;
    padding: 1rem;
}

/* Filter Subtitle */
.filter-subtitle {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 0.75rem;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid var(--border);
}

/* Category Filters */
.category-options {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.category-filter {
    background-color: var(--surface);
    color: var(--text-secondary);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all var(--transition-normal);
    cursor: pointer;
    text-align: center;
    white-space: normal;
    display: inline-block;
    flex-grow: 1;
    flex-basis: calc(33.333% - 0.5rem);
    max-width: calc(33.333% - 0.5rem);
    min-width: 80px;
}

.category-filter:hover {
    background-color: var(--surface-alt);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.category-filter.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Size Filter Buttons */
.size-options {
    display: flex;
    flex-wrap: nowrap;
    gap: 0.5rem;
    width: 100%;
    overflow-x: auto;
    padding-bottom: 5px;
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}

.size-options::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
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
    font-size: 0.85rem;
    font-weight: 500;
    transition: all var(--transition-normal);
    cursor: pointer;
    padding: 0 0.75rem;
    flex-grow: 0;
    flex-shrink: 0;
}

.size-filter:hover {
    background-color: var(--surface-alt);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.size-filter.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Color Filter Buttons */
.color-dropdown-menu {
    width: auto !important;
    min-width: 250px !important;
}

.color-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, 32px);
    gap: 0.75rem;
    width: 100%;
    padding-bottom: 5px;
    justify-content: center;
}

.color-filter {
    width: 32px;
    height: 32px;
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

/* Sort Options */
.sort-options {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    width: 100%;
}

.sort-button {
    width: auto;
    text-align: left;
    justify-content: space-between;
    position: relative;
    padding-right: 30px;
}

.sort-button::after {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
}

.sort-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.sort-dropdown-menu {
    min-width: unset !important;
    width: 200px;
}

.selected-sort {
    font-weight: normal;
    color: var(--text-secondary);
    margin-left: 4px;
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sort-option {
    background-color: var(--surface);
    color: var(--text-secondary);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 0.6rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all var(--transition-normal);
    cursor: pointer;
    text-align: left;
    width: 100%;
    display: flex;
    align-items: center;
}

.sort-option:hover {
    background-color: var(--surface-alt);
    border-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.sort-option.active {
    background-color: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Active Filters */
#activeFiltersContainer {
    padding: 0.75rem 0;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

.filter-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.active-filter-tag {
    background-color: var(--surface-alt);
    color: var(--text-primary);
    border-radius: 30px;
    padding: 0.35rem 0.75rem;
    font-size: 0.8rem;
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

/* Clear All Filters Button */
#clearAllFilters {
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-md);
    transition: all var(--transition-normal);
}

#clearAllFilters:hover {
    background-color: var(--bs-danger);
    color: white;
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
    .filter-title {
        font-size: 0.95rem;
    }
    
    .filter-subtitle {
        font-size: 0.8rem;
    }
    
    .category-filter {
        padding: 0.35rem 0.7rem;
        font-size: 0.75rem;
        flex-basis: calc(50% - 0.5rem);
        max-width: calc(50% - 0.5rem);
    }
    
    .size-filter {
        min-width: 36px;
        height: 36px;
        font-size: 0.75rem;
    }
    
    .filter-btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    .filter-dropdown .dropdown-menu {
        min-width: 280px;
        max-width: 350px;
    }
    
    .select-wrapper {
        min-width: 160px;
    }
    
    .sort-control .form-select {
        font-size: 0.85rem;
    }
    
    .sort-control label {
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .filter-dropdown .dropdown-menu {
        min-width: 260px;
        max-width: 320px;
        padding: 0.75rem;
    }
    
    .filter-bar {
        padding-bottom: 0.5rem;
    }
    
    .filter-dropdown {
        margin-right: 0.5rem;
    }
    
    .category-filter {
        flex-basis: 100%;
        max-width: 100%;
    }
    
    .size-options, .color-options {
        gap: 0.4rem;
    }
    
    .size-filter {
        min-width: 34px;
        height: 34px;
        padding: 0 0.5rem;
    }
    
    .color-filter {
        width: 28px;
        height: 28px;
    }
    
    .sort-wrapper {
        width: 100%;
        margin-top: 0.5rem;
    }
    
    .sort-control {
        width: 100%;
        justify-content: space-between;
    }
    
    .select-wrapper {
        flex-grow: 1;
        margin-left: 0.5rem;
    }
}

/* Sort Control - Enhanced Version */
.sort-wrapper {
    position: relative;
}

.sort-control {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sort-control label {
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 0.9rem;
    white-space: nowrap;
}

.select-wrapper {
    position: relative;
    min-width: 180px;
}

.select-wrapper::after {
    content: '';
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid var(--text-secondary);
    pointer-events: none;
}

.sort-control .form-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: var(--surface);
    color: var(--text-primary);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 0.5rem 2.5rem 0.5rem 0.75rem;
    font-size: 0.9rem;
    font-weight: 500;
    width: 100%;
    transition: all var(--transition-normal);
    background-image: none;
}

.sort-control .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px var(--focus-ring);
    outline: none;
}

.sort-control .form-select:hover {
    border-color: var(--primary-light);
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
            colors: [],
            categories: []
        };
        
        // DOM elements
        const productsGrid = document.getElementById('productsGrid');
        const activeFiltersContainer = document.getElementById('activeFiltersContainer');
        const activeFiltersDiv = document.getElementById('activeFilters');
        const clearAllFiltersBtn = document.getElementById('clearAllFilters');
        const sizesInput = document.getElementById('sizesInput');
        const colorsInput = document.getElementById('colorsInput');
        const categoriesInput = document.getElementById('categoriesInput');
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

        // Category filter functionality
        const categoryFilters = document.querySelectorAll('.category-filter');
        categoryFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                const filterId = this.dataset.filter;
                const filterType = this.dataset.type;
                
                // Toggle this filter
                const filterIndex = filterState.categories.indexOf(filterId);
                if (filterIndex === -1) {
                    // Add filter
                    filterState.categories.push(filterId);
                    this.classList.add('active');
                } else {
                    // Remove filter
                    filterState.categories.splice(filterIndex, 1);
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
            filterState.categories = [];
            
            // Reset UI
            sizeFilters.forEach(f => f.classList.remove('active'));
            colorFilters.forEach(f => f.classList.remove('active'));
            categoryFilters.forEach(f => f.classList.remove('active'));
            
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

            // Add category filters
            filterState.categories.forEach(categoryId => {
                const filter = document.querySelector(`.category-filter[data-filter="${categoryId}"]`);
                if (filter) {
                    const filterName = filter.textContent.trim();
                    addActiveFilterTag(categoryId, filterName, 'category');
                }
            });
            
            // Show/hide active filters container
            const hasActiveFilters = filterState.sizes.length > 0 || filterState.colors.length > 0 || filterState.categories.length > 0;
                                    
            activeFiltersContainer.style.display = hasActiveFilters ? 'block' : 'none';
            
            // Update hidden inputs for form submission
            sizesInput.value = filterState.sizes.join(',');
            colorsInput.value = filterState.colors.join(',');
            categoriesInput.value = filterState.categories.join(',');
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

                case 'category':
                    const categoryIndex = filterState.categories.indexOf(id);
                    if (categoryIndex !== -1) {
                        filterState.categories.splice(categoryIndex, 1);
                        document.querySelector(`.category-filter[data-filter="${id}"]`).classList.remove('active');
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

                // Check category filters
                if (showItem && filterState.categories.length > 0) {
                    const productCategories = item.dataset.categories.split(',');
                    const hasMatchingCategory = filterState.categories.some(categoryId => 
                        productCategories.includes(categoryId)
                    );
                    
                    if (!hasMatchingCategory) {
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
        
        // Sort Options
        const sortOptions = document.querySelectorAll('.sort-option');
        const selectedSortSpan = document.querySelector('.selected-sort');
        
        sortOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Update hidden input value
                const sortValue = this.dataset.value;
                document.getElementById('sortInput').value = sortValue;
                
                // Update UI
                sortOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                // Update the displayed sort option
                const sortText = this.textContent.trim();
                selectedSortSpan.textContent = ': ' + sortText;
                
                // Submit form
                document.getElementById('sortForm').submit();
            });
        });
        
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

            // Initialize categories
            if (urlParams.has('categories')) {
                const categoryIds = urlParams.get('categories').split(',').filter(id => id.trim() !== '');
                categoryIds.forEach(id => {
                    const filter = document.querySelector(`.category-filter[data-filter="${id}"]`);
                    if (filter) {
                        filter.classList.add('active');
                        filterState.categories.push(id);
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
