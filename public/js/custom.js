/**
 * MyClothes Store - Modern UI 2025
 * Custom JavaScript for enhanced interactions and animations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all interactive elements
    initNavbar();
    initAnimations();
    initThemeToggle();
    initProductInteractions();
    initTooltips();
    initCartFunctionality();
    
    // Get theme from localStorage or use the server-provided theme
    const savedTheme = localStorage.getItem('theme');
    const htmlElement = document.documentElement;
    
    if (savedTheme) {
        htmlElement.classList.remove('theme-light', 'theme-dark');
        htmlElement.classList.add(`theme-${savedTheme}`);
        
        // Also update any theme toggles
        const themeToggles = document.querySelectorAll('.theme-toggle-btn');
        themeToggles.forEach(toggle => {
            const icon = toggle.querySelector('i');
            if (icon) {
                if (savedTheme === 'dark') {
                    icon.classList.remove('bi-moon-stars-fill');
                    icon.classList.add('bi-sun-fill');
                } else {
                    icon.classList.remove('bi-sun-fill');
                    icon.classList.add('bi-moon-stars-fill');
                }
            }
        });
        
        // Sync with server if theme doesn't match
        const serverTheme = htmlElement.className.includes('theme-dark') ? 'dark' : 'light';
        if (savedTheme !== serverTheme) {
            fetch(`/theme/${savedTheme}`)
                .catch(error => console.error('Failed to sync theme with server:', error));
        }
    }
    
    // Listen for theme form submissions
    document.querySelectorAll('form[action*="preferences/theme"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get the new theme from the form input
            const newTheme = this.querySelector('input[name="theme"]').value;
            
            // Save theme preference
            localStorage.setItem('theme', newTheme);
            
            // Apply theme immediately for better UX
            htmlElement.classList.remove('theme-light', 'theme-dark');
            htmlElement.classList.add(`theme-${newTheme}`);
            
            // Update icons
            const themeToggles = document.querySelectorAll('.theme-toggle-btn');
            themeToggles.forEach(toggle => {
                const icon = toggle.querySelector('i');
                if (icon) {
                    if (newTheme === 'dark') {
                        icon.classList.remove('bi-moon-stars-fill');
                        icon.classList.add('bi-sun-fill');
                    } else {
                        icon.classList.remove('bi-sun-fill');
                        icon.classList.add('bi-moon-stars-fill');
                    }
                }
            });
            
            // Submit the form to update on server
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this)
            }).catch(error => {
                console.error('Failed to update theme preference on server:', error);
            });
            
            // Add a nice transition effect
            document.body.style.transition = 'background-color 0.5s ease, color 0.5s ease';
            setTimeout(() => {
                document.body.style.transition = '';
            }, 500);
        });
    });
    
    // Listen for direct theme links
    const themeLinks = document.querySelectorAll('a[href*="theme/toggle"], a[href*="theme/light"], a[href*="theme/dark"]');
    
    themeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const href = this.getAttribute('href');
            const newTheme = href.includes('light') ? 'light' : 
                            href.includes('dark') ? 'dark' : 
                            (htmlElement.classList.contains('theme-dark') ? 'light' : 'dark');
            
            // Save theme preference
            localStorage.setItem('theme', newTheme);
            
            // Apply theme immediately for better UX
            htmlElement.classList.remove('theme-light', 'theme-dark');
            htmlElement.classList.add(`theme-${newTheme}`);
            
            // Update icon
            const icon = this.querySelector('i');
            if (icon) {
                if (newTheme === 'dark') {
                    icon.classList.remove('bi-moon-stars-fill');
                    icon.classList.add('bi-sun-fill');
                } else {
                    icon.classList.remove('bi-sun-fill');
                    icon.classList.add('bi-moon-stars-fill');
                }
            }
            
            // Make server request to update session
            fetch(href)
                .catch(error => {
                    console.error('Failed to update theme preference:', error);
                });
                
            // Add a nice transition effect
            document.body.style.transition = 'background-color 0.5s ease, color 0.5s ease';
            setTimeout(() => {
                document.body.style.transition = '';
            }, 500);
        });
    });
    
    // Format currency amounts
    function formatCurrency(amount, currency = 'EGP', locale = 'en-US') {
        // Use current locale or fallback to en-US
        const userLocale = document.documentElement.lang || locale;
        
        // Format the amount based on currency and locale
        return new Intl.NumberFormat(userLocale, {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 2
        }).format(amount);
    }

    // Apply currency formatting to all price elements
    function applyPriceFormatting() {
        const currentCurrency = document.documentElement.getAttribute('data-currency') || 'EGP';
        const priceElements = document.querySelectorAll('.price-value');
        
        priceElements.forEach(el => {
            const baseAmount = parseFloat(el.getAttribute('data-base-price') || el.textContent);
            if (!isNaN(baseAmount)) {
                el.textContent = formatCurrency(baseAmount, currentCurrency);
            }
        });
    }
    
    // Call initially
    applyPriceFormatting();
    
    // Listen for currency changes
    document.querySelectorAll('form[action*="preferences/currency"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Don't prevent default - let the form submit normally
            // But we can update the UI immediately
            const newCurrency = this.querySelector('input[name="currency_code"]').value;
            document.documentElement.setAttribute('data-currency', newCurrency);
        });
    });
});

/**
 * Navbar scroll effect and mobile menu handling
 */
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    
    // Add scroll effect to navbar
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }

    // Mobile menu improved interaction
    const navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            document.body.classList.toggle('menu-open');
        });
    }

    // Add hover effect to dropdown menus
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.paddingLeft = '1.5rem';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.paddingLeft = '1rem';
        });
    });
}

/**
 * Smooth reveal animations for page elements
 */
function initAnimations() {
    // Add animation classes to elements as they enter the viewport
    const animatedElements = document.querySelectorAll('.card, .btn-primary, .hero-wrapper, .category-card, .feature-item');
    
    if ('IntersectionObserver' in window) {
        const appearOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const appearOnScroll = new IntersectionObserver(function(entries, observer) {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                
                const element = entry.target;
                const delay = element.dataset.delay || 0;
                
                setTimeout(() => {
                    element.classList.add('appeared');
                }, delay);
                
                observer.unobserve(element);
            });
        }, appearOptions);
        
        animatedElements.forEach((element, index) => {
            element.classList.add('will-animate');
            element.dataset.delay = index * 100; // Stagger the animations
            appearOnScroll.observe(element);
        });
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        animatedElements.forEach(el => el.classList.add('appeared'));
    }
}

/**
 * Dark/Light theme toggle functionality
 */
function initThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    
    if (themeToggle) {
        // Check for saved theme preference or use device preference
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        updateThemeIcon(savedTheme === 'light');
        
        themeToggle.addEventListener('click', function() {
            // Toggle theme
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update icon
            updateThemeIcon(newTheme === 'light');
            
            // Animate the transition
            document.documentElement.classList.add('theme-transition');
            setTimeout(() => {
                document.documentElement.classList.remove('theme-transition');
            }, 1000);
            
            // Update server
            fetch(`/theme/${newTheme}`)
                .catch(error => {
                    console.error('Failed to update theme preference:', error);
                });
        });
    }
}

/**
 * Update theme toggle button icon based on current theme
 */
function updateThemeIcon(isLight) {
    const icon = document.querySelector('#themeToggle i');
    if (icon) {
        if (isLight) {
            icon.classList.remove('bi-moon-stars');
            icon.classList.add('bi-sun');
        } else {
            icon.classList.remove('bi-sun');
            icon.classList.add('bi-moon-stars');
        }
    }
}

/**
 * Initialize product-specific interactions
 */
function initProductInteractions() {
    // Product image gallery
    const productThumbnails = document.querySelectorAll('.product-thumbnail');
    const mainProductImage = document.querySelector('.product-main-image');
    
    if (productThumbnails.length && mainProductImage) {
        productThumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Update main image
                const newSrc = this.getAttribute('data-image');
                
                // First remove active class from all thumbnails
                productThumbnails.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked thumbnail
                this.classList.add('active');
                
                // Fade effect for image change
                mainProductImage.style.opacity = '0';
                setTimeout(() => {
                    mainProductImage.setAttribute('src', newSrc);
                    mainProductImage.style.opacity = '1';
                }, 300);
            });
        });
    }
    
    // Product size selection
    const sizeOptions = document.querySelectorAll('.product-size-option');
    if (sizeOptions.length) {
        sizeOptions.forEach(option => {
            option.addEventListener('click', function() {
                sizeOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                // Update hidden input if it exists
                const sizeInput = document.querySelector('input[name="size"]');
                if (sizeInput) {
                    sizeInput.value = this.getAttribute('data-size');
                }
            });
        });
    }
    
    // Product color selection
    const colorOptions = document.querySelectorAll('.product-color-option');
    if (colorOptions.length) {
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                colorOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                // Update hidden input if it exists
                const colorInput = document.querySelector('input[name="color"]');
                if (colorInput) {
                    colorInput.value = this.getAttribute('data-color');
                }
            });
        });
    }
}

/**
 * Initialize Bootstrap tooltips and custom tooltips
 */
function initTooltips() {
    // Bootstrap tooltips (if Bootstrap JS is loaded)
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            });
        });
    }
    
    // Custom tooltips for elements without Bootstrap
    const customTooltips = document.querySelectorAll('[data-tooltip]');
    customTooltips.forEach(element => {
        const tooltipText = element.getAttribute('data-tooltip');
        
        element.addEventListener('mouseenter', function(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'custom-tooltip';
            tooltip.textContent = tooltipText;
            document.body.appendChild(tooltip);
            
            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
            
            setTimeout(() => tooltip.classList.add('show'), 50);
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.custom-tooltip');
            if (tooltip) {
                tooltip.classList.remove('show');
                setTimeout(() => tooltip.remove(), 300);
            }
        });
    });
}

/**
 * Cart functionality for adding/removing items
 */
function initCartFunctionality() {
    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    if (addToCartButtons.length) {
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');
                
                // Show quick confirmation
                showCartNotification(`${productName} added to cart!`);
                
                // Animate cart icon
                const cartIcon = document.querySelector('.cart-icon');
                if (cartIcon) {
                    cartIcon.classList.add('cart-pulse');
                    setTimeout(() => {
                        cartIcon.classList.remove('cart-pulse');
                    }, 1000);
                    
                    // Update cart count
                    const cartBadge = document.querySelector('.cart-badge');
                    if (cartBadge) {
                        let currentCount = parseInt(cartBadge.textContent || '0');
                        cartBadge.textContent = currentCount + 1;
                        
                        // Make sure the badge is visible
                        cartBadge.style.display = 'flex';
                    }
                }
            });
        });
    }
}

/**
 * Show a temporary notification when items are added to cart
 */
function showCartNotification(message) {
    // Create and show notification
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <div class="cart-notification-content">
            <i class="bi bi-bag-check"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Remove after delay
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

// Add these styles to the head to support the JS functionality
(function addDynamicStyles() {
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        .will-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .appeared {
            opacity: 1;
            transform: translateY(0);
        }
        
        .theme-transition * {
            transition: background-color 0.5s ease, color 0.5s ease, border-color 0.5s ease !important;
        }
        
        .cart-pulse {
            animation: pulse 0.7s ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .cart-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }
        
        .cart-notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .cart-notification-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .custom-tooltip {
            position: fixed;
            background-color: var(--surface-alt);
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            pointer-events: none;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 9999;
            box-shadow: var(--shadow-md);
        }
        
        .custom-tooltip::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid var(--surface-alt);
        }
        
        .custom-tooltip.show {
            opacity: 1;
            transform: translateY(0);
        }
    `;
    document.head.appendChild(styleSheet);
})(); 