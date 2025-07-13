/**
 * Custom JavaScript for the Clothes Store
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.navbar-toggler');
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            document.body.classList.toggle('menu-open');
        });
    }
    
    // Initialize dynamic select
    initDynamicSelect();
    
    // Live Search Functionality
    initializeSearch();
    
    // Add to cart buttons with AJAX
    initializeCartButtons();
    
    // Initialize theme toggle 
    initializeThemeToggle();
    
    // Initialize cart functionality
    initCartFunctionality();
});

/**
 * Initialize dynamic select elements
 */
function initDynamicSelect() {
    const dynamicSelects = document.querySelectorAll('.dynamic-select');
    
    if (dynamicSelects.length === 0) {
        return;
    }
    
    dynamicSelects.forEach(select => {
        select.addEventListener('change', function() {
            const value = this.value;
            const name = this.name;
            
            // Create and dispatch a custom event
            const event = new CustomEvent('categoryChanged', {
                detail: {
                    name: name,
                    value: value
                },
                bubbles: true
            });
            
            this.dispatchEvent(event);
        });
    });
    
    console.log('Dynamic select initialized');
}

/**
 * Initialize the live search functionality
 */
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) {
        console.log('Search elements not found');
        return;
    }
    
    console.log('Search initialized');
    
    let typingTimer;
    const doneTypingInterval = 300; // wait 300ms after user stops typing
    
    // Function to perform search
    function performSearch() {
        const searchTerm = searchInput.value.trim();
        
        if (searchTerm.length < 2) {
            searchResults.classList.remove('show');
            return;
        }
        
        console.log('Performing search for:', searchTerm);
        
        // Show loading indicator
        searchResults.innerHTML = `
            <div class="search-result-item text-center">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2">Searching...</span>
            </div>
        `;
        searchResults.classList.add('show');
        
        // Get category value
        const categoryInput = document.getElementById('categoryInput');
        const categoryId = categoryInput ? categoryInput.value : '';
        
        // Build API URL with parameters
        let apiUrl = `/api/search?q=${encodeURIComponent(searchTerm)}`;
        if (categoryId) {
            apiUrl += `&category_id=${categoryId}`;
        }
        
        // Add CSRF token to headers
        const headers = new Headers({
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        });
        
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            headers.append('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
        }
        
        console.log('Fetching:', apiUrl);
        
        // Send AJAX request
        fetch(apiUrl, {
            method: 'GET',
            headers: headers,
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Search results:', data);
            
            if (!data || !data.products) {
                throw new Error('Invalid response data');
            }
            
            let html = '';
            
            if (data.products.length > 0) {
                // Group products by category
                const productsByCategory = {};
                
                data.products.forEach(product => {
                    const category = product.category_name || 'Uncategorized';
                    if (!productsByCategory[category]) {
                        productsByCategory[category] = [];
                    }
                    productsByCategory[category].push(product);
                });
                
                // Show only first 5 results
                let count = 0;
                const maxResults = 5;
                
                // Display products grouped by category
                for (const category in productsByCategory) {
                    if (count < maxResults) {
                        // Add category header
                        html += `
                            <div class="search-category-header">
                                <small class="text-tertiary">${category}</small>
                            </div>
                        `;
                        
                        // Add products in this category
                        productsByCategory[category].forEach(product => {
                            if (count < maxResults) {
                                html += `
                                    <a href="/product/${product.slug || product.id}" class="text-decoration-none">
                                        <div class="search-result-item d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="${product.image}" alt="${product.name}" class="me-3" width="40" height="40" style="object-fit: cover; border-radius: var(--radius-sm);">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="product-title">${product.name}</div>
                                                <div class="product-price">${product.formatted_price}</div>
                                                <div class="product-availability ${product.quantity > 0 ? 'text-success' : 'text-danger'}">
                                                    ${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                `;
                                count++;
                            }
                        });
                    }
                }
                
                // Add "View all results" link
                if (data.products.length > maxResults) {
                    const searchParams = new URLSearchParams();
                    searchParams.append('q', searchTerm);
                    if (categoryId) {
                        searchParams.append('category_id', categoryId);
                    }
                    
                    html += `
                        <a href="/products/search?${searchParams.toString()}" class="text-decoration-none">
                            <div class="search-result-item text-center">
                                <strong class="text-primary">View all results (${data.products.length})</strong>
                            </div>
                        </a>
                    `;
                }
            } else {
                html = `
                    <div class="search-result-item text-center">
                        No results found
                    </div>
                `;
            }
            
            searchResults.innerHTML = html;
            searchResults.classList.add('show');
        })
        .catch(error => {
            console.error('Error searching products:', error);
            searchResults.innerHTML = `
                <div class="search-result-item text-center text-danger">
                    Search error. Please try again.
                </div>
            `;
        });
    }
    
    // Search on typing
    searchInput.addEventListener('input', function() {
        console.log('Search input changed');
        clearTimeout(typingTimer);
        typingTimer = setTimeout(performSearch, doneTypingInterval);
    });
    
    // Search on category change
    document.addEventListener('categoryChanged', function(e) {
        console.log('Category changed event received:', e.detail.value);
        if (searchInput.value.trim().length >= 2) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        }
    });
    
    // Hide search results when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.classList.remove('show');
        }
    });
}

/**
 * Initialize the cart buttons with AJAX functionality
 */
function initializeCartButtons() {
    // Add to cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.getAttribute('data-method') === 'post') {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('Product added to cart!', 'success');
                        
                        // Update cart count if element exists
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement && data.cartCount) {
                            cartCountElement.textContent = data.cartCount;
                        }
            } else {
                        showNotification(data.message || 'Error adding to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error adding to cart', 'error');
                });
            }
        });
    });
}

/**
 * Initialize theme toggle functionality
 */
function initializeThemeToggle() {
    const themeToggle = document.querySelector('.theme-toggle-btn');
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.getAttribute('href');
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Toggle theme class on html element
                        document.documentElement.className = 
                            document.documentElement.className.replace(/theme-\w+/, `theme-${data.theme}`);
                        
                        // Update icon
                        const icon = this.querySelector('i');
                        if (icon) {
                            if (data.theme === 'dark') {
                                icon.className = icon.className.replace('bi-moon-stars-fill', 'bi-sun-fill');
                            } else {
                                icon.className = icon.className.replace('bi-sun-fill', 'bi-moon-stars-fill');
                            }
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }
}

/**
 * Initialize cart functionality with AJAX support
 */
function initCartFunctionality() {
    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const isFormButton = this.type === 'submit';
            
            // If it's a direct link (not a form submit button), prevent default
            if (!isFormButton) {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                // Send AJAX request
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        updateCartCount(data.cart_count);
                        
                        // Show success message
                        showNotification('success', data.message);
                    } else {
                        showNotification('error', data.message || 'Error adding item to cart');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'An error occurred. Please try again.');
                });
            }
        });
    });
    
    // Update cart quantity buttons
    const quantityForms = document.querySelectorAll('.cart-quantity-form');
    quantityForms.forEach(form => {
        const quantityInput = form.querySelector('input[name="quantity"]');
        const updateButton = form.querySelector('.update-cart-btn');
        
        if (quantityInput && updateButton) {
            updateButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const url = form.getAttribute('action');
                
                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        updateCartCount(data.cart_count);
                        
                        // Update item total if available
                        const itemTotalElement = form.closest('tr').querySelector('.item-total');
                        if (itemTotalElement && data.item_total) {
                            itemTotalElement.textContent = data.item_total;
                        }
                        
                        // Recalculate cart totals if needed
                        recalculateCartTotals();
                        
                        showNotification('success', data.message);
                    } else {
                        showNotification('error', data.message || 'Error updating cart');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'An error occurred. Please try again.');
                });
            });
        }
    });
    
    // Remove from cart buttons
    const removeButtons = document.querySelectorAll('.remove-from-cart-btn');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.getAttribute('href') || this.getAttribute('data-url');
            if (!url) return;
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    updateCartCount(data.cart_count);
                    
                    // Remove the row from the cart table
                    const row = this.closest('tr');
                    if (row) {
                        row.remove();
                        
                        // Recalculate cart totals
                        recalculateCartTotals();
                        
                        // If cart is empty, refresh the page to show empty cart message
                        if (data.cart_count === 0) {
                            window.location.reload();
                        }
                    }
                    
                    showNotification('success', data.message);
                } else {
                    showNotification('error', data.message || 'Error removing item from cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            });
        });
    });
    
    // Clear cart button
    const clearCartButton = document.querySelector('.clear-cart-btn');
    if (clearCartButton) {
        clearCartButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.getAttribute('href') || this.getAttribute('data-url');
            if (!url) return;
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    updateCartCount(0);
                    
                    // Refresh the page to show empty cart
                    window.location.reload();
                    
                    showNotification('success', data.message);
                } else {
                    showNotification('error', data.message || 'Error clearing cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            });
        });
    }
}

/**
 * Update the cart count badge in the header
 * @param {number} count - The new cart count
 */
function updateCartCount(count) {
    const cartBadge = document.querySelector('.cart-badge');
    
    if (cartBadge) {
        // Update existing badge
        cartBadge.textContent = count;
        
        // Show/hide badge based on count
        if (count > 0) {
            cartBadge.classList.remove('d-none');
            // Add a small animation
            cartBadge.classList.add('animate-pulse');
            setTimeout(() => {
                cartBadge.classList.remove('animate-pulse');
            }, 500);
        } else {
            cartBadge.classList.add('d-none');
        }
    } else if (count > 0) {
        // Create new badge if it doesn't exist
        const cartIcon = document.querySelector('.header-icon-btn i.bi-cart');
        if (cartIcon) {
            const badge = document.createElement('span');
            badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge animate-pulse';
            badge.textContent = count;
            cartIcon.parentNode.appendChild(badge);
            
            // Remove animation after a short delay
            setTimeout(() => {
                badge.classList.remove('animate-pulse');
            }, 500);
        }
    }
}

/**
 * Recalculate cart totals
 */
function recalculateCartTotals() {
    const cartTable = document.querySelector('.cart-table');
    if (!cartTable) return;
    
    // Get all item totals
    const itemTotals = Array.from(cartTable.querySelectorAll('.item-total')).map(el => {
        // Parse the price (remove currency symbol and convert to number)
        const price = parseFloat(el.textContent.replace(/[^0-9.-]+/g, ''));
        return isNaN(price) ? 0 : price;
    });
    
    // Calculate subtotal
    const subtotal = itemTotals.reduce((sum, price) => sum + price, 0);
    
    // Update subtotal display
    const subtotalElement = document.querySelector('.cart-subtotal');
    if (subtotalElement) {
        // Format with currency symbol
        const currencySymbol = document.documentElement.dataset.currency || '$';
        subtotalElement.textContent = `${currencySymbol}${subtotal.toFixed(2)}`;
    }
    
    // Update other totals if needed (shipping, tax, etc.)
    // ...
    
    // Update grand total
    const grandTotalElement = document.querySelector('.cart-total');
    if (grandTotalElement) {
        // Get shipping cost if available
        const shippingElement = document.querySelector('.cart-shipping');
        const shipping = shippingElement ? parseFloat(shippingElement.textContent.replace(/[^0-9.-]+/g, '')) : 0;
        
        // Get tax if available
        const taxElement = document.querySelector('.cart-tax');
        const tax = taxElement ? parseFloat(taxElement.textContent.replace(/[^0-9.-]+/g, '')) : 0;
        
        // Calculate grand total
        const grandTotal = subtotal + shipping + tax;
        
        // Format with currency symbol
        const currencySymbol = document.documentElement.dataset.currency || '$';
        grandTotalElement.textContent = `${currencySymbol}${grandTotal.toFixed(2)}`;
    }
}

/**
 * Show notification message
 * @param {string} type - The type of notification (success, error, warning, info)
 * @param {string} message - The message to display
 */
function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Hide and remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}