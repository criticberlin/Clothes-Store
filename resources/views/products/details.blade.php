@extends('layouts.master')
@section('title', $product->name)

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.byCategory', $product->category) }}">{{ ucfirst($product->category) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Gallery -->
        <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="product-gallery">
                <img src="{{ asset("images/$product->photo") }}" class="img-fluid rounded-lg product-main-image" alt="{{ $product->name }}">
                
                <div class="mt-4">
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="product-thumbnail active" data-image="{{ asset("images/$product->photo") }}">
                                <img src="{{ asset("images/$product->photo") }}" class="img-fluid rounded" alt="{{ $product->name }}">
                            </div>
                        </div>
                        @for ($i = 1; $i <= 3; $i++)
                        <div class="col-3">
                            <div class="product-thumbnail" data-image="{{ asset("images/$product->photo") }}">
                                <img src="{{ asset("images/$product->photo") }}" class="img-fluid rounded" alt="{{ $product->name }} - View {{ $i }}">
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="product-info">
                <span class="badge bg-primary mb-3">{{ ucfirst($product->category) }}</span>
                
                <h1 class="mb-3">{{ $product->name }}</h1>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="product-rating me-3">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill {{ $i <= 4 ? 'text-warning' : 'text-secondary' }}"></i>
                        @endfor
                    </div>
                    <span class="text-secondary small">(24 reviews)</span>
                </div>
                
                <div class="product-price mb-4">
                    <span class="h2">${{ $product->price }}</span>
                    @if(rand(0, 1))
                    <span class="text-decoration-line-through ms-3 text-secondary">${{ number_format($product->price * 1.2, 2) }}</span>
                    <span class="badge bg-accent ms-2">20% OFF</span>
                    @endif
                </div>
                
                <div class="product-description mb-4">
                    <p class="text-secondary">{{ $product->description }}</p>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success border-0 rounded-lg mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle me-2"></i>
                            <p class="m-0">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-lg mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <p class="m-0">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    
                    <!-- Color Selection -->
                    <div class="mb-4">
                        <h6 class="text-tertiary mb-3">Color</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($product->colors as $color)
                                <div class="product-color-option" 
                                    data-tooltip="{{ $color->name }}"
                                    data-color="{{ $color->id }}">
                                    <span class="color-swatch" data-color="{{ $color->hex_code }}"></span>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="color_id" value="{{ $product->colors->first()->id }}">
                    </div>

                    <!-- Size Selection -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-tertiary m-0">Size</h6>
                            <a href="#" class="text-primary small" data-bs-toggle="modal" data-bs-target="#sizeGuideModal">Size Guide</a>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($product->sizes as $size)
                                <div class="product-size-option {{ $loop->first ? 'active' : '' }}" data-size="{{ $size->id }}">
                                    {{ $size->name }}
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="size_id" value="{{ $product->sizes->first()->id }}">
                    </div>

                    <!-- Quantity Selection -->
                    <div class="mb-4">
                        <h6 class="text-tertiary mb-3">Quantity</h6>
                        <div class="input-group quantity-selector" style="width: 140px;">
                            <button type="button" class="btn btn-outline-light quantity-minus">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control text-center" name="quantity" value="1" min="1" max="{{ $product->quantity }}" readonly>
                            <button type="button" class="btn btn-outline-light quantity-plus">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <p class="text-tertiary small mt-2">{{ $product->quantity }} items available</p>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary flex-grow-1 py-3">
                            <i class="bi bi-bag-plus me-2"></i> Add to Cart
                        </button>
                        <button type="button" class="btn btn-outline-light wishlist-btn" data-tooltip="Add to Wishlist">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Additional Info -->
                <div class="product-meta mt-4 pt-4 border-top border-surface">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-truck me-2 text-primary"></i>
                                <span class="text-secondary small">Free shipping on orders over $50</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-arrow-repeat me-2 text-primary"></i>
                                <span class="text-secondary small">30-day return policy</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="product-tabs mt-6">
        <ul class="nav nav-tabs border-0 mb-4" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active border-0 bg-transparent" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link border-0 bg-transparent" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Specifications</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link border-0 bg-transparent" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews (24)</button>
            </li>
        </ul>
        <div class="tab-content p-4 bg-surface rounded-lg" id="productTabsContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                <p>{{ $product->description }}</p>
                <p>Made with premium quality materials that ensure comfort and durability. Perfect for everyday wear or special occasions.</p>
            </div>
            <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="ps-0">Material</th>
                                    <td>Cotton, Polyester</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Care Instructions</th>
                                    <td>Machine wash cold, tumble dry low</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">SKU</th>
                                    <td>{{ strtoupper(substr(md5($product->id), 0, 8)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="ps-0">Country of Origin</th>
                                    <td>Made in USA</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Weight</th>
                                    <td>0.5 kg</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Model Number</th>
                                    <td>MST-{{ $product->id }}235</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Customer Reviews</h5>
                    <button class="btn btn-outline-light btn-sm">Write a Review</button>
                </div>
                <div class="review-list">
                    <div class="review-item mb-4 pb-4 border-bottom border-surface">
                        <div class="d-flex mb-3">
                            <div class="avatar-circle me-3">J</div>
                            <div>
                                <h6 class="mb-1">John Doe</h6>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= 5 ? 'text-warning' : 'text-secondary' }} small"></i>
                                        @endfor
                                    </div>
                                    <span class="text-tertiary small">2 weeks ago</span>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0">This is exactly what I was looking for. Great quality, perfect fit, and fast delivery. Highly recommended!</p>
                    </div>
                    <div class="review-item">
                        <div class="d-flex mb-3">
                            <div class="avatar-circle me-3">S</div>
                            <div>
                                <h6 class="mb-1">Sarah Johnson</h6>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= 4 ? 'text-warning' : 'text-secondary' }} small"></i>
                                        @endfor
                                    </div>
                                    <span class="text-tertiary small">1 month ago</span>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0">Love the material and design. The color is slightly different from what I expected, but still looks great.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Size Guide Modal -->
<div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-labelledby="sizeGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sizeGuideModalLabel">Size Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Size</th>
                            <th>Chest (in)</th>
                            <th>Waist (in)</th>
                            <th>Hips (in)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>S</td>
                            <td>34-36</td>
                            <td>28-30</td>
                            <td>34-36</td>
                        </tr>
                        <tr>
                            <td>M</td>
                            <td>38-40</td>
                            <td>32-34</td>
                            <td>38-40</td>
                        </tr>
                        <tr>
                            <td>L</td>
                            <td>42-44</td>
                            <td>36-38</td>
                            <td>42-44</td>
                        </tr>
                        <tr>
                            <td>XL</td>
                            <td>46-48</td>
                            <td>40-42</td>
                            <td>46-48</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .product-color-option .color-swatch {
        display: block;
        width: 100%;
        height: 100%;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set color swatches
    document.querySelectorAll('.color-swatch').forEach(swatch => {
        const color = swatch.getAttribute('data-color');
        swatch.style.backgroundColor = color;
    });
    
    // Quantity selector
    const plusBtn = document.querySelector('.quantity-plus');
    const minusBtn = document.querySelector('.quantity-minus');
    const qtyInput = document.querySelector('.quantity-selector input');
    const maxQty = parseInt(qtyInput.getAttribute('max'));
    
    if (plusBtn && minusBtn && qtyInput) {
        plusBtn.addEventListener('click', function() {
            let currentValue = parseInt(qtyInput.value);
            if (currentValue < maxQty) {
                qtyInput.value = currentValue + 1;
            }
        });
        
        minusBtn.addEventListener('click', function() {
            let currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        });
    }
});
</script>
@endsection
