/**
 * Admin Dashboard JavaScript
 * 
 * Handles sidebar toggle, search, and other admin-specific functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const adminSidebar = document.getElementById('adminSidebar');
    const adminContent = document.querySelector('.admin-content');
    
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('show');
            
            // Add overlay when sidebar is shown on mobile
            if (window.innerWidth < 992) {
                if (adminSidebar.classList.contains('show')) {
                    const overlay = document.createElement('div');
                    overlay.className = 'sidebar-overlay';
                    overlay.style.position = 'fixed';
                    overlay.style.top = '0';
                    overlay.style.left = '0';
                    overlay.style.width = '100%';
                    overlay.style.height = '100%';
                    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                    overlay.style.zIndex = '1020';
                    overlay.style.opacity = '0';
                    overlay.style.transition = 'opacity 0.3s ease';
                    
                    document.body.appendChild(overlay);
                    
                    // Fade in overlay
                    setTimeout(() => {
                        overlay.style.opacity = '1';
                    }, 10);
                    
                    // Close sidebar when overlay is clicked
                    overlay.addEventListener('click', function() {
                        adminSidebar.classList.remove('show');
                        overlay.style.opacity = '0';
                        
                        setTimeout(() => {
                            document.body.removeChild(overlay);
                        }, 300);
                    });
                } else {
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) {
                        overlay.style.opacity = '0';
                        
                        setTimeout(() => {
                            document.body.removeChild(overlay);
                        }, 300);
                    }
                }
            }
        });
    }
    
    // Close sidebar on document click (for mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 992 && adminSidebar && adminSidebar.classList.contains('show')) {
            // Check if the click is outside the sidebar and not on the toggle button
            if (!adminSidebar.contains(e.target) && 
                (!sidebarToggle || !sidebarToggle.contains(e.target))) {
                adminSidebar.classList.remove('show');
                
                const overlay = document.querySelector('.sidebar-overlay');
                if (overlay) {
                    overlay.style.opacity = '0';
                    
                    setTimeout(() => {
                        document.body.removeChild(overlay);
                    }, 300);
                }
            }
        }
    });
    
    // Handle data tables sorting indicators
    const sortableHeaders = document.querySelectorAll('th[data-sort]');
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const direction = this.getAttribute('data-direction') === 'asc' ? 'desc' : 'asc';
            
            // Reset all headers
            sortableHeaders.forEach(h => {
                h.removeAttribute('data-direction');
                const icon = h.querySelector('.sort-icon');
                if (icon) {
                    icon.className = 'sort-icon bi bi-arrow-down-up ms-1';
                }
            });
            
            // Set new direction and update icon
            this.setAttribute('data-direction', direction);
            const icon = this.querySelector('.sort-icon');
            if (icon) {
                icon.className = `sort-icon bi bi-arrow-${direction === 'asc' ? 'up' : 'down'} ms-1`;
            }
            
            // Trigger sort event (can be captured by data table libraries or custom handlers)
            const sortEvent = new CustomEvent('table:sort', {
                detail: {
                    column: this.getAttribute('data-sort'),
                    direction: direction
                }
            });
            document.dispatchEvent(sortEvent);
        });
    });
    
    // Tooltips initialization
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
    
    // Popover initialization
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(popover => {
        new bootstrap.Popover(popover);
    });
    
    // Handle theme synchronization with system preference
    if (window.matchMedia) {
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        
        const syncWithSystemTheme = function(e) {
            // Only sync if the user hasn't explicitly set a theme preference
            if (!localStorage.getItem('theme_preference')) {
                const newTheme = e.matches ? 'dark' : 'light';
                
                // Use the theme manager if available
                if (window.themeManager) {
                    window.themeManager.applyTheme(newTheme);
                } else {
                    // Fallback to basic theme switching
                    document.documentElement.classList.remove('theme-light', 'theme-dark');
                    document.documentElement.classList.add(`theme-${newTheme}`);
                }
            }
        };
        
        // Listen for changes to color scheme preference
        prefersDarkScheme.addEventListener('change', syncWithSystemTheme);
    }
}); 