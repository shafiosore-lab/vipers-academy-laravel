@extends('layouts.academy')

@section('title', 'Privacy Policy - Vipers Academy')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Privacy Policy</h1>
            <p class="text-muted">Last updated: {{ date('F d, Y') }}</p>

            <div class="content">
                <h2>1. Information We Collect</h2>
                <p>We collect information that you provide directly to us, including name, email address, phone number, and any other information you choose to provide.</p>

                <h2>2. How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide, maintain, and improve our services</li>
                    <li>Send you technical notices, updates, and support messages</li>
                    <li>Respond to your comments, questions, and requests</li>
                    <li>Communicate with you about products, services, and events</li>
                </ul>

                <h2>3. Information Sharing</h2>
                <p>We do not share your personal information with third parties except as described in this policy.</p>

                <h2>4. Data Security</h2>
                <p>We take reasonable measures to help protect your personal information from loss, theft, misuse, and unauthorized access.</p>

                <h2>5. Your Rights</h2>
                <p>You have the right to access, update, or delete your personal information at any time.</p>

                <h2>6. Contact Information</h2>
                <p>For questions about this privacy policy, please contact us at privacy@vipersacademy.com</p>
            </div>
        </div>
    </div>
</div>
@endsection
