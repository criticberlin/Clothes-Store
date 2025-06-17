@extends('layouts.master')
@section('title', "Home")

@section('content')
<!-- Hero Section -->
<section class="hero mb-6">
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
<section id="collections" class="my-6">
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

<!-- Features Section -->
<section class="my-6 py-5 bg-surface rounded-4">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="feature-item px-3">
                    <div class="icon-wrapper mb-3">
                        <i class="bi bi-truck text-primary fs-2"></i>
                    </div>
                    <h4 class="h6 mb-2">Free Shipping</h4>
                    <p class="text-secondary small m-0">On all orders over $50</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="feature-item px-3">
                    <div class="icon-wrapper mb-3">
                        <i class="bi bi-arrow-repeat text-primary fs-2"></i>
                    </div>
                    <h4 class="h6 mb-2">Easy Returns</h4>
                    <p class="text-secondary small m-0">30-day return policy</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mt-4 mt-md-0">
                <div class="feature-item px-3">
                    <div class="icon-wrapper mb-3">
                        <i class="bi bi-shield-check text-primary fs-2"></i>
                    </div>
                    <h4 class="h6 mb-2">Secure Checkout</h4>
                    <p class="text-secondary small m-0">100% protected payments</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mt-4 mt-md-0">
                <div class="feature-item px-3">
                    <div class="icon-wrapper mb-3">
                        <i class="bi bi-headset text-primary fs-2"></i>
                    </div>
                    <h4 class="h6 mb-2">Dedicated Support</h4>
                    <p class="text-secondary small m-0">24/7 customer service</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="my-6">
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
</style>
@endsection
