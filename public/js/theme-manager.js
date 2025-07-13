/**
 * Unified Theme and Language Manager
 * 
 * Handles theme switching, language switching, and ensures consistency
 * between client-side (localStorage) and server-side (cookies/session) storage.
 */

class UnifiedPreferencesManager {
    constructor() {
        // Constants
        this.THEME_KEY = 'theme_preference';
        this.LANG_KEY = 'language_preference';
        this.htmlElement = document.documentElement;
        
        // Initialize preferences
        this.init();
        
        // Setup event listeners
        this.setupEventListeners();
        
        // Log initial state
        console.log('Theme Manager initialized:', {
            theme: localStorage.getItem(this.THEME_KEY),
            htmlClass: this.htmlElement.className,
            documentTheme: document.documentElement.classList.contains('theme-light') ? 'light' : 'dark'
        });
    }

    init() {
        // First check for theme class already applied to HTML element (from server-side)
        let currentTheme = this.htmlElement.classList.contains('theme-light') ? 'light' : 'dark';
        
        // Then check localStorage (client-side preference)
        const savedTheme = localStorage.getItem(this.THEME_KEY);
        
        // If localStorage has a theme and it's different from the current theme, use the localStorage theme
        if (savedTheme && savedTheme !== currentTheme) {
            currentTheme = savedTheme;
        }
        
        // Get language from localStorage or fallback to HTML lang attribute
        const savedLang = localStorage.getItem(this.LANG_KEY) || 
                         this.htmlElement.getAttribute('lang') || 'en';
        
        // Apply theme and language without syncing with server (to avoid loops)
        this.applyTheme(currentTheme, true);
        this.applyLanguage(savedLang, false);
    }

    setupEventListeners() {
        // Theme toggle buttons
        document.querySelectorAll('.theme-toggle-btn, #themeToggle').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleTheme();
            });
        });
        
        // Theme links
        document.querySelectorAll('a[href*="theme/toggle"], a[href*="theme/light"], a[href*="theme/dark"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const href = link.getAttribute('href');
                if (href.includes('toggle')) {
                    this.toggleTheme();
                } else {
                    const theme = href.includes('light') ? 'light' : 'dark';
                    this.applyTheme(theme);
                }
            });
        });
        
        // Theme radio buttons
        document.querySelectorAll('input[name="theme_mode"]').forEach(radio => {
            radio.addEventListener('change', () => this.applyTheme(radio.value));
        });
        
        // Language dropdown items
        document.querySelectorAll('.language-switcher .dropdown-item, a[href*="set-language/"]').forEach(item => {
            item.addEventListener('click', (e) => {
                // Don't prevent default for form submissions
                if (!item.closest('form')) {
                    e.preventDefault();
                    const href = item.getAttribute('href');
                    let lang;
                    
                    // Extract language from URL
                    if (href?.includes('set-language/')) {
                        lang = href.split('/').pop().split('?')[0];
                    } else {
                        lang = item.closest('form')?.querySelector('input[name="language"]')?.value || 'en';
                    }
                    
                    this.applyLanguage(lang);
                }
            });
        });
    }

    toggleTheme() {
        const currentTheme = this.htmlElement.classList.contains('theme-light') ? 'light' : 'dark';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
    }

    applyTheme(theme, skipServerSync = false) {
        console.log('Applying theme:', theme);
        
        // Save theme preference in localStorage
        localStorage.setItem(this.THEME_KEY, theme);
        
        // Apply theme class to HTML element
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
        document.querySelectorAll('input[name="theme_mode"]').forEach(input => {
            input.checked = input.value === theme;
        });
        
        // Sync with server if needed
        if (!skipServerSync) {
            this.syncThemeWithServer(theme);
        }
    }
    
    applyLanguage(language, syncWithServer = true) {
        // Save language preference in localStorage
        localStorage.setItem(this.LANG_KEY, language);
        
        // Update any language inputs
        document.querySelectorAll('input[name="language"]').forEach(input => {
            if (input.type === 'radio') {
                input.checked = input.value === language;
            }
        });
        
        // Highlight active language in dropdowns
        document.querySelectorAll('.language-switcher .dropdown-item').forEach(item => {
            const itemLang = item.getAttribute('href')?.split('/').pop() || 
                           item.closest('form')?.querySelector('input[name="language"]')?.value;
            
            if (itemLang === language) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
        
        // Sync with server if needed
        if (syncWithServer) {
            this.syncLanguageWithServer(language);
        }
    }
    
    updateThemeIcons(theme) {
        // Update all theme toggle buttons
        document.querySelectorAll('.theme-toggle-btn, #themeToggle').forEach(btn => {
            const btnIcon = btn.querySelector('i');
            if (btnIcon) {
                if (theme === 'dark') {
                    btnIcon.className = '';
                    btnIcon.classList.add('bi', 'bi-sun-fill');
                } else {
                    btnIcon.className = '';
                    btnIcon.classList.add('bi', 'bi-moon-stars-fill');
                }
            }
        });
    }
    
    syncThemeWithServer(theme) {
        console.log('Syncing theme with server:', theme);
        
        // Create form data
        const formData = new FormData();
        formData.append('theme', theme);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        
        // Use POST method instead of GET for better reliability
        fetch('/preferences/theme', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Theme synced with server successfully:', data);
        })
        .catch(error => {
            console.error('Error syncing theme with server:', error);
            
            // Fallback to GET method if POST fails
            fetch(`/theme/${theme}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Theme synced with server using fallback method:', data);
            })
            .catch(fallbackError => {
                console.error('Fallback theme sync also failed:', fallbackError);
            });
        });
    }
    
    syncLanguageWithServer(language) {
        console.log('Syncing language with server:', language);
        
        // Get the current URL
        const currentUrl = window.location.href;
        
        // Build the direct URL to the language switcher script
        const scriptPath = 'switch_language.php';
        
        // Navigate to the language switcher script
        window.location.href = scriptPath + '?lang=' + language + '&redirect=' + encodeURIComponent(currentUrl);
    }
    
    /**
     * Get the base path for the application
     * This handles cases where the app is in a subdirectory
     */
    getBasePath() {
        // Get the base URL from the <base> tag if available
        const baseTag = document.querySelector('base');
        if (baseTag && baseTag.href) {
            return baseTag.href.replace(/\/$/, '');
        }
        
        // Try to determine from script tags
        const scripts = document.querySelectorAll('script[src*="/js/"]');
        for (const script of scripts) {
            const src = script.getAttribute('src');
            if (src && src.includes('/js/')) {
                return src.split('/js/')[0];
            }
        }
        
        // Check if we're in a known subdirectory
        const pathname = window.location.pathname;
        if (pathname.startsWith('/Clothes_Store')) {
            return window.location.origin + '/Clothes_Store';
        }
        
        // Default to origin
        return window.location.origin;
    }
}

// Initialize the manager when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.preferencesManager = new UnifiedPreferencesManager();
}); 