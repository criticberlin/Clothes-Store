/**
 * Admin Currency Switcher
 * Handles currency switching in the admin dashboard
 */
class AdminCurrencySwitcher {
    constructor() {
        this.initEventListeners();
        this.updateAllPrices();
    }

    /**
     * Initialize event listeners for currency switching
     */
    initEventListeners() {
        // Listen for currency form submissions
        document.querySelectorAll('.admin-currency-switcher .currency-form').forEach(form => {
            form.addEventListener('submit', this.handleCurrencySwitch.bind(this));
        });
        
        // Display a note about prices being stored in EGP when adding/editing products
        const priceInputs = document.querySelectorAll('form.product-form input[name="price"], form.product-form input[name="regular_price"], form.product-form input[name="sale_price"]');
        if (priceInputs.length > 0) {
            priceInputs.forEach(input => {
                const helpText = document.createElement('small');
                helpText.classList.add('form-text', 'text-muted', 'currency-note');
                helpText.innerHTML = '<i class="bi bi-info-circle"></i> Prices are stored in EGP but displayed in your selected currency.';
                
                if (!input.parentNode.querySelector('.currency-note')) {
                    input.parentNode.appendChild(helpText);
                }
                
                // Add data attribute to show the current display currency
                const currencyCode = localStorage.getItem('admin_currency_code') || 'EGP';
                input.setAttribute('data-display-currency', currencyCode);
                
                // Add display helper beside the input
                const displayHelper = document.createElement('div');
                displayHelper.classList.add('currency-display-helper');
                displayHelper.innerHTML = `<small class="text-muted">Displaying in ${currencyCode}</small>`;
                
                if (!input.parentNode.querySelector('.currency-display-helper')) {
                    input.parentNode.appendChild(displayHelper);
                }
            });
        }
    }

    /**
     * Handle currency switch form submission
     * @param {Event} event 
     */
    handleCurrencySwitch(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const currencyCode = formData.get('currency_code');
        
        // Send AJAX request to update currency
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update admin currency in localStorage
                localStorage.setItem('admin_currency_code', data.currency);
                localStorage.setItem('admin_currency_symbol', data.symbol);
                localStorage.setItem('admin_currency_rate', data.rate);
                
                // Update currency display in UI
                document.querySelectorAll('.admin-currency-switcher .dropdown-toggle').forEach(button => {
                    button.textContent = `${data.symbol} ${data.currency}`;
                });
                
                // Update all prices with new currency
                this.updateAllPrices();
                
                // Update form currency helpers
                document.querySelectorAll('input[data-display-currency]').forEach(input => {
                    input.setAttribute('data-display-currency', data.currency);
                    
                    const displayHelper = input.parentNode.querySelector('.currency-display-helper');
                    if (displayHelper) {
                        displayHelper.innerHTML = `<small class="text-muted">Displaying in ${data.currency}</small>`;
                    }
                });
                
                // Close dropdown (if using Bootstrap)
                if (typeof bootstrap !== 'undefined') {
                    const dropdowns = document.querySelectorAll('.dropdown-toggle');
                    dropdowns.forEach(dropdown => {
                        const bsDropdown = bootstrap.Dropdown.getInstance(dropdown);
                        if (bsDropdown) {
                            bsDropdown.hide();
                        }
                    });
                }
                
                // Show a notification if available
                if (typeof toastr !== 'undefined') {
                    toastr.success(`Currency changed to ${data.currency}`);
                }
            }
        })
        .catch(error => {
            console.error('Error switching currency:', error);
            
            // Show error notification if available
            if (typeof toastr !== 'undefined') {
                toastr.error('Could not change currency');
            }
        });
    }
    
    /**
     * Update all price displays on the admin page
     */
    updateAllPrices() {
        document.querySelectorAll('.price-display').forEach(priceElement => {
            this.updatePriceDisplay(priceElement);
        });
    }
    
    /**
     * Update a single price display element
     * @param {HTMLElement} priceElement 
     */
    updatePriceDisplay(priceElement) {
        const basePrice = parseFloat(priceElement.getAttribute('data-base-price'));
        
        if (isNaN(basePrice)) {
            return;
        }
        
        // Get admin currency from localStorage or fallback to session data
        const currencyCode = localStorage.getItem('admin_currency_code');
        
        // Send AJAX request to get formatted price
        fetch(`/api/format-price?price=${basePrice}&currency_code=${currencyCode}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.formatted_price) {
                priceElement.innerHTML = data.formatted_price;
            }
        })
        .catch(error => {
            console.error('Error formatting price:', error);
        });
    }
}

// Initialize the admin currency switcher when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.admin-currency-switcher')) {
        window.adminCurrencySwitcher = new AdminCurrencySwitcher();
    }
}); 