@extends('layouts.master')
@section('title', 'About Us')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">About MyClothes</h1>
                <p class="lead text-secondary">Redefining fashion for everyone, everywhere.</p>
            </div>
            
            <div class="row g-5 mb-5">
                <div class="col-md-6">
                    <img src="{{ asset('images/about-1.jpg') }}" alt="Our Story" class="img-fluid rounded-4 shadow-sm">
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Our Story</h2>
                    <p>Founded in 2020, MyClothes started with a simple mission: to provide high-quality, stylish clothing that's accessible to everyone. What began as a small online store has grown into a beloved brand with customers around the world.</p>
                    <p>Our founder, Jane Smith, started the company after noticing a gap in the market for affordable yet fashionable clothing that didn't compromise on quality. With a background in fashion design and a passion for sustainability, Jane set out to create a brand that would make a difference.</p>
                    <p>Today, MyClothes continues to grow while staying true to our core values of quality, affordability, and sustainability.</p>
                </div>
            </div>
            
            <div class="row g-5 mb-5 flex-md-row-reverse">
                <div class="col-md-6">
                    <img src="{{ asset('images/about-2.jpg') }}" alt="Our Values" class="img-fluid rounded-4 shadow-sm">
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Our Values</h2>
                    <div class="mb-4">
                        <h5><i class="bi bi-star-fill text-primary me-2"></i> Quality First</h5>
                        <p>We never compromise on quality. Every piece of clothing we sell undergoes rigorous quality checks to ensure it meets our high standards.</p>
                    </div>
                    <div class="mb-4">
                        <h5><i class="bi bi-heart-fill text-primary me-2"></i> Customer Satisfaction</h5>
                        <p>Our customers are at the heart of everything we do. We're committed to providing exceptional service and a seamless shopping experience.</p>
                    </div>
                    <div>
                        <h5><i class="bi bi-globe text-primary me-2"></i> Sustainability</h5>
                        <p>We're committed to reducing our environmental impact. From sourcing sustainable materials to implementing eco-friendly packaging, we're constantly looking for ways to be more sustainable.</p>
                    </div>
                </div>
            </div>
            
            <div class="row g-5 mb-5">
                <div class="col-md-6">
                    <img src="{{ asset('images/about-3.jpg') }}" alt="Our Team" class="img-fluid rounded-4 shadow-sm">
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Our Team</h2>
                    <p>Behind MyClothes is a diverse team of passionate individuals who bring their unique skills and perspectives to the table. From our designers who create beautiful, on-trend pieces, to our customer service representatives who ensure you have the best shopping experience, every team member plays a crucial role in our success.</p>
                    <p>We believe in fostering a positive and inclusive work environment where everyone feels valued and empowered to do their best work. This philosophy extends to our relationships with suppliers and partners, who we consider an extension of our team.</p>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <h2 class="mb-4">Join Our Journey</h2>
                <p class="lead">We're excited about the future and would love for you to be a part of it. Whether you're a first-time customer or a loyal fan, thank you for supporting MyClothes.</p>
                <a href="{{ route('products.list') }}" class="btn btn-primary btn-lg mt-3">Shop Now</a>
            </div>
        </div>
    </div>
</div>
@endsection 