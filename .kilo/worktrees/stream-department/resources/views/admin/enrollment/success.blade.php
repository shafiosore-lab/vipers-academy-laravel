@extends('layouts.academy')

@section('title', 'Enrollment Successful - Vipers Academy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow text-center">
                <div class="card-body p-5">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>

                    <h2 class="card-title mb-3">Enrollment Successful!</h2>

                    <p class="card-text text-muted mb-4">
                        Thank you for enrolling in Vipers Academy. Your account has been created and your enrollment has been processed successfully.
                    </p>

                    <div class="alert alert-info mb-4">
                        <strong>Next Steps:</strong>
                        <ul class="mb-0 text-start">
                            <li>Check your email for login credentials</li>
                            <li>Login to access your player portal</li>
                            <li>View your enrolled program details</li>
                            <li>Contact us if you have any questions</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('player.portal.dashboard') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-user me-2"></i> Go to Player Portal
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.card {
    border: none;
    border-radius: 16px;
}

.btn-primary {
    background: linear-gradient(135deg, #ea1c4d 0%, #c0173f 100%);
    border: none;
    padding: 1rem;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #c0173f 0%, #a01435 100%);
}
</style>
@endsection
