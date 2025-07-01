<div class="dropdown language-switcher">
    <button class="btn btn-sm dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        @if($currentLocale === 'ar')
            <span class="fi fi-eg me-1"></span> العربية
        @else
            <span class="fi fi-gb me-1"></span> English
        @endif
    </button>
    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
        <li>
            <form action="{{ route('preferences.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="en">
                <input type="hidden" name="redirect" value="{{ url()->current() }}">
                <button type="submit" class="dropdown-item {{ $currentLocale === 'en' ? 'active' : '' }}">
                    <span class="fi fi-gb me-2"></span> English
                </button>
            </form>
        </li>
        <li>
            <form action="{{ route('preferences.language') }}" method="POST">
                @csrf
                <input type="hidden" name="language" value="ar">
                <input type="hidden" name="redirect" value="{{ url()->current() }}">
                <button type="submit" class="dropdown-item {{ $currentLocale === 'ar' ? 'active' : '' }}">
                    <span class="fi fi-eg me-2"></span> العربية
                </button>
            </form>
        </li>
    </ul>
</div> 