@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Product Management</h1>
            <p class="text-secondary mb-0">Manage all products in your store</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Add New Product
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Products List</span>
            <span class="badge bg-primary">{{ $products->total() }} Products</span>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table">
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
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50" class="img-thumbnail">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if(isset($product->stock) || isset($product->quantity))
                                        @php $stock = isset($product->stock) ? $product->stock : $product->quantity; @endphp
                                        @if($stock > 10)
                                            <span class="badge bg-success">{{ $stock }}</span>
                                        @elseif($stock > 0)
                                            <span class="badge bg-warning">{{ $stock }}</span>
                                        @else
                                            <span class="badge bg-danger">Out of stock</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if(method_exists($product, 'categories') && $product->categories && $product->categories->count() > 0)
                                        @foreach($product->categories as $category)
                                            <span class="badge bg-info">{{ $category->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">{{ $product->category ?? 'No category' }}</span>
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
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection 