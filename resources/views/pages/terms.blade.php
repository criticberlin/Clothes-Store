@extends('layouts.master')
@section('title', 'Terms & Conditions')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-5">
                <h1 class="display-4 fw-bold">Terms & Conditions</h1>
                <p class="text-secondary">Last updated: {{ date('F d, Y') }}</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Introduction</h2>
                <p>Welcome to MyClothes. These terms and conditions outline the rules and regulations for the use of our website and services.</p>
                <p>By accessing this website, we assume you accept these terms and conditions in full. Do not continue to use MyClothes if you do not agree to all of the terms and conditions stated on this page.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Intellectual Property Rights</h2>
                <p>Unless otherwise stated, MyClothes and/or its licensors own the intellectual property rights for all material on the website. All intellectual property rights are reserved. You may view and/or print pages from the website for your own personal use subject to restrictions set in these terms and conditions.</p>
                <p>You must not:</p>
                <ul>
                    <li>Republish material from this website</li>
                    <li>Sell, rent or sub-license material from this website</li>
                    <li>Reproduce, duplicate or copy material from this website</li>
                    <li>Redistribute content from MyClothes (unless content is specifically made for redistribution)</li>
                </ul>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">User Accounts</h2>
                <p>When you create an account with us, you guarantee that the information you provide us is accurate, complete, and current at all times. Inaccurate, incomplete, or obsolete information may result in the immediate termination of your account on the website.</p>
                <p>You are responsible for maintaining the confidentiality of your account and password, including but not limited to the restriction of access to your computer and/or account. You agree to accept responsibility for any and all activities or actions that occur under your account and/or password.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Products</h2>
                <p>All products displayed on our website are subject to availability. We reserve the right to discontinue any product at any time.</p>
                <p>We have made every effort to display as accurately as possible the colors and images of our products that appear on the website. We cannot guarantee that your computer monitor's display of any color will be accurate.</p>
                <p>We reserve the right to refuse any order you place with us. We may, in our sole discretion, limit or cancel quantities purchased per person, per household or per order. These restrictions may include orders placed by or under the same customer account, the same credit card, and/or orders that use the same billing and/or shipping address.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Pricing and Payment</h2>
                <p>All prices are shown in USD and are exclusive of taxes unless otherwise stated. We reserve the right to change prices at any time without notice.</p>
                <p>Payment for all purchases must be made in full at the time of ordering. Once payment has been received, you will receive a confirmation email.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Shipping and Delivery</h2>
                <p>Delivery times are estimates only and commence from the date of shipping, not the date of order. We are not responsible for any delays caused by destination customs clearance processes.</p>
                <p>Risk of loss and title for items purchased from our website pass to you upon delivery of the items to the carrier.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Returns and Refunds</h2>
                <p>We offer a 30-day return policy for unworn items in their original condition with tags attached. Returns must be initiated within 30 days of receiving your order.</p>
                <p>Refunds will be processed to the original method of payment once we receive and inspect the returned item.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Limitation of Liability</h2>
                <p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website (including, without limitation, any warranties implied by law in respect of satisfactory quality, fitness for purpose and/or the use of reasonable care and skill).</p>
                <p>Nothing in this disclaimer will:</p>
                <ul>
                    <li>Limit or exclude our or your liability for death or personal injury resulting from negligence</li>
                    <li>Limit or exclude our or your liability for fraud or fraudulent misrepresentation</li>
                    <li>Limit any of our or your liabilities in any way that is not permitted under applicable law</li>
                    <li>Exclude any of our or your liabilities that may not be excluded under applicable law</li>
                </ul>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Changes to Terms</h2>
                <p>We reserve the right to modify these terms at any time. When we do, we will revise the updated date at the top of this page. We encourage users to frequently check this page for any changes.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Contact Us</h2>
                <p>If you have any questions about these Terms & Conditions, please contact us at:</p>
                <p>Email: legal@myclothes.com</p>
                <p>Or visit our <a href="{{ route('pages.contact') }}">Contact page</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection 