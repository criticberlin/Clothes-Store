@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Add New Product</h1>
            <p class="text-secondary mb-0">Create a new product in your store</p>
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
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}">
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price (EGP)</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="compare_price" class="form-label">Compare at Price ($)</label>
                        <input type="number" class="form-control @error('compare_price') is-invalid @enderror" id="compare_price" name="compare_price" value="{{ old('compare_price') }}" step="0.01" min="0">
                        @error('compare_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="cost" class="form-label">Cost per Item ($)</label>
                        <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{ old('cost') }}" step="0.01" min="0">
                        @error('cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" step="0.01" min="0">
                        @error('weight')
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
                                        <option value="{{ $category->id }}" {{ old('main_category') == $category->id ? 'selected' : '' }}>
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
                                        <option value="{{ $category->id }}" {{ old('clothing_category') == $category->id ? 'selected' : '' }}
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
                                        <option value="{{ $category->id }}" {{ old('item_category') == $category->id ? 'selected' : '' }}
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
                
                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <div class="dropzone-container" id="productImageDropzone">
                        <div class="dropzone-prompt">
                            <i class="bi bi-cloud-arrow-up fs-3 mb-2"></i>
                            <p class="mb-0">Drag and drop your image here, or click to browse</p>
                            <p class="small text-muted">Supports: JPG, PNG, GIF (Max 5MB)</p>
                        </div>
                        <div class="dropzone-preview" style="display: none;">
                            <div class="image-preview"></div>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-image">
                                <i class="bi bi-trash"></i> Remove Image
                            </button>
                        </div>
                        <input type="file" class="form-control d-none @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">
                            Product Active
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="featured">
                            Featured Product
                        </label>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Create Product
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
                
                // Check file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size should not exceed 5MB');
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
    });
</script>
@endpush 