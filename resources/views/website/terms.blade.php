@extends('layouts.academy')

@section('title', 'Terms of Service - Vipers Academy')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Terms of Service</h1>
            <p class="text-muted">Last updated: {{ date('F d, Y') }}</p>

            <div class="content">
                <h2>1. Acceptance of Terms</h2>
                <p>By accessing and using Vipers Academy website, you accept and agree to be bound by the terms and provision of this agreement.</p>

                <h2>2. Description of Service</h2>
                <p>Vipers Academy provides various sports training programs, event information, and membership services.</p>

                <h2>3. User Responsibilities</h2>
                <p>Users agree to use the website in accordance with all applicable laws and regulations.</p>

                <h2>4. Intellectual Property</h2>
                <p>All content on this website is the property of Vipers Academy and may not be reproduced without permission.</p>

                <h2>5. Limitation of Liability</h2>
                <p>Vipers Academy shall not be liable for any damages arising from the use of this website.</p>

                <h2>6. Contact Information</h2>
                <p>For questions about these terms, please contact us at info@vipersacademy.com</p>
            </div>
        </div>
    </div>
</div>
@endsection
