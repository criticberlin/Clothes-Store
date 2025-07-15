<div class="dropdown currency-switcher admin-currency-switcher">
    @php
        $currencyService = app(\App\Services\CurrencyService::class);
        $currentCurrency = $currencyService->getCurrentCurrency();
    @endphp

    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="adminCurrencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $currentCurrency->getSymbolForCurrentLocale() }} {{ $currentCurrency->code }}
    </button>

    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminCurrencyDropdown">
        <li><div class="dropdown-header">{{ __('admin.display_currency') }}</div></li>
        <li><hr class="dropdown-divider"></li>
        @foreach($currencyService->getActiveCurrencies() as $currency)
            <li>
                <form action="{{ route('admin.preferences.currency') }}" method="POST" class="currency-form">
                    @csrf
                    <input type="hidden" name="currency_code" value="{{ $currency->code }}">
                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                    <button type="submit" class="dropdown-item {{ $currentCurrency->code === $currency->code ? 'active' : '' }}">
                        {{ $currency->getSymbolForCurrentLocale() }} {{ $currency->code }} - {{ $currency->name }}
                    </button>
                </form>
            </li>
        @endforeach
        <li><hr class="dropdown-divider"></li>
        <li><div class="dropdown-item-text small text-muted px-2">
            <i class="bi bi-info-circle me-1"></i> {{ __('admin.prices_saved_as_egp') }}
        </div></li>
    </ul>
</div> 