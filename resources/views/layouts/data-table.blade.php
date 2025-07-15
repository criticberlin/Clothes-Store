{{-- 
    Standardized Data Table Component
    Usage:
    @include('layouts.data-table', [
        'title' => 'Table Title',
        'createRoute' => route('some.create.route'), // Optional
        'createText' => 'Add New Item', // Optional
        'columns' => ['Column 1', 'Column 2', ...],
        'data' => $dataCollection,
        'emptyMessage' => 'No items found', // Optional
        'searchPlaceholder' => 'Search items...', // Optional
    ])
--}}

<div class="admin-card mb-4">
    <div class="admin-card-header">
        <div>
            <h5 class="mb-0">{{ $title ?? 'Data Table' }}</h5>
            @if(isset($subtitle))
                <p class="text-secondary mb-0 mt-1 small">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="d-flex gap-2">
            @if(isset($searchPlaceholder))
            <div class="search-wrapper position-relative">
                <input type="search" class="form-control search-input" placeholder="{{ $searchPlaceholder }}" 
                       aria-label="Search" id="tableSearch">
                <span class="search-icon">
                    <i class="bi bi-search"></i>
                </span>
            </div>
            @endif
            
            @if(isset($createRoute) && isset($createText))
            <a href="{{ $createRoute }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> {{ $createText }}
            </a>
            @endif
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover data-table">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th scope="col">{{ $column }}</th>
                        @endforeach
                        @if(isset($actions) && $actions)
                            <th scope="col" class="text-end">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        @yield('table_rows')
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + (isset($actions) && $actions ? 1 : 0) }}" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox fs-1 text-secondary mb-3"></i>
                                    <p class="mb-0">{{ $emptyMessage ?? 'No items found' }}</p>
                                    @if(isset($emptyActionRoute) && isset($emptyActionText))
                                        <a href="{{ $emptyActionRoute }}" class="btn btn-sm btn-primary mt-3">
                                            {{ $emptyActionText }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
    
    /* Theme-aware table styles */
    .data-table {
        color: var(--text-primary);
    }
    
    .data-table thead th {
        background-color: var(--surface-alt);
        color: var(--text-secondary);
        border-color: var(--border);
    }
    
    .data-table tbody td {
        border-color: var(--border);
        background-color: var(--surface);
    }
    
    .data-table tbody tr:hover td {
        background-color: var(--surface-alt);
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.admin-card').querySelector('.data-table');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script> 