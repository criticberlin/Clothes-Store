@extends('layouts.master')
@section('title', 'Frequently Asked Questions')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">Frequently Asked Questions</h1>
                <p class="lead text-secondary">Find answers to common questions about our products and services.</p>
            </div>
            
            <div class="accordion" id="faqAccordion">
                <!-- Order & Shipping -->
                <div class="mb-4">
                    <h3 class="mb-3">Orders & Shipping</h3>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                                How long does shipping take?
                            </button>
                        </h4>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Standard shipping typically takes 3-5 business days within the continental United States. International shipping can take 7-14 business days depending on the destination country.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                How can I track my order?
                            </button>
                        </h4>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Once your order ships, you'll receive a confirmation email with a tracking number. You can use this number to track your package on our website or the carrier's website.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                Do you ship internationally?
                            </button>
                        </h4>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Yes, we ship to most countries worldwide. International shipping rates and delivery times vary by location. Please note that customers are responsible for any customs fees or import taxes that may apply.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Returns & Exchanges -->
                <div class="mb-4">
                    <h3 class="mb-3">Returns & Exchanges</h3>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                What is your return policy?
                            </button>
                        </h4>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>We offer a 30-day return policy for unworn items in their original condition with tags attached. Returns must be initiated within 30 days of receiving your order.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                                How do I exchange an item?
                            </button>
                        </h4>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>To exchange an item, please return the original item following our return process and place a new order for the desired item. This ensures you get the replacement as quickly as possible.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Information -->
                <div class="mb-4">
                    <h3 class="mb-3">Product Information</h3>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                                How do I find my size?
                            </button>
                        </h4>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>We provide detailed size guides on each product page. You can also refer to our general size guide in the Help section. If you're between sizes, we generally recommend sizing up.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
                                Are your products sustainable?
                            </button>
                        </h4>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>We're committed to increasing sustainability across our product line. Many of our products use recycled materials or sustainable fabrics. Look for our "Eco-Friendly" tag on products that meet our sustainability criteria.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Account & Payment -->
                <div class="mb-4">
                    <h3 class="mb-3">Account & Payment</h3>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                                What payment methods do you accept?
                            </button>
                        </h4>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>We accept all major credit cards (Visa, Mastercard, American Express, Discover), PayPal, and Apple Pay. All payments are securely processed.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9" aria-expanded="false" aria-controls="faq9">
                                How do I create an account?
                            </button>
                        </h4>
                        <div id="faq9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>You can create an account by clicking on the "Account" icon in the top right corner of our website and selecting "Register." You'll need to provide your email address and create a password.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <h3>Still have questions?</h3>
                <p class="lead">Our customer support team is here to help.</p>
                <a href="{{ route('pages.contact') }}" class="btn btn-primary mt-3">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection 