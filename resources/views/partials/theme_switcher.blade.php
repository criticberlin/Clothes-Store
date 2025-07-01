<div class="theme-switcher">
    <a href="{{ route('theme.toggle') }}" class="theme-toggle-btn" title="{{ $currentTheme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode' }}">
        @if($currentTheme === 'dark')
            <i class="bi bi-sun-fill"></i>
        @else
            <i class="bi bi-moon-stars-fill"></i>
        @endif
    </a>
</div> 