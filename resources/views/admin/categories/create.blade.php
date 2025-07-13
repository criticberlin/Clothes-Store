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
                        <div class="dropzone-container border rounded p-3">
                            <div class="dz-message text-center py-5">
                                <div class="mb-3">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                                </div>
                                <h5>Drop files here or click to upload</h5>
                                <p class="text-secondary small">Upload a representative image for this category (optional)</p>
                            </div>
                            <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
</style>
@endpush 