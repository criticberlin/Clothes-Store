// Add this to the end of the app.js file or where appropriate

// Product Card Wishlist Toggle

/**
 * Update the cart counter in the header with animation
 * @param {number} count - The new cart count
 * @param {boolean} animate - Whether to show animation
 */
function updateCartCounter(count, animate = false) {
    const cartCounter = document.querySelector('.cart-counter');
    
    if (cartCounter) {
        // Update existing counter
        cartCounter.textContent = count;
        
        // Show/hide counter based on count
        if (count > 0) {
            cartCounter.style.display = 'flex';
            
            // Add animation if requested
            if (animate) {
                cartCounter.classList.add('counter-animate');
                setTimeout(() => {
                    cartCounter.classList.remove('counter-animate');
                }, 500);
            }
        } else {
            cartCounter.style.display = 'none';
        }
    }
}

/**
 * Update the wishlist counter in the dropdown menu
 * @param {number} count - The new wishlist count
 * @param {boolean} animate - Whether to show animation
 */
function updateWishlistCounter(count, animate = false) {
    const wishlistCounter = document.querySelector('.wishlist-counter');
    
    if (wishlistCounter) {
        // Update existing counter
        wishlistCounter.textContent = count;
        
        // Show/hide counter based on count
        if (count > 0) {
            wishlistCounter.style.display = 'flex';
            
            // Add animation if requested
            if (animate) {
                wishlistCounter.classList.add('counter-animate');
                setTimeout(() => {
                    wishlistCounter.classList.remove('counter-animate');
                }, 500);
            }
        } else {
            wishlistCounter.style.display = 'none';
        }
    }
}

// Initialize counters on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart counter
    const cartCounter = document.querySelector('.cart-counter');
    if (cartCounter && cartCounter.textContent.trim() === '0') {
        cartCounter.style.display = 'none';
    }
    
    // Initialize wishlist counter
    const wishlistCounter = document.querySelector('.wishlist-counter');
    if (wishlistCounter && wishlistCounter.textContent.trim() === '0') {
        wishlistCounter.style.display = 'none';
    }
    
    // Prevent wishlist button from triggering card link
    document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            
            // Toggle wishlist state visually
            this.classList.toggle('active');
            
            // Add wishlist functionality here
            fetch('/api/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI based on the current status (added/removed)
                    if (data.status === 'added') {
                        this.classList.add('active');
                        // Update wishlist counter
                        updateWishlistCounter(data.wishlist_count || 1, true);
                    } else {
                        this.classList.remove('active');
                        // Update wishlist counter
                        updateWishlistCounter(data.wishlist_count || 0, true);
                    }
                    
                    // Show notification if you have a notification system
                    if (typeof showNotification === 'function') {
                        showNotification(data.message, data.status === 'added' ? 'success' : 'info');
                    }
                } else {
                    // Handle error
                    if (typeof showNotification === 'function') {
                        showNotification(data.message || 'Error updating wishlist', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error notification
                if (typeof showNotification === 'function') {
                    showNotification('An error occurred while updating your wishlist', 'error');
                }
            });
        });
    });
}); 