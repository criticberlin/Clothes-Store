<!DOCTYPE html>
<html lang="{{ $currentLocale ?? app()->getLocale() }}" dir="{{ isset($isRTL) && $isRTL ? 'rtl' : 'ltr' }}" class="theme-{{ $currentTheme ?? session('theme_mode', 'dark') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MyClothes Admin Dashboard">
    <meta name="theme-color" content="#7F5AF0">
    <title>MyClothes Admin - @yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Core Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @if(isset($isRTL) && $isRTL)
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endif
    
    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>

    <style>
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
        
        body {
            transition: background-color var(--transition-normal), 
                        color var(--transition-normal);
        }
        
        /* RTL Support for Admin */
        html[dir="rtl"] .admin-sidebar {
            left: auto;
            right: 0;
            border-right: none;
            border-left: 1px solid var(--border);
        }
        
        html[dir="rtl"] .admin-content {
            margin-left: 0;
            margin-right: 280px;
        }
        
        html[dir="rtl"] .sidebar-nav .nav-link {
            border-left: none;
            border-right: 3px solid transparent;
        }
        
        html[dir="rtl"] .sidebar-nav .nav-link.active {
            border-left: none;
            border-right: 3px solid var(--primary);
        }
        
        html[dir="rtl"] .sidebar-nav .nav-link .nav-icon {
            margin-right: 0;
            margin-left: 0.75rem;
        }

        :root {
            /* Modern Color Palette 2025 */
            --primary: #7F5AF0;
            --primary-light: #A78BFA;
            --primary-dark: #6D28D9;
            --secondary: #2CB67D;
            --secondary-light: #4ADE80;
            --accent: #FF7F50;
            
            /* Neutral Colors */
            --bg-primary: #16161A;
            --bg-secondary: #242629;
            --surface: #2D2D33;
            --surface-alt: #383841;
            
            /* Text Colors */
            --text-primary: #FFFFFE;
            --text-secondary: #94A1B2;
            --text-tertiary: #72757E;
            
            /* UI Colors */
            --border: #383841;
            --focus-ring: rgba(127, 90, 240, 0.5);
            --overlay: rgba(22, 22, 26, 0.8);
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        /* Admin Sidebar */
        .admin-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .admin-sidebar {
            width: 280px;
            background-color: var(--surface);
            border-right: 1px solid var(--border);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-header h3 {
            margin: 0;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-tertiary);
            font-weight: 600;
        }

        .sidebar-nav .nav-link {
            padding: 0.75rem 1.5rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover {
            background-color: var(--surface-alt);
            color: var(--text-primary);
        }

        .sidebar-nav .nav-link.active {
            background-color: var(--surface-alt);
            color: var(--primary-light);
            border-left: 3px solid var(--primary);
        }

        .sidebar-nav .nav-icon {
            font-size: 1.25rem;
            width: 1.5rem;
            text-align: center;
        }

        /* Admin Content */
        .admin-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            overflow-y: auto;
            max-height: 100vh;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .admin-header h1 {
            margin: 0;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
        }

        .admin-header .breadcrumb {
            margin: 0;
            background-color: transparent;
        }

        .admin-card {
            background-color: var(--surface);
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }

        .admin-card-header {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-card {
            background-color: var(--surface);
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-light);
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-icon.purple {
            background-color: rgba(127, 90, 240, 0.2);
            color: var(--primary-light);
        }

        .stats-icon.green {
            background-color: rgba(44, 182, 125, 0.2);
            color: var(--secondary-light);
        }

        .stats-icon.orange {
            background-color: rgba(255, 127, 80, 0.2);
            color: var(--accent);
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stats-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <i class="bi bi-bag-heart-fill text-primary fs-4"></i>
                <h3>{{ __('MyClothes Admin') }}</h3>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Dashboard') }}</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
                        <span>{{ __('Overview') }}</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('User Management') }}</div>
                    <a href="{{ route('admin.users.list') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-people"></i></span>
                        <span>{{ __('All Users') }}</span>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-person-plus"></i></span>
                        <span>{{ __('Add User') }}</span>
                    </a>
                    <a href="{{ route('admin.users.list') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-person-gear"></i></span>
                        <span>{{ __('Roles & Permissions') }}</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Products') }}</div>
                    <a href="{{ route('admin.products.list') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-grid"></i></span>
                        <span>{{ __('All Products') }}</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-plus-circle"></i></span>
                        <span>{{ __('Add Product') }}</span>
                    </a>
                    <a href="{{ route('admin.products.list') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-tags"></i></span>
                        <span>{{ __('Categories') }}</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Orders') }}</div>
                    <a href="{{ route('admin.orders.list') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-box"></i></span>
                        <span>{{ __('All Orders') }}</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Support') }}</div>
                    <a href="{{ route('admin.support.index') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-headset"></i></span>
                        <span>{{ __('Support Tickets') }}</span>
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Settings') }}</div>
                    <a href="{{ route('admin.settings') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-gear"></i></span>
                        <span>{{ __('Store Settings') }}</span>
                    </a>
                    <a href="{{ route('admin.currencies') }}" class="nav-link {{ request()->routeIs('admin.currencies') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-currency-exchange"></i></span>
                        <span>{{ __('Currencies') }}</span>
                    </a>
                </div>
                
                <div class="nav-section mt-4">
                    <a href="{{ route('home') }}" class="nav-link">
                        <span class="nav-icon"><i class="bi bi-house"></i></span>
                        <span>{{ __('Back to Store') }}</span>
                    </a>
                    <a href="{{ route('do_logout') }}" class="nav-link text-danger">
                        <span class="nav-icon"><i class="bi bi-box-arrow-right"></i></span>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-content">
            <div class="admin-header">
                <div>
                    <h1>@yield('title', __('Dashboard'))</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Admin') }}</a></li>
                            @yield('breadcrumbs')
                        </ol>
                    </nav>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Theme Switcher -->
                    <div class="theme-switcher">
                        <form action="{{ route('preferences.theme') }}" method="POST">
                            @csrf
                            <input type="hidden" name="theme" value="{{ session('theme_mode', 'dark') == 'dark' ? 'light' : 'dark' }}">
                            <input type="hidden" name="redirect" value="{{ url()->current() }}">
                            <button type="submit" class="theme-toggle-btn border-0 bg-transparent">
                                @if(session('theme_mode', 'dark') == 'dark')
                                <i class="bi bi-sun-fill"></i>
                                @else
                                <i class="bi bi-moon-stars-fill"></i>
                                @endif
                            </button>
                        </form>
                    </div>
                    
                    <!-- Language Switcher -->
                    <div class="language-switcher mx-3">
                        <div class="dropdown">
                            <button class="theme-toggle-btn dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ strtoupper(app()->getLocale()) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                                <li>
                                    <form action="{{ route('preferences.language') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="language" value="en">
                                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                        <button type="submit" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">English</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('preferences.language') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="language" value="ar">
                                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                        <button type="submit" class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">العربية</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Currency Switcher -->
                    <div class="currency-switcher">
                        <div class="dropdown">
                            <button class="theme-toggle-btn dropdown-toggle" type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ session('currency_code', 'EGP') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="currencyDropdown">
                                @foreach(App\Models\Currency::where('is_active', true)->get() as $currency)
                                <li>
                                    <form action="{{ route('preferences.currency') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="currency_code" value="{{ $currency->code }}">
                                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                        <button type="submit" class="dropdown-item {{ session('currency_code', 'EGP') == $currency->code ? 'active' : '' }}">
                                            {{ $currency->code }} - {{ $currency->name }}
                                        </button>
                                    </form>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="dropdown ms-3">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="{{ route('profile', Auth::id()) }}"><i class="bi bi-person me-2"></i> {{ __('My Profile') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('do_logout') }}"><i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const adminSidebar = document.querySelector('.admin-sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    adminSidebar.classList.toggle('show');
                });
            }
        });
    </script>
</body>
</html> 