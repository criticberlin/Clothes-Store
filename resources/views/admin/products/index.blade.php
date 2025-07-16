@extends('layouts.admin')

@section('title', 'Product Management')
@section('description', 'Manage all products in your store')

@section('content')
    <div class="admin-header">
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Add New Product
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Products List</span>
            <span class="badge bg-primary">{{ $products->count() }} Products</span>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Categories</th>
                            <th class="text-center">Ratings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <div class="product-thumbnail">
                                    @if($product->photo)
                                            <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="product-img">
                                    @else
                                            <div class="product-img-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    </div>
                                </td>
                                <td>{{ $product->name }}</td>
                                <td><span class="price-display" data-base-price="{{ $product->price }}">{{ format_price($product->price) }}</span></td>
                                <td>
                                    @if($product->quantity > 10)
                                        <span class="status-badge completed">
                                            <i class="bi bi-check-circle"></i> {{ $product->quantity }}
                                        </span>
                                    @elseif($product->quantity > 0)
                                        <span class="status-badge pending">
                                            <i class="bi bi-exclamation-circle"></i> {{ $product->quantity }}
                                        </span>
                                    @else
                                        <span class="status-badge cancelled">
                                            <i class="bi bi-x-circle"></i> Out of stock
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->categories && $product->categories->count() > 0)
                                        @foreach($product->categories as $category)
                                            <span class="status-badge completed">
                                                <i class="bi bi-tag"></i> {{ $category->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="status-badge cancelled">
                                            <i class="bi bi-dash-circle"></i> No category
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center" 
                                         data-bs-toggle="tooltip" 
                                         data-bs-placement="top" 
                                         title="{{ $product->ratings_count }} ratings">
                                        <div class="d-inline-flex mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= round($product->average_rating))
                                                    <i class="bi bi-star-fill text-warning small"></i>
                                                @else
                                                    <i class="bi bi-star text-warning small"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-secondary">{{ number_format($product->average_rating, 1) }} ({{ $product->ratings_count }})</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="action-btn" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="action-btn delete" title="Delete"
                                                onclick="document.getElementById('delete-product-{{ $product->id }}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-product-{{ $product->id }}" 
                                          action="{{ route('admin.products.delete', $product) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 