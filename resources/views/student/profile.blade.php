@extends('layouts.academy')

@section('title', 'My Profile - Vipers Academy')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">My Profile</h1>
                    <p class="text-muted">Manage your account information</p>
                </div>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name</label>
                            <p class="form-control-plaintext">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="form-control-plaintext">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <p class="form-control-plaintext">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Account Type</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-success">Student</span>
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Member Since</label>
                            <p class="form-control-plaintext">{{ auth()->user()->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Login Credentials</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Login Information:</strong><br>
                        Username: {{ auth()->user()->email }}<br>
                        Password: Your phone number
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Update Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Programs Enrolled:</span>
                        <strong>{{ \App\Models\Enrollment::where('email', auth()->user()->email)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Account Status:</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
