/**
 * Admin DataTables initialization with theme support
 */

document.addEventListener('DOMContentLoaded', function() {
    // Destroy any existing DataTables instances first
    $('.admin-datatable').each(function() {
        if ($.fn.DataTable.isDataTable(this)) {
            $(this).DataTable().destroy();
        }
    });
    
    // Initialize DataTables
    const tables = document.querySelectorAll('.admin-datatable');
    
    tables.forEach(function(table) {
        // Check if table has Laravel pagination
        const tableContainer = table.closest('.admin-card-body');
        const hasLaravelPagination = tableContainer && tableContainer.querySelector('.pagination') !== null;
        
        // Don't use DataTables pagination if Laravel pagination exists or no-pagination class is present
        const hasPagination = !table.classList.contains('no-pagination') && !hasLaravelPagination;
        
        // Remove any existing DataTables wrapper pagination if Laravel pagination exists
        if (hasLaravelPagination) {
            const dataTablesWrapper = table.closest('.dataTables_wrapper');
            if (dataTablesWrapper) {
                const dtPagination = dataTablesWrapper.querySelector('.dataTables_paginate');
                if (dtPagination) {
                    dtPagination.style.display = 'none';
                }
            }
        }
        
        const options = {
            ordering: true,
            searching: true,
            paging: hasPagination,
            lengthChange: hasPagination,
            info: hasPagination,
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search...",
                emptyTable: "No data available",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                lengthMenu: "Show _MENU_ entries"
            },
            // Prevent DataTables from adding its own ID
            bDeferRender: true,
            destroy: true
        };
        
        const dataTable = new DataTable(table, options);
        
        // Hide DataTables pagination if Laravel pagination exists (after initialization)
        if (hasLaravelPagination) {
            const dataTablesWrapper = table.closest('.dataTables_wrapper');
            if (dataTablesWrapper) {
                const dtPagination = dataTablesWrapper.querySelector('.dataTables_paginate');
                if (dtPagination) {
                    dtPagination.style.display = 'none';
                }
                const dtInfo = dataTablesWrapper.querySelector('.dataTables_info');
                if (dtInfo) {
                    dtInfo.style.display = 'none';
                }
                const dtLength = dataTablesWrapper.querySelector('.dataTables_length');
                if (dtLength) {
                    dtLength.style.display = 'none';
                }
            }
        }
        
        // Add theme-aware classes to DataTables elements
        const jqTableContainer = $(table).closest('.dataTables_wrapper');
        
        // Style the search input
        const searchInput = jqTableContainer.find('.dataTables_filter input');
        searchInput.addClass('search-input');
        jqTableContainer.find('.dataTables_filter').addClass('has-search');
        jqTableContainer.find('.dataTables_filter label').prepend(
            '<span class="bi bi-search search-icon"></span>'
        );
        
        // Style the length select
        jqTableContainer.find('.dataTables_length select').addClass('form-select');
        
        // Theme toggle detection for refresh
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                // Short delay to allow theme to change first
                setTimeout(() => {
                    // Force DataTable to recalculate layout for the new theme
                    dataTable.columns.adjust().responsive.recalc();
                }, 300);
            });
        }
    });
    
    // Confirm delete for all delete forms
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const confirmMessage = form.dataset.confirmMessage || 'Are you sure you want to delete this item?';
            if (confirm(confirmMessage)) {
                this.submit();
            }
        });
    });
    
    // Make sure tables adapt to container size changes
    window.addEventListener('resize', function() {
        const tables = document.querySelectorAll('.admin-datatable');
        tables.forEach(table => {
            if ($.fn.dataTable.isDataTable(table)) {
                $(table).DataTable().columns.adjust();
            }
        });
    });
}); 