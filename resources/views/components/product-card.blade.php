@props(['product'])

<div class="product-card h-100">
    <a href="{{ route('products.details', $product->id) }}" class="product-card-link">
        <div class="product-image-container">
            <img src="{{ $product->imageUrl }}" alt="{{ $product->name }}" class="img-fluid product-image">
            
            <!-- Product Badges -->
            @if($product->quantity <= 0)
                <div class="product-badge out-of-stock">{{ __('general.out_of_stock') }}</div>
            @elseif($product->created_at && $product->created_at->diffInDays(now()) <= 7)
                <div class="product-badge new top-left">{{ __('general.new') }}</div>
            @endif
            
            <!-- Color Swatches -->
            @if(isset($product->colors) && $product->colors && $product->colors->count() > 0)
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
                @if(isset($product->categories) && $product->categories && $product->categories->isNotEmpty())
                    {{ $product->categories->first()->name }}
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="product-title h6 mb-1">{{ $product->name }}</h3>
                <!-- Wishlist button moved next to product name -->
                <button type="button" class="btn btn-sm btn-icon wishlist-toggle wishlist-btn-inline" 
                        data-product-id="{{ $product->id }}" 
                        data-bs-toggle="tooltip"
                        title="{{ __('Add to Wishlist') }}">
                    <i class="bi bi-heart"></i>
                </button>
            </div>
            <!-- Available Sizes -->
            @if(isset($product->sizes) && $product->sizes && $product->sizes->count() > 0)
                <div class="available-sizes mb-2">
                    @foreach($product->sizes->take(5) as $size)
                        <span class="size-badge">{{ $size->name }}</span>
                    @endforeach
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div class="product-price fw-bold price-value" data-base-price="{{ $product->price }}">
                    {{ app(\App\Services\CurrencyService::class)->formatPrice($product->price) }}
                </div>
                <div class="product-rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    <span class="ms-1 small">{{ number_format($product->average_rating ?? 0, 1) }}</span>
                    @if(isset($product->ratings_count) && $product->ratings_count > 0)
                        <span class="text-tertiary small">({{ $product->ratings_count }})</span>
                    @endif
                </div>
            </div>
        </div>
    </a>
</div> 