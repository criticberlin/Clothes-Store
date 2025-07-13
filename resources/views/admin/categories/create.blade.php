@extends('layouts.admin')

@section('title', 'Add New Category')

@section('content')
    <div class="admin-header">
        <div>
            <h1 class="mb-2">Add New Category</h1>
            <p class="text-secondary mb-0">Create a new product category</p>
        </div>
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
            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
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
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Save Category
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
        const dropzone = document.getElementById('categoryImageDropzone');
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
    });
</script>
@endpush 