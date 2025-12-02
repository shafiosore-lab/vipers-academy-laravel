@extends('layouts.academy')

@section('title', 'My Program Enrollments - Vipers Academy')

@section('content')
<section class="enrollments-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5" data-aos="fade-up">
                    <h1 class="display-5 fw-bold mb-3">My Program Enrollments</h1>
                    <p class="lead text-muted">Track your enrolled programs and training progress</p>
                </div>

                @if($enrollments->count() > 0)
                    <!-- Enrollments List -->
                    <div class="row g-4">
                        @foreach($enrollments as $enrollment)
                        <div class="col-lg-6" data-aos="fade-up">
                            <div class="enrollment-card card border-0 shadow-lg h-100">
                                <div class="card-header bg-primary text-white">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h5 class="mb-1">{{ $enrollment->program->title }}</h5>
                                            <small class="opacity-75">{{ $enrollment->program->age_group }}</small>
                                        </div>
                                        <span class="badge bg-{{ $enrollment->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <div class="fw-bold text-primary">{{ $enrollment->program->schedule }}</div>
                                                <small class="text-muted">Schedule</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <div class="fw-bold text-success">KSH {{ number_format($enrollment->fee_amount) }}</div>
                                                <small class="text-muted">Fee</small>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="enrollment-details">
                                        <div class="row g-2 text-sm">
                                            <div class="col-6">
                                                <strong>Enrolled:</strong><br>
                                                {{ $enrollment->enrollment_date->format('M d, Y') }}
                                            </div>
                                            <div class="col-6">
                                                <strong>Payment:</strong><br>
                                                <span class="badge bg-{{ $enrollment->payment_status === 'paid' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($enrollment->payment_status) }}
                                                </span>
                                            </div>
                                            @if($enrollment->start_date)
                                            <div class="col-6">
                                                <strong>Started:</strong><br>
                                                {{ $enrollment->start_date->format('M d, Y') }}
                                            </div>
                                            @endif
                                            @if($enrollment->end_date)
                                            <div class="col-6">
                                                <strong>Ends:</strong><br>
                                                {{ $enrollment->end_date->format('M d, Y') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($enrollment->notes)
                                    <div class="mt-3">
                                        <strong>Notes:</strong>
                                        <p class="mb-0 small text-muted">{{ $enrollment->notes }}</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="card-footer bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            Enrolled {{ $enrollment->created_at->diffForHumans() }}
                                        </small>
                                        <div>
                                            @if($enrollment->payment_status !== 'paid')
                                            <button class="btn btn-sm btn-success" onclick="alert('Payment integration would be implemented here')">
                                                <i class="fas fa-credit-card me-1"></i>Pay Now
                                            </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-primary" onclick="alert('Program details would be shown here')">
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Summary Stats -->
                    <div class="row mt-5" data-aos="fade-up">
                        <div class="col-12">
                            <div class="stats-card card border-0 shadow">
                                <div class="card-body p-4">
                                    <h4 class="mb-3">Enrollment Summary</h4>
                                    <div class="row text-center">
                                        <div class="col-md-3 col-6">
                                            <div class="stat-value h2 text-primary mb-1">{{ $enrollments->count() }}</div>
                                            <small class="text-muted">Total Enrollments</small>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="stat-value h2 text-success mb-1">{{ $enrollments->where('status', 'active')->count() }}</div>
                                            <small class="text-muted">Active Programs</small>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="stat-value h2 text-info mb-1">{{ $enrollments->where('payment_status', 'paid')->count() }}</div>
                                            <small class="text-muted">Paid Programs</small>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="stat-value h2 text-warning mb-1">{{ $enrollments->where('payment_status', 'pending')->count() }}</div>
                                            <small class="text-muted">Pending Payment</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- No Enrollments -->
                    <div class="text-center py-5" data-aos="fade-up">
                        <div class="empty-state">
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-4"></i>
                            <h3 class="mb-3">No Program Enrollments Yet</h3>
                            <p class="lead text-muted mb-4">You haven't enrolled in any programs yet. Browse our available programs and start your football journey!</p>
                            <a href="{{ route('programs') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Browse Programs
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

<style>
.enrollment-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.enrollment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.stat-value {
    font-weight: 700;
}

.empty-state {
    max-width: 500px;
    margin: 0 auto;
}

.enrollment-details .row {
    font-size: 0.875rem;
}
</style>
