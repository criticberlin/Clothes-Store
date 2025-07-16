@extends('layouts.master')

@section('title', __('My Wishlist'))

@push('styles')
<style>
.wishlist-item {
    transition: all var(--transition-normal);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    overflow: hidden;
}

.wishlist-item:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-5px);
    border-color: var(--primary-light);
}

.wishlist-item-img {
    height: 180px;
    object-fit: cover;
    transition: all var(--transition-normal);
}

.wishlist-item:hover .wishlist-item-img {
    transform: scale(1.05);
}

.empty-wishlist-icon {
    font-size: 5rem;
    color: var(--primary-light);
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ __('My Wishlist') }}</h1>
        <a href="{{ route('products.list') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Continue Shopping') }}
        </a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($wishlistItems->count() > 0)
    <div class="row g-4">
        @foreach($wishlistItems as $item)
        <div class="col-md-3 wishlist-item-container">
            <div class="wishlist-item h-100">
                <div class="position-relative">
                    <a href="{{ route('products.details', $item->product->id) }}">
                        <img src="{{ $item->product->imageUrl }}" class="w-100 wishlist-item-img" alt="{{ $item->product->name }}">
                    </a>
                    @if($item->product->quantity <= 0)
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">{{ __('Out of Stock') }}</span>
                    @endif
                    <button class="btn btn-sm btn-outline-danger position-absolute top-0 start-0 m-2 remove-from-wishlist-btn" 
                            data-id="{{ $item->id }}">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="p-3">
                    <a href="{{ route('products.details', $item->product->id) }}" class="text-decoration-none">
                        <h5 class="card-title text-truncate">{{ $item->product->name }}</h5>
                    </a>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold price-value" data-base-price="{{ $item->product->price }}">
                            {{ app(\App\Services\CurrencyService::class)->formatPrice($item->product->price) }}
                        </span>
                        <div class="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($item->product->average_rating))
                                    <i class="bi bi-star-fill text-warning small"></i>
                                @else
                                    <i class="bi bi-star text-warning small"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary add-to-cart-btn" 
                                data-product-id="{{ $item->product->id }}" 
                                {{ $item->product->quantity <= 0 ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus me-1"></i> {{ __('Add to Cart') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-heart empty-wishlist-icon"></i>
        </div>
        <h2 class="mb-3">{{ __('Your wishlist is empty') }}</h2>
        <p class="text-secondary mb-4 mx-auto" style="max-width: 500px;">
            {{ __('Add items to your wishlist by clicking the heart icon on product pages') }}
        </p>
        <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
            {{ __('Explore Products') }}
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remove from wishlist
        document.querySelectorAll('.remove-from-wishlist-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const container = this.closest('.wishlist-item-container');
                
                fetch(`{{ url('/wishlist/remove') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        container.remove();
                        
                        // If no items left, reload page to show empty state
                        if (document.querySelectorAll('.wishlist-item-container').length === 0) {
                            window.location.reload();
                        }
                    } else {
                        alert(data.message || 'Failed to remove item from wishlist');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the item');
                });
            });
        });
        
        // Add to cart
        document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                
                // Redirect to product details page to select options
                window.location.href = `{{ url('/product') }}/${productId}`;
            });
        });
    });
</script>
@endpush 