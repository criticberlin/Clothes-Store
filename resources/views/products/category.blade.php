@extends('layouts.master')
@section('title', "Collections")

@section('content')
<div class="container my-6">
    <div class="section-header text-center mb-5">
        <h6 class="text-primary text-uppercase fw-bold tracking-wide mb-2">Explore</h6>
        <h1 class="display-5 fw-bold">Our Collections</h1>
        <p class="text-secondary col-lg-8 mx-auto mt-3">Discover the perfect style for every occasion with our carefully curated collections.</p>
    </div>
    
    <div class="row g-4 mb-5">
        <!-- Women Category -->
        <div class="col-lg-4 col-md-6">
            <div class="category-card shadow-sm position-relative overflow-hidden">
                <img src="{{ asset('images/women.jpg') }}" alt="Women's Collection">
                <div class="overlay d-flex flex-column justify-content-end">
                    <div class="category-content p-4">
                        <span class="badge bg-primary mb-3 px-3 py-2">Most Popular</span>
                        <h3 class="h2 text-white mb-3">Women</h3>
                        <p class="text-light mb-4">Elegant designs for the modern woman. From casual to formal, find your perfect look.</p>
                        <a href="{{ route('products.byCategory',['category' => 'women']) }}" class="btn btn-outline-light d-flex align-items-center gap-2 justify-content-center">
                            <span>Explore Collection</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="category-hover-effect"></div>
            </div>
        </div>

        <!-- Men Category -->
        <div class="col-lg-4 col-md-6">
            <div class="category-card shadow-sm position-relative overflow-hidden">
                <img src="{{ asset('images/men.jpg') }}" alt="Men's Collection">
                <div class="overlay d-flex flex-column justify-content-end">
                    <div class="category-content p-4">
                        <span class="badge bg-secondary mb-3 px-3 py-2">Featured</span>
                        <h3 class="h2 text-white mb-3">Men</h3>
                        <p class="text-light mb-4">Contemporary styles that combine comfort and sophistication for the modern man.</p>
                        <a href="{{ route('products.byCategory',['category' => 'men']) }}" class="btn btn-outline-light d-flex align-items-center gap-2 justify-content-center">
                            <span>Explore Collection</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="category-hover-effect"></div>
            </div>
        </div>

        <!-- Kids Category -->
        <div class="col-lg-4 col-md-6">
            <div class="category-card shadow-sm position-relative overflow-hidden">
                <img src="{{ asset('images/kids.jpg') }}" alt="Kids Collection">
                <div class="overlay d-flex flex-column justify-content-end">
                    <div class="category-content p-4">
                        <span class="badge bg-accent mb-3 px-3 py-2">New Arrivals</span>
                        <h3 class="h2 text-white mb-3">Kids</h3>
                        <p class="text-light mb-4">Playful and comfortable styles that kids love and parents approve of.</p>
                        <a href="{{ route('products.byCategory',['category' => 'kids']) }}" class="btn btn-outline-light d-flex align-items-center gap-2 justify-content-center">
                            <span>Explore Collection</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="category-hover-effect"></div>
            </div>
        </div>
    </div>
    
    <!-- Featured Collection -->
    <div class="featured-collection bg-surface p-5 rounded-4 mb-6 position-relative overflow-hidden">
        <div class="row align-items-center">
            <div class="col-lg-6 position-relative z-2">
                <h2 class="display-6 mb-4">The 2025 Collection</h2>
                <p class="lead text-secondary mb-4">Discover our exclusive collection designed for the modern lifestyle. Sustainable materials, timeless designs.</p>
                <div class="d-flex gap-3 mb-4">
                    <a href="{{ route('products.list') }}" class="btn btn-primary">Shop Collection</a>
                    <a href="#" class="btn btn-outline-light">Learn More</a>
                </div>
                <div class="d-flex align-items-center gap-4 mt-5">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-flower2 text-primary fs-4"></i>
                        <span class="text-secondary">Sustainable</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-lightning text-primary fs-4"></i>
                        <span class="text-secondary">Innovative</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-heart text-primary fs-4"></i>
                        <span class="text-secondary">Ethical</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-end">
                <div class="position-relative">
                    @php
                        $imagePath = 'images/featured_collection.jpg';
                        $fallbackPath = 'images/logo.png';
                        $imageSrc = file_exists(public_path($imagePath)) ? asset($imagePath) : asset($fallbackPath);
                    @endphp
                    <img src="{{ $imageSrc }}" alt="Featured Collection" class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute top-0 end-0 translate-middle-y bg-primary text-white px-4 py-2 rounded-pill fw-bold">
                        Limited Edition
                    </div>
                </div>
            </div>
        </div>
        <div class="featured-shape"></div>
    </div>
</div>

<style>
    .my-6 {
        margin-top: 5rem;
        margin-bottom: 5rem;
    }
    
    .mb-6 {
        margin-bottom: 5rem;
    }
    
    .tracking-wide {
        letter-spacing: 0.1em;
    }
    
    .category-card {
        height: 500px;
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
    }
    
    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-slow);
    }
    
    .category-card .overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(22, 22, 26, 0.9) 20%, transparent 70%);
        transition: all var(--transition-normal);
    }
    
    .category-card .category-hover-effect {
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        transition: transform var(--transition-normal);
        transform: translateY(100%);
    }
    
    .category-card:hover .category-hover-effect {
        transform: translateY(0);
    }
    
    .category-card:hover img {
        transform: scale(1.05);
    }
    
    .featured-shape {
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: linear-gradient(225deg, var(--primary), var(--secondary));
        opacity: 0.07;
        filter: blur(80px);
        bottom: -250px;
        right: -100px;
        z-index: 0;
    }
    
    .z-2 {
        position: relative;
        z-index: 2;
    }
    
    .bg-accent {
        background-color: var(--accent);
    }
</style>
@endsection
