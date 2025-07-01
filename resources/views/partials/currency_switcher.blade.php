<div class="dropdown currency-switcher">
    <button class="btn btn-sm dropdown-toggle" type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        @php
            $currency = App\Models\Currency::where('code', $currentCurrency)->first();
            $symbol = $currency ? $currency->symbol : 'ج.م';
        @endphp
        {{ $symbol }} {{ $currentCurrency }}
    </button>
    <ul class="dropdown-menu" aria-labelledby="currencyDropdown">
        @foreach($currencies as $currency)
            <li>
                <form action="{{ route('preferences.currency') }}" method="POST">
                    @csrf
                    <input type="hidden" name="currency_code" value="{{ $currency->code }}">
                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                    <button type="submit" class="dropdown-item {{ $currentCurrency === $currency->code ? 'active' : '' }}">
                        {{ $currency->symbol }} {{ $currency->code }} - {{ $currency->name }}
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
</div>

@push('scripts')
<script>
    // Fetch currencies for the dropdown
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route("currencies.list") }}')
            .then(response => response.json())
            .then(data => {
                const currencyDropdown = document.getElementById('currencyDropdown').nextElementSibling;
                currencyDropdown.innerHTML = '';
                
                data.forEach(currency => {
                    const isActive = currency.code === '{{ $currentCurrency }}';
                    
                    const listItem = document.createElement('li');
                    const link = document.createElement('a');
                    link.className = `dropdown-item ${isActive ? 'active' : ''}`;
                    link.href = `/currency/${currency.code}`;
                    link.textContent = `${currency.name} (${currency.symbol})`;
                    
                    listItem.appendChild(link);
                    currencyDropdown.appendChild(listItem);
                });
            })
            .catch(error => console.error('Error fetching currencies:', error));
    });
</script>
@endpush 