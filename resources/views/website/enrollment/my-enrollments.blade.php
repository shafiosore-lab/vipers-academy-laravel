@extends('layouts.academy')

@section('title', 'My Enrollments - Vipers Academy')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold mb-3">My Enrollments</h1>
                    <p class="lead text-muted">Track your program enrollments and training progress</p>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-graduation-cap fa-4x text-primary mb-4"></i>
                        <h3 class="mb-3">Enrollment Dashboard</h3>
                        <p class="text-muted mb-4">
                            This page will display your current and past program enrollments,
                            training schedules, and progress tracking.
                        </p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Coming Soon:</strong> Enrollment tracking and progress monitoring features.
                        </div>
                        <a href="{{ route('programs') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Programs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
