@extends('layouts.admin')

@section('title', 'Category Management')
@section('description', 'Manage product categories')

@section('content')
    <div class="admin-header">
        <div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Add New Category
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="admin-card">
        <div class="admin-card-header">
            <span>Categories List</span>
            <span class="badge bg-primary">{{ $categories->count() }} Categories</span>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table admin-datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Hierarchy</th>
                            <th>Status</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    <div class="product-thumbnail">
                                        @if($category->photo)
                                            <img src="{{ asset('storage/' . $category->photo) }}" alt="{{ $category->name }}" class="product-img">
                                        @else
                                            <div class="product-img-placeholder">
                                                <i class="bi bi-tag"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                    <div class="small text-muted">{{ $category->slug }}</div>
                                </td>
                                <td>
                                    @if($category->type == 'main')
                                        <span class="badge bg-primary">Main</span>
                                    @elseif($category->type == 'clothing')
                                        <span class="badge bg-secondary">Clothing</span>
                                    @elseif($category->type == 'item_type')
                                        <span class="badge bg-info">Item Type</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->parent)
                                        <div class="category-path">
                                            {{ $category->getBreadcrumbPath() }}
                                        </div>
                                    @else
                                        <span class="text-muted">Top Level</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge completed">
                                        <i class="bi bi-collection"></i> {{ $category->products->count() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="action-btn delete" title="Delete"
                                                onclick="document.getElementById('delete-category-{{ $category->id }}').submit()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-category-{{ $category->id }}" 
                                          action="{{ route('admin.categories.destroy', $category) }}" 
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
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <div class="admin-card mt-4">
        <div class="admin-card-header">
            <span>Category Hierarchy</span>
        </div>
        <div class="admin-card-body">
            <div class="category-tree">
                @php
                    $mainCategories = $categories->where('type', 'main')->where('parent_id', null);
                @endphp

                @foreach($mainCategories as $mainCat)
                    <div class="tree-item main-category">
                        <div class="tree-content">
                            <span class="tree-icon"><i class="bi bi-people"></i></span>
                            <strong>{{ $mainCat->name }}</strong>
                            <span class="badge bg-primary ms-2">Main</span>
                            @if(!$mainCat->status)
                                <span class="badge bg-danger ms-1">Hidden</span>
                            @endif
                        </div>

                        <div class="tree-children">
                            @php
                                $clothingTypes = $categories->where('parent_id', $mainCat->id)->where('type', 'clothing');
                            @endphp

                            @foreach($clothingTypes as $clothingType)
                                <div class="tree-item clothing-category">
                                    <div class="tree-content">
                                        <span class="tree-icon"><i class="bi bi-collection"></i></span>
                                        <strong>{{ $clothingType->name }}</strong>
                                        <span class="badge bg-secondary ms-2">Clothing</span>
                                        @if(!$clothingType->status)
                                            <span class="badge bg-danger ms-1">Hidden</span>
                                        @endif
                                    </div>

                                    <div class="tree-children">
                                        @php
                                            $itemTypes = $categories->where('parent_id', $clothingType->id)->where('type', 'item_type');
                                        @endphp

                                        @foreach($itemTypes as $itemType)
                                            <div class="tree-item item-type-category">
                                                <div class="tree-content">
                                                    <span class="tree-icon"><i class="bi bi-tag"></i></span>
                                                    <strong>{{ $itemType->name }}</strong>
                                                    <span class="badge bg-info ms-2">Item</span>
                                                    @if(!$itemType->status)
                                                        <span class="badge bg-danger ms-1">Hidden</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        @if($itemTypes->isEmpty())
                                            <div class="tree-item empty-node">
                                                <div class="tree-content text-muted">
                                                    <i class="bi bi-dash-circle"></i> No specific items added
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if($clothingTypes->isEmpty())
                                <div class="tree-item empty-node">
                                    <div class="tree-content text-muted">
                                        <i class="bi bi-dash-circle"></i> No clothing types added
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($mainCategories->isEmpty())
                    <div class="tree-item empty-node">
                        <div class="tree-content text-muted">
                            <i class="bi bi-exclamation-circle"></i> No main categories found
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .category-path {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
    
    .category-tree {
        margin: 1rem 0;
    }
    
    .tree-item {
        margin-bottom: 0.5rem;
    }
    
    .tree-content {
        padding: 0.5rem 0.75rem;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
    }
    
    .main-category > .tree-content {
        background-color: rgba(var(--primary-rgb), 0.05);
    }
    
    .clothing-category > .tree-content {
        background-color: rgba(var(--secondary-rgb), 0.05);
    }
    
    .item-type-category > .tree-content {
        background-color: rgba(var(--info-rgb), 0.05);
    }
    
    .tree-icon {
        margin-right: 0.5rem;
    }
    
    .tree-children {
        padding-left: 2rem;
        border-left: 1px dashed var(--border);
        margin-left: 0.75rem;
        margin-top: 0.5rem;
    }
    
    .empty-node .tree-content {
        font-style: italic;
        background-color: transparent;
    }
</style>
@endpush 