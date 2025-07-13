@extends('layouts.admin')

@section('title', isset($product->id) ? 'Edit Product' : 'Add New Product')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">{{ isset($product->id) ? 'Edit Product' : 'Add New Product' }}</h1>
            <p class="text-secondary mb-0">{{ isset($product->id) ? 'Modify product details' : 'Create a new product in your store' }}</p>
        </div>
        <div>
            <a href="{{ route('admin.products.list') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Product Information</span>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.products.save', $product->id ?? null) }}" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="code" class="form-label">SKU/Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $product->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Quantity in Stock</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="categories" class="form-label">Categories</label>
                        <select class="form-select @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) || 
                                       (isset($product->id) && $product->categories->contains($category->id)) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('categories')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Hold Ctrl/Cmd to select multiple categories</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="colors" class="form-label">Available Colors</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="color_{{ $color->id }}" name="colors[]" value="{{ $color->id }}"
                                        {{ (is_array(old('colors')) && in_array($color->id, old('colors'))) || 
                                           (isset($product->id) && $product->colors->contains($color->id)) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="color_{{ $color->id }}">
                                        <span class="color-swatch me-1" data-color="{{ $color->hex_code }}"></span>
                                        {{ $color->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('colors')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="sizes" class="form-label">Available Sizes</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($sizes as $size)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="size_{{ $size->id }}" name="sizes[]" value="{{ $size->id }}"
                                        {{ (is_array(old('sizes')) && in_array($size->id, old('sizes'))) || 
                                           (isset($product->id) && $product->sizes->contains($size->id)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="size_{{ $size->id }}">
                                        {{ $size->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('sizes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="image" class="form-label">Product Image</label>
                        
                        @if($product->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 200px">
                                <p class="small text-secondary mt-1">Current image from storage</p>
                            </div>
                        @elseif($product->photo)
                            <div class="mb-3">
                                <img src="{{ asset('img/products/' . $product->photo) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 200px">
                                <p class="small text-secondary mt-1">Current image from legacy path</p>
                            </div>
                        @endif
                        
                        <div class="dropzone-container border rounded p-3">
                            <div class="dz-message text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                                </div>
                                <h5>Drop files here or click to upload</h5>
                                <p class="text-secondary small">Upload a product image (JPEG, PNG, WebP)</p>
                            </div>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> {{ isset($product->id) ? 'Update Product' : 'Save Product' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .dropzone-container {
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .dropzone-container:hover {
        border-color: var(--primary) !important;
        background-color: rgba(127, 90, 240, 0.05);
    }
    
    .dropzone-container input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 1;
    }
    
    .color-swatch {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 1px solid var(--border);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.color-swatch').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
    });
</script>
@endpush 