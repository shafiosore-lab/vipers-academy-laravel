@extends('layouts.academy')

@section('title', 'Student Dashboard - Vipers Academy')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                            <p class="text-muted mb-0">Continue your learning journey at Vipers Academy</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-success fs-6 mb-2">Student Account</div>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-body text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                    </div>
                    <h4 class="stat-value">{{ \App\Models\Enrollment::where('email', auth()->user()->email)->count() }}</h4>
                    <p class="stat-label text-muted mb-0">Enrolled Programs</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 border-success">
                <div class="card-body text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-book-open fa-3x text-success"></i>
                    </div>
                    <h4 class="stat-value">{{ \App\Models\Enrollment::where('email', auth()->user()->email)->where('learning_option', 'Online')->count() }}</h4>
                    <p class="stat-label text-muted mb-0">Online Courses</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card h-100 border-info">
                <div class="card-body text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users fa-3x text-info"></i>
                    </div>
                    <h4 class="stat-value">{{ \App\Models\Enrollment::where('email', auth()->user()->email)->where('learning_option', 'Physical')->count() }}</h4>
                    <p class="stat-label text-muted mb-0">Physical Sessions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- My Enrollments -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list-check me-2"></i>My Program Enrollments
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $enrollments = \App\Models\Enrollment::with('program')
                            ->where('email', auth()->user()->email)
                            ->latest()
                            ->get();
                    @endphp

                    @if($enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Program</th>
                                        <th>Learning Option</th>
                                        <th>Enrolled Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments as $enrollment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="fas fa-futbol text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $enrollment->program->title ?? 'N/A' }}</h6>
                                                    <small class="text-muted">{{ $enrollment->program->age_group ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $enrollment->learning_option === 'Online' ? 'primary' : 'success' }}">
                                                {{ $enrollment->learning_option }}
                                            </span>
                                        </td>
                                        <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="alert('Learning materials coming soon!')">
                                                <i class="fas fa-play me-1"></i>Start Learning
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Enrollments Yet</h5>
                            <p class="text-muted mb-4">You haven't enrolled in any programs yet.</p>
                            <a href="{{ route('programs') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Browse Programs
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('student.learning') }}" class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center">
                                <i class="fas fa-book-open fa-2x mb-2"></i>
                                <span>My Learning</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('student.profile') }}" class="btn btn-outline-success w-100 p-3 h-100 d-flex flex-column align-items-center">
                                <i class="fas fa-user fa-2x mb-2"></i>
                                <span>My Profile</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('programs') }}" class="btn btn-outline-info w-100 p-3 h-100 d-flex flex-column align-items-center">
                                <i class="fas fa-plus fa-2x mb-2"></i>
                                <span>Enroll More</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('contact') }}" class="btn btn-outline-secondary w-100 p-3 h-100 d-flex flex-column align-items-center">
                                <i class="fas fa-headset fa-2x mb-2"></i>
                                <span>Support</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin: 0 auto 1rem;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    font-weight: 500;
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    border: none;
    font-weight: 600;
}

.btn-outline-primary,
.btn-outline-success,
.btn-outline-info,
.btn-outline-secondary {
    border-width: 2px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-info:hover,
.btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.9rem;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush
