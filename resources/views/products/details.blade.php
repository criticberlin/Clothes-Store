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
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('general.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.category', $product->category) }}">{{ ucfirst(__('general.' . $product->category)) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="product-image-wrapper position-relative">
                <img src="{{ asset('img/products/' . $product->photo) }}" alt="{{ $product->name }}" class="img-fluid rounded-4 shadow-sm product-main-image">
                <span class="position-absolute top-0 start-0 m-3 badge rounded-pill {{ $product->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                    {{ $product->quantity > 0 ? __('general.available') : __('general.out_of_stock') }}
                </span>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <h1 class="mb-3 display-6 fw-bold">{{ $product->name }}</h1>
            
            <div class="mb-4">
                <div class="price-block d-flex align-items-center">
                    <span class="fs-3 fw-bold me-3 price-value" data-base-price="{{ $product->price }}">{{ formatPrice($product->price) }}</span>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="lead text-secondary">{{ $product->description }}</p>
            </div>
            
            <div class="mb-4">
                <h5 class="mb-3">{{ __('general.color') }}</h5>
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
                <h5 class="mb-3">{{ __('general.size') }}</h5>
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
                    <label for="quantity" class="form-label">{{ __('general.quantity') }}</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" form="add-to-cart-form" value="1" min="1" max="{{ $product->quantity }}" style="width: 80px;">
                </div>
                <div class="availability">
                    <span class="text-secondary">{{ __('general.available') }}: <strong>{{ $product->quantity }}</strong></span>
                </div>
            </div>
            
            <form id="add-to-cart-form" action="{{ route('cart.add', $product->id) }}" method="POST" class="d-grid gap-2 d-md-flex mb-4">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                    <i class="bi bi-cart-plus me-2"></i> {{ __('general.add_to_cart') }}
                </button>
                <button type="button" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-heart"></i>
                </button>
            </form>
            
            <div class="product-meta">
                <div class="row text-secondary">
                    <div class="col-6 col-md-4">
                        <small class="d-block mb-1">{{ __('general.category') }}</small>
                        <span>{{ ucfirst($product->category) }}</span>
                    </div>
                    <div class="col-6 col-md-4">
                        <small class="d-block mb-1">{{ __('general.code') }}</small>
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
                    {{ __('general.description') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">
                    {{ __('general.reviews') }}
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
                                            $ratingCounts = $product->ratings()
                                                ->selectRaw('rating, COUNT(*) as count')
                                                ->where('is_approved', true)
                                                ->groupBy('rating')
                                                ->pluck('count', 'rating')
                                                ->toArray();
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
    
    .product-image-wrapper {
        overflow: hidden;
        border-radius: 1rem;
    }
    
    .product-image-wrapper:hover .product-main-image {
        transform: scale(1.03);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set color labels background
        var colorLabels = document.querySelectorAll('.color-label[data-bg]');
        for (var i = 0; i < colorLabels.length; i++) {
            var label = colorLabels[i];
            label.style.backgroundColor = label.getAttribute('data-bg');
        }
    });
</script>
@endpush
