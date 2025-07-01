<div class="dropdown language-switcher">
    <button class="btn btn-sm dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        @if($currentLocale === 'ar')
            <i class="bi bi-globe2"></i> العربية
        @else
            <i class="bi bi-globe2"></i> English
        @endif
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        <li>
            <form action="{{ route('preferences.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="en">
                <input type="hidden" name="redirect" value="{{ url()->current() }}">
                <button type="submit" class="dropdown-item {{ $currentLocale === 'en' ? 'active' : '' }}">
                    English
                </button>
            </form>
        </li>
        <li>
            <form action="{{ route('preferences.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="ar">
                <input type="hidden" name="redirect" value="{{ url()->current() }}">
                <button type="submit" class="dropdown-item {{ $currentLocale === 'ar' ? 'active' : '' }}">
                    العربية
                </button>
            </form>
        </li>
    </ul>
</div> 