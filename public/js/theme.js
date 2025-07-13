/**
 * Theme Utility Functions
 * 
 * Handles theme switching, persistence, and UI updates
 */

class ThemeManager {
    constructor() {
        this.THEME_KEY = 'theme_preference';
        this.htmlElement = document.documentElement;
        this.defaultTheme = 'dark';
        
        // Initialize theme from localStorage or fallback to session default
        this.init();
        
        // Setup event listeners for theme toggle buttons
        this.setupEventListeners();
    }

    init() {
        // Get theme preference from localStorage first, then session, then default to dark
        const savedTheme = localStorage.getItem(this.THEME_KEY) || 
                          this.htmlElement.className.includes('theme-light') ? 'light' : 
                          this.htmlElement.className.includes('theme-dark') ? 'dark' : 
                          this.defaultTheme;
        
        this.applyTheme(savedTheme);
    }

    setupEventListeners() {
        // Listen for theme form submissions
        document.querySelectorAll('form[action*="preferences/theme"]').forEach(form => {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e, form));
        });
        
        // Listen for theme toggle buttons or links
        const themeToggleButtons = document.querySelectorAll('.theme-toggle-btn');
        themeToggleButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // If inside a form, the form handler will take care of it
                if (!button.closest('form')) {
                    e.preventDefault();
                    this.toggleTheme();
                }
            });
        });
        
        // Listen for direct theme links
        const themeLinks = document.querySelectorAll('a[href*="theme/toggle"], a[href*="theme/light"], a[href*="theme/dark"]');
        themeLinks.forEach(link => {
            link.addEventListener('click', (e) => this.handleLinkClick(e, link));
        });
        
        // Listen for theme radio buttons
        const themeRadios = document.querySelectorAll('input[name="theme_mode"]');
        themeRadios.forEach(radio => {
            radio.addEventListener('change', () => this.applyTheme(radio.value));
        });
    }

    handleFormSubmit(e, form) {
        e.preventDefault();
        
        // Get the new theme from the form input
        const themeInput = form.querySelector('input[name="theme"]');
        if (themeInput) {
            const newTheme = themeInput.value;
            this.applyTheme(newTheme);
            
            // Submit the form to update on server
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            }).catch(error => {
                console.error('Failed to update theme preference on server:', error);
            });
        }
    }

    handleLinkClick(e, link) {
        e.preventDefault();
        
        const href = link.getAttribute('href');
        const newTheme = href.includes('light') ? 'light' : 
                       href.includes('dark') ? 'dark' : 
                       this.getCurrentTheme() === 'dark' ? 'light' : 'dark';
        
        this.applyTheme(newTheme);
        
        // Make server request to update session
        fetch(href).catch(error => {
            console.error('Failed to update theme preference:', error);
        });
    }

    getCurrentTheme() {
        if (this.htmlElement.classList.contains('theme-light')) {
            return 'light';
        }
        return 'dark';
    }

    toggleTheme() {
        const currentTheme = this.getCurrentTheme();
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.applyTheme(newTheme);
    }

    applyTheme(theme) {
        // Save theme preference in localStorage
        localStorage.setItem(this.THEME_KEY, theme);
        
        // Apply theme class to html element
        this.htmlElement.classList.remove('theme-light', 'theme-dark');
        this.htmlElement.classList.add(`theme-${theme}`);
        
        // Update all toggle buttons icons
        this.updateThemeIcons(theme);
        
        // Add transition effect
        document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 300);
        
        // Update any theme inputs
        this.updateThemeInputs(theme);
    }

    updateThemeIcons(theme) {
        const themeToggles = document.querySelectorAll('.theme-toggle-btn');
        themeToggles.forEach(toggle => {
            const icon = toggle.querySelector('i');
            if (icon) {
                if (theme === 'dark') {
                    icon.classList.remove('bi-moon-stars-fill');
                    icon.classList.add('bi-sun-fill');
                } else {
                    icon.classList.remove('bi-sun-fill');
                    icon.classList.add('bi-moon-stars-fill');
                }
            }
        });
    }

    updateThemeInputs(theme) {
        // Update radio buttons
        const themeRadios = document.querySelectorAll('input[name="theme_mode"]');
        themeRadios.forEach(radio => {
            radio.checked = radio.value === theme;
        });
        
        // Update hidden inputs
        const themeInputs = document.querySelectorAll('input[name="theme"]');
        themeInputs.forEach(input => {
            if (input.type === 'hidden') {
                const opposite = theme === 'dark' ? 'light' : 'dark';
                input.value = opposite; // For toggle functionality
            }
        });
    }
}

// Initialize theme manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
}); 