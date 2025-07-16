@extends('layouts.admin')

@section('title', 'Add New Category')
@section('description', 'Create a new product category')

@section('content')
    <div class="admin-header">
        <div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i> Back to Categories
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Category Information</span>
        </div>
        <div class="admin-card-body">
            <form method="POST" id="category-form" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="slug" class="form-label">Slug (Optional)</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="auto-generated-if-empty">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave empty to auto-generate from name</small>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label">Category Type</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            @foreach($categoryTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Parent Categories</label>
                        <div class="parent-categories-container p-3 border rounded" style="max-height: 200px; overflow-y: auto;">
                            @foreach($parentCategories as $parentCategory)
                                <div class="form-check mb-2 parent-option" data-type="{{ $parentCategory->type }}">
                                    <input class="form-check-input" type="checkbox" name="parent_id[]" 
                                        id="parent-{{ $parentCategory->id }}" value="{{ $parentCategory->id }}"
                                        {{ in_array($parentCategory->id, old('parent_id', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="parent-{{ $parentCategory->id }}">
                                        {{ $parentCategory->name }} 
                                        @if($parentCategory->parent)
                                            <span class="text-muted">({{ $parentCategory->parent->name }})</span>
                                        @endif
                                        <span class="badge bg-secondary">{{ $categoryTypes[$parentCategory->type] }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('parent_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="parent-category-help" class="form-text">
                            <ul class="mb-0 ps-3 small">
                                <li>Main categories should be top level (no parent)</li>
                                <li>Clothing types should have Main category parents</li>
                                <li>Item types should have Clothing type parents</li>
                                <li>You can select multiple parent categories</li>
                            </ul>
                            @if(session('parent_error'))
                                <div class="text-danger mt-2">{{ session('parent_error') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="photo" class="form-label">Category Image</label>
                        <div class="dropzone-container" id="categoryImageDropzone">
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
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" checked>
                            <label class="form-check-label" for="status">Visible on store</label>
                        </div>
                        <div class="form-text">Uncheck to hide this category from the store front</div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Save Category
                    </button>
                </div>
            </form>
            
            <div id="form-result" class="mt-3" style="display: none;"></div>
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
        // Form elements
        var form = document.querySelector('form');
        var typeSelect = document.getElementById('type');
        var parentOptions = document.querySelectorAll('.parent-option');
        
        // Make sure submit button is clickable
        var submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = false;
        }
        
        // Validate form before submission
        form.addEventListener('submit', function(e) {
            // Get selected parents
            var selectedParents = document.querySelectorAll('input[name="parent_id[]"]:checked');
            
            // Check if parents are required but none selected
            if (typeSelect.value !== 'main' && selectedParents.length === 0) {
                e.preventDefault();
                var message = 'Please select at least one parent category. ';
                if (typeSelect.value === 'clothing') {
                    message += 'Clothing types must have Main category parents.';
                } else {
                    message += 'Item types must have Clothing type parents.';
                }
                alert(message);
                return false;
            }
            
            return true;
        });
        
        // Filter parent options based on selected type
        function filterParentOptions() {
            var selectedType = typeSelect.value;
            
            // Show/hide parent options based on type
            parentOptions.forEach(function(option) {
                var parentType = option.getAttribute('data-type');
                var checkbox = option.querySelector('input[type="checkbox"]');
                
                if (selectedType === 'main') {
                    // Main categories should have no parent
                    option.style.display = 'none';
                    checkbox.checked = false;
                } else if (selectedType === 'clothing' && parentType === 'main') {
                    // Clothing types should have main category parents
                    option.style.display = '';
                } else if (selectedType === 'item_type' && parentType === 'clothing') {
                    // Item types should have clothing type parents
                    option.style.display = '';
                } else {
                    // Hide and uncheck other options
                    option.style.display = 'none';
                    checkbox.checked = false;
                }
            });
        }
        
        // Initial filtering
        filterParentOptions();
        
        // Filter on type change
        typeSelect.addEventListener('change', filterParentOptions);
        
        // Dropzone functionality
        var dropzone = document.getElementById('categoryImageDropzone');
        var fileInput = dropzone.querySelector('input[type="file"]');
        var preview = dropzone.querySelector('.dropzone-preview');
        var imagePreview = preview.querySelector('.image-preview');
        var prompt = dropzone.querySelector('.dropzone-prompt');
        var removeButton = dropzone.querySelector('.remove-image');
        
        // Handle click on dropzone
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });
        
        // Handle file selection
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var file = this.files[0];
                
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
                
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.style.backgroundImage = 'url("' + e.target.result + '")';
                    prompt.style.display = 'none';
                    preview.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        // Handle drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });
        
        ['dragenter', 'dragover'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function() {
                this.classList.add('dragover');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(function(eventName) {
            dropzone.addEventListener(eventName, function() {
                this.classList.remove('dragover');
            }, false);
        });
        
        dropzone.addEventListener('drop', function(e) {
            var files = e.dataTransfer.files;
            if (files && files.length) {
                fileInput.files = files;
                
                // Trigger change event
                var event = new Event('change', { bubbles: true });
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
        
        // Auto-generate slug from name
        var nameInput = document.getElementById('name');
        var slugInput = document.getElementById('slug');
        
        nameInput.addEventListener('input', function() {
            if (!slugInput.value) {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
            }
        });
    });
</script>
@endpush 