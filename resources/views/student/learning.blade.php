@extends('layouts.academy')

@section('title', 'My Learning - Vipers Academy')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">My Learning</h1>
                    <p class="text-muted">Access your course materials and track progress</p>
                </div>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">Learning Materials Coming Soon</h4>
                    <p class="text-muted mb-4">
                        Your personalized learning dashboard is being prepared.
                        Course materials, videos, and assignments will be available here.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>What's coming:</strong> Interactive lessons, video tutorials, quizzes, progress tracking, and certificates.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
