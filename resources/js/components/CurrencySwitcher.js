/**
 * Currency Switcher component
 * Handles currency switching and price updates
 */
class CurrencySwitcher {
    constructor() {
        this.initEventListeners();
        this.updateAllPrices();
    }

    /**
     * Initialize event listeners for currency switching
     */
    initEventListeners() {
        // Listen for currency form submissions
        document.querySelectorAll('.currency-form').forEach(form => {
            form.addEventListener('submit', this.handleCurrencySwitch.bind(this));
        });
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
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update currency in localStorage
                localStorage.setItem('currency_code', data.currency);
                localStorage.setItem('currency_symbol', data.symbol);
                localStorage.setItem('currency_rate', data.exchange_rate);
                
                // Update currency display in UI
                document.querySelectorAll('.currency-switcher .dropdown-toggle').forEach(button => {
                    button.textContent = `${data.symbol} ${data.currency}`;
                });
                
                // Update all prices with new currency
                this.updateAllPrices();
                
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
            }
        })
        .catch(error => {
            console.error('Error switching currency:', error);
        });
    }
    
    /**
     * Update all price displays on the page
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
        
        // Get current currency from localStorage or fallback to session data
        const currencyCode = localStorage.getItem('currency_code');
        
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

// Initialize the currency switcher when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.currencySwitcher = new CurrencySwitcher();
}); 