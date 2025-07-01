@extends('layouts.master')
@section('title', 'Contact Us')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">Contact Us</h1>
                <p class="lead text-secondary">We'd love to hear from you. Get in touch with our team.</p>
            </div>
            
            <div class="row g-5">
                <div class="col-md-5">
                    <h3 class="mb-4">Get In Touch</h3>
                    <p class="mb-4">Have a question, comment, or suggestion? We're here to help! Fill out the form and we'll get back to you as soon as possible.</p>
                    
                    <div class="mb-4">
                        <h5><i class="bi bi-envelope-fill text-primary me-2"></i> Email</h5>
                        <p>support@myclothes.com</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="bi bi-telephone-fill text-primary me-2"></i> Phone</h5>
                        <p>+1 (555) 123-4567</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="bi bi-geo-alt-fill text-primary me-2"></i> Address</h5>
                        <p>123 Fashion Street<br>New York, NY 10001<br>United States</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5><i class="bi bi-clock-fill text-primary me-2"></i> Business Hours</h5>
                        <p>Monday - Friday: 9:00 AM - 6:00 PM EST<br>Saturday: 10:00 AM - 4:00 PM EST<br>Sunday: Closed</p>
                    </div>
                    
                    <div class="mt-5">
                        <h5 class="mb-3">Follow Us</h5>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-secondary hover-text-primary fs-4"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="text-secondary hover-text-primary fs-4"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="text-secondary hover-text-primary fs-4"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-secondary hover-text-primary fs-4"><i class="bi bi-pinterest"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="mb-4">Send Us a Message</h3>
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Your Name</label>
                                        <input type="text" class="form-control" id="name" placeholder="John Doe" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" placeholder="john@example.com" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" id="subject" placeholder="How can we help?">
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="5" placeholder="Your message here..." required></textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 pt-5">
                <h3 class="text-center mb-4">Frequently Asked Questions</h3>
                <p class="text-center mb-4">Find quick answers to common questions about our products and services.</p>
                <div class="text-center">
                    <a href="{{ route('pages.faq') }}" class="btn btn-outline-primary">View FAQs</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 