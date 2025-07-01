@extends('layouts.master')
@section('title', 'Shipping Information')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-5">
                <h1 class="display-4 fw-bold">Shipping Information</h1>
                <p class="lead text-secondary">Everything you need to know about our shipping policies and procedures.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Shipping Options</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Shipping Method</th>
                                <th>Estimated Delivery Time</th>
                                <th>Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Standard Shipping</td>
                                <td>3-5 business days</td>
                                <td>$5.99 (Free on orders over $50)</td>
                            </tr>
                            <tr>
                                <td>Express Shipping</td>
                                <td>1-2 business days</td>
                                <td>$12.99</td>
                            </tr>
                            <tr>
                                <td>Next Day Delivery</td>
                                <td>Next business day if ordered before 2PM EST</td>
                                <td>$19.99</td>
                            </tr>
                            <tr>
                                <td>International Shipping</td>
                                <td>7-14 business days</td>
                                <td>Starting at $15.99 (varies by country)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 small text-secondary">* Business days are Monday through Friday, excluding holidays.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Order Processing</h2>
                <p>All orders are processed within 1-2 business days after payment confirmation. Orders placed after 2PM EST may not be processed until the next business day.</p>
                <p>During peak seasons (holidays, special promotions), processing times may be extended by 1-2 additional business days.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Tracking Your Order</h2>
                <p>Once your order ships, you will receive a shipping confirmation email with a tracking number. You can use this number to track your package's progress on our website or directly through the carrier's website.</p>
                <p>If you have an account with us, you can also track your orders by logging into your account and viewing your order history.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">International Shipping</h2>
                <p>We ship to most countries worldwide. International shipping rates vary based on destination, weight, and dimensions of the package.</p>
                <p>Please note that international customers may be subject to customs fees, import duties, and taxes, which are the responsibility of the recipient. These charges vary by country and are not included in the shipping cost or product price.</p>
                <p>International delivery times are estimates and may be affected by customs processing in the destination country.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Shipping Restrictions</h2>
                <p>Some products may have shipping restrictions to certain countries due to local laws and regulations. If a product cannot be shipped to your location, you will be notified during the checkout process.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Delivery Issues</h2>
                <p>If your package is lost, damaged, or significantly delayed, please contact our customer service team. We will work with the shipping carrier to resolve the issue.</p>
                <p>For packages marked as delivered but not received, please check with neighbors and your local post office first. If you still cannot locate your package, contact us within 7 days of the delivery date.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Address Changes</h2>
                <p>If you need to change your shipping address after placing an order, please contact us immediately. We will try to accommodate your request if the order has not yet been processed. Once an order has shipped, we cannot change the delivery address.</p>
            </div>
            
            <div class="alert alert-info">
                <h4 class="alert-heading">Need Help?</h4>
                <p>If you have any questions about shipping or need assistance with your order, please don't hesitate to contact us.</p>
                <a href="{{ route('pages.contact') }}" class="btn btn-primary mt-2">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection 