<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}" class="theme-{{ $currentTheme }}" data-currency="{{ $currentCurrency }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MyClothes - Modern fashion store for all your style needs">
    <meta name="theme-color" content="#7F5AF0">
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
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.list') }}">{{ __('Shop') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pages.about') ? 'active' : '' }}" href="{{ route('pages.about') }}">{{ __('About') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pages.contact') ? 'active' : '' }}" href="{{ route('pages.contact') }}">{{ __('Contact') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('3d-customizer') ? 'active' : '' }}" href="{{ route('3d-customizer') }}">
                            <i class="bi bi-palette-fill me-1"></i>{{ __('3D Customizer') }}
                            <span class="badge bg-primary ms-1">New</span>
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    @include('partials.theme_switcher')
                    @include('partials.language_switcher')
                    
                    <div class="ms-2">
                        @auth
                        <div class="dropdown">
                            <a href="#" class="btn btn-sm btn-primary dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile', Auth::id()) }}"><i class="bi bi-person me-1"></i> {{ __('My Profile') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag me-1"></i> {{ __('My Orders') }}</a></li>
                                @can('admin_dashboard')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i> {{ __('Admin Dashboard') }}</a></li>
                                @endcan
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('do_logout') }}"><i class="bi bi-box-arrow-right me-1"></i> {{ __('Logout') }}</a></li>
                            </ul>
                        </div>
                        @else
                        <div class="d-flex">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-2">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}" class="btn btn-sm btn-primary">{{ __('Register') }}</a>
                        </div>
                        @endauth
                    </div>
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-link position-relative ms-2">
                        <i class="bi bi-cart text-primary fs-5"></i>
                        @if(Auth::check() && Auth::user()->cart && Auth::user()->cart->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ Auth::user()->cart->count() }}
                            </span>
                        @endif
                    </a>
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
                    <p>{{ __('Your one-stop destination for premium fashion.') }}</p>
                    <div class="social-links mt-3">
                        <a href="#" class="me-2 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="me-2 fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="me-2 fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-pinterest"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="mb-4">{{ __('Shop') }}</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('products.byCategory', 'men') }}" class="text-reset">{{ __('Men') }}</a></li>
                        <li class="mb-2"><a href="{{ route('products.byCategory', 'women') }}" class="text-reset">{{ __('Women') }}</a></li>
                        <li class="mb-2"><a href="{{ route('products.byCategory', 'kids') }}" class="text-reset">{{ __('Kids') }}</a></li>
                        <li class="mb-2"><a href="{{ route('3d-customizer') }}" class="text-reset">{{ __('3D Customizer') }} <span class="badge bg-primary">New</span></a></li>
                        <li><a href="{{ route('products.list') }}" class="text-reset">{{ __('All Products') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="mb-4">{{ __('Company') }}</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('pages.about') }}" class="text-reset">{{ __('About Us') }}</a></li>
                        <li class="mb-2"><a href="{{ route('pages.contact') }}" class="text-reset">{{ __('Contact') }}</a></li>
                        <li class="mb-2"><a href="{{ route('pages.faq') }}" class="text-reset">{{ __('FAQ') }}</a></li>
                        <li><a href="#" class="text-reset">{{ __('Careers') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="mb-4">{{ __('Newsletter') }}</h6>
                    <p>{{ __('Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals.') }}</p>
                    <form class="newsletter-form mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="{{ __('Enter your email') }}">
                            <button class="btn btn-primary" type="button">{{ __('Subscribe') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <hr>
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <p class="mb-0">© {{ date('Y') }} MyClothes. {{ __('All rights reserved.') }}</p>
                        <div class="d-flex mt-2 mt-sm-0">
                            <a href="{{ route('pages.privacy') }}" class="text-reset me-3">{{ __('Privacy Policy') }}</a>
                            <a href="{{ route('pages.terms') }}" class="text-reset">{{ __('Terms of Service') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>
