<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <span class="brand-icon">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="32" height="32" class="d-inline-block align-middle">
            </span>
            <span class="ms-2">MyClothes</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="navbar-nav me-auto main-nav">
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('products.byCategory',['category' => 'men']) }}">
                        <span class="nav-icon"><i class="bi bi-gender-male"></i></span>
                        <span>Men</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('products.byCategory',['category' => 'women']) }}">
                        <span class="nav-icon"><i class="bi bi-gender-female"></i></span>
                        <span>Women</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('products.byCategory',['category' => 'kids']) }}">
                        <span class="nav-icon"><i class="bi bi-stars"></i></span>
                        <span>Kids & Baby</span>
                    </a>
                </div>
                
                @can('manage_products')
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('products.manage') }}">
                        <span class="nav-icon"><i class="bi bi-grid"></i></span>
                        <span>Manage Products</span>
                    </a>
                </div>
                @endcan
                
                @can('mange_Orders')
                <div class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('orders.admin') }}">
                        <span class="nav-icon"><i class="bi bi-box"></i></span>
                        <span>Manage Orders</span>
                    </a>
                </div>
                @endcan
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="search-wrapper me-2 position-relative d-none d-lg-block">
                    <form action="{{ route('products.list') }}" method="GET" class="search-form">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" class="form-control border-start-0 ps-0" placeholder="Search products..." name="query">
                        </div>
                    </form>
                </div>
                
                <div class="navbar-nav flex-row">
                    @auth
                        @can('Complaints')
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="supportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="nav-icon"><i class="bi bi-headset"></i></span>
                                <span class="d-none d-lg-inline-block ms-1">Support</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="supportDropdown">
                                @can('show_Complaints')
                                <li>
                                    <a class="dropdown-item" href="{{ route('support.list') }}">
                                        <i class="bi bi-ticket me-2"></i> My Complaints
                                    </a>
                                </li>
                                @endcan
                                @can('add_Complaints')
                                <li>
                                    <a class="dropdown-item" href="{{ route('support.add') }}">
                                        <i class="bi bi-plus-circle me-2"></i> New Complaint
                                    </a>
                                </li>
                                @endcan()
                                @can('admin_Complaints')
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.support.index') }}">
                                        <i class="bi bi-shield me-2"></i> Admin Panel
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                        @endcan
                        
                        <div class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar-circle">
                                    <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="d-none d-lg-inline-block ms-2">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile', ['user' => Auth::id()]) }}">
                                        <i class="bi bi-person me-2"></i> My Profile
                                    </a>
                                </li>
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('edit_password', ['user' => Auth::id()]) }}">
                                        <i class="bi bi-key me-2"></i> Change Password
                                    </a>
                                </li>
                                
                                @can('view_users')
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.list') }}">
                                        <i class="bi bi-people me-2"></i> All Users
                                    </a>
                                </li>
                                @endcan
                                
                                @can('add_users')
                                <li>
                                    <a class="dropdown-item" href="{{ route('users_create') }}">
                                        <i class="bi bi-person-plus me-2"></i> Add User
                                    </a>
                                </li>
                                @endcan
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('do_logout') }}">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="nav-item ms-2">
                            <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </div>
                        <div class="nav-item ms-2">
                            <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i> Register
                            </a>
                        </div>
                    @endauth
                    
                    <div class="nav-item ms-3">
                        <a class="nav-link position-relative cart-icon" href="{{ route('cart.index') }}">
                            <i class="bi bi-bag fs-5"></i>
                            <span class="cart-badge">0</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
