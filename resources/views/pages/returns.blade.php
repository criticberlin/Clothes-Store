@extends('layouts.master')
@section('title', 'Returns & Exchanges')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-5">
                <h1 class="display-4 fw-bold">Returns & Exchanges</h1>
                <p class="lead text-secondary">Our policy for returns, exchanges, and refunds.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Return Policy Overview</h2>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <i class="bi bi-calendar-check text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h5>30-Day Returns</h5>
                                <p class="mb-0">Return items within 30 days of delivery</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <i class="bi bi-tag text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h5>Original Condition</h5>
                                <p class="mb-0">Items must be unworn with tags attached</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <i class="bi bi-credit-card text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h5>Full Refunds</h5>
                                <p class="mb-0">Refunded to original payment method</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">How to Return an Item</h2>
                <ol class="list-group list-group-numbered mb-4">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Log in to your account</div>
                            Go to "My Orders" and select the order containing the item you wish to return.
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Select "Return Item"</div>
                            Choose the specific item(s) you want to return and select a reason for the return.
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Print return label</div>
                            We'll email you a prepaid return shipping label to print.
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Package your return</div>
                            Place the item(s) in the original packaging if possible, or any secure packaging.
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">Ship your return</div>
                            Attach the return label to your package and drop it off at any authorized shipping location.
                        </div>
                    </li>
                </ol>
                <p>Don't have an account? Contact our customer service team for assistance with your return.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Return Conditions</h2>
                <p>To be eligible for a return, your item must be:</p>
                <ul>
                    <li>Returned within 30 days of delivery</li>
                    <li>In the original, unworn condition</li>
                    <li>With all tags and labels attached</li>
                    <li>In the original packaging, if possible</li>
                </ul>
                <p>Items that have been worn, damaged, altered, or are missing tags are not eligible for return or exchange.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Exchanges</h2>
                <p>We currently do not offer direct exchanges. If you need a different size, color, or item, please return your original purchase for a refund and place a new order for the desired item.</p>
                <p>This ensures you get the replacement item as quickly as possible without waiting for the exchange process to complete.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Refunds</h2>
                <p>Once we receive and inspect your return, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.</p>
                <p>If approved, your refund will be processed, and a credit will automatically be applied to your original method of payment within 3-5 business days. Please note that it may take an additional 2-10 business days for the refund to appear in your account, depending on your payment provider.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Return Shipping Costs</h2>
                <p>For standard returns, we provide a prepaid return shipping label at no cost to you.</p>
                <p>However, if the return is due to a preference change (you changed your mind or ordered the wrong size), a shipping fee of $5.99 will be deducted from your refund amount.</p>
                <p>Returns due to defects, incorrect items shipped, or damaged goods are always free.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Non-Returnable Items</h2>
                <p>The following items cannot be returned:</p>
                <ul>
                    <li>Sale items marked as "Final Sale"</li>
                    <li>Intimate apparel and swimwear (for hygiene reasons)</li>
                    <li>Gift cards</li>
                    <li>Personalized or custom-made items</li>
                </ul>
            </div>
            
            <div class="alert alert-info">
                <h4 class="alert-heading">Need Help?</h4>
                <p>If you have any questions about our return policy or need assistance with a return, please contact our customer service team.</p>
                <a href="{{ route('pages.contact') }}" class="btn btn-primary mt-2">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection 