<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}" class="theme-{{ $currentTheme }}" data-currency="{{ $currentCurrency }}"
      data-currency-code="{{ app(\App\Services\CurrencyService::class)->getCurrentCurrencyCode() }}" 
      data-currency-symbol="{{ app(\App\Services\CurrencyService::class)->getCurrentCurrency()->getSymbolForCurrentLocale() }}"
      data-currency-rate="{{ app(\App\Services\CurrencyService::class)->getCurrentCurrency()->rate }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MyClothes - Modern fashion store for all your style needs">
    <meta name="theme-color" content="#7F5AF0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ url('/') }}/">
    @yield('meta')
    <title>MyClothes - @yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@6.11.0/css/flag-icons.min.css">

    <!-- Core Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @if($isRTL)
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endif
    
    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <script>
        // This script needs to run before Bootstrap initializes to override dropdown behavior
        document.addEventListener('DOMContentLoaded', function() {
            // Define baseUrl globally for search functionality
            window.baseUrl = function() {
                const path = window.location.pathname;
                if (path.includes('/Clothes_Store/public')) {
                    return window.location.origin + '/Clothes_Store/public';
                } else if (path.includes('/Clothes_Store')) {
                    return window.location.origin + '/Clothes_Store';
                }
                return window.location.origin;
            }();
            
            // Prevent dropdown from closing when clicking on theme toggle
            document.addEventListener('click', function(e) {
                if (e.target.closest('#direct-theme-toggle')) {
                    e.stopPropagation();
                }
            }, true);
        });
    </script>
    <script src="{{ asset('js/theme-manager.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <style>
        :root {
            /* Modern Color Palette 2025 */
            --primary: #7F5AF0;
            --primary-light: #A78BFA;
            --primary-dark: #6D28D9;
            --secondary: #2CB67D;
            --secondary-light: #4ADE80;
            --accent: #FF7F50;
            
            /* Neutral Colors - Dark Theme (default) */
            --bg-primary: #16161A;
            --bg-secondary: #242629;
            --surface: #2D2D33;
            --surface-alt: #383841;
            
            /* Text Colors - Dark Theme (default) */
            --text-primary: #FFFFFE;
            --text-secondary: #94A1B2;
            --text-tertiary: #72757E;
            
            /* UI Colors */
            --border: #383841;
            --focus-ring: rgba(127, 90, 240, 0.5);
            --overlay: rgba(22, 22, 26, 0.8);
            
            /* Radius & Spacing */
            --radius-sm: 0.375rem;
            --radius-md: 0.75rem;
            --radius-lg: 1.5rem;
            --radius-xl: 2rem;
            
            /* Animation */
            --transition-fast: 150ms ease;
            --transition-normal: 250ms ease;
            --transition-slow: 350ms cubic-bezier(0.65, 0, 0.35, 1);
            
            /* Shadows */
            --shadow-sm: 0px 2px 4px rgba(0, 0, 0, 0.1), 0px 0px 2px rgba(0, 0, 0, 0.2);
            --shadow-md: 0px 4px 8px rgba(0, 0, 0, 0.12), 0px 2px 4px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0px 8px 16px rgba(0, 0, 0, 0.16), 0px 4px 8px rgba(0, 0, 0, 0.2);
            --shadow-glow: 0px 0px 20px rgba(127, 90, 240, 0.5);
        }
        
        /* Light Theme Colors */
        html.theme-light {
            --bg-primary: #FFFFFF;
            --bg-secondary: #F5F5F5;
            --surface: #FFFFFF;
            --surface-alt: #F9F9F9;
            --text-primary: #222222;
            --text-secondary: #555555;
            --text-tertiary: #777777;
            --border: #E2E2E2;
            --overlay: rgba(255, 255, 255, 0.8);
        }
        
        /* Light/Dark Mode Transition */
        body, .card, .navbar, .btn, .form-control {
            transition: background-color var(--transition-normal), 
                        color var(--transition-normal), 
                        border-color var(--transition-normal);
        }

        /* Base Styles */
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            letter-spacing: -0.011em;
            font-weight: 400;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        /* Glassmorphism Navbar */
        .navbar-custom {
            background: rgba(22, 22, 26, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0.75rem 0;
            transition: all var(--transition-normal);
        }
        
        html.theme-light .navbar-custom {
            background: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            position: relative;
            z-index: 1;
            transition: all var(--transition-normal);
            border-radius: var(--radius-sm);
        }

        .navbar .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0.35rem;
            left: 1rem;
            width: 0;
            height: 2px;
            background-color: var(--primary);
            transition: width var(--transition-normal);
            z-index: -1;
        }

        .navbar .nav-link:hover::before {
            width: calc(100% - 2rem);
        }

        .navbar .nav-link:hover, 
        .navbar .nav-link.active {
            color: var(--primary-light) !important;
            transform: translateY(-2px);
        }

        .navbar .navbar-brand {
            color: var(--text-primary) !important;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.03em;
        }

        /* Search bar */
        .search-bar {
            position: relative;
            width: 100%;
            max-width: 300px;
        }

        .search-input {
            background-color: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: var(--radius-lg);
            padding-right: 2.5rem;
            padding-left: 1rem;
            height: 40px;
            width: 100%;
            transition: all var(--transition-normal);
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--focus-ring);
            outline: none;
        }

        .search-input::placeholder {
            color: var(--text-tertiary);
        }

        .search-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }

        /* Search Results Dropdown - Fixed Positioning */
        .search-results {
            position: absolute;
            top: 100%;
            left: 80px; /* Align with the search input, not the category dropdown */
            right: 0;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            margin-top: 5px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 2000;
            box-shadow: var(--shadow-md);
            display: none;
            padding: 0.5rem 0;
        }

        /* Search Results Dropdown - Improved Positioning */
        .search-results {
            position: absolute;
            top: calc(100% + 5px); /* Position below the search bar with a small gap */
            left: 80px; /* Align with the search input, not the category dropdown */
            right: 0;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            max-height: 400px;
            overflow-y: auto;
            z-index: 2000;
            box-shadow: var(--shadow-lg);
            display: none;
            padding: 0.5rem 0;
            animation: fadeInDown 0.2s ease-out;
        }
        
        .search-results.show {
            display: block !important;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Search Loading Indicator */
        .search-loading-indicator {
            transition: opacity 0.2s ease-out;
            z-index: 5;
        }
        
        .search-loading-indicator .spinner-grow {
            width: 1rem;
            height: 1rem;
            color: var(--primary);
            opacity: 0.8;
        }
        
        /* Loading Spinner Animation */
        @keyframes pulse-opacity {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        
        .search-result-item .spinner-border {
            animation: spinner-border 0.75s linear infinite, pulse-opacity 1.5s ease-in-out infinite;
        }
        
        /* Improve focus styles for keyboard navigation */
        .search-results a:focus {
            outline: none;
        }
        
        .search-results a:focus .search-result-item {
            background-color: var(--surface-alt);
            box-shadow: inset 3px 0 0 var(--primary);
        }
        
        /* Empty state styling */
        .search-result-item.empty-state {
            color: var(--text-secondary);
            padding: 1.5rem;
            text-align: center;
        }
        
        .search-result-item.empty-state i {
            font-size: 1.5rem;
            color: var(--text-tertiary);
            margin-bottom: 0.5rem;
            display: block;
        }

        .search-category-header {
            padding: 0.5rem 1rem 0.25rem;
            font-weight: 500;
            color: var(--text-tertiary);
            border-bottom: 1px solid var(--border);
            margin-bottom: 0.25rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .search-result-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: background-color var(--transition-fast);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-item:hover {
            background-color: var(--surface-alt);
        }

        .search-results a:focus {
            outline: none;
        }

        .search-results a:focus .search-result-item {
            background-color: rgba(127, 90, 240, 0.1);
            border-left: 3px solid var(--primary);
        }

        .search-result-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: var(--radius-sm);
        }

        .search-result-item .product-title {
            font-weight: 500;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-item .product-price {
            color: var(--primary);
            font-weight: 600;
        }

        .search-result-item .product-category {
            font-size: 0.75rem;
            color: var(--text-tertiary);
        }
        
        .search-result-item .product-availability {
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Modern Cards */
        .card {
            background-color: var(--surface);
            color: var(--text-primary);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: all var(--transition-normal);
            box-shadow: var(--shadow-sm);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid var(--border);
            padding: 1.25rem;
        }

        /* Category Cards */
        .category-card {
            height: 350px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            position: relative;
            border: none;
        }

        .category-card img {
            object-fit: cover;
            height: 100%;
            width: 100%;
            transition: transform var(--transition-slow);
        }

        .category-card .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(22, 22, 26, 0.9), transparent);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2rem;
            opacity: 0.9;
            transition: all var(--transition-normal);
        }

        .category-card:hover img {
            transform: scale(1.05);
        }

        .category-card:hover .overlay {
            opacity: 1;
        }

        /* Buttons */
        .btn {
            border-radius: var(--radius-md);
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            letter-spacing: -0.01em;
            transition: all var(--transition-normal);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            box-shadow: 0 0 0 0 var(--focus-ring);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            transform: translateY(-2px);
            box-shadow: var(--shadow-glow);
        }

        .btn-outline-light {
            border: 2px solid var(--text-primary);
            color: var(--text-primary);
            font-weight: 600;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-light);
            color: var(--primary-light);
            transform: translateY(-2px);
        }

        /* Form Elements */
        .form-control {
            background-color: var(--surface-alt);
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            transition: all var(--transition-normal);
        }

        .form-control:focus {
            background-color: var(--surface-alt);
            border-color: var(--primary);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px var(--focus-ring);
        }

        .form-control::placeholder {
            color: var(--text-tertiary);
            opacity: 0.7;
        }

        /* Tables */
        .table {
            color: var(--text-primary);
            border-collapse: separate;
            border-spacing: 0;
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--surface-alt);
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            border-color: var(--border);
            vertical-align: middle;
        }

        /* Dropdowns & Modals */
        .dropdown-menu {
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            color: var(--text-secondary);
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            transition: all var(--transition-fast);
        }

        .dropdown-item:hover {
            color: var(--text-primary);
            background-color: var(--surface-alt);
        }

        .dropdown-divider {
            border-color: var(--border);
            margin: 0.5rem 0;
        }

        .modal-content {
            background-color: var(--surface);
            color: var(--text-primary);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
        }
        
        /* Nested dropdown submenu */
        .dropdown-submenu {
            position: absolute;
            left: 100%;
            top: 0;
            display: none;
        }
        
        .dropdown-item:hover + .dropdown-submenu,
        .dropdown-submenu:hover {
            display: block;
        }
        
        /* Mobile responsive submenu */
        @media (max-width: 991.98px) {
            .dropdown-submenu {
                position: static;
                margin-left: 1rem;
                box-shadow: none;
                border-left: 1px solid var(--border);
            }
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 1.5rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        .page-transition {
            animation-delay: calc(var(--animation-order) * 100ms);
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        /* Dark Mode Toggle */
        .theme-toggle {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            background-color: var(--surface);
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all var(--transition-normal);
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 100;
            box-shadow: var(--shadow-md);
        }

        .theme-toggle:hover {
            background-color: var(--primary);
            transform: rotate(45deg);
        }

        /* Theme & Language Switchers */
        .theme-switcher, 
        .language-switcher,
        .currency-switcher {
            display: inline-block;
            margin-left: 0.5rem;
        }
        
        .theme-toggle-btn {
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
            color: var(--text-primary);
            background-color: var(--surface);
            border: 1px solid var(--border);
            transition: all var(--transition-normal);
            cursor: pointer;
        }
        
        .theme-toggle-btn:hover {
            transform: translateY(-2px);
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }
        
        /* RTL Support */
        html[dir="rtl"] .ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }
        
        html[dir="rtl"] .me-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }
        
        html[dir="rtl"] .navbar .dropdown-menu {
            left: auto;
            right: 0;
            text-align: right;
        }

        /* Dynamic Category Dropdown */
        .search-category-wrapper {
            width: auto;
            min-width: 60px;
            flex-shrink: 0;
            flex-grow: 0;
            position: relative;
            transition: width 0.2s ease;
        }
        
        .search-category-select {
            height: 46px;
            border-color: var(--border);
            background-color: var(--surface);
            color: var(--text-primary);
            border-radius: var(--radius-lg) 0 0 var(--radius-lg);
            padding-left: 1rem;
            padding-right: 2rem;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            width: auto;
            min-width: 60px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            transition: width 0.2s ease;
        }
        
        .search-category-select::-ms-expand {
            display: none;
        }
        
        .search-category-wrapper::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid var(--text-secondary);
            pointer-events: none;
        }
        
        /* Enhanced Search Bar Styles */
        .search-bar-container {
            position: relative;
            width: 100%;
            max-width: 700px;
        }
        
        .search-input-group {
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-normal);
            display: flex;
        }
        
        .search-input-group:focus-within {
            box-shadow: var(--shadow-md);
        }
        
        .search-input {
            height: 46px;
            border-color: var(--border);
            background-color: var(--surface);
            color: var(--text-primary);
            padding-left: 1rem;
            padding-right: 1rem;
            flex: 1;
            min-width: 0;
            border-radius: 0;
            border-left: none;
        }
        
        .search-button {
            border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
            padding: 0 1.25rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
        }
        
        .search-button:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
        }
        
        /* Button Icon */
        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: var(--surface);
            color: var(--text-primary);
            border: 1px solid var(--border);
            transition: all var(--transition-normal);
        }
        
        .btn-icon:hover {
            background-color: var(--surface-alt);
            transform: translateY(-2px);
        }
        
        /* Header Icon Buttons */
        .header-icon-btn {
            width: 46px;
            height: 46px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: var(--surface);
            color: var(--text-primary);
            border: 1px solid var(--border);
            transition: all var(--transition-normal);
            font-size: 1.2rem;
        }
        
        .header-icon-btn:hover {
            background-color: var(--surface-alt);
            transform: translateY(-2px);
            color: var(--primary);
        }
        
        .dropdown-toggle-no-arrow::after {
            display: none;
        }
        
        .dropdown-header {
            font-weight: 600;
            color: var(--text-primary);
        }
        
        /* Profile Placeholder */
        .profile-placeholder {
            width: 32px;
            height: 32px;
            background-color: transparent;
            color: var(--text-primary);
            font-size: 18px;
            transition: color var(--transition-normal);
            border-radius: 4px;
        }
        
        .profile-placeholder i {
            font-size: 20px;
        }
        
        html.theme-dark .profile-placeholder i {
            color: var(--text-primary);
        }
        
        html.theme-light .profile-placeholder i {
            color: var(--text-primary);
        }
        
        /* Enhanced Profile Dropdown */
        .profile-dropdown {
            min-width: 220px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            box-shadow: var(--shadow-lg);
        }
        
        .profile-dropdown .dropdown-item {
            padding: 0.625rem 1rem;
            transition: all 0.2s ease;
        }
        
        .profile-dropdown .dropdown-item:hover {
            background-color: var(--surface-alt);
        }
        
        .profile-dropdown .dropdown-header {
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .profile-dropdown .dropdown-divider {
            margin: 0.25rem 0;
        }
        
        
        /* Cart Badge */
        .cart-badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.4rem;
            transform: translate(-40%, -40%) !important;
        }
        
        .animate-pulse {
            animation: pulse 0.5s cubic-bezier(0.4, 0, 0.6, 1);
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: translate(-40%, -40%) scale(1);
            }
            50% {
                opacity: 0.8;
                transform: translate(-40%, -40%) scale(1.2);
            }
        }
        
        /* Notifications */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
            padding: 15px;
            border-radius: var(--radius-md);
            background-color: var(--surface);
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--primary);
            transform: translateX(120%);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .notification.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .notification-content {
            display: flex;
            align-items: center;
        }
        
        .notification-content i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .notification-success {
            border-left-color: var(--secondary);
        }
        
        .notification-success i {
            color: var(--secondary);
        }
        
        .notification-error {
            border-left-color: #dc3545;
        }
        
        .notification-error i {
            color: #dc3545;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .search-bar-container {
                max-width: 100%;
                order: 3;
                margin-top: 1rem;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            
            .navbar-collapse {
                flex-direction: column;
            }
            
            .search-category-wrapper {
                min-width: 80px;
            }
            
            .header-icon-btn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 575.98px) {
            .search-category-select {
                max-width: 100px;
                padding-left: 0.5rem;
                padding-right: 1.5rem;
            }
            
            .search-button {
                padding: 0 0.75rem;
            }
            
            .navbar-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }
            
            .navbar-nav .nav-item {
                margin-right: 1rem;
            }
            
            .header-icon-btn {
                width: 36px;
                height: 36px;
            }
        }

        /* Custom Category Dropdown */
        .custom-dropdown {
            position: relative;
            height: 46px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg) 0 0 var(--radius-lg);
            border-right: none;
            min-width: 60px;
            cursor: pointer;
            z-index: 100;
            user-select: none;
        }
        
        .selected-option {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
            white-space: nowrap;
            color: var(--text-primary);
            font-size: 0.875rem;
            pointer-events: auto;
        }
        
        .selected-option i {
            margin-left: 5px;
            font-size: 0.75rem;
            transition: transform 0.2s ease;
        }
        
        .custom-dropdown.open .selected-option i {
            transform: rotate(180deg);
        }
        
        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 100%;
            max-height: 300px;
            overflow-y: auto;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            margin-top: 0.25rem;
            box-shadow: var(--shadow-md);
            display: none;
            z-index: 1000;
        }
        
        .custom-dropdown.open .dropdown-options {
            display: block;
            animation: fadeInDown 0.2s ease-out;
        }
        
        .dropdown-option {
            padding: 0.5rem 1rem;
            white-space: nowrap;
            cursor: pointer;
            transition: background-color 0.15s ease;
            pointer-events: auto;
        }
        
        .dropdown-option:hover,
        .dropdown-option.selected {
            background-color: var(--surface-alt);
            color: var(--primary);
        }
        
        /* Search Input */
        .search-input {
            height: 46px;
            border-color: var(--border);
            background-color: var(--surface);
            color: var(--text-primary);
            padding-left: 1rem;
            padding-right: 1rem;
            flex: 1;
            min-width: 0;
            border-radius: 0;
            border-left: none;
        }

        /* Category Dropdown - New Implementation */
        .category-dropdown {
            position: relative;
            height: 46px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg) 0 0 var(--radius-lg);
            border-right: none;
            min-width: 80px;
            cursor: pointer;
            z-index: 1500;
        }
        
        .dropdown-header {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            white-space: nowrap;
            user-select: none;
        }
        
        .dropdown-header i {
            margin-left: 10px;
            font-size: 12px;
            transition: transform 0.2s ease;
        }
        
        .category-dropdown.open .dropdown-header i {
            transform: rotate(180deg);
        }
        
        .category-dropdown .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            padding: 5px 0;
            margin-top: 1px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            box-shadow: var(--shadow-md);
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            display: none;
            z-index: 1500;
        }

        .category-dropdown.open .dropdown-menu {
            display: block !important;
        }

        /* Category Dropdown - Fixed Implementation */
        .category-dropdown {
            position: relative;
            height: 46px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg) 0 0 var(--radius-lg);
            border-right: none;
            min-width: 80px;
            cursor: pointer;
            z-index: 1500;
        }
        
        .search-bar-container {
            position: relative;
        }
        
        .category-dropdown-menu {
            position: absolute;
            top: 46px;
            left: 0;
            width: 180px; /* Narrower to match category dropdown width */
            padding: 8px 0;
            margin-top: 2px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            display: none;
            z-index: 3000;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        
        .category-dropdown-menu.show {
            display: block !important;
            animation: fadeInDown 0.2s ease-out;
        }

        /* Enhanced Category Dropdown */
        .category-dropdown-menu {
            position: absolute;
            top: 46px;
            left: 0;
            width: 200px;
            padding: 8px 0;
            margin-top: 2px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            display: none;
            z-index: 3000;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        
        .category-dropdown-menu.show {
            display: block !important;
            opacity: 1;
            transform: translateY(0);
        }
        
        .category-dropdown-menu .dropdown-item {
            padding: 10px 15px;
            font-size: 0.9rem;
            color: var(--text-primary);
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
        }
        
        .category-dropdown-menu .dropdown-item:hover,
        .category-dropdown-menu .dropdown-item.active {
            background-color: var(--surface-alt);
            color: var(--primary);
            border-left-color: var(--primary);
        }
        
        /* Make sure the dropdown button looks active when dropdown is open */
        .category-dropdown.open .dropdown-header {
            background-color: var(--surface-alt);
            color: var(--primary);
        }

        /* General Dropdown Item Styles */
        .dropdown-item {
            padding: 8px 15px;
            white-space: nowrap;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        
        .dropdown-item:hover,
        .dropdown-item.active {
            background-color: var(--surface-alt);
            color: var(--primary);
        }
        
        /* Theme-specific styles */
        :root.theme-light .dropdown-menu {
            background-color: var(--surface);
            border-color: var(--border);
        }
        
        :root.theme-dark .dropdown-menu {
            background-color: var(--surface);
            border-color: var(--border);
        }

        /* Fix search bar integration with dropdown */
        .search-input-group {
            position: relative;
        }

        /* Create smooth transition between dropdown and search */
        .category-dropdown.open + .search-input {
            border-left: none;
        }

        /* Theme Toggle Switch Styles */
        .theme-toggle-item {
            cursor: pointer;
            padding: 0.75rem 1rem;
            transition: background-color var(--transition-fast);
        }
        
        .theme-toggle-item:hover {
            background-color: var(--surface-alt);
        }
        
        /* Modern Switch Design */
        .modern-switch {
            position: relative;
            width: 44px;
            height: 22px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .modern-switch-input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
            cursor: pointer;
        }

        .modern-switch-label {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--surface-alt);
            border: 1px solid var(--border);
            border-radius: 34px;
            cursor: pointer;
            transition: all var(--transition-normal);
        }

        .modern-switch-label:before {
            content: '';
            position: absolute;
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 2px;
            background-color: var(--text-secondary);
            border-radius: 50%;
            transition: all var(--transition-normal);
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
        }

        /* Switch states for both themes */
        html.theme-dark .modern-switch-input:checked + .modern-switch-label {
            background-color: var(--primary);
            border-color: var(--primary-dark);
        }

        html.theme-dark .modern-switch-input:checked + .modern-switch-label:before {
            transform: translateX(21px);
            background-color: white;
            box-shadow: 0 0 8px rgba(127, 90, 240, 0.5);
        }

        html.theme-light .modern-switch-input:not(:checked) + .modern-switch-label {
            background-color: var(--border);
            border-color: var(--text-tertiary);
        }

        html.theme-light .modern-switch-input:checked + .modern-switch-label {
            background-color: var(--secondary-light);
            border-color: var(--secondary);
        }

        html.theme-light .modern-switch-input:checked + .modern-switch-label:before {
            transform: translateX(21px);
            background-color: white;
            box-shadow: 0 0 4px rgba(44, 182, 125, 0.5);
        }

        /* Focus and hover states for better UX */
        .modern-switch-input:focus + .modern-switch-label,
        .modern-switch-label:hover {
            box-shadow: 0 0 0 2px var(--focus-ring);
        }

        /* Theme toggle item hover state */
        .theme-toggle-item {
            cursor: pointer;
            transition: background-color var(--transition-fast);
            position: relative;
            overflow: hidden;
        }

        .theme-toggle-item:hover {
            background-color: var(--surface-alt);
        }
        
        .theme-toggle-item:active {
            transform: translateY(1px);
        }
        
        /* Add ripple effect to make it more obviously clickable */
        .theme-toggle-item::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(127, 90, 240, 0.4);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%, -50%);
            transform-origin: 50% 50%;
        }
        
        .theme-toggle-item:active::after {
            animation: ripple 0.5s ease-out;
        }
        
        @keyframes ripple {
            0% {
                opacity: 1;
                transform: scale(0, 0) translate(-50%, -50%);
            }
            100% {
                opacity: 0;
                transform: scale(20, 20) translate(-50%, -50%);
            }
        }

        /* Theme toggle icons */
        #themeToggleIcon {
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            transition: transform var(--transition-normal), color var(--transition-normal);
        }

        /* Theme-specific icon colors */
        html.theme-dark #themeToggleIcon {
            color: #f59e0b; /* amber/yellow for sun icon in dark mode */
        }

        html.theme-light #themeToggleIcon {
            color: var(--primary); /* purple for moon icon in light mode */
        }

        /* Profile dropdown theme toggle icon */
        #theme-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            transition: color var(--transition-normal);
        }

        #theme-icon i {
            font-size: 1.1rem;
            transition: color var(--transition-normal), transform var(--transition-fast);
            color: var(--text-secondary); /* Neutral color by default */
        }

        /* Theme-specific colors for profile dropdown icons */
        html.theme-dark #theme-icon i.bi-sun-fill,
        html.theme-light #theme-icon i.bi-moon-stars-fill {
            /* Use neutral color by default */
            color: var(--text-secondary);
        }

        /* Hover effects for theme toggle icons */
        #direct-theme-toggle:hover #theme-icon i {
            transform: scale(1.2);
            color: var(--primary); /* Primary color on hover */
        }

        /* Prevent dropdown close */
        .dropdown-menu .dropdown-item[data-prevent-close="true"] {
            cursor: pointer;
        }

        /* Theme transition - Prevent flicker */
        html.theme-transition,
        html.theme-transition *,
        html.theme-transition *:before,
        html.theme-transition *:after {
            transition: background-color var(--transition-normal), 
                        color var(--transition-normal), 
                        border-color var(--transition-normal), 
                        box-shadow var(--transition-normal), 
                        opacity var(--transition-normal) !important;
            transition-delay: 0 !important;
        }

        /* Theme Loading Indicator */
        .search-loading-indicator {
            transition: opacity 0.2s ease-out;
            z-index: 5;
        }
        
        /* Toggle Switch - New Implementation */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 22px;
            margin: 0;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .toggle-slider {
            background-color: var(--primary);
        }
        
        input:checked + .toggle-slider:before {
            transform: translateX(22px);
        }
        
        /* Theme Toggle Item */
        #direct-theme-toggle {
            cursor: pointer;
            user-select: none;
            padding: 0.75rem 1rem;
            transition: background-color 0.2s ease;
        }
        
        #direct-theme-toggle:hover {
            background-color: var(--surface-alt);
        }
        
        #theme-icon {
            font-size: 1.1rem;
            display: inline-block;
            width: 24px;
            text-align: center;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-bag-heart-fill text-primary me-2"></i> MyClothes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.list') }}">{{ __('general.shop') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('3d-customizer') ? 'active' : '' }}" href="{{ route('3d-customizer') }}">
                            <i class="bi bi-palette-fill me-1"></i>{{ __('general.3d_customizer') }}
                            <span class="badge bg-primary ms-1">{{ __('general.new') }}</span>
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                    <!-- Enhanced Search Bar -->
                    <div class="search-bar-container mx-2">
                        <form action="{{ route('products.search') }}" method="GET" class="d-flex">
                            <div class="input-group search-input-group">
                                <!-- Category Dropdown - New Implementation -->
                                <div class="category-dropdown" id="categoryDropdown">
                                    <div class="dropdown-header" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">
                                        <span id="selectedCategory">
                                            @if(request()->has('category_id') && request('category_id') != '')
                                                {{ App\Models\Category::find(request('category_id'))->name ?? 'All' }}
                                            @else
                                                All
                                            @endif
                                        </span>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                    <input type="hidden" name="category_id" id="categoryInput" value="{{ request('category_id', '') }}">
                                </div>
                                
                                <input type="text" name="q" class="form-control search-input" 
                                    placeholder="{{ __('general.search_products') }}" autocomplete="off" id="searchInput" value="{{ request('q', '') }}">
                                <button class="btn btn-primary search-button" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Dropdown menu positioned outside the search bar -->
                        <div class="dropdown-menu category-dropdown-menu" id="categoryDropdownMenu" role="menu">
                            <div class="dropdown-item {{ !request()->has('category_id') || request('category_id') == '' ? 'active' : '' }}" 
                                 data-value="" 
                                 role="menuitem" 
                                 tabindex="-1">All</div>
                            @foreach(App\Models\Category::all() as $category)
                                <div class="dropdown-item {{ request('category_id') == $category->id ? 'active' : '' }}" 
                                     data-value="{{ $category->id }}" 
                                     role="menuitem" 
                                     tabindex="-1">
                                    {{ $category->name }}
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="search-results" id="searchResults"></div>
                    </div>
                    
                    <!-- Theme, Language, Profile, Cart -->
                    <div class="d-flex align-items-center">
                        <!-- Compact Language Switcher -->
                        <div class="dropdown ms-2">
                            <button class="btn header-icon-btn dropdown-toggle-no-arrow" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="{{ $currentLocale == 'en' ? 'fi fi-us' : 'fi fi-eg' }}"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                                <li>
                                    <a class="dropdown-item {{ $currentLocale == 'en' ? 'active' : '' }}" href="{{ asset('switch_language.php') }}?lang=en&redirect={{ urlencode(url()->current()) }}">
                                        <span class="fi fi-us me-2"></span> English
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ $currentLocale == 'ar' ? 'active' : '' }}" href="{{ asset('switch_language.php') }}?lang=ar&redirect={{ urlencode(url()->current()) }}">
                                        <span class="fi fi-eg me-2"></span> العربية
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Currency Switcher -->
                        <div class="dropdown ms-2">
                            @php
                                $currencyService = app(\App\Services\CurrencyService::class);
                                $currentCurrency = $currencyService->getCurrentCurrency();
                            @endphp
                            <button class="btn header-icon-btn dropdown-toggle-no-arrow" type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $currentCurrency->getSymbolForCurrentLocale() }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="currencyDropdown">
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
                        
                        <div class="ms-2">
                            @auth
                            <div class="dropdown">
                                <a href="#" class="header-icon-btn" id="userDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ Auth::user()->profile_photo_url }}" 
                                             alt="{{ Auth::user()->name }}" 
                                             class="rounded" 
                                             width="32" height="32">
                                    @else
                                        <div class="profile-placeholder d-flex align-items-center justify-content-center">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end profile-dropdown" aria-labelledby="userDropdown" id="userDropdownMenu" data-bs-popper="none">
                                    <li class="dropdown-header">{{ Auth::user()->name }}</li>
                                    
                                    <!-- Admin Dashboard - Always show for user ID 1 or those with permission -->
                                    @if(Auth::id() == 1 || Auth::user()->can('admin_dashboard'))
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> 
                                            <span>{{ __('general.admin_dashboard') }}</span>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @endif
                                    
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile', Auth::id()) }}">
                                            <i class="bi bi-person me-2"></i> 
                                            <span>{{ __('general.my_profile') }}</span>
                                        </a>
                                    </li>
                                    
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('orders.index') }}">
                                            <i class="bi bi-bag me-2"></i> 
                                            <span>{{ __('general.my_orders') }}</span>
                                        </a>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <!-- Theme Toggle Switch -->
                                    <li>
                                        <div class="dropdown-item d-flex align-items-center justify-content-between" id="direct-theme-toggle">
                                            <div class="d-flex align-items-center">
                                                <span id="theme-icon" class="me-2">
                                                    <i class="bi bi-moon-stars-fill"></i>
                                                </span>
                                                <span id="theme-label">Dark Mode</span>
                                            </div>
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="theme-toggle">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </li>
                                    
                                    <li><hr class="dropdown-divider"></li>
                                    
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center text-danger" href="{{ route('do_logout') }}">
                                            <i class="bi bi-box-arrow-right me-2"></i> 
                                            <span>{{ __('general.logout') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <div class="d-flex">
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-2">{{ __('general.login') }}</a>
                                <a href="{{ route('register') }}" class="btn btn-sm btn-primary">{{ __('general.register') }}</a>
                            </div>
                            @endauth
                        </div>
                        
                        <a href="{{ route('cart.index') }}" class="header-icon-btn position-relative ms-2">
                            <i class="bi bi-cart text-primary"></i>
                            @if(Auth::check() && Auth::user()->cart && Auth::user()->cart->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                                    {{ Auth::user()->cart->count() }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <main>
        @yield('content')
    </main>
    
    <footer class="bg-surface text-text-secondary py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-primary mb-4"><i class="bi bi-bag-heart-fill me-2"></i> MyClothes</h5>
                    <p>{{ __('general.your_one_stop_destination') }}</p>
                    <div class="social-links mt-3">
                        <a href="#" class="me-2 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-2 fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="me-2 fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-pinterest"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="mb-4">{{ __('general.shop') }}</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('products.byCategory', 'men') }}" class="text-reset">{{ __('general.men') }}</a></li>
                        <li class="mb-2"><a href="{{ route('products.byCategory', 'women') }}" class="text-reset">{{ __('general.women') }}</a></li>
                        <li class="mb-2"><a href="{{ route('products.byCategory', 'kids') }}" class="text-reset">{{ __('general.kids') }}</a></li>
                        <li class="mb-2"><a href="{{ route('3d-customizer') }}" class="text-reset">{{ __('general.3d_customizer') }} <span class="badge bg-primary">{{ __('general.new') }}</span></a></li>
                        <li><a href="{{ route('products.list') }}" class="text-reset">{{ __('general.all_products') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="mb-4">{{ __('general.company') }}</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('pages.about') }}" class="text-reset">{{ __('general.about_us') }}</a></li>
                        <li class="mb-2"><a href="{{ route('pages.contact') }}" class="text-reset">{{ __('general.contact') }}</a></li>
                        <li class="mb-2"><a href="{{ route('pages.faq') }}" class="text-reset">{{ __('general.faq') }}</a></li>
                        <li><a href="#" class="text-reset">{{ __('general.careers') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-4">{{ __('general.newsletter') }}</h6>
                    <p>{{ __('general.subscribe_message') }}</p>
                    <form class="newsletter-form mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="{{ __('general.enter_your_email') }}">
                            <button class="btn btn-primary" type="button">{{ __('general.subscribe') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <hr>
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <p class="mb-0">© {{ date('Y') }} MyClothes. {{ __('general.all_rights_reserved') }}</p>
                        <div class="d-flex mt-2 mt-sm-0">
                            <a href="{{ route('pages.privacy') }}" class="text-reset me-3">{{ __('general.privacy_policy') }}</a>
                            <a href="{{ route('pages.terms') }}" class="text-reset">{{ __('general.terms_of_service') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        // Helper function to get the correct base URL for the application
        function getBaseUrl() {
            // Get the current URL path
            const path = window.location.pathname;
            
            // Check if we're in the Clothes_Store/public directory
            if (path.includes('/Clothes_Store/public')) {
                return window.location.origin + '/Clothes_Store/public';
            } 
            // Check if we're just in the Clothes_Store directory
            else if (path.includes('/Clothes_Store')) {
                return window.location.origin + '/Clothes_Store';
            }
            // Otherwise, assume we're at the root
            return window.location.origin;
        }
        
        // Helper function to get the API URL
        function getApiUrl(endpoint) {
            return getBaseUrl() + '/api/' + endpoint;
        }
        
        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggle functionality
            const themeSwitch = document.getElementById('themeSwitch');
            const themeToggleIcon = document.getElementById('themeToggleIcon');
            const themeToggleLabel = document.getElementById('themeToggleLabel');
            const themeToggleOption = document.getElementById('themeToggleOption');
            const html = document.documentElement;
            
            if (themeSwitch && themeToggleIcon && themeToggleLabel) {
                // Get current theme from HTML class or localStorage
                const savedTheme = localStorage.getItem('theme');
                const currentTheme = html.classList.contains('theme-dark') ? 'dark' : 'light';
                
                // Initialize theme from localStorage if available
                if (savedTheme && savedTheme !== currentTheme) {
                    html.classList.remove('theme-light', 'theme-dark');
                    html.classList.add('theme-' + savedTheme);
                }
                
                // Update UI based on current theme
                updateThemeUI();
                
                // Handle toggle changes - use both change and click events for better support
                themeSwitch.addEventListener('change', toggleTheme);
                
                // Make the entire toggle option clickable
                if (themeToggleOption) {
                    themeToggleOption.addEventListener('click', function(e) {
                        // Only toggle if not clicking on the switch itself (it handles its own events)
                        if (!e.target.closest('.modern-switch')) {
                            themeSwitch.checked = !themeSwitch.checked;
                            toggleTheme();
                            e.stopPropagation(); // Prevent dropdown from closing
                        }
                    });
                }
                
                // Main theme toggle function
                function toggleTheme() {
                    // Toggle theme
                    html.classList.add('theme-transition');
                    html.classList.toggle('theme-dark');
                    html.classList.toggle('theme-light');
                    
                    // Update localStorage
                    const newTheme = html.classList.contains('theme-dark') ? 'dark' : 'light';
                    localStorage.setItem('theme', newTheme);
                    
                    // Update UI
                    updateThemeUI();
                    
                    // Remove transition class after animation completes
                    setTimeout(() => {
                        html.classList.remove('theme-transition');
                    }, 300);
                }
                
                // Function to update theme toggle UI
                function updateThemeUI() {
                    const isDarkTheme = html.classList.contains('theme-dark');
                    
                    // Update toggle state
                    themeSwitch.checked = isDarkTheme;
                    
                    // Update icon and label
                    if (isDarkTheme) {
                        themeToggleIcon.className = 'bi bi-sun me-2';
                        themeToggleLabel.textContent = 'Light Mode';
                        } else {
                        themeToggleIcon.className = 'bi bi-moon me-2';
                        themeToggleLabel.textContent = 'Dark Mode';
                    }
                }
                
                // Ensure UI is correctly initialized on page load
                updateThemeUI();
                
                // Fix for dropdown closing when toggling theme
                document.querySelectorAll('[data-prevent-close="true"]').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });
            }
        });
    </script>
    
    @stack('scripts')
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent dropdown from closing when clicking on elements with data-prevent-close attribute
        document.addEventListener('click', function(e) {
            if (e.target.closest('[data-prevent-close="true"]')) {
                e.stopPropagation();
            }
        }, true);
        
        // New Category Dropdown Implementation
        function initCategoryDropdown() {
            const dropdown = document.getElementById('categoryDropdown');
            if (!dropdown) return;
            
            const header = dropdown.querySelector('.dropdown-header');
            const dropdownMenu = document.getElementById('categoryDropdownMenu');
            const items = dropdownMenu.querySelectorAll('.dropdown-item');
            const input = document.getElementById('categoryInput');
            const selectedCategory = document.getElementById('selectedCategory');
            
            if (!header || !items.length || !input || !selectedCategory || !dropdownMenu) {
                console.error('Missing dropdown elements');
                return;
            }
            
            console.log('Category dropdown initialized');
            
            // Ensure dropdown is closed initially
            dropdownMenu.classList.remove('show');
            
            // Toggle dropdown
            header.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle dropdown visibility
                dropdownMenu.classList.toggle('show');
                dropdown.classList.toggle('open');
                
                console.log('Toggle dropdown:', dropdownMenu.classList.contains('show'));
            });
            
            // Handle item selection
            items.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Update display and value
                    selectedCategory.textContent = this.textContent.trim();
                    input.value = this.dataset.value;
                    
                    // Update active state
                    items.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Close dropdown
                    dropdownMenu.classList.remove('show');
                    dropdown.classList.remove('open');
                    
                    // Trigger category changed event
                    const event = new CustomEvent('categoryChanged', {
                        bubbles: true,
                        detail: { value: this.dataset.value }
                    });
                    document.dispatchEvent(event);
                });
            });
            
            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    dropdown.classList.remove('open');
                }
            });
        }
        
        // Initialize the dropdown
        initCategoryDropdown();

        // New Search Suggestions Implementation
        function initSearchSuggestions() {
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const categoryInput = document.getElementById('categoryInput');
            
            if (!searchInput || !searchResults) {
                console.error('Search elements not found');
                return;
            }
            
            console.log('Search suggestions initialized');
            
            let searchTimer;
            const searchDelay = 300; // ms delay after typing stops
            
            // Search function
            function doSearch() {
                const query = searchInput.value.trim();
                
                // Don't search if query is too short
                if (query.length < 2) {
                    searchResults.classList.remove('show');
                    searchResults.style.display = 'none';
                    return;
                }
                
                // Get selected category
                const categoryId = categoryInput ? categoryInput.value : '';
                
                // Show loading state
                searchResults.innerHTML = `
                    <div class="search-result-item text-center">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Searching${categoryId ? ' in selected category' : ''}...</span>
                    </div>
                `;
                searchResults.classList.add('show');
                searchResults.style.display = 'block';
                
                // Use the helper function to get the correct API URL
                const baseUrl = getBaseUrl();
                let searchUrl = getApiUrl('search') + `?q=${encodeURIComponent(query)}`;
                
                // Always include category_id if available to filter results
                if (categoryId) {
                    searchUrl += `&category_id=${categoryId}`;
                    console.log('Searching in category:', categoryId);
                }
                
                console.log('Fetching:', searchUrl);
                
                // Simple fetch with minimal headers and better error handling
                fetch(searchUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Search request failed: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Search results:', data);
                    renderSearchResults(data);
                    // Ensure dropdown is visible
                    searchResults.classList.add('show');
                    searchResults.style.display = 'block';
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = `
                        <div class="search-result-item text-center text-danger">
                            Search failed. Please try again later.
                        </div>
                    `;
                    // Still show the error message
                    searchResults.classList.add('show');
                    searchResults.style.display = 'block';
                });
            }
            
            // Render search results
            function renderSearchResults(data) {
                // Check if we have valid data
                if (!data || !data.products) {
                    searchResults.innerHTML = `
                        <div class="search-result-item text-center">
                            No results found
                        </div>
                    `;
                    return;
                }
                
                const products = data.products;
                
                if (products.length === 0) {
                    searchResults.innerHTML = `
                        <div class="search-result-item text-center">
                            No results found
                        </div>
                    `;
                    return;
                }
                
                // Group products by category
                const productsByCategory = {};
                products.forEach(product => {
                    const category = product.category_name || 'Uncategorized';
                    if (!productsByCategory[category]) {
                        productsByCategory[category] = [];
                    }
                    productsByCategory[category].push(product);
                });
                
                // Build HTML
                let html = '';
                let count = 0;
                const maxResults = 5;
                
                // Loop through categories
                for (const category in productsByCategory) {
                    if (count >= maxResults) break;
                    
                    // Category header
                    html += `
                        <div class="search-category-header">
                            <small class="text-tertiary">${category}</small>
                        </div>
                    `;
                    
                    // Products in this category
                    productsByCategory[category].forEach(product => {
                        if (count >= maxResults) return;
                        
                        // Safe access to properties with fallbacks
                        const name = product.name || 'Unnamed Product';
                        const image = product.image || '/images/placeholder.jpg';
                        const price = product.formatted_price || `$${product.price || 0}`;
                        const slug = product.slug || product.id || '#';
                        const inStock = product.quantity > 0;
                        
                        html += `
                            <a href="${baseUrl}/product/${slug}" class="text-decoration-none">
                                <div class="search-result-item d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="${image}" alt="${name}" class="me-3" width="40" height="40">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="product-title">${name}</div>
                                        <div class="product-price">${price}</div>
                                        <div class="product-availability ${inStock ? 'text-success' : 'text-danger'}">
                                            ${inStock ? 'In Stock' : 'Out of Stock'}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `;
                        count++;
                    });
                }
                
                // Add "view all" link if there are more results
                if (products.length > maxResults) {
                    const query = searchInput.value.trim();
                    const categoryId = categoryInput ? categoryInput.value : '';
                    
                    const params = new URLSearchParams();
                    params.append('q', query);
                    if (categoryId) {
                        params.append('category_id', categoryId);
                    }
                    
                    html += `
                        <a href="${baseUrl}/products/search?${params.toString()}" class="text-decoration-none">
                            <div class="search-result-item text-center">
                                <strong class="text-primary">View all results (${products.length})</strong>
                            </div>
                        </a>
                    `;
                }
                
                // Update DOM
                searchResults.innerHTML = html;
            }
            
            // Event listeners
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(doSearch, searchDelay);
            });
            
            // Listen for category changes
            document.addEventListener('categoryChanged', function(e) {
                if (searchInput.value.trim().length >= 2) {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(doSearch, searchDelay);
                }
            });
            
            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.remove('show');
                    searchResults.style.display = 'none';
                }
            });
        }
        
        // Initialize search suggestions
        initSearchSuggestions();
    });
    </script>
    
    <!-- Currency JS -->
    <script src="{{ asset('js/currency.js') }}"></script>
    
    <!-- Direct Theme Toggle Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get elements
            const toggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const themeLabel = document.getElementById('theme-label');
            const html = document.documentElement;
            
            // Theme keys
            const THEME_KEY = 'site_theme_preference';
            const DARK_CLASS = 'theme-dark';
            const LIGHT_CLASS = 'theme-light';
            
            // Initialize theme
            function initTheme() {
                // Get saved theme or use system preference
                const savedTheme = localStorage.getItem(THEME_KEY);
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const defaultTheme = prefersDark ? 'dark' : 'light';
                const currentTheme = savedTheme || defaultTheme;
                
                // Apply theme
                applyTheme(currentTheme);
                
                // Update UI
                updateUI(currentTheme);
            }
            
            // Apply theme to document
            function applyTheme(theme) {
                // Add transition class for smooth change
                html.classList.add('theme-transition');
                
                // Remove both theme classes
                html.classList.remove(DARK_CLASS, LIGHT_CLASS);
                
                // Add correct theme class
                html.classList.add(theme === 'dark' ? DARK_CLASS : LIGHT_CLASS);
                
                // Remove transition class after animation completes
                setTimeout(() => {
                    html.classList.remove('theme-transition');
                }, 300);
            }
            
            // Update UI elements
            function updateUI(theme) {
                const isDark = theme === 'dark';
                
                // Update toggle state
                if (toggle) toggle.checked = isDark;
                
                // Update icon and label
                if (themeIcon) {
                    // Clear existing content
                    themeIcon.innerHTML = '';
                    
                    // Create icon element
                    const iconElement = document.createElement('i');
                    iconElement.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
                    
                    // Add icon to container
                    themeIcon.appendChild(iconElement);
                }
                
                if (themeLabel) themeLabel.textContent = isDark ? 'Light Mode' : 'Dark Mode';
                
                // Save to localStorage
                localStorage.setItem(THEME_KEY, theme);
                
                // Try to sync with server if available
                syncWithServer(theme);
            }
            
            // Sync theme with server
            function syncWithServer(theme) {
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    if (csrfToken) {
                        const formData = new FormData();
                        formData.append('theme', theme);
                        formData.append('_token', csrfToken);
                        
                        fetch('/preferences/theme', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }).catch(() => {
                            // Silently fail - user still has theme in localStorage
                        });
                    }
                } catch(e) {
                    console.warn('Could not sync theme with server:', e);
                }
            }
            
            // Toggle theme
            function toggleTheme() {
                const isDark = html.classList.contains(DARK_CLASS);
                const newTheme = isDark ? 'light' : 'dark';
                
                applyTheme(newTheme);
                updateUI(newTheme);
            }
            
            // Add event listeners
            if (toggle) {
                toggle.addEventListener('change', toggleTheme);
            }
            
            const directToggle = document.getElementById('direct-theme-toggle');
            if (directToggle) {
                directToggle.addEventListener('click', function(e) {
                    // Don't trigger if clicking on the actual toggle input
                    if (e.target !== toggle) {
                        toggleTheme();
                        // Update checkbox state
                        toggle.checked = !toggle.checked;
                    }
                    
                    // Prevent dropdown from closing
                    e.stopPropagation();
                });
            }
            
            // Initialize
            initTheme();
        });
    </script>
</body>
</html>
