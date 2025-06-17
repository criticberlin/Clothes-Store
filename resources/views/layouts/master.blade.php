<!DOCTYPE html>
<html lang="en">
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

    <!-- Core Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
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
    </style>
</head>
<body>
    @include('layouts.menu')

    <div class="container my-5">
        <main class="fade-in">
            @yield('content')
        </main>
    </div>

    <footer class="mt-5 py-5 border-top border-surface">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-4 text-primary">MyClothes</h5>
                    <p class="text-secondary mb-4">Modern fashion for everyone. Discover the latest trends and timeless classics for all ages.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary hover-text-primary transition-normal"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-secondary hover-text-primary transition-normal"><i class="bi bi-twitter-x fs-5"></i></a>
                        <a href="#" class="text-secondary hover-text-primary transition-normal"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-secondary hover-text-primary transition-normal"><i class="bi bi-pinterest fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="mb-4">Shop</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Men</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Women</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Kids</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">New Arrivals</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="mb-4">Help</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Shipping</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Returns</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="mb-4">Stay Updated</h6>
                    <p class="text-secondary mb-4">Subscribe to get special offers, free giveaways, and new arrivals.</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email address" aria-label="Email address">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="mt-5 pt-4 border-top border-surface">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-tertiary mb-md-0">© {{ date('Y') }} MyClothes. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="#" class="text-tertiary small">Privacy Policy</a></li>
                            <li class="list-inline-item ms-3"><a href="#" class="text-tertiary small">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <button class="theme-toggle" id="themeToggle">
        <i class="bi bi-moon-stars"></i>
    </button>

    <script>
        // Add smooth scrolling
        document.addEventListener('DOMContentLoaded', function() {
            // Add scroll animation class to elements
            const elements = document.querySelectorAll('.card, .btn, .section-title, .product-item');
            let delay = 0;
            
            elements.forEach((el, i) => {
                el.style.setProperty('--animation-order', i);
                el.classList.add('page-transition');
            });

            // Navbar scroll effect
            const navbar = document.querySelector('.navbar-custom');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('shadow-lg');
                } else {
                    navbar.classList.remove('shadow-lg');
                }
            });
        });
    </script>
</body>
</html>
