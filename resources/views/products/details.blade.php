@extends('layouts.master')
@section('title', $product->name)

@section('meta')
<meta name="product-id" content="{{ $product->id }}">
@endsection

@section('content')
<style>
/* Dynamic width classes for progress bars */
.w-0 { width: 0%; }
.w-10 { width: 10%; }
.w-20 { width: 20%; }
.w-30 { width: 30%; }
.w-40 { width: 40%; }
.w-50 { width: 50%; }
.w-60 { width: 60%; }
.w-70 { width: 70%; }
.w-80 { width: 80%; }
.w-90 { width: 90%; }
.w-100 { width: 100%; }
</style>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.category', $product->category) }}">{{ ucfirst(__('general.' . $product->category)) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="product-gallery">
                <div class="main-image-container mb-3 position-relative">
                    <div id="productImagesCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner rounded-4 shadow-sm">
                            @if($product->images && count($product->images) > 0)
                                @foreach($product->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->filename) }}" alt="{{ $product->name }}" 
                                             class="d-block w-100 product-main-image">
                                    </div>
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" 
                                         class="d-block w-100 product-main-image">
                                </div>
                            @endif
                        </div>
                        @if($product->images && count($product->images) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                        <span class="position-absolute top-0 start-0 m-3 badge rounded-pill {{ $product->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->quantity > 0 ? __('Available') : __('Out of Stock') }}
                        </span>
                    </div>
                </div>
                
                @if($product->images && count($product->images) > 1)
                    <div class="thumbnails-container">
                        <div class="row g-2">
                            @foreach($product->images as $index => $image)
                                <div class="col-3">
                                    <img src="{{ asset('storage/' . $image->filename) }}" 
                                         alt="Thumbnail" 
                                         class="img-thumbnail product-thumbnail {{ $index === 0 ? 'active' : '' }}"
                                         data-bs-target="#productImagesCarousel"
                                         data-bs-slide-to="{{ $index }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <h1 class="mb-3 display-6 fw-bold">{{ $product->name }}</h1>
            
            <div class="mb-4">
                <div class="price-block d-flex align-items-center">
                    <span class="fs-3 fw-bold me-3 price-value" data-base-price="{{ $product->price }}">{{ app(\App\Services\CurrencyService::class)->formatPrice($product->price) }}</span>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="lead text-secondary">{{ $product->description }}</p>
            </div>
            
            <div class="mb-4">
                <h5 class="mb-3">{{ __('Color') }}</h5>
                <div class="color-options d-flex gap-2 flex-wrap">
                    @foreach($product->colors as $color)
                    <div class="color-option form-check">
                        <input type="radio" name="color" id="color-{{ $color->id }}" class="form-check-input color-radio" value="{{ $color->id }}" form="add-to-cart-form">
                        <label for="color-{{ $color->id }}" class="color-label" data-bg="{{ $color->hex_code }}" title="{{ $color->name }}"></label>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-4">
                <h5 class="mb-3">{{ __('Size') }}</h5>
                <div class="size-options d-flex gap-2 flex-wrap">
                    @foreach($product->sizes as $size)
                    <div class="form-check">
                        <input class="btn-check" type="radio" name="size" id="size-{{ $size->id }}" value="{{ $size->id }}" form="add-to-cart-form">
                        <label class="btn btn-outline-secondary" for="size-{{ $size->id }}">{{ $size->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" form="add-to-cart-form" value="1" min="1" max="{{ $product->quantity }}" style="width: 80px;">
                </div>
                <div class="availability">
                    <span class="text-secondary">{{ __('Available') }}: <strong>{{ $product->quantity }}</strong></span>
                </div>
            </div>
            
            <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="d-grid gap-2 d-md-flex mb-4">
                @csrf
                <button type="submit" id="add-to-cart-btn" class="btn btn-primary btn-lg flex-grow-1">
                    <i class="bi bi-cart-plus me-2"></i> {{ __('Add to cart') }}
                </button>
                <button type="button" class="btn btn-outline-primary btn-lg wishlist-toggle" data-product-id="{{ $product->id }}">
                    <i class="bi bi-heart"></i>
                </button>
            </form>
            
            <div id="validation-message" class="alert alert-warning d-none">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <span id="validation-text"></span>
            </div>

            <div id="success-message" class="alert alert-success d-none">
                <i class="bi bi-check-circle me-2"></i>
                <span>{{ __('Product added to cart') }}</span>
            </div>
            
            <div class="product-meta">
                <div class="row text-secondary">
                    <div class="col-6 col-md-4">
                        <small class="d-block mb-1">{{ __('Category') }}</small>
                        <span>{{ ucfirst($product->category) }}</span>
                    </div>
                    <div class="col-6 col-md-4">
                        <small class="d-block mb-1">{{ __('Code') }}</small>
                        <span>{{ $product->code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Details Tabs -->
    <div class="product-tabs mt-5">
        <ul class="nav nav-tabs" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">
                    {{ __('Description') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">
                    {{ __('Reviews') }}
                </button>
            </li>
        </ul>
        <div class="tab-content pt-4" id="productTabsContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                <div class="row">
                    <div class="col-md-8">
                        <p>{{ $product->description }}</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-surface mb-4">
                            <div class="card-header">
                                <h3 class="h5 mb-0">{{ __('Customer Reviews') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="rating-summary me-4">
                                        <div class="display-4 fw-bold text-primary">{{ number_format($product->average_rating, 1) }}</div>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($product->average_rating))
                                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                                @else
                                                    <i class="bi bi-star text-warning me-1"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2 text-secondary">({{ $product->ratings_count }} {{ __('reviews') }})</span>
                                        </div>
                                    </div>
                                    <div class="rating-breakdown flex-grow-1">
                                        @php
                                            $ratingCounts = [];
                                            if ($product->ratings_count > 0) {
                                                $ratingCounts = $product->ratings()
                                                    ->selectRaw('rating, COUNT(*) as count')
                                                    ->where('is_approved', true)
                                                    ->groupBy('rating')
                                                    ->pluck('count', 'rating')
                                                    ->toArray();
                                            }
                                        @endphp
                                        
                                        @for ($i = 5; $i >= 1; $i--)
                                            @php 
                                                $count = $ratingCounts[$i] ?? 0;
                                                $percentage = $product->ratings_count > 0 ? ($count / $product->ratings_count) * 100 : 0;
                                                $widthClass = 'w-' . round($percentage / 10) * 10;
                                            @endphp
                                            <div class="d-flex align-items-center mb-1">
                                                <div class="rating-stars me-2">
                                                    {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                                                </div>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-primary {{ $widthClass }}" 
                                                         role="progressbar" 
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div class="rating-count ms-2">
                                                    <small>{{ $count }}</small>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                
                                @auth
                                    @php
                                        $userRating = $product->ratings()->where('user_id', Auth::id())->first();
                                    @endphp
                                    <div class="card bg-surface-alt mb-4">
                                        <div class="card-body">
                                            <h4 class="h6 mb-3">{{ $userRating ? __('Update Your Review') : __('Write a Review') }}</h4>
                                            <form action="{{ route('products.rate', $product->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Your Rating') }}</label>
                                                    <div class="rating-input">
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}" 
                                                                    value="{{ $i }}" {{ $userRating && $userRating->rating == $i ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="rating{{ $i }}">
                                                                    {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                                                                </label>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="review" class="form-label">{{ __('Your Review') }} ({{ __('optional') }})</label>
                                                    <textarea class="form-control" id="review" name="review" rows="3">{{ $userRating ? $userRating->review : '' }}</textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">
                                                    {{ $userRating ? __('Update Review') : __('Submit Review') }}
                                                </button>
                                                
                                                @if ($userRating)
                                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-rating-form').submit();" 
                                                       class="btn btn-outline-danger ms-2">{{ __('Delete Review') }}</a>
                                                    <form id="delete-rating-form" action="{{ route('ratings.destroy', $userRating->id) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> 
                                        <a href="{{ route('login') }}">{{ __('Login') }}</a> {{ __('to leave a review') }}
                                    </div>
                                @endauth
                                
                                <div class="reviews-list">
                                    <h4 class="h6 mb-3">{{ __('Customer Reviews') }}</h4>
                                    
                                    @if ($product->ratings()->where('is_approved', true)->count() > 0)
                                        @foreach ($product->ratings()->where('is_approved', true)->with('user')->latest()->get() as $rating)
                                            <div class="review-item mb-4 pb-4 border-bottom">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar me-2">
                                                            <img src="{{ $rating->user->profile_photo_url }}" alt="{{ $rating->user->name }}" 
                                                                 class="rounded-circle" width="40" height="40">
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $rating->user->name }}</div>
                                                            <div class="text-tertiary small">{{ $rating->created_at->diffForHumans() }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="rating-stars">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $rating->rating)
                                                                <i class="bi bi-star-fill text-warning"></i>
                                                            @else
                                                                <i class="bi bi-star text-warning"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                
                                                @if ($rating->review)
                                                    <div class="review-text">
                                                        {{ $rating->review }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4 text-tertiary">
                                            <i class="bi bi-chat-square-text display-6 mb-3"></i>
                                            <p>{{ __('No reviews yet. Be the first to review this product!') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- You might also like section -->
    @if($product->recommendedProducts && $product->recommendedProducts->count() > 0)
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">{{ __('You might also like') }}</h3>
            <div class="recommendation-nav">
                <button class="btn btn-sm btn-icon nav-btn prev-btn me-2" id="recommendPrev">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm btn-icon nav-btn next-btn" id="recommendNext">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="recommended-products-container position-relative">
            <div class="recommended-products row g-4" id="recommendedProductsRow">
                @php
                    // Get recommended products and sort by category
                    $recommendedProducts = $product->recommendedProducts->load(['categories', 'colors', 'sizes', 'images']);
                    
                    // Get additional products if needed, prioritizing same category
                    if($recommendedProducts->count() < 8) {
                        $categoryIds = $product->categories->pluck('id')->toArray();
                        $moreProducts = App\Models\Product::with(['categories', 'colors', 'sizes', 'images'])
                            ->whereHas('categories', function($query) use ($categoryIds) {
                                $query->whereIn('categories.id', $categoryIds);
                            })
                            ->whereNotIn('id', [$product->id])
                            ->whereNotIn('id', $recommendedProducts->pluck('id')->toArray())
                            ->inRandomOrder()
                            ->limit(8 - $recommendedProducts->count())
                            ->get();
                        
                        // If still not enough, get random products
                        if($moreProducts->count() + $recommendedProducts->count() < 8) {
                            $existingIds = $moreProducts->pluck('id')
                                ->merge($recommendedProducts->pluck('id'))
                                ->push($product->id)
                                ->toArray();
                                
                            $randomProducts = App\Models\Product::with(['categories', 'colors', 'sizes', 'images'])
                                ->whereNotIn('id', $existingIds)
                                ->inRandomOrder()
                                ->limit(8 - $moreProducts->count() - $recommendedProducts->count())
                                ->get();
                                
                            $moreProducts = $moreProducts->concat($randomProducts);
                        }
                        
                        $recommendedProducts = $recommendedProducts->concat($moreProducts);
                    }
                    
                    // Sort by matching categories with current product
                    $currentProductCategoryIds = $product->categories->pluck('id')->toArray();
                    $recommendedProducts = $recommendedProducts->sortByDesc(function($recommendedProduct) use ($currentProductCategoryIds) {
                        return $recommendedProduct->categories->whereIn('id', $currentProductCategoryIds)->count();
                    });
                @endphp
                
                @foreach($recommendedProducts->take(8) as $recommendedProduct)
                <div class="col-6 col-md-3 recommended-item">
                    <div class="product-card h-100">
                        <a href="{{ route('products.details', $recommendedProduct->id) }}" class="product-card-link">
                            <div class="product-image-container">
                                <img src="{{ $recommendedProduct->imageUrl }}" alt="{{ $recommendedProduct->name }}" class="img-fluid product-image">
                                
                                <!-- Product Badges -->
                                @if($recommendedProduct->quantity <= 0)
                                    <div class="product-badge out-of-stock">{{ __('general.out_of_stock') }}</div>
                                @elseif($recommendedProduct->created_at && $recommendedProduct->created_at->diffInDays(now()) <= 7)
                                    <div class="product-badge new top-left">{{ __('general.new') }}</div>
                                @endif
                                
                                <!-- Color Swatches -->
                                @if(isset($recommendedProduct->colors) && $recommendedProduct->colors && $recommendedProduct->colors->count() > 0)
                                    <div class="color-swatches">
                                        @foreach($recommendedProduct->colors->take(4) as $color)
                                            <div class="color-swatch" data-color="{{ $color->hex_code }}" title="{{ $color->name }}"></div>
                                        @endforeach
                                        @if($recommendedProduct->colors->count() > 4)
                                            <div class="color-swatch more-colors">+{{ $recommendedProduct->colors->count() - 4 }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="product-info p-3">
                                <div class="product-category text-tertiary small mb-1">
                                    @if($recommendedProduct->categories && $recommendedProduct->categories->isNotEmpty())
                                        {{ $recommendedProduct->categories->first()->name }}
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="product-title h6 mb-1">{{ $recommendedProduct->name }}</h3>
                                    <!-- Wishlist button moved next to product name -->
                                    <button type="button" class="btn btn-sm btn-icon wishlist-toggle wishlist-btn-inline" 
                                            data-product-id="{{ $recommendedProduct->id }}" 
                                            data-bs-toggle="tooltip"
                                            title="{{ __('Add to Wishlist') }}">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                </div>
                                <!-- Available Sizes -->
                                @if(isset($recommendedProduct->sizes) && $recommendedProduct->sizes && $recommendedProduct->sizes->count() > 0)
                                    <div class="available-sizes mb-2">
                                        @foreach($recommendedProduct->sizes->take(5) as $size)
                                            <span class="size-badge">{{ $size->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="product-price fw-bold price-value" data-base-price="{{ $recommendedProduct->price }}">
                                        {{ app(\App\Services\CurrencyService::class)->formatPrice($recommendedProduct->price) }}
                                    </div>
                                    <div class="product-rating">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="ms-1 small">{{ number_format($recommendedProduct->average_rating ?? 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .color-options {
        display: flex;
        gap: 0.5rem;
    }
    
    .color-option {
        position: relative;
        margin: 0;
    }
    
    .color-radio {
        position: absolute;
        opacity: 0;
    }
    
    .color-label {
        display: block;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        position: relative;
        border: 2px solid transparent;
    }
    
    .color-radio:checked + .color-label {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px var(--bg-primary);
    }
    
    .color-radio:focus + .color-label {
        box-shadow: 0 0 0 3px var(--primary-light);
    }
    
    .product-main-image {
        max-height: 600px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .main-image-container {
        overflow: hidden;
        border-radius: var(--radius-lg);
    }
    
    .main-image-container:hover .product-main-image {
        transform: scale(1.03);
    }
    
    .product-thumbnail {
        cursor: pointer;
        height: 80px;
        width: 100%;
        object-fit: cover;
        border: 2px solid transparent;
        border-radius: var(--radius-md);
        transition: all var(--transition-normal);
    }
    
    .product-thumbnail:hover {
        transform: translateY(-2px);
    }
    
    .product-thumbnail.active {
        border-color: var(--primary);
        box-shadow: 0 0 0 1px var(--primary);
    }
    
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
    
    /* New badge position in top left */
    .product-badge.top-left {
        top: 10px;
        left: 10px;
        right: auto;
    }
    
    .product-title {
        font-weight: 600;
        line-height: 1.3;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }
    
    /* Color Swatches */
    .color-swatches {
        position: absolute;
        bottom: 10px;
        left: 10px;
        display: flex;
        gap: 5px;
        z-index: 2;
    }
    
    .color-swatch {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1px solid var(--border);
        transition: transform var(--transition-normal);
    }
    
    .color-swatch:hover {
        transform: scale(1.2);
    }
    
    .color-swatch.more-colors {
        background-color: var(--surface);
        color: var(--text-tertiary);
        font-size: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
    }
    
    /* Size Badges */
    .available-sizes {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .size-badge {
        background-color: var(--surface-alt);
        color: var(--text-secondary);
        font-size: 0.75rem;
        padding: 0.15rem 0.35rem;
        border-radius: var(--radius-sm);
        display: inline-block;
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
    
    /* Inline wishlist button */
    .wishlist-btn-inline {
        padding: 0.25rem;
        width: auto;
        height: auto;
        font-size: 1rem;
        transition: all var(--transition-normal);
        color: var(--text-tertiary);
    }
    
    .wishlist-btn-inline:hover {
        color: var(--primary);
        transform: scale(1.2);
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
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .alert {
        animation: fadeIn 0.3s ease forwards;
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply color swatches background
    document.querySelectorAll('.color-swatch[data-color]').forEach(function(swatch) {
        swatch.style.backgroundColor = swatch.dataset.color;
    });
    
    // Set color labels background
    var colorLabels = document.querySelectorAll('.color-label[data-bg]');
    for (var i = 0; i < colorLabels.length; i++) {
        var label = colorLabels[i];
        label.style.backgroundColor = label.getAttribute('data-bg');
    }
    
    // Thumbnail functionality
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            const slideIndex = this.getAttribute('data-bs-slide-to');
            const carousel = document.getElementById('productImagesCarousel');
            const bsCarousel = new bootstrap.Carousel(carousel);
            
            bsCarousel.to(parseInt(slideIndex));
            
            // Update active state
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Recommended products navigation
    const recommendRow = document.getElementById('recommendedProductsRow');
    const prevBtn = document.getElementById('recommendPrev');
    const nextBtn = document.getElementById('recommendNext');
    
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
    
    // Form validation
    const form = document.getElementById('add-to-cart-form');
    const validationMessage = document.getElementById('validation-message');
    const validationText = document.getElementById('validation-text');
    const successMessage = document.getElementById('success-message');
    
    form.addEventListener('submit', function(e) {
        const colorSelected = document.querySelector('input[name="color"]:checked');
        const sizeSelected = document.querySelector('input[name="size"]:checked');
        
        // Reset messages
        validationMessage.classList.add('d-none');
        successMessage.classList.add('d-none');
        
        if (!colorSelected && !sizeSelected) {
            e.preventDefault();
            validationText.textContent = "{{ __('Please select color and size') }}";
            validationMessage.classList.remove('d-none');
            return false;
        }
        
        if (!colorSelected) {
            e.preventDefault();
            validationText.textContent = "{{ __('Please select color') }}";
            validationMessage.classList.remove('d-none');
            return false;
        }
        
        if (!sizeSelected) {
            e.preventDefault();
            validationText.textContent = "{{ __('Please select size') }}";
            validationMessage.classList.remove('d-none');
            return false;
        }
        
        // If validation passes, submit via AJAX
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                successMessage.classList.remove('d-none');
                
                // Update cart counter in header
                const cartBadge = document.querySelector('.cart-counter');
                if (cartBadge) {
                    cartBadge.textContent = data.cart_count || parseInt(cartBadge.textContent || '0') + 1;
                    cartBadge.classList.add('animate-pulse');
                    setTimeout(() => {
                        cartBadge.classList.remove('animate-pulse');
                    }, 500);
                } else {
                    // If badge doesn't exist yet, we need to add it
                    const cartBtn = document.querySelector('.smart-cart-btn');
                    if (cartBtn) {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'cart-badge animate-pulse';
                        newBadge.textContent = '1';
                        cartBtn.appendChild(newBadge);
                        setTimeout(() => {
                            newBadge.classList.remove('animate-pulse');
                        }, 500);
                    }
                }
                
                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                // Show error message
                validationText.textContent = data.message || "{{ __('Error adding to cart') }}";
                validationMessage.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            validationText.textContent = "{{ __('Error adding to cart') }}";
            validationMessage.classList.remove('d-none');
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
