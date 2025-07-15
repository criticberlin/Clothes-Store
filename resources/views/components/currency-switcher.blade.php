<div class="dropdown currency-switcher">
    @php
        $currencyService = app(\App\Services\CurrencyService::class);
        $currentCurrency = $currencyService->getCurrentCurrency();
    @endphp

    <button class="btn btn-sm dropdown-toggle" type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $currentCurrency->getSymbolForCurrentLocale() }} {{ $currentCurrency->code }}
    </button>

    <ul class="dropdown-menu" aria-labelledby="currencyDropdown">
        @foreach($currencyService->getActiveCurrencies() as $currency)
            <li>
                <form action="{{ route('preferences.currency') }}" method="POST" class="currency-form">
                    @csrf
                    <input type="hidden" name="currency_code" value="{{ $currency->code }}">
                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                    <button type="submit" class="dropdown-item {{ $currentCurrency->code === $currency->code ? 'active' : '' }}">
                        {{ $currency->getSymbolForCurrentLocale() }} {{ $currency->code }} - {{ $currency->name }}
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
</div> 