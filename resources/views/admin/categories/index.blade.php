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
                            <th>Parents</th>
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
                                    <div class="small text-secondary">{{ $category->slug }}</div>
                                </td>
                                <td>
                                    @if($category->type == 'main')
                                        <span class="status-badge completed">
                                            <i class="bi bi-people"></i> Main
                                        </span>
                                    @elseif($category->type == 'clothing')
                                        <span class="status-badge pending">
                                            <i class="bi bi-collection"></i> Clothing
                                        </span>
                                    @elseif($category->type == 'item_type')
                                        <span class="status-badge info">
                                            <i class="bi bi-tag"></i> Item Type
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->parents->count() > 0)
                                        <div class="category-parents">
                                            @foreach($category->parents as $parent)
                                                <span class="status-badge completed">
                                                    <i class="bi bi-tag"></i> {{ $parent->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="status-badge cancelled">
                                            <i class="bi bi-dash-circle"></i> Top Level
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->status)
                                        <span class="status-badge completed">
                                            <i class="bi bi-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="status-badge cancelled">
                                            <i class="bi bi-x-circle"></i> Hidden
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge info">
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
                    $mainCategories = $categories->where('type', 'main');
                @endphp

                @foreach($mainCategories as $mainCat)
                    <div class="tree-item main-category">
                        <div class="tree-content">
                            <span class="tree-icon"><i class="bi bi-people"></i></span>
                            <strong>{{ $mainCat->name }}</strong>
                            <span class="status-badge completed ms-2">Main</span>
                            @if(!$mainCat->status)
                                <span class="status-badge cancelled ms-1">Hidden</span>
                            @endif
                        </div>

                        <div class="tree-children">
                            @php
                                // Get clothing types that have this main category as parent
                                $clothingTypes = $categories->where('type', 'clothing')->filter(function($cat) use ($mainCat) {
                                    return $cat->parents->contains('id', $mainCat->id);
                                });
                            @endphp

                            @foreach($clothingTypes as $clothingType)
                                <div class="tree-item clothing-category">
                                    <div class="tree-content">
                                        <span class="tree-icon"><i class="bi bi-collection"></i></span>
                                        <strong>{{ $clothingType->name }}</strong>
                                        <span class="status-badge pending ms-2">Clothing</span>
                                        @if(!$clothingType->status)
                                            <span class="status-badge cancelled ms-1">Hidden</span>
                                        @endif
                                        @if($clothingType->parents->count() > 1)
                                            <span class="status-badge info ms-1">{{ $clothingType->parents->count() }} Parents</span>
                                        @endif
                                    </div>

                                    <div class="tree-children">
                                        @php
                                            // Get item types that have this clothing type as parent
                                            $itemTypes = $categories->where('type', 'item_type')->filter(function($cat) use ($clothingType) {
                                                return $cat->parents->contains('id', $clothingType->id);
                                            });
                                        @endphp

                                        @foreach($itemTypes as $itemType)
                                            <div class="tree-item item-type-category">
                                                <div class="tree-content">
                                                    <span class="tree-icon"><i class="bi bi-tag"></i></span>
                                                    <strong>{{ $itemType->name }}</strong>
                                                    <span class="status-badge info ms-2">Item</span>
                                                    @if(!$itemType->status)
                                                        <span class="status-badge cancelled ms-1">Hidden</span>
                                                    @endif
                                                    @if($itemType->parents->count() > 1)
                                                        <span class="status-badge info ms-1">{{ $itemType->parents->count() }} Parents</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        @if($itemTypes->isEmpty())
                                            <div class="tree-item empty-node">
                                                <div class="tree-content">
                                                    <i class="bi bi-dash-circle text-secondary"></i> <span class="text-secondary">No specific items added</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if($clothingTypes->isEmpty())
                                <div class="tree-item empty-node">
                                    <div class="tree-content">
                                        <i class="bi bi-dash-circle text-secondary"></i> <span class="text-secondary">No clothing types added</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($mainCategories->isEmpty())
                    <div class="tree-item empty-node">
                        <div class="tree-content">
                            <i class="bi bi-exclamation-circle text-secondary"></i> <span class="text-secondary">No main categories found</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="admin-card mt-4">
        <div class="admin-card-header">
            <span>Category Statistics</span>
        </div>
        <div class="admin-card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon purple">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stats-value">{{ $categories->where('type', 'main')->count() }}</div>
                        <div class="stats-label">Main Categories</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon blue">
                            <i class="bi bi-collection"></i>
                        </div>
                        <div class="stats-value">{{ $categories->where('type', 'clothing')->count() }}</div>
                        <div class="stats-label">Clothing Types</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon teal">
                            <i class="bi bi-tag"></i>
                        </div>
                        <div class="stats-value">{{ $categories->where('type', 'item_type')->count() }}</div>
                        <div class="stats-label">Item Types</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon red">
                            <i class="bi bi-eye-slash"></i>
                        </div>
                        <div class="stats-value">{{ $categories->where('status', false)->count() }}</div>
                        <div class="stats-label">Hidden Categories</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .category-parents .status-badge {
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
        display: inline-flex;
    }
    
    .category-tree {
        margin: 1rem 0;
    }
    
    .tree-item {
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .tree-content {
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        transition: all var(--transition-normal);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .tree-icon {
        margin-right: 0.75rem;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(var(--primary-rgb), 0.1);
    }
    
    .main-category > .tree-content {
        background-color: var(--surface);
        color: var(--text-primary);
        border-left: 4px solid var(--primary);
    }
    
    .clothing-category > .tree-content {
        background-color: var(--surface);
        color: var(--text-primary);
        border-left: 4px solid var(--secondary);
    }
    
    .item-type-category > .tree-content {
        background-color: var(--surface);
        color: var(--text-primary);
        border-left: 4px solid var(--info);
    }
    
    .empty-node .tree-content {
        background-color: var(--surface-alt);
        border-left: 4px solid var(--border);
    }
    
    .tree-children {
        padding-left: 2.5rem;
        border-left: 1px dashed var(--border);
        margin-left: 0.75rem;
        margin-top: 0.75rem;
    }
    
    /* Hover effects */
    .tree-content:hover {
        transform: translateX(3px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }
    
    .main-category > .tree-content:hover {
        box-shadow: 0 3px 6px rgba(var(--primary-rgb), 0.2);
    }
    
    .clothing-category > .tree-content:hover {
        box-shadow: 0 3px 6px rgba(var(--secondary-rgb), 0.2);
    }
    
    .item-type-category > .tree-content:hover {
        box-shadow: 0 3px 6px rgba(var(--info-rgb), 0.2);
    }
    
    /* Statistics cards */
    .stats-card {
        background-color: var(--surface);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all var(--transition-normal);
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
    }
    
    .stats-icon.purple {
        background-color: var(--primary);
    }
    
    .stats-icon.blue {
        background-color: var(--secondary);
    }
    
    .stats-icon.teal {
        background-color: var(--info);
    }
    
    .stats-icon.red {
        background-color: var(--danger);
    }
    
    .stats-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }
    
    .stats-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }
    
    /* Dark theme adjustments */
    html.theme-dark .tree-icon {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    html.theme-dark .tree-content:hover {
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
    }
    
    html.theme-dark .stats-card {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    html.theme-dark .stats-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Additional theme-aware styles */
    html.theme-dark .product-img-placeholder {
        background-color: rgba(255, 255, 255, 0.1);
    }

    html.theme-dark .small.text-secondary {
        color: rgba(255, 255, 255, 0.6) !important;
    }
</style>
@endpush 

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add no-pagination class to the table to disable DataTables pagination
        document.querySelector('.admin-datatable').classList.add('no-pagination');
        
        // Delete confirmation for category delete forms
        document.querySelectorAll('.delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const formId = this.getAttribute('onclick').match(/document\.getElementById\('([^']+)'\)/)[1];
                if (confirm('Are you sure you want to delete this category?')) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });
</script>
@endpush 