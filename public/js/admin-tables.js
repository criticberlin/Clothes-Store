/**
 * Admin DataTables initialization with theme support
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all admin tables with DataTables
    const tables = document.querySelectorAll('.admin-datatable');
    
    tables.forEach(table => {
        const dataTable = $(table).DataTable({
            responsive: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: '<i class="bi bi-chevron-double-left"></i>',
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                    last: '<i class="bi bi-chevron-double-right"></i>'
                }
            }
        });

        // Add theme-aware classes to DataTables elements
        const tableContainer = $(table).closest('.dataTables_wrapper');
        
        // Style the search input
        const searchInput = tableContainer.find('.dataTables_filter input');
        searchInput.addClass('search-input');
        tableContainer.find('.dataTables_filter').addClass('has-search');
        tableContainer.find('.dataTables_filter label').prepend(
            '<span class="bi bi-search search-icon"></span>'
        );
        
        // Style the length select
        tableContainer.find('.dataTables_length select').addClass('form-select');
        
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