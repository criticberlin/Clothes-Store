@extends('layouts.master')
@section('title', "Home")

@section('content')
<!-- Hero Section -->
<section class="hero mt-4 mb-5">
    <div class="container">
        <div class="hero-wrapper position-relative overflow-hidden rounded-4">
            <div class="row g-0 align-items-center">
                <div class="col-lg-6 p-5 p-lg-6">
                    <h1 class="display-4 fw-bold mb-4 text-gradient">Discover Your Style <span class="text-nowrap">in 2025</span></h1>
                    <p class="lead text-secondary mb-4">Explore our curated collection of sustainable fashion with cutting-edge designs and premium quality.</p>
                    <div class="d-flex gap-3 mb-4">
                        <a href="{{ route('products.category') }}" class="btn btn-primary">
                            <span>Shop Now</span>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#collections" class="btn btn-outline-light">Collections</a>
                    </div>
                    <div class="hero-stats d-flex gap-4">
                        <div class="stat-item">
                            <div class="h3 m-0">10k+</div>
                            <div class="text-secondary small">Products</div>
                        </div>
                        <div class="stat-item">
                            <div class="h3 m-0">97%</div>
                            <div class="text-secondary small">Happy Customers</div>
                        </div>
                        <div class="stat-item">
                            <div class="h3 m-0">24/7</div>
                            <div class="text-secondary small">Support</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block hero-image-container">
                    <img src="{{ asset('images/hero.jpg') }}" alt="Fashion Model" class="hero-image img-fluid">
                </div>
            </div>
            <div class="hero-shape"></div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="collections" class="my-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h6 class="text-primary text-uppercase fw-bold tracking-wide mb-2">Collections</h6>
            <h2 class="display-6 fw-bold">Shop by Category</h2>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="category-card">
                    <img src="{{ asset('images/men.jpg') }}" alt="Men's Collection">
                    <div class="overlay">
                        <h3 class="h2 text-white mb-3">Men</h3>
                        <p class="text-light mb-4">Casual to formal, redefine your style with our premium collection.</p>
                        <a href="{{ route('products.byCategory',['category' => 'men']) }}" class="btn btn-outline-light">
                            Explore <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <img src="{{ asset('images/women.jpg') }}" alt="Women's Collection">
                    <div class="overlay">
                        <h3 class="h2 text-white mb-3">Women</h3>
                        <p class="text-light mb-4">Elegant and trendy designs for the modern woman.</p>
                        <a href="{{ route('products.byCategory',['category' => 'women']) }}" class="btn btn-outline-light">
                            Explore <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <img src="{{ asset('images/kids.jpg') }}" alt="Kid's Collection">
                    <div class="overlay">
                        <h3 class="h2 text-white mb-3">Kids & Baby</h3>
                        <p class="text-light mb-4">Comfortable and colorful styles for your little ones.</p>
                        <a href="{{ route('products.byCategory',['category' => 'kids']) }}" class="btn btn-outline-light">
                            Explore <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3D Customizer Section -->
<section class="my-5 py-2">
    <div class="container">
        <div class="customizer-card rounded-4 position-relative overflow-hidden">
            <div class="customizer-ribbon">
                <span>NEW</span>
            </div>
            <div class="customizer-content p-4 py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7 position-relative z-2">
                        <div class="d-flex align-items-center mb-3">
                            <div class="customizer-tag">Design Your Own</div>
                        </div>
                        <h2 class="display-6 fw-bold mb-4">Customize Your <span class="text-gradient">Fashion</span> in 3D</h2>
                        <p class="mb-4 lead">Create clothing that's uniquely yours with our interactive 3D designer. Express yourself like never before.</p>
                        
                        <div class="features-grid">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="bi bi-palette2"></i>
                                </div>
                                <div class="feature-text">
                                    <strong>Unlimited Colors</strong>
                                    <span>Any shade you can imagine</span>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="bi bi-image"></i>
                                </div>
                                <div class="feature-text">
                                    <strong>Custom Uploads</strong>
                                    <span>Add your photos & logos</span>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="bi bi-type"></i>
                                </div>
                                <div class="feature-text">
                                    <strong>Texts & Shapes</strong>
                                    <span>Add custom elements & designs</span>
                                </div>
                            </div>
                            <div class="feature-item highlight">
                                <div class="feature-icon">
                                    <i class="bi bi-stars"></i>
                                </div>
                                <div class="feature-text">
                                    <strong>AI Art Generator</strong>
                                    <span>Create unique designs with AI</span>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="bi bi-eye"></i>
                                </div>
                                <div class="feature-text">
                                    <strong>Live Preview</strong>
                                    <span>See changes in real-time</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <a href="{{ route('3d-customizer') }}" class="customizer-btn">
                                <span>Try the 3D Customizer</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block position-relative">
                        <div class="customizer-image-wrapper">
                            <div class="customizer-decoration"></div>
                            <div class="customizer-image-container">
                                <img src="{{ asset('images/3d-customizer.jpg') }}" alt="3D Customizer" class="customizer-image">
                            </div>
                            <div class="customizer-dots"></div>
                            <div class="tshirt-icon">
                                <i class="bi bi-tshirt"></i>
                            </div>
                            <div class="hoodie-icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="customizer-shape"></div>
            <div class="customizer-shape-2"></div>
            <div class="customizer-bottom-accent"></div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="my-5">
    <div class="container">
        <div class="features-wrapper rounded-4 p-4 p-md-5">
            <div class="row row-cols-2 row-cols-md-4 g-4">
                <div class="col">
                    <div class="feature-item d-flex flex-column align-items-center text-center h-100">
                        <div class="feature-icon-wrapper mb-3">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                        <h4 class="feature-title">Free Shipping</h4>
                        <p class="feature-text">On all orders over $50</p>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-item d-flex flex-column align-items-center text-center h-100">
                        <div class="feature-icon-wrapper mb-3">
                            <i class="bi bi-arrow-repeat fs-4"></i>
                        </div>
                        <h4 class="feature-title">Easy Returns</h4>
                        <p class="feature-text">30-day return policy</p>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-item d-flex flex-column align-items-center text-center h-100">
                        <div class="feature-icon-wrapper mb-3">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <h4 class="feature-title">Secure Checkout</h4>
                        <p class="feature-text">100% protected payments</p>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-item d-flex flex-column align-items-center text-center h-100">
                        <div class="feature-icon-wrapper mb-3">
                            <i class="bi bi-headset fs-4"></i>
                        </div>
                        <h4 class="feature-title">Dedicated Support</h4>
                        <p class="feature-text">24/7 customer service</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="my-5">
    <div class="container">
        <div class="newsletter-box rounded-4 p-5 position-relative overflow-hidden">
            <div class="row align-items-center">
                <div class="col-lg-6 position-relative z-2">
                    <h2 class="h1 mb-4">Get 10% Off Your First Order</h2>
                    <p class="lead text-secondary mb-4">Subscribe to our newsletter to receive updates on new arrivals, special offers and style tips.</p>
                    <form class="row g-2">
                        <div class="col-sm-8">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="newsletterEmail" placeholder="Enter your email">
                                <label for="newsletterEmail" class="text-tertiary">Email address</label>
                            </div>
                        </div>
                        <div class="col-sm-4 d-grid">
                            <button type="submit" class="btn btn-primary h-100 fw-medium">
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="newsletter-decoration"></div>
                </div>
            </div>
            <div class="newsletter-shape"></div>
        </div>
    </div>
</section>

<!-- Extra styling for new components -->
<style>
    .my-5 {
        margin-top: 4rem !important;
        margin-bottom: 4rem !important;
    }
    
    .my-6 {
        margin-top: 5rem;
        margin-bottom: 5rem;
    }

    .hero-wrapper {
        background-color: var(--surface);
        min-height: 600px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .text-gradient {
        background: linear-gradient(to right, var(--primary-light), var(--secondary-light));
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .tracking-wide {
        letter-spacing: 0.1em;
    }

    .hero-image-container {
        position: relative;
        height: 100%;
        overflow: hidden;
    }

    .hero-image {
        position: absolute;
        height: 100%;
        width: 100%;
        object-fit: cover;
        object-position: center;
        mask-image: linear-gradient(to left, rgba(0,0,0,1), rgba(0,0,0,0.5), rgba(0,0,0,0));
        -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,1), rgba(0,0,0,0.5), rgba(0,0,0,0));
    }

    .hero-shape {
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--primary), var(--secondary));
        opacity: 0.1;
        filter: blur(80px);
        top: -200px;
        left: -200px;
        z-index: 0;
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: rgba(127, 90, 240, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-inline: auto;
        transition: all var(--transition-normal);
    }

    .feature-item:hover .icon-wrapper {
        transform: translateY(-5px);
        background-color: rgba(127, 90, 240, 0.2);
        box-shadow: var(--shadow-glow);
    }

    .newsletter-box {
        background-color: var(--surface);
        border: 1px solid var(--border);
    }

    .newsletter-shape {
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: linear-gradient(225deg, var(--primary), var(--accent));
        opacity: 0.07;
        filter: blur(70px);
        bottom: -250px;
        right: -100px;
        z-index: 0;
    }

    .form-floating label {
        color: var(--text-tertiary) !important;
    }

    .z-2 {
        position: relative;
        z-index: 2;
    }

    /* 3D Customizer Card Styles */
    .customizer-card {
        background: linear-gradient(135deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
        transition: all var(--transition-normal);
        min-height: 320px;
        margin-top: 15px;
    }

    .customizer-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }
    
    .customizer-content {
        position: relative;
        z-index: 2;
    }
    
    .customizer-ribbon {
        position: absolute;
        top: -5px;
        left: -5px;
        z-index: 9;
        overflow: hidden;
        width: 100px;
        height: 100px;
        text-align: right;
    }
    
    .customizer-ribbon span {
        font-size: 0.8rem;
        font-weight: bold;
        color: white;
        text-align: center;
        line-height: 28px;
        transform: rotate(-45deg);
        width: 140px;
        display: block;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        position: absolute;
        top: 22px;
        left: -30px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .customizer-ribbon span::before {
        content: "";
        position: absolute;
        left: 0;
        top: 100%;
        z-index: -1;
        border-left: 3px solid var(--primary-dark);
        border-right: 3px solid transparent;
        border-bottom: 3px solid transparent;
        border-top: 3px solid var(--primary-dark);
    }
    
    .customizer-ribbon span::after {
        content: "";
        position: absolute;
        right: 0;
        top: 100%;
        z-index: -1;
        border-left: 3px solid transparent;
        border-right: 3px solid var(--primary-dark);
        border-bottom: 3px solid transparent;
        border-top: 3px solid var(--primary-dark);
    }
    
    .customizer-badge {
        position: relative;
        background: var(--primary);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.8rem;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    
    .customizer-badge-glow {
        position: absolute;
        top: 0;
        left: -50%;
        width: 50%;
        height: 100%;
        background: rgba(255, 255, 255, 0.3);
        transform: skewX(-25deg);
        animation: badgeGlow 3s infinite;
    }
    
    .customizer-tag {
        background: linear-gradient(90deg, var(--secondary), var(--secondary-light));
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.8rem;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }
    
    .customizer-tag::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
    }
    
    .customizer-tag::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        animation: wave 2s ease-in-out infinite;
    }
    
    .text-gradient {
        background: linear-gradient(to right, var(--primary), var(--accent));
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: var(--radius-md);
        transition: all var(--transition-normal);
        border: 1px solid transparent;
    }
    
    .feature-item:hover {
        background: rgba(127, 90, 240, 0.05);
        border-color: var(--border);
        transform: translateY(-2px);
    }
    
    .feature-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(127, 90, 240, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--primary);
        flex-shrink: 0;
    }
    
    .feature-text {
        display: flex;
        flex-direction: column;
    }
    
    .feature-text strong {
        font-size: 0.9rem;
        margin-bottom: 0.1rem;
    }
    
    .feature-text span {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
    
    .feature-item.highlight {
        background: linear-gradient(135deg, rgba(127, 90, 240, 0.1), rgba(255, 127, 80, 0.1));
        border: 1px solid rgba(127, 90, 240, 0.2);
    }
    
    .feature-item.highlight .feature-icon {
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
    }
    
    .customizer-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 500;
        text-decoration: none;
        transition: all var(--transition-normal);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        z-index: 1;
    }
    
    .customizer-btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        color: white;
    }
    
    .customizer-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: all 0.6s;
        z-index: -1;
    }
    
    .customizer-btn:hover::before {
        left: 100%;
    }
    
    .customizer-image-wrapper {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .customizer-shape {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: linear-gradient(225deg, var(--primary), var(--accent));
        opacity: 0.12;
        filter: blur(70px);
        top: -200px;
        right: -100px;
        z-index: 0;
        animation: float 8s ease-in-out infinite alternate;
    }
    
    .customizer-shape-2 {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--secondary), var(--accent));
        opacity: 0.08;
        filter: blur(50px);
        bottom: -150px;
        left: -100px;
        z-index: 0;
        animation: float2 10s ease-in-out infinite alternate;
    }
    
    .customizer-bottom-accent {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--primary), var(--accent), var(--secondary), var(--primary));
        background-size: 300% 100%;
        animation: gradient 8s ease infinite;
    }
    
    .customizer-decoration {
        position: absolute;
        width: 120px;
        height: 120px;
        border: 3px solid var(--primary-light);
        border-radius: 15px;
        top: -20px;
        right: 20px;
        z-index: 1;
        opacity: 0.15;
        transform: rotate(-10deg);
        animation: pulse 3s ease-in-out infinite alternate;
    }
    
    .customizer-dots {
        position: absolute;
        width: 100px;
        height: 100px;
        bottom: -20px;
        right: 40px;
        z-index: 1;
        opacity: 0.15;
        background-image: radial-gradient(var(--accent) 2px, transparent 2px);
        background-size: 12px 12px;
        animation: rotate 30s linear infinite;
    }

    .customizer-image-container {
        position: relative;
        max-width: 280px;
        margin-left: auto;
        margin-right: auto;
        z-index: 2;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .customizer-image {
        width: 100%;
        height: auto;
        transition: all var(--transition-normal);
        transform: scale(1.05);
    }
    
    .customizer-image-container:hover .customizer-image {
        transform: scale(1);
    }
    
    .tshirt-icon, .hoodie-icon {
        position: absolute;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-md);
        z-index: 3;
    }
    
    .tshirt-icon {
        top: 20%;
        left: 10%;
        animation: float 5s ease-in-out infinite;
    }
    
    .hoodie-icon {
        bottom: 20%;
        right: 10%;
        animation: float2 6s ease-in-out infinite;
    }
    
    @keyframes badgeGlow {
        0% {
            left: -50%;
        }
        100% {
            left: 150%;
        }
    }
    
    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes shimmer {
        0% {
            left: -150%;
        }
        100% {
            left: 150%;
        }
    }
    
    @keyframes wave {
        0%, 100% {
            transform: scaleX(1);
        }
        50% {
            transform: scaleX(0.95);
        }
    }

    .feature-card {
        background-color: var(--surface);
        border-radius: var(--radius-md);
        transition: all var(--transition-normal);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }

    .features-wrapper {
        background-color: var(--surface);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-md);
    }
    
    .feature-item {
        padding: 1rem 0.5rem;
        height: 100%;
    }
    
    .feature-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: rgba(127, 90, 240, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-normal);
        color: var(--primary);
    }
    
    .feature-icon-wrapper i {
        font-size: 1.5rem;
    }
    
    .feature-title {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .feature-text {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 0;
    }
    
    @media (hover: hover) {
        .feature-icon-wrapper:hover {
            transform: translateY(-5px);
            background-color: rgba(127, 90, 240, 0.2);
            box-shadow: var(--shadow-glow);
        }
    }
</style>
@endsection
