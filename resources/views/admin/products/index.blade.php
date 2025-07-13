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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->photo)
                                        <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" width="50" class="img-thumbnail">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if($product->quantity > 10)
                                        <span class="badge bg-success">{{ $product->quantity }}</span>
                                    @elseif($product->quantity > 0)
                                        <span class="badge bg-warning">{{ $product->quantity }}</span>
                                    @else
                                        <span class="badge bg-danger">Out of stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->categories && $product->categories->count() > 0)
                                        @foreach($product->categories as $category)
                                            <span class="badge bg-info">{{ $category->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-secondary">No category</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
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