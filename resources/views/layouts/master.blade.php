<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}" class="theme-{{ $currentTheme }}" data-currency="{{ $currentCurrency }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MyClothes - Modern fashion store for all your style needs">
    <meta name="theme-color" content="#7F5AF0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script src="{{ asset('js/custom.js') }}" defer></script>

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

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            margin-top: 0.5rem;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1050;
            box-shadow: var(--shadow-md);
            display: none;
            padding: 0.5rem 0;
        }

        .search-results.show {
            display: block;
            animation: fadeInDown 0.3s ease-out forwards;
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
        
        /* Profile Dropdown */
        .profile-dropdown {
            border-radius: var(--radius-md);
            overflow: hidden;
        }
        
        .profile-dropdown .dropdown-item {
            border-radius: 0;
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

        /* Category Dropdown */
        .category-dropdown {
            position: relative;
            height: 46px;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg) 0 0 var(--radius-lg);
            border-right: none;
            min-width: 80px;
            cursor: pointer;
            z-index: 9999 !important; /* Extremely high z-index to ensure visibility */
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
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            z-index: 9999 !important;
            width: 100% !important;
            padding: 5px 0;
            background-color: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
            box-shadow: var(--shadow-md);
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            display: none;
        }

        .category-dropdown.open .dropdown-menu {
            display: block !important;
        }

        .dropdown-item {
            padding: 8px 15px;
            white-space: nowrap;
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background-color: var(--surface-alt);
            color: var(--primary);
        }

        /* Theme-specific styles */
        :root.theme-light .category-dropdown .dropdown-menu {
            background-color: var(--surface);
            border-color: var(--border);
        }

        :root.theme-dark .category-dropdown .dropdown-menu {
            background-color: var(--surface);
            border-color: var(--border);
        }

        /* Fix search bar integration with dropdown */
        .search-input-group {
            position: relative;
            z-index: 100;
        }

        /* Create smooth transition between dropdown and search */
        .category-dropdown.open + .search-input {
            border-left: none;
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
                                <!-- Dropdown -->
                                <div class="category-dropdown" id="categoryDropdown">
                                    <div class="dropdown-header">
                                        <span id="selectedCategory">
                                            @if(request()->has('category_id') && request('category_id') != '')
                                                {{ App\Models\Category::find(request('category_id'))->name ?? 'All' }}
                                            @else
                                                All
                                            @endif
                                        </span>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-item {{ !request()->has('category_id') || request('category_id') == '' ? 'active' : '' }}" data-value="">All</div>
                                        @foreach(App\Models\Category::all() as $category)
                                            <div class="dropdown-item {{ request('category_id') == $category->id ? 'active' : '' }}" data-value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </div>
                                        @endforeach
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
                        <div class="search-results" id="searchResults"></div>
                    </div>
                    
                    <!-- Theme, Language, Profile, Cart -->
                    <div class="d-flex align-items-center">
                        <!-- Compact Theme Switcher -->
                        <button class="btn header-icon-btn" id="themeToggle">
                            <i class="bi bi-circle-half"></i>
                        </button>
                        
                        <!-- Compact Language Switcher -->
                        <div class="dropdown ms-2">
                            <button class="btn header-icon-btn dropdown-toggle-no-arrow" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="{{ $currentLocale == 'en' ? 'fi fi-us' : 'fi fi-eg' }}"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                                <li>
                                    <a class="dropdown-item {{ $currentLocale == 'en' ? 'active' : '' }}" href="{{ url('locale/en') }}">
                                        <span class="fi fi-us me-2"></span> English
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ $currentLocale == 'ar' ? 'active' : '' }}" href="{{ url('locale/ar') }}">
                                        <span class="fi fi-eg me-2"></span> العربية
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="ms-2">
                            @auth
                            <div class="dropdown">
                                <a href="#" class="header-icon-btn" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                                <ul class="dropdown-menu dropdown-menu-end profile-dropdown" aria-labelledby="userDropdown">
                                    <li class="dropdown-header">{{ Auth::user()->name }}</li>
                                    <li><a class="dropdown-item" href="{{ route('profile', Auth::id()) }}"><i class="bi bi-person me-1"></i> {{ __('general.my_profile') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag me-1"></i> {{ __('general.my_orders') }}</a></li>
                                    @can('admin_dashboard')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> {{ __('general.admin_dashboard') }}</a></li>
                                    @endcan
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('do_logout') }}"><i class="bi bi-box-arrow-right me-1"></i> {{ __('general.logout') }}</a></li>
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
        // Live search functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggle functionality
            const themeToggle = document.getElementById('themeToggle');
            if(themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const html = document.documentElement;
                    const currentTheme = html.classList.contains('theme-light') ? 'light' : 'dark';
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    html.classList.remove(`theme-${currentTheme}`);
                    html.classList.add(`theme-${newTheme}`);
                    
                    // Save theme preference
                    fetch('/theme/switch/' + newTheme, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    // Update icon
                    const icon = themeToggle.querySelector('i');
                    if(newTheme === 'light') {
                        icon.classList.remove('bi-moon-fill');
                        icon.classList.add('bi-sun-fill');
                    } else {
                        icon.classList.remove('bi-sun-fill');
                        icon.classList.add('bi-moon-fill');
                    }
                });
                
                // Set initial icon based on current theme
                const currentTheme = document.documentElement.classList.contains('theme-light') ? 'light' : 'dark';
                const icon = themeToggle.querySelector('i');
                if(currentTheme === 'light') {
                    icon.classList.remove('bi-circle-half');
                    icon.classList.add('bi-sun-fill');
                } else {
                    icon.classList.remove('bi-circle-half');
                    icon.classList.add('bi-moon-fill');
                }
            }
            
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const categorySelect = document.querySelector('.search-category-select');
            
            if(searchInput && searchResults) {
                let typingTimer;
                const doneTypingInterval = 300; // Wait 300ms after user stops typing
                let currentProductId = null; // Store the current product ID if on a product page
                
                // Check if we're on a product page
                const productIdMeta = document.querySelector('meta[name="product-id"]');
                if (productIdMeta) {
                    currentProductId = productIdMeta.getAttribute('content');
                }
                
                // Function to handle search
                const performSearch = function() {
                    const searchTerm = searchInput.value.trim();
                    const categoryId = categorySelect ? categorySelect.value : '';
                    
                    if(searchTerm.length < 2) {
                        searchResults.classList.remove('show');
                        return;
                    }
                    
                    // Show loading indicator
                    searchResults.innerHTML = `
                        <div class="search-result-item text-center">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="ms-2">{{ __('general.searching') }}...</span>
                        </div>
                    `;
                    searchResults.classList.add('show');
                    
                    // Build API URL with parameters
                    let apiUrl = `/api/search?q=${encodeURIComponent(searchTerm)}`;
                    
                    if (categoryId) {
                        apiUrl += `&category_id=${categoryId}`;
                    }
                    
                    if (currentProductId) {
                        apiUrl += `&product_id=${currentProductId}`;
                    }
                    
                    // Send AJAX request to search products
                    fetch(apiUrl)
                        .then(response => response.json())
                        .then(data => {
                            let html = '';
                            
                            if(data.products.length > 0) {
                                // Group products by category for better organization
                                const productsByCategory = {};
                                
                                data.products.forEach(product => {
                                    const category = product.category_name || 'Uncategorized';
                                    if (!productsByCategory[category]) {
                                        productsByCategory[category] = [];
                                    }
                                    productsByCategory[category].push(product);
                                });
                                
                                // Show only first 5 results
                                let count = 0;
                                const maxResults = 5;
                                
                                // Display products grouped by category
                                for (const category in productsByCategory) {
                                    if (count < maxResults) {
                                        // Add category header
                                        html += `
                                            <div class="search-category-header">
                                                <small class="text-tertiary">${category}</small>
                                            </div>
                                        `;
                                        
                                        // Add products in this category
                                        productsByCategory[category].forEach(product => {
                                            if (count < maxResults) {
                                                html += `
                                                    <a href="/product/${product.slug}" class="text-decoration-none">
                                                        <div class="search-result-item d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img src="${product.image}" alt="${product.name}" class="me-3">
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="product-title">${product.name}</div>
                                                                <div class="product-price">${product.formatted_price}</div>
                                                                <div class="product-availability ${product.quantity > 0 ? 'text-success' : 'text-danger'}">
                                                                    ${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                `;
                                                count++;
                                            }
                                        });
                                    }
                                }
                                
                                // Add related products section if available
                                if (data.related && data.related.length > 0 && currentProductId) {
                                    html += `
                                        <div class="search-category-header mt-2">
                                            <small class="text-tertiary">{{ __('general.related_products') }}</small>
                                        </div>
                                    `;
                                    
                                    data.related.forEach(product => {
                                        html += `
                                            <a href="/product/${product.slug}" class="text-decoration-none">
                                                <div class="search-result-item d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="${product.image}" alt="${product.name}" class="me-3">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="product-title">${product.name}</div>
                                                        <div class="product-price">${product.formatted_price}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        `;
                                    });
                                }
                                
                                // Add "View all results" link with search parameters
                                if(data.products.length > maxResults) {
                                    const searchParams = new URLSearchParams();
                                    searchParams.append('q', searchTerm);
                                    if (categoryId) {
                                        searchParams.append('category_id', categoryId);
                                    }
                                    
                                    html += `
                                        <a href="/products/search?${searchParams.toString()}" class="text-decoration-none">
                                            <div class="search-result-item text-center">
                                                <strong class="text-primary">{{ __('general.view_all_results') }} (${data.products.length})</strong>
                                            </div>
                                        </a>
                                    `;
                                }
                            } else {
                                html = `
                                    <div class="search-result-item text-center">
                                        {{ __('general.no_results') }}
                                    </div>
                                `;
                            }
                            
                            searchResults.innerHTML = html;
                            searchResults.classList.add('show');
                        })
                        .catch(error => {
                            console.error('Error searching products:', error);
                            searchResults.innerHTML = `
                                <div class="search-result-item text-center text-danger">
                                    {{ __('general.search_error') }}
                                </div>
                            `;
                        });
                };
                
                // Search on typing
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(performSearch, doneTypingInterval);
                });
                
                // Search when category changes
                if (categorySelect) {
                    categorySelect.addEventListener('change', function() {
                        if (searchInput.value.trim().length >= 2) {
                            clearTimeout(typingTimer);
                            typingTimer = setTimeout(performSearch, doneTypingInterval);
                        }
                    });
                }
                
                // Handle keyboard navigation in search results
                searchInput.addEventListener('keydown', function(e) {
                    const results = searchResults.querySelectorAll('a');
                    
                    if (!results.length || !searchResults.classList.contains('show')) {
                        return;
                    }
                    
                    // Down arrow
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        let focusedItem = searchResults.querySelector('a:focus');
                        if (!focusedItem) {
                            results[0].focus();
                        } else {
                            const currentIndex = Array.from(results).indexOf(focusedItem);
                            if (currentIndex < results.length - 1) {
                                results[currentIndex + 1].focus();
                            }
                        }
                    }
                    
                    // Up arrow
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        let focusedItem = searchResults.querySelector('a:focus');
                        if (focusedItem) {
                            const currentIndex = Array.from(results).indexOf(focusedItem);
                            if (currentIndex > 0) {
                                results[currentIndex - 1].focus();
                            } else {
                                searchInput.focus();
                            }
                        }
                    }
                    
                    // Enter key
                    if (e.key === 'Enter') {
                        let focusedItem = searchResults.querySelector('a:focus');
                        if (focusedItem) {
                            e.preventDefault();
                            focusedItem.click();
                        }
                    }
                    
                    // Escape key
                    if (e.key === 'Escape') {
                        searchResults.classList.remove('show');
                        searchInput.blur();
                    }
                });
                
                // Hide search results when clicking outside
                document.addEventListener('click', function(event) {
                    if(!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                        searchResults.classList.remove('show');
                    }
                });
                
                // Keep search results open when clicking inside
                searchResults.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            }
        });
    </script>
    
    @stack('scripts')
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dropdown
        const dropdown = document.getElementById('categoryDropdown');
        const header = dropdown ? dropdown.querySelector('.dropdown-header') : null;
        const items = dropdown ? dropdown.querySelectorAll('.dropdown-item') : [];
        const input = document.getElementById('categoryInput');
        const selectedCategory = document.getElementById('selectedCategory');
        
        if (dropdown && header && items.length && input && selectedCategory) {
            console.log('Dropdown elements found');
            
            // Force close dropdown initially
            dropdown.classList.remove('open');
            
            // Toggle dropdown with direct DOM manipulation
            header.addEventListener('click', function(e) {
                console.log('Dropdown header clicked');
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle the open class
                const isOpen = dropdown.classList.contains('open');
                dropdown.classList.toggle('open');
                
                // Get dropdown menu
                const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                if (!dropdownMenu) return;
                
                // Force display style
                if (!isOpen) {
                    // Opening the dropdown
                    dropdownMenu.style.display = 'block';
                    dropdownMenu.style.visibility = 'visible';
                    dropdownMenu.style.opacity = '1';
                } else {
                    // Closing the dropdown
                    dropdownMenu.style.display = 'none';
                    dropdownMenu.style.visibility = 'hidden';
                    dropdownMenu.style.opacity = '0';
                }
            });
            
            // Select item
            items.forEach(item => {
                item.addEventListener('click', function(e) {
                    console.log('Item clicked:', this.textContent);
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Update display
                    selectedCategory.textContent = this.textContent.trim();
                    
                    // Update input
                    input.value = this.dataset.value;
                    
                    // Update active class
                    items.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Close dropdown
                    dropdown.classList.remove('open');
                    const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                    if (dropdownMenu) {
                        dropdownMenu.style.display = 'none';
                        dropdownMenu.style.visibility = 'hidden';
                    }
                    
                    // Dispatch custom event
                    const event = new CustomEvent('categoryChanged', {
                        bubbles: true,
                        detail: { value: this.dataset.value }
                    });
                    document.dispatchEvent(event);
                    
                    // Trigger search if needed
                    const searchInput = document.getElementById('searchInput');
                    if (searchInput && searchInput.value.trim().length >= 2) {
                        // Manually trigger search
                        const searchEvent = new Event('input');
                        searchInput.dispatchEvent(searchEvent);
                    }
                });
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (dropdown.classList.contains('open') && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                    const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                    if (dropdownMenu) {
                        dropdownMenu.style.display = 'none';
                        dropdownMenu.style.visibility = 'hidden';
                    }
                }
            });
        } else {
            console.error('Dropdown elements not found:', {
                dropdown: !!dropdown,
                header: !!header,
                items: items.length,
                input: !!input,
                selectedCategory: !!selectedCategory
            });
        }

        // Initialize search functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        
        if(searchInput && searchResults) {
            let typingTimer;
            const doneTypingInterval = 300; // Wait 300ms after user stops typing
            let currentProductId = null; // Store the current product ID if on a product page
            
            // Check if we're on a product page
            const productIdMeta = document.querySelector('meta[name="product-id"]');
            if (productIdMeta) {
                currentProductId = productIdMeta.getAttribute('content');
            }
            
            // Function to handle search
            const performSearch = function() {
                const searchTerm = searchInput.value.trim();
                const categoryId = input ? input.value : ''; // Use the hidden input for category
                
                if(searchTerm.length < 2) {
                    searchResults.classList.remove('show');
                    return;
                }
                
                console.log('Performing search for:', searchTerm, 'Category:', categoryId);
                
                // Show loading indicator
                searchResults.innerHTML = `
                    <div class="search-result-item text-center">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Searching...</span>
                    </div>
                `;
                searchResults.classList.add('show');
                
                // Build API URL with parameters
                let apiUrl = `/api/search?q=${encodeURIComponent(searchTerm)}`;
                
                if (categoryId) {
                    apiUrl += `&category_id=${categoryId}`;
                }
                
                if (currentProductId) {
                    apiUrl += `&product_id=${currentProductId}`;
                }
                
                // Simplified fetch without headers that might cause issues
                fetch(apiUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Search results:', data);
                        
                        let html = '';
                        
                        if(data.products && data.products.length > 0) {
                            // Group products by category for better organization
                            const productsByCategory = {};
                            
                            data.products.forEach(product => {
                                const category = product.category_name || 'Uncategorized';
                                if (!productsByCategory[category]) {
                                    productsByCategory[category] = [];
                                }
                                productsByCategory[category].push(product);
                            });
                            
                            // Show only first 5 results
                            let count = 0;
                            const maxResults = 5;
                            
                            // Display products grouped by category
                            for (const category in productsByCategory) {
                                if (count < maxResults) {
                                    // Add category header
                                    html += `
                                        <div class="search-category-header">
                                            <small class="text-tertiary">${category}</small>
                                        </div>
                                    `;
                                    
                                    // Add products in this category
                                    productsByCategory[category].forEach(product => {
                                        if (count < maxResults) {
                                            // Use default image if none provided
                                            const imageUrl = product.image || '/images/placeholder.jpg';
                                            // Use formatted price or fallback to raw price
                                            const price = product.formatted_price || `$${product.price}`;
                                            
                                            html += `
                                                <a href="/product/${product.slug || product.id}" class="text-decoration-none">
                                                    <div class="search-result-item d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <img src="${imageUrl}" alt="${product.name}" class="me-3" width="40" height="40">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="product-title">${product.name}</div>
                                                            <div class="product-price">${price}</div>
                                                            <div class="product-availability ${product.quantity > 0 ? 'text-success' : 'text-danger'}">
                                                                ${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            `;
                                            count++;
                                        }
                                    });
                                }
                            }
                            
                            // Add "View all results" link with search parameters
                            if(data.products.length > maxResults) {
                                const searchParams = new URLSearchParams();
                                searchParams.append('q', searchTerm);
                                if (categoryId) {
                                    searchParams.append('category_id', categoryId);
                                }
                                
                                html += `
                                    <a href="/products/search?${searchParams.toString()}" class="text-decoration-none">
                                        <div class="search-result-item text-center">
                                            <strong class="text-primary">View all results (${data.products.length})</strong>
                                        </div>
                                    </a>
                                `;
                            }
                        } else {
                            html = `
                                <div class="search-result-item text-center">
                                    No results found
                                </div>
                            `;
                        }
                        
                        searchResults.innerHTML = html;
                        searchResults.classList.add('show');
                    })
                    .catch(error => {
                        console.error('Error searching products:', error);
                        searchResults.innerHTML = `
                            <div class="search-result-item text-center text-danger">
                                Search error. Please try again.
                            </div>
                        `;
                    });
            };
            
            // Search on typing
            searchInput.addEventListener('input', function() {
                console.log('Search input changed');
                clearTimeout(typingTimer);
                typingTimer = setTimeout(performSearch, doneTypingInterval);
            });
            
            // Listen for category changes
            document.addEventListener('categoryChanged', function(e) {
                console.log('Category changed event received:', e.detail.value);
                if (searchInput.value.trim().length >= 2) {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(performSearch, doneTypingInterval);
                }
            });
            
            // Hide search results when clicking outside
            document.addEventListener('click', function(event) {
                if(!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
                    searchResults.classList.remove('show');
                }
            });
        }
    });
    </script>
</body>
</html>
