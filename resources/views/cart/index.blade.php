@extends('layouts.master')

@section('title', __('general.your_cart'))

@push('styles')
<style>
.color-swatch {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 1px solid #ccc;
    margin-right: 5px;
    vertical-align: middle;
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('general.your_cart') }}</h1>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if(!empty($cartItems))
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('general.product') }}</th>
                                    <th>{{ __('general.color') }}</th>
                                    <th>{{ __('general.size') }}</th>
                                    <th>{{ __('general.price') }}</th>
                                    <th>{{ __('general.quantity') }}</th>
                                    <th>{{ __('general.total') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('img/products/' . $item->product->photo) }}" alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-secondary">{{ __('general.available') }}: {{ $item->product->quantity }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->color)
                                            <span class="color-swatch" data-color="{{ $item->color->hex_code }}"></span>
                                            {{ $item->color->name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $item->size ? $item->size->name : 'N/A' }}</td>
                                    <td><span class="price-value" data-base-price="{{ $item->product->price }}">{{ formatPrice($item->product->price) }}</span></td>
                                    <td>
                                        <div class="input-group input-group-sm" style="width: 100px;">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                                @csrf
                                                <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}">
                                                <button type="submit" class="btn btn-outline-secondary">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td><span class="price-value" data-base-price="{{ $item->product->price * $item->quantity }}">{{ formatPrice($item->product->price * $item->quantity) }}</span></td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('products.list') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('general.continue_shopping') }}
                </a>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-2"></i>{{ __('general.clear_cart') }}
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">{{ __('general.order_summary') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('general.subtotal') }}</span>
                        <span class="price-value" data-base-price="{{ $subTotal }}">{{ formatPrice($subTotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('general.shipping') }}</span>
                        <span class="price-value" data-base-price="{{ $shippingCost }}">{{ formatPrice($shippingCost) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>{{ __('general.tax') }}</span>
                        <span class="price-value" data-base-price="{{ $tax }}">{{ formatPrice($tax) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>{{ __('general.total') }}</strong>
                        <strong class="price-value" data-base-price="{{ $total }}">{{ formatPrice($total) }}</strong>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                            {{ __('general.checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-cart-x" style="font-size: 4rem;"></i>
        </div>
        <h2>{{ __('general.cart_empty') }}</h2>
        <p class="text-secondary mb-4">{{ __('general.cart_empty_message') }}</p>
        <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg">
            {{ __('general.start_shopping') }}
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.color-swatch').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
    });
</script>
@endpush