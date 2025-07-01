@extends('layouts.master')
@section('title', 'Privacy Policy')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-5">
                <h1 class="display-4 fw-bold">Privacy Policy</h1>
                <p class="text-secondary">Last updated: {{ date('F d, Y') }}</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Introduction</h2>
                <p>At MyClothes, we respect your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.</p>
                <p>Please read this privacy policy carefully before using our services.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Information We Collect</h2>
                <p>We may collect, use, store and transfer different kinds of personal data about you which we have grouped together as follows:</p>
                <ul>
                    <li><strong>Identity Data</strong> includes first name, last name, username or similar identifier.</li>
                    <li><strong>Contact Data</strong> includes billing address, delivery address, email address and telephone numbers.</li>
                    <li><strong>Financial Data</strong> includes payment card details.</li>
                    <li><strong>Transaction Data</strong> includes details about payments to and from you and other details of products you have purchased from us.</li>
                    <li><strong>Technical Data</strong> includes internet protocol (IP) address, your login data, browser type and version, time zone setting and location, browser plug-in types and versions, operating system and platform, and other technology on the devices you use to access this website.</li>
                    <li><strong>Profile Data</strong> includes your username and password, purchases or orders made by you, your interests, preferences, feedback and survey responses.</li>
                    <li><strong>Usage Data</strong> includes information about how you use our website and services.</li>
                    <li><strong>Marketing and Communications Data</strong> includes your preferences in receiving marketing from us and our third parties and your communication preferences.</li>
                </ul>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">How We Use Your Information</h2>
                <p>We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:</p>
                <ul>
                    <li>Where we need to perform the contract we are about to enter into or have entered into with you.</li>
                    <li>Where it is necessary for our legitimate interests (or those of a third party) and your interests and fundamental rights do not override those interests.</li>
                    <li>Where we need to comply with a legal obligation.</li>
                </ul>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Data Security</h2>
                <p>We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed. In addition, we limit access to your personal data to those employees, agents, contractors and other third parties who have a business need to know.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Data Retention</h2>
                <p>We will only retain your personal data for as long as reasonably necessary to fulfill the purposes we collected it for, including for the purposes of satisfying any legal, regulatory, tax, accounting or reporting requirements.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Your Legal Rights</h2>
                <p>Under certain circumstances, you have rights under data protection laws in relation to your personal data, including the right to:</p>
                <ul>
                    <li>Request access to your personal data.</li>
                    <li>Request correction of your personal data.</li>
                    <li>Request erasure of your personal data.</li>
                    <li>Object to processing of your personal data.</li>
                    <li>Request restriction of processing your personal data.</li>
                    <li>Request transfer of your personal data.</li>
                    <li>Right to withdraw consent.</li>
                </ul>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Changes to This Privacy Policy</h2>
                <p>We may update our privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page and updating the "Last updated" date at the top of this privacy policy.</p>
            </div>
            
            <div class="mb-5">
                <h2 class="mb-3">Contact Us</h2>
                <p>If you have any questions about this privacy policy or our privacy practices, please contact us at:</p>
                <p>Email: privacy@myclothes.com</p>
                <p>Or visit our <a href="{{ route('pages.contact') }}">Contact page</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection 