/**
 * Initialize wishlist functionality
 */
function initWishlistFunctionality() {
    // Get the base URL from meta tag
    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    
    // Wishlist toggle buttons
    const wishlistBtns = document.querySelectorAll('.wishlist-toggle');
    wishlistBtns.forEach(btn => {
        // Check if product is in wishlist
        const productId = btn.getAttribute('data-product-id');
        
        // Make AJAX request to check if in wishlist
        checkWishlistStatus(btn, productId, baseUrl);
        
        // Add click event listener
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleWishlistItem(btn, productId, baseUrl);
        });
    });
}

/**
 * Check if a product is in the wishlist
 * 
 * @param {Element} btn - The wishlist button element
 * @param {string|number} productId - The product ID
 * @param {string} baseUrl - The base URL
 */
function checkWishlistStatus(btn, productId, baseUrl) {
    fetch(`${baseUrl}/wishlist/check/${productId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin' // Include cookies
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.in_wishlist) {
            btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
            btn.classList.add('active');
        } else {
            btn.innerHTML = '<i class="bi bi-heart"></i>';
            btn.classList.remove('active');
        }
    })
    .catch(error => {
        console.error('Error checking wishlist status:', error);
        // Set default state on error
        btn.innerHTML = '<i class="bi bi-heart"></i>';
        btn.classList.remove('active');
    });
}

/**
 * Toggle a product in the wishlist
 * 
 * @param {Element} btn - The wishlist button element
 * @param {string|number} productId - The product ID
 * @param {string} baseUrl - The base URL
 */
function toggleWishlistItem(btn, productId, baseUrl) {
    fetch(`${baseUrl}/wishlist/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        credentials: 'same-origin' // Include cookies
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.in_wishlist) {
                btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
                btn.classList.add('active');
            } else {
                btn.innerHTML = '<i class="bi bi-heart"></i>';
                btn.classList.remove('active');
            }
            
            // Update wishlist count if element exists
            const wishlistCountElem = document.querySelector('.wishlist-count');
            if (wishlistCountElem && data.wishlist_count !== undefined) {
                wishlistCountElem.textContent = data.wishlist_count;
            }
        }
    })
    .catch(error => {
        console.error('Error toggling wishlist item:', error);
    });
}

// Initialize on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    initWishlistFunctionality();
});

// Export functions for use in other scripts
window.initWishlistFunctionality = initWishlistFunctionality;
window.checkWishlistStatus = checkWishlistStatus;
window.toggleWishlistItem = toggleWishlistItem; 