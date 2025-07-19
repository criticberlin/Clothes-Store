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
        fetch(`${baseUrl}/wishlist/check/${productId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
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
        });
        
        // Add click event listener
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Show loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="spinner-border spinner-border-sm"></i>';
            this.disabled = true;
            
            fetch(`${baseUrl}/wishlist/toggle/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update button state
                    if (data.in_wishlist) {
                        this.innerHTML = '<i class="bi bi-heart-fill"></i>';
                        this.classList.add('active');
                        showToast('Product added to wishlist', 'success');
                    } else {
                        this.innerHTML = '<i class="bi bi-heart"></i>';
                        this.classList.remove('active');
                        showToast('Product removed from wishlist', 'info');
                    }
                    
                    // Update wishlist counter in header
                    updateWishlistCounter(data.wishlist_count);
                } else {
                    this.innerHTML = originalContent;
                    showToast(data.message || 'Failed to update wishlist', 'error');
                }
                this.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = originalContent;
                this.disabled = false;
                showToast('An error occurred while updating your wishlist', 'error');
            });
        });
    });
}

/**
 * Update the wishlist counter in the header
 * @param {number} count - The new count
 */
function updateWishlistCounter(count) {
    const wishlistCounter = document.querySelector('.wishlist-counter');
    if (wishlistCounter) {
        wishlistCounter.textContent = count;
        if (count > 0) {
            wishlistCounter.style.display = '';
        } else {
            wishlistCounter.style.display = 'none';
        }
    }
}

/**
 * Show a toast notification
 * @param {string} message - The message to display
 * @param {string} type - The type of toast (success, error, info, warning)
 */
function showToast(message, type = 'info') {
    // Check if the toast container exists, if not create it
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    // Create a unique ID for the toast
    const toastId = 'toast-' + Date.now();
    
    // Set the icon based on type
    let icon = '';
    switch (type) {
        case 'success':
            icon = '<i class="bi bi-check-circle-fill text-success me-2"></i>';
            break;
        case 'error':
            icon = '<i class="bi bi-x-circle-fill text-danger me-2"></i>';
            break;
        case 'warning':
            icon = '<i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>';
            break;
        default:
            icon = '<i class="bi bi-info-circle-fill text-info me-2"></i>';
    }
    
    // Create the toast HTML
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${icon} ${message}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Add the toast to the container
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Initialize and show the toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });
    toast.show();
    
    // Remove the toast from the DOM after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

// Initialize wishlist functionality when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initWishlistFunctionality();
}); 