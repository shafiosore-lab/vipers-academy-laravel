@extends('layouts.academy')

@section('title', $job->title . ' - Career Opportunity at Vipers Academy')

@section('meta_description', 'Apply for ' . $job->title . ' position at Vipers Academy. ' . Str::limit($job->description, 150))

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden"
    style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; min-height: 40vh;">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 100%);"></div>
    <div class="container position-relative h-100">
        <div class="row align-items-center h-100">
            <div class="col-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">{{ $job->title }}</h1>
                <p class="lead mb-0">{{ $job->location }} • {{ ucfirst($job->type) }} • <span class="badge bg-success">Open Position</span></p>
            </div>
        </div>
    </div>
</section>

<!-- Job Details Section -->
<section class="job-details-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Job Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Job Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Location:</strong> {{ $job->location }}</p>
                                <p><strong>Job Type:</strong> {{ ucfirst($job->type) }}</p>
                                @if($job->department)
                                    <p><strong>Department:</strong> {{ $job->department }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($job->salary)
                                    <p><strong>Salary:</strong> {{ $job->salary }}</p>
                                @endif
                                @if($job->application_deadline)
                                    <p><strong>Application Deadline:</strong> {{ $job->application_deadline->format('M d, Y') }}</p>
                                @endif
                                <p><strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h4 class="mb-0"><i class="fas fa-file-alt me-2 text-success"></i>Job Description</h4>
                    </div>
                    <div class="card-body">
                        <div class="job-description">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h4 class="mb-0"><i class="fas fa-list-check me-2 text-warning"></i>Requirements</h4>
                    </div>
                    <div class="card-body">
                        <div class="job-requirements">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>
                </div>

                <!-- Apply Button -->
                <div class="text-center mb-5">
                    <a href="#apply" class="btn btn-success btn-lg px-5 py-3">
                        <i class="fas fa-paper-plane me-2"></i>Apply for This Position
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Quick Apply Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-rocket me-2"></i>Quick Apply</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Ready to join our team? Apply now and start your journey with Vipers Academy!</p>
                        <div class="d-grid">
                            <a href="#apply" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Apply Now
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Why Join Us -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-star me-2 text-warning"></i>Why Join Vipers Academy?</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Work with passionate professionals</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Make a real impact on young lives</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Competitive compensation package</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Professional development opportunities</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Modern facilities and equipment</li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2 text-info"></i>Questions?</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Have questions about this position?</p>
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-1"></i>Contact HR
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Application Form Section -->
<section id="apply" class="application-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Apply for {{ $job->title }}</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('careers.apply', $job) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="applicant_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('applicant_name') is-invalid @enderror"
                                           id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" required>
                                    @error('applicant_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="resume" class="form-label">Resume/CV *</label>
                                <input type="file" class="form-control @error('resume') is-invalid @enderror"
                                       id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                                @error('resume')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Accepted formats: PDF, DOC, DOCX (Max 5MB)</div>
                            </div>

                            <div class="mb-4">
                                <label for="cover_letter" class="form-label">Cover Letter</label>
                                <textarea class="form-control @error('cover_letter') is-invalid @enderror"
                                          id="cover_letter" name="cover_letter" rows="5" placeholder="Tell us why you're interested in this position...">{{ old('cover_letter') }}</textarea>
                                @error('cover_letter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional but recommended</div>
                            </div>

                            <div class="alert alert-info">
                                By submitting this application, you agree to our terms and conditions.
                                We will process your application and contact you if there's a potential match.
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<style>
.hero-section {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5));
}

.job-description, .job-requirements {
    line-height: 1.6;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.application-section .card {
    border-radius: 20px;
}

.application-section .card-header {
    border-radius: 20px 20px 0 0 !important;
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.btn-success {
    background: linear-gradient(45deg, #65c16e, #4a8c52);
    border: none;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: linear-gradient(45deg, #4a8c52, #3a6b3f);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(101, 193, 110, 0.3);
}
</style>

<script>
// File validation
document.getElementById('resume').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (file) {
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid file type (PDF, DOC, or DOCX).');
            e.target.value = '';
            return;
        }

        if (file.size > maxSize) {
            alert('File size must be less than 5MB.');
            e.target.value = '';
            return;
        }
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});
</script>
