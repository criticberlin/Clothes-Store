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
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-auto">
                <a href="{{ isset($category) ? route('products.byCategory', $category) : route('products.list') }}" 
                   class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div class="row">
    @forelse($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            @if($product->photo)
            <img src="{{ asset("images/$product->photo") }}" class="card-img-top" alt="{{ $product->name }}">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                {{-- <p class="card-text">{{ $product->description }}</p> --}}
                <p class="text-warning h4 mb-3">${{ $product->price }}</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('products.details', $product->id) }}" class="btn btn-outline-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
    @empty
        <p>No products found.</p>
    @endforelse
    </div>
    
    @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-5px);
}
.btn {
    transition: all 0.2s ease-in-out;
}
.btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection
