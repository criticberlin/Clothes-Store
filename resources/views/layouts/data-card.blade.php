{{-- 
    Standardized Data Card Component for Grid Views
    Usage:
    @include('layouts.data-card', [
        'title' => 'Grid Title',
        'createRoute' => route('some.create.route'), // Optional
        'createText' => 'Add New Item', // Optional
        'data' => $dataCollection,
        'emptyMessage' => 'No items found', // Optional
        'searchPlaceholder' => 'Search items...', // Optional
    ])
--}}

<div class="admin-card mb-4">
    <div class="admin-card-header">
        <div>
            <h5 class="mb-0">{{ $title ?? 'Data Grid' }}</h5>
            @if(isset($subtitle))
                <p class="text-secondary mb-0 mt-1 small">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="d-flex gap-2">
            @if(isset($searchPlaceholder))
            <div class="search-wrapper position-relative">
                <input type="search" class="form-control search-input" placeholder="{{ $searchPlaceholder }}" 
                       aria-label="Search" id="gridSearch">
                <span class="search-icon">
                    <i class="bi bi-search"></i>
                </span>
            </div>
            @endif
            
            <div class="view-toggle btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary active" data-view="grid">
                    <i class="bi bi-grid"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" data-view="list">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
            
            @if(isset($createRoute) && isset($createText))
            <a href="{{ $createRoute }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> {{ $createText }}
            </a>
            @endif
        </div>
    </div>
    <div class="admin-card-body p-3">
        @if(count($data) > 0)
            <div class="row g-3 data-grid-container">
                @foreach($data as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 data-grid-item">
                        <div class="data-card">
                            @yield('card_content')
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state py-5">
                <i class="bi bi-inbox fs-1 text-secondary mb-3"></i>
                <p class="mb-0">{{ $emptyMessage ?? 'No items found' }}</p>
                @if(isset($emptyActionRoute) && isset($emptyActionText))
                    <a href="{{ $emptyActionRoute }}" class="btn btn-sm btn-primary mt-3">
                        {{ $emptyActionText }}
                    </a>
                @endif
            </div>
        @endif
    </div>
    
    @if(isset($data) && method_exists($data, 'links') && $data->hasPages())
    <div class="admin-card-footer p-3 d-flex justify-content-between align-items-center">
        <div class="text-secondary small">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} of {{ $data->total() ?? 0 }} entries
        </div>
        <div>
            {{ $data->links() }}
        </div>
    </div>
    @endif
</div>

<style>
    .search-wrapper {
        position: relative;
        min-width: 250px;
    }
    
    .search-input {
        padding-left: 2.5rem;
        border-radius: 0.5rem;
        background-color: var(--surface-alt);
        border: 1px solid var(--border);
        color: var(--text-primary);
    }
    
    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
        color: var(--text-secondary);
    }
    
    .admin-card-footer .pagination {
        margin-bottom: 0;
    }
    
    /* Admin card theme awareness */
    .admin-card {
        background-color: var(--surface);
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    
    .admin-card-header {
        background-color: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .admin-card-body {
        background-color: var(--surface);
        color: var(--text-primary);
    }
    
    .admin-card-footer {
        background-color: var(--surface);
        border-top: 1px solid var(--border);
        color: var(--text-secondary);
    }
    
    /* Theme-aware card styles */
    .data-card {
        background-color: var(--surface);
        border: 1px solid var(--border);
        color: var(--text-primary);
        border-radius: 0.5rem;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }
    
    .data-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    
    .data-card-header {
        border-bottom: 1px solid var(--border);
        background-color: var(--surface);
        padding: 0.75rem;
    }
    
    .data-card-body {
        padding: 0.75rem;
        background-color: var(--surface);
    }
    
    .data-card-footer {
        border-top: 1px solid var(--border);
        background-color: var(--surface);
        padding: 0.75rem;
    }
    
    /* Pagination theme awareness */
    .pagination .page-item .page-link {
        background-color: var(--surface);
        border-color: var(--border);
        color: var(--text-primary);
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .pagination .page-item.disabled .page-link {
        background-color: var(--surface);
        border-color: var(--border);
        color: var(--text-tertiary);
    }
    
    /* List view styles */
    .data-grid-container.list-view .data-grid-item {
        width: 100%;
        max-width: 100%;
        flex: 0 0 100%;
    }
    
    .data-grid-container.list-view .data-card {
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    
    .data-grid-container.list-view .data-card-img {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        margin-right: 1rem;
        margin-bottom: 0;
    }
    
    .data-grid-container.list-view .data-card-body {
        flex-grow: 1;
    }
    
    .data-grid-container.list-view .data-card-footer {
        flex-shrink: 0;
        margin-left: 1rem;
        border-top: none;
        padding-top: 0;
    }
    
    /* View toggle buttons */
    .view-toggle .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        padding: 0;
        color: var(--text-secondary);
        background-color: var(--surface);
        border-color: var(--border);
    }
    
    .view-toggle .btn.active {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('gridSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const gridItems = document.querySelectorAll('.data-grid-item');
            
            gridItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // View toggle functionality
    const viewToggleBtns = document.querySelectorAll('.view-toggle button');
    if (viewToggleBtns.length) {
        viewToggleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;
                const gridContainer = document.querySelector('.data-grid-container');
                
                // Update active button
                viewToggleBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update view
                if (view === 'list') {
                    gridContainer.classList.add('list-view');
                } else {
                    gridContainer.classList.remove('list-view');
                }
            });
        });
    }
});
</script> 