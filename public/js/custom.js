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
});

/**
 * Navbar scroll effect and mobile menu handling
 */
function initNavbar() {
    const navbar = document.querySelector('.navbar-custom');
    
    // Add scroll effect to navbar
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled', 'shadow-lg');
            } else {
                navbar.classList.remove('scrolled', 'shadow-lg');
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