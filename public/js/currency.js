/**
 * Currency handling functionality for MyClothes store
 */

document.addEventListener('DOMContentLoaded', function() {
    // Handle currency form submission
    const currencyForms = document.querySelectorAll('.currency-form');
    
    if (currencyForms) {
        currencyForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Allow default behavior - the form will submit normally
            });
        });
    }

    // Format prices according to selected currency
    function formatPriceAccordingToCurrency() {
        // Get currency data from HTML
        const html = document.documentElement;
        const currencyCode = html.getAttribute('data-currency-code');
        const currencySymbol = html.getAttribute('data-currency-symbol');
        const currencyRate = parseFloat(html.getAttribute('data-currency-rate') || 1);
        
        if (!currencyCode || !currencySymbol) return;
        
        // Log currency info
        console.log('Currency initialized:', {
            code: currencyCode,
            symbol: currencySymbol,
            rate: currencyRate
        });
    }

    // Initialize currency functionality
    formatPriceAccordingToCurrency();
}); 