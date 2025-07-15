<div class="dropdown currency-switcher">
    @php
        $currencyService = app(\App\Services\CurrencyService::class);
        $currentCurrency = $currencyService->getCurrentCurrency();
        $symbol = $currentCurrency->getSymbolForCurrentLocale();
    @endphp

    <button class="btn btn-sm dropdown-toggle" type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-currency-exchange me-1"></i> {{ $symbol }} {{ $currentCurrency->code }}
    </button>

    <ul class="dropdown-menu" aria-labelledby="currencyDropdown">
        @foreach($currencyService->getActiveCurrencies() as $currency)
            <li>
                <form action="{{ route('preferences.currency') }}" method="POST" class="currency-form">
                    @csrf
                    <input type="hidden" name="currency_code" value="{{ $currency->code }}">
                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                    <button type="submit" class="dropdown-item {{ $currentCurrency->code === $currency->code ? 'active' : '' }}">
                        @if($currency->code == 'USD')
                            <i class="bi bi-currency-dollar me-1"></i>
                        @elseif($currency->code == 'EUR')
                            <i class="bi bi-currency-euro me-1"></i>
                        @elseif($currency->code == 'GBP')
                            <i class="bi bi-currency-pound me-1"></i>
                        @elseif($currency->code == 'JPY')
                            <i class="bi bi-currency-yen me-1"></i>
                        @elseif($currency->code == 'EGP')
                            <i class="bi bi-cash-coin me-1"></i>
                        @else
                            <i class="bi bi-cash me-1"></i>
                        @endif
                        {{ $currency->getSymbolForCurrentLocale() }} {{ $currency->code }} - {{ $currency->name }}
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Submit currency form via AJAX to prevent full page reload
    document.querySelectorAll('.currency-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = form.getAttribute('action');
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update all price values on the page
                    updatePagePrices();
                    
                    // Update the currency dropdown
                    const currencyDropdown = document.getElementById('currencyDropdown');
                    if (currencyDropdown) {
                        currencyDropdown.innerHTML = data.symbol + ' ' + data.currency;
                    }
                }
            })
            .catch(error => console.error('Error changing currency:', error));
        });
    });
    
    // Function to update all prices on the page with the new currency
    function updatePagePrices() {
        // Reload the page to update all prices (could be enhanced with AJAX in the future)
        location.reload();
    }
});
</script>
@endpush 