@extends('layouts.admin')

@section('title', isset($product->id) ? 'Edit Product' : 'Add New Product')
@section('description', isset($product->id) ? 'Update product information' : 'Create a new product')

@section('content')
    <div class="admin-header">
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
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i> All product prices are stored in EGP (Egyptian Pound) as the base currency. Currency conversion happens dynamically at display time.
            </div>
            
            <form method="POST" action="{{ isset($product->id) ? route('admin.products.save', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Product Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $product->code ?? '') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">EGP</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Stock Quantity</label>
                        <input type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Categories</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Main Category</label>
                                <select class="form-select @error('main_category') is-invalid @enderror" id="main_category" name="main_category">
                                    <option value="">Select Main Category</option>
                                    @foreach(\App\Models\Category::where('type', 'main')->get() as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (isset($product) && $product->categories->contains($category->id)) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                                @error('main_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Clothing Type</label>
                                <select class="form-select @error('clothing_category') is-invalid @enderror" id="clothing_category" name="clothing_category">
                                    <option value="">Select Clothing Type</option>
                                    @foreach(\App\Models\Category::where('type', 'clothing')->with('parents')->get() as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (isset($product) && $product->categories->contains($category->id)) ? 'selected' : '' }}
                                            data-parents="{{ $category->parents->pluck('id')->join(',') }}">
                                            {{ $category->name }}
                                            @if($category->parents->count() > 0)
                                                ({{ $category->parents->pluck('name')->join(', ') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('clothing_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Item Type</label>
                                <select class="form-select @error('item_category') is-invalid @enderror" id="item_category" name="item_category">
                                    <option value="">Select Item Type</option>
                                    @foreach(\App\Models\Category::where('type', 'item_type')->with('parents')->get() as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (isset($product) && $product->categories->contains($category->id)) ? 'selected' : '' }}
                                            data-parents="{{ $category->parents->pluck('id')->join(',') }}">
                                            {{ $category->name }}
                                            @if($category->parents->count() > 0)
                                                ({{ $category->parents->pluck('name')->join(', ') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                            </div>
                        </div>
                        
                        {{-- Hidden field to store all selected categories --}}
                        <input type="hidden" name="categories[]" id="categories_combined">
                        
                        <div class="form-text mt-2">
                            Select one category from each type. The product will automatically be assigned to the selected categories. Note that clothing types and item types may belong to multiple parent categories.
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Colors</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($colors as $color)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $color->id }}" 
                                        id="color-{{ $color->id }}" {{ (isset($product) && $product->colors->contains($color->id)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="color-{{ $color->id }}">
                                        <span class="color-swatch" data-color="{{ $color->hex_code }}"></span>
                                        {{ $color->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sizes</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($sizes as $size)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size->id }}" 
                                        id="size-{{ $size->id }}" {{ (isset($product) && $product->sizes->contains($size->id)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="size-{{ $size->id }}">{{ $size->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="photo" class="form-label">Product Image</label>
                        
                        @if(isset($product) && $product->photo)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 200px">
                                <p class="small text-secondary mt-1">Current image</p>
                            </div>
                        @endif
                        
                        <div class="dropzone-container" id="productImageDropzone">
                            <div class="dropzone-prompt">
                                <i class="bi bi-cloud-arrow-up fs-3 mb-2"></i>
                                <p class="mb-0">Drag and drop your image here, or click to browse</p>
                                <p class="small text-muted">Supports: JPG, PNG, GIF (Max 2MB)</p>
                            </div>
                            <div class="dropzone-preview" style="display: none;">
                                <div class="image-preview"></div>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-image">
                                    <i class="bi bi-trash"></i> Remove Image
                                </button>
                            </div>
                            <input type="file" class="form-control d-none @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                        </div>
                        @error('photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
        border: 2px dashed var(--border);
        border-radius: var(--radius-md);
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: var(--surface-alt);
    }
    
    .dropzone-container:hover, .dropzone-container.dragover {
        border-color: var(--primary);
        background-color: rgba(127, 90, 240, 0.05);
    }
    
    .dropzone-prompt {
        color: var(--text-secondary);
    }
    
    .image-preview {
        max-width: 100%;
        height: 200px;
        margin: 0 auto;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .color-swatch {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        margin-right: 0.5rem;
        border: 1px solid var(--border);
    }
</style>
@endpush 

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle categories
        const mainCategorySelect = document.getElementById('main_category');
        const clothingCategorySelect = document.getElementById('clothing_category');
        const itemCategorySelect = document.getElementById('item_category');
        const categoriesCombined = document.getElementById('categories_combined');
        const form = document.querySelector('form');
        
        // Submit handler to ensure categories are combined
        form.addEventListener('submit', function(e) {
            updateCombinedCategories();
        });
        
        // Filter clothing categories based on selected main category
        mainCategorySelect.addEventListener('change', function() {
            const mainCategoryId = this.value;
            const clothingOptions = clothingCategorySelect.querySelectorAll('option:not(:first-child)');
            
            clothingOptions.forEach(option => {
                const parentIds = option.getAttribute('data-parents') ? option.getAttribute('data-parents').split(',') : [];
                if (mainCategoryId === '') {
                    option.style.display = '';  // Show all if no main category selected
                } else if (parentIds.includes(mainCategoryId)) {
                    option.style.display = '';  // Show if parent matches
                } else {
                    option.style.display = 'none';  // Hide if parent doesn't match
                    // Deselect if currently selected but should be hidden
                    if (option.selected) {
                        clothingCategorySelect.value = '';
                    }
                }
            });
            
            // Trigger clothing category change to update item types
            clothingCategorySelect.dispatchEvent(new Event('change'));
            updateCombinedCategories();
        });
        
        // Filter item categories based on selected clothing category
        clothingCategorySelect.addEventListener('change', function() {
            const clothingCategoryId = this.value;
            const itemOptions = itemCategorySelect.querySelectorAll('option:not(:first-child)');
            
            itemOptions.forEach(option => {
                const parentIds = option.getAttribute('data-parents') ? option.getAttribute('data-parents').split(',') : [];
                if (clothingCategoryId === '') {
                    option.style.display = '';  // Show all if no clothing category selected
                } else if (parentIds.includes(clothingCategoryId)) {
                    option.style.display = '';  // Show if parent matches
                } else {
                    option.style.display = 'none';  // Hide if parent doesn't match
                    // Deselect if currently selected but should be hidden
                    if (option.selected) {
                        itemCategorySelect.value = '';
                    }
                }
            });
            
            updateCombinedCategories();
        });
        
        // Update combined categories when item type changes
        itemCategorySelect.addEventListener('change', updateCombinedCategories);
        
        // Combine selected categories into the hidden input
        function updateCombinedCategories() {
            const selectedCategories = [];
            
            if (mainCategorySelect.value) {
                selectedCategories.push(mainCategorySelect.value);
            }
            
            if (clothingCategorySelect.value) {
                selectedCategories.push(clothingCategorySelect.value);
            }
            
            if (itemCategorySelect.value) {
                selectedCategories.push(itemCategorySelect.value);
            }
            
            // Update the hidden input with all selected category IDs
            categoriesCombined.value = selectedCategories.join(',');
        }
        
        // Initial update
        mainCategorySelect.dispatchEvent(new Event('change'));
        
        // Apply color swatches background colors
        document.querySelectorAll('.color-swatch').forEach(function(swatch) {
            swatch.style.backgroundColor = swatch.dataset.color;
        });
        
        // Initialize dropzone functionality
        const dropzone = document.getElementById('productImageDropzone');
        const fileInput = dropzone.querySelector('input[type="file"]');
        const preview = dropzone.querySelector('.dropzone-preview');
        const imagePreview = preview.querySelector('.image-preview');
        const prompt = dropzone.querySelector('.dropzone-prompt');
        const removeButton = dropzone.querySelector('.remove-image');
        
        // Handle click on dropzone
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Handle file selection
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Check file type
                if (!file.type.match('image.*')) {
                    alert('Please select an image file');
                    return;
                }
                
                // Check file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should not exceed 2MB');
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.style.backgroundImage = `url(${e.target.result})`;
                    prompt.style.display = 'none';
                    preview.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        // Handle drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, function() {
                this.classList.add('dragover');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, function() {
                this.classList.remove('dragover');
            }, false);
        });
        
        dropzone.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            if (files && files.length) {
                fileInput.files = files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        }, false);
        
        // Handle remove button
        removeButton.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.value = '';
            imagePreview.style.backgroundImage = '';
            prompt.style.display = 'block';
            preview.style.display = 'none';
        });
        
        // Make the categories select more user-friendly
        const categoriesSelect = document.getElementById('categories');
        if (categoriesSelect) {
            // Add Bootstrap's select2 if available (optional enhancement)
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $(categoriesSelect).select2({
                    placeholder: 'Select categories',
                    allowClear: true
                });
            }
        }
    });
</script>
@endpush 