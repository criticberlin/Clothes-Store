@extends('layouts.master')
@section('title', $product->name)

@section('content')
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
                        <input type="radio" name="color" id="color-{{ $color->id }}" class="form-check-input color-radio" value="{{ $color->id }}">
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
                        <input class="btn-check" type="radio" name="size" id="size-{{ $size->id }}" value="{{ $size->id }}">
                        <label class="btn btn-outline-secondary" for="size-{{ $size->id }}">{{ $size->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <label for="quantity" class="form-label">{{ __('general.quantity') }}</label>
                    <input type="number" class="form-control" id="quantity" value="1" min="1" max="{{ $product->quantity }}" style="width: 80px;">
                </div>
                <div class="availability">
                    <span class="text-secondary">{{ __('general.available') }}: <strong>{{ $product->quantity }}</strong></span>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex mb-4">
                <button id="add-to-cart" class="btn btn-primary btn-lg flex-grow-1 add-to-cart-btn" data-product-id="{{ $product->id }}">
                    <i class="bi bi-cart-plus me-2"></i> {{ __('general.add_to_cart') }}
                </button>
                <button class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-heart"></i>
                </button>
            </div>
            
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
                <p class="text-secondary">{{ __('general.coming_soon') }}</p>
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
        var colorLabels = document.querySelectorAll('.color-label[data-bg]');
        for (var i = 0; i < colorLabels.length; i++) {
            var label = colorLabels[i];
            label.style.backgroundColor = label.getAttribute('data-bg');
        }
    });
</script>
@endpush
