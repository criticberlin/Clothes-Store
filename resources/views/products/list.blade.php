@extends('layouts.master')

@section('title', isset($category) ? ucfirst($category) . ' Category' : 'Product Search Results')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-capitalize">{{ isset($category) ? $category . ' Collection' : 'Product Search Results' }}</h2>

    <form class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input name="{{ isset($category) ? 'keywords' : 'query' }}" type="text" class="form-control" 
                    placeholder="Search Keywords" value="{{ isset($category) ? request()->get('keywords') : request()->get('query') }}" />
            </div>
            @if(isset($category))
            <div class="col-md-2">
                <input name="min_price" type="numeric" class="form-control" placeholder="Min Price" value="{{ request()->input('min_price') }}"/>
            </div>
            <div class="col-md-2">
                <input name="max_price" type="numeric" class="form-control" placeholder="Max Price" value="{{ request()->input('max_price') }}"/>
            </div>
            <div class="col-md-2">
                <select name="order_by" class="form-select">
                    <option value="" {{ request()->input('order_by') == "" ? "selected" : "" }} disabled>Order By</option>
                    <option value="name" {{ request()->input('order_by') == "name" ? "selected" : "" }}>Name</option>
                    <option value="price" {{ request()->input('order_by') == "price" ? "selected" : "" }}>Price</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="order_direction" class="form-select">
                    <option value="" {{ request()->input('order_direction') == "" ? "selected" : "" }} disabled>Order Direction</option>
                    <option value="ASC" {{ request()->input('order_direction') == "ASC" ? "selected" : "" }}>ASC</option>
                    <option value="DESC" {{ request()->input('order_direction') == "DESC" ? "selected" : "" }}>DESC</option>
                </select>
            </div>
            @endif
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">{{ __('general.search') }}</button>
            </div>
            <div class="col-auto">
                <a href="{{ isset($category) ? route('products.category', $category) : route('products.list') }}" 
                   class="btn btn-outline-secondary">{{ __('general.reset') }}</a>
            </div>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    @forelse($products as $product)
    <div class="col">
        <div class="card h-100 product-card">
            <div class="position-relative">
                @if($product->photo)
                <img src="{{ asset('img/products/' . $product->photo) }}" class="card-img-top" alt="{{ $product->name }}">
                @endif
                <div class="position-absolute top-0 end-0 m-2">
                    @if($product->quantity <= 0)
                    <span class="badge bg-danger">{{ __('general.out_of_stock') }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text text-secondary small flex-grow-1">{{ Str::limit($product->description, 70) }}</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="price-value fw-bold" data-base-price="{{ $product->price }}">{{ formatPrice($product->price) }}</span>
                    <a href="{{ route('products.details', $product->id) }}" class="btn btn-sm btn-primary">{{ __('general.view') }}</a>
                </div>
            </div>
        </div>
    </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">{{ __('general.no_products_found') }}</div>
        </div>
    @endforelse
    </div>
    
    @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>

<style>
.product-card {
    transition: transform 0.2s ease-in-out;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}
.card-img-top {
    height: 220px;
    object-fit: cover;
}
</style>
@endsection
