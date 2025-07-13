<!DOCTYPE html>
<html lang="{{ $currentLocale ?? app()->getLocale() }}" dir="{{ isset($isRTL) && $isRTL ? 'rtl' : 'ltr' }}" class="theme-{{ $currentTheme ?? session('theme_mode', 'dark') }}" data-currency="{{ $currentCurrency }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MyClothes Admin Dashboard">
    <meta name="theme-color" content="#7F5AF0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyClothes Admin - @yield('title')</title>

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
    
    @if(isset($isRTL) && $isRTL)
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endif
    
    @stack('styles')
    
    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js" defer></script>

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
            cursor: pointer;
        }
        
        .sidebar-nav .nav-section-title:hover {
            color: var(--text-primary);
        }

        .sidebar-nav .collapse .nav-link {
            padding-left: 2.5rem;
        }

        .sidebar-nav .nav-section-title .bi-chevron-down {
            transition: transform 0.3s ease;
        }

        .sidebar-nav .nav-section-title[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
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
        
        /* Header Utilities */
        .header-utils {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .header-utils .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            padding: 0;
            border-radius: var(--radius-md);
            background-color: var(--surface-alt);
            border: 1px solid var(--border);
            color: var(--text-primary);
            transition: all var(--transition-normal);
        }
        
        .header-utils .btn:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        .header-utils .dropdown-toggle::after {
            display: none;
        }
        
        .header-utils .dropdown-menu {
            min-width: 200px;
            padding: 0.5rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background-color: var(--surface);
            box-shadow: var(--shadow-lg);
        }
        
        .header-utils .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
            color: var(--text-secondary);
        }
        
        .header-utils .dropdown-item:hover {
            background-color: var(--surface-alt);
            color: var(--text-primary);
        }
        
        .header-utils .dropdown-item.active {
            background-color: var(--primary);
            color: white;
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
    
    @stack('styles')
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
                
                <!-- User Management Section -->
                <div class="nav-section">
                    <div class="nav-section-title d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#usersCollapse" role="button" aria-expanded="false" aria-controls="usersCollapse">
                        <span>{{ __('User Management') }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </div>
                    <div class="collapse {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="usersCollapse">
                        <a href="{{ route('admin.users.list') }}" class="nav-link {{ request()->routeIs('admin.users.list') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="bi bi-people"></i></span>
                            <span>{{ __('All Users') }}</span>
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="bi bi-person-plus"></i></span>
                            <span>{{ __('Add User') }}</span>
                        </a>
                    </div>
                </div>
                
                <!-- Product Management Section -->
                <div class="nav-section">
                    <div class="nav-section-title d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#productsCollapse" role="button" aria-expanded="false" aria-controls="productsCollapse">
                        <span>{{ __('Products') }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </div>
                    <div class="collapse {{ (request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*')) ? 'show' : '' }}" id="productsCollapse">
                        <a href="{{ route('admin.products.list') }}" class="nav-link {{ request()->routeIs('admin.products.list') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="bi bi-grid"></i></span>
                            <span>{{ __('All Products') }}</span>
                        </a>
                        <a href="{{ route('admin.products.create') }}" class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="bi bi-plus-circle"></i></span>
                            <span>{{ __('Add Product') }}</span>
                        </a>
                    </div>
                </div>
                
                <!-- Categories Section -->
                <div class="nav-section">
                    <div class="nav-section-title d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#categoriesCollapse" role="button" aria-expanded="false" aria-controls="categoriesCollapse">
                        <span>{{ __('Categories') }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </div>
                    <div class="collapse {{ request()->routeIs('admin.categories.*') ? 'show' : '' }}" id="categoriesCollapse">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="bi bi-tags"></i></span>
                            <span>{{ __('All Categories') }}</span>
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="nav-link {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                            <span class="nav-icon"><i class="bi bi-plus-circle"></i></span>
                            <span>{{ __('Add Category') }}</span>
                        </a>
                    </div>
                </div>
                
                <!-- Orders Section -->
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Orders') }}</div>
                    <a href="{{ route('admin.orders.list') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-box"></i></span>
                        <span>{{ __('All Orders') }}</span>
                    </a>
                </div>
                
                <!-- Support Section -->
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Support') }}</div>
                    <a href="{{ route('admin.support.index') }}" class="nav-link {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-headset"></i></span>
                        <span>{{ __('Support Tickets') }}</span>
                    </a>
                </div>
                
                <!-- Settings Section -->
                <div class="nav-section">
                    <div class="nav-section-title">{{ __('Settings') }}</div>
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="bi bi-gear"></i></span>
                        <span>{{ __('Store Settings') }}</span>
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
                    <h1 class="mb-3">@yield('title', __('Dashboard'))</h1>
                    <p class="text-secondary fs-5 mb-0">@yield('description')</p>
                </div>
                <div class="header-utils">
                    <!-- Theme Switcher -->
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="themeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(session('theme_mode', 'dark') == 'dark')
                                <i class="bi bi-moon-stars-fill"></i>
                            @else
                                <i class="bi bi-sun-fill"></i>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="themeDropdown">
                            <li>
                                <form action="{{ route('preferences.theme') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="theme" value="light">
                                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                    <button type="submit" class="dropdown-item {{ session('theme_mode', 'dark') == 'light' ? 'active' : '' }}">
                                        <i class="bi bi-sun-fill me-2"></i> {{ __('general.light_mode') }}
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('preferences.theme') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="theme" value="dark">
                                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                    <button type="submit" class="dropdown-item {{ session('theme_mode', 'dark') == 'dark' ? 'active' : '' }}">
                                        <i class="bi bi-moon-stars-fill me-2"></i> {{ __('general.dark_mode') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Language Switcher -->
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(app()->getLocale() == 'ar')
                                <span class="fi fi-eg"></span>
                            @else
                                <span class="fi fi-gb"></span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li>
                                <form action="{{ route('preferences.language') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="language" value="en">
                                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                    <button type="submit" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                        <span class="fi fi-gb me-2"></span> English
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('preferences.language') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="language" value="ar">
                                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                    <button type="submit" class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                        <span class="fi fi-eg me-2"></span> العربية
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Currency Switcher -->
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                                $currency = App\Models\Currency::where('code', session('currency_code', 'EGP'))->first();
                                $symbol = $currency ? $currency->symbol : 'ج.م';
                            @endphp
                            {{ $symbol }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="currencyDropdown">
                            @foreach(App\Models\Currency::where('is_active', true)->get() as $currency)
                            <li>
                                <form action="{{ route('preferences.currency') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="currency_code" value="{{ $currency->code }}">
                                    <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                    <button type="submit" class="dropdown-item {{ session('currency_code', 'EGP') == $currency->code ? 'active' : '' }}">
                                        {{ $currency->symbol }} {{ $currency->code }} - {{ $currency->name }}
                                    </button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Logout Button -->
                    <a href="{{ route('do_logout') }}" class="btn" title="{{ __('Logout') }}">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
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
            
            // Initialize DataTables
            $('.admin-datatable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                language: {
                    search: "<i class='bi bi-search'></i> _INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "<i class='bi bi-chevron-double-left'></i>",
                        last: "<i class='bi bi-chevron-double-right'></i>",
                        next: "<i class='bi bi-chevron-right'></i>",
                        previous: "<i class='bi bi-chevron-left'></i>"
                    }
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html> 