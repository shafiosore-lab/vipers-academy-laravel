@extends('layouts.academy')

@section('title', 'Careers - Join Vipers Academy Team')

@section('meta_description', 'Explore exciting career opportunities at Vipers Academy. Join our team of football
professionals and contribute to youth development.')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden"
    style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; min-height: 60vh;">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"
        style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 100%);"></div>
    <div class="container position-relative h-100">
        <div class="row align-items-center h-100">
            <div class="col-lg-8">
                <div class="hero-content text-white" data-aos="fade-right">
                    <span class="badge bg-success fs-6 px-3 py-2 mb-3">Join Our Team</span>
                    <h1 class="display-4 fw-bold mb-4">
                        <span class="text-white">Shape the Future of </span>
                        <span class="text-warning">Football</span>
                    </h1>
                    <p class="lead mb-4 fs-5 opacity-90">
                        Join Vipers Academy and be part of Kenya's premier football development institution.
                        We're looking for passionate professionals to help us nurture the next generation of football
                        stars.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="#openings" class="btn btn-success btn-lg px-4 py-3 fw-semibold">
                            <i class="fas fa-briefcase me-2"></i>View Open Positions
                        </a>
                        <a href="#culture" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                            <i class="fas fa-users me-2"></i>Our Culture
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Join Us Section -->
<section class="why-join-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Why Join Vipers Academy?</h2>
            <p class="lead text-muted">Be part of a winning team that makes a real difference in young lives</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-3">
                        <div class="benefit-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-trophy fa-2x text-primary"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Impact Lives</h4>
                        <p class="card-text text-muted">Make a meaningful impact on the next generation of football
                            talent and help shape their futures.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-3">
                        <div class="benefit-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Great Team</h4>
                        <p class="card-text text-muted">Work alongside passionate professionals who share your love for
                            football and youth development.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-3">
                        <div class="benefit-icon bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-rocket fa-2x text-warning"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Growth Opportunities</h4>
                        <p class="card-text text-muted">Continuous learning, professional development, and career
                            advancement in a growing organization.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Company Culture Section -->
<section id="culture" class="culture-section py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Our Culture</h2>
            <p class="lead text-muted">The values that drive everything we do</p>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h3 class="fw-bold mb-4">Excellence in Everything</h3>
                <p class="mb-4">We strive for excellence in coaching, facilities, and player development. Our commitment
                    to quality ensures that every player who walks through our doors gets the best possible experience.
                </p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Professional coaching staff</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>State-of-the-art facilities</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Comprehensive development programs
                    </li>
                </ul>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80"
                    alt="Team culture" class="img-fluid rounded shadow">
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <h3 class="fw-bold mb-4">Community & Respect</h3>
                <p class="mb-4">We foster a culture of respect, teamwork, and community involvement. Our players learn
                    not just football skills, but also life skills that will serve them throughout their lives.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Respect for all individuals</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Teamwork and collaboration</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Community engagement</li>
                </ul>
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80"
                    alt="Community values" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Benefits & Perks</h2>
            <p class="lead text-muted">We offer competitive packages to attract the best talent</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-item text-center">
                    <div class="benefit-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h5 class="fw-bold">Competitive Salary</h5>
                    <p class="text-muted small">Market-leading compensation packages</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-item text-center">
                    <div class="benefit-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h5 class="fw-bold">Health Insurance</h5>
                    <p class="text-muted small">Comprehensive medical coverage</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-item text-center">
                    <div class="benefit-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5 class="fw-bold">Professional Development</h5>
                    <p class="text-muted small">Ongoing training and certifications</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="benefit-item text-center">
                    <div class="benefit-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px;">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h5 class="fw-bold">Football Passion</h5>
                    <p class="text-muted small">Work in your passion every day</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="search-filter-section py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="search-filter-card card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('careers.index') }}" class="row g-3">
                            <!-- Search Bar -->
                            <div class="col-lg-6">
                                <label for="search" class="form-label fw-semibold">Search Jobs</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="search" name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Search by title, description, or location...">
                                </div>
                            </div>

                            <!-- Type Filter -->
                            <div class="col-lg-2">
                                <label for="type" class="form-label fw-semibold">Job Type</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">All Types</option>
                                    @foreach($types as $typeOption)
                                    <option value="{{ $typeOption }}"
                                        {{ request('type') == $typeOption ? 'selected' : '' }}>
                                        {{ ucfirst($typeOption) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Location Filter -->
                            <div class="col-lg-2">
                                <label for="location" class="form-label fw-semibold">Location</label>
                                <select class="form-select" id="location" name="location">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $locationOption)
                                    <option value="{{ $locationOption }}"
                                        {{ request('location') == $locationOption ? 'selected' : '' }}>
                                        {{ $locationOption }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Department Filter -->
                            <div class="col-lg-2">
                                <label for="department" class="form-label fw-semibold">Department</label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $deptOption)
                                    <option value="{{ $deptOption }}"
                                        {{ request('department') == $deptOption ? 'selected' : '' }}>
                                        {{ $deptOption }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="fas fa-search me-2"></i>Search Jobs
                                    </button>
                                    <a href="{{ route('careers.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-2"></i>Clear Filters
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Open Positions Section -->
<section id="openings" class="openings-section py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Open Positions</h2>
            <p class="lead text-muted">Join our team and help shape the future of football</p>
            @if(request()->hasAny(['search', 'type', 'location', 'department']))
            <div class="alert alert-info mt-3">
                Showing {{ $jobs->count() }} job{{ $jobs->count() !== 1 ? 's' : '' }} matching your search criteria.
                <a href="{{ route('careers.index') }}" class="alert-link">Clear filters</a> to see all positions.
            </div>
            @endif
        </div>

        @if($jobs->count() > 0)
        <div class="row g-3">
            @foreach($jobs as $job)
            <div class="col-lg-6 col-xl-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="job-card card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title fw-bold mb-1">{{ $job->title }}</h5>
                                <p class="text-muted mb-2">{{ $job->location }} • {{ ucfirst($job->type) }}</p>
                            </div>
                            <span class="badge bg-success">Open</span>
                        </div>

                        <p class="card-text text-muted mb-3">{{ Str::limit($job->description, 120) }}</p>

                        @if($job->salary)
                        <p class="mb-2"><strong>Salary:</strong> {{ $job->salary }}</p>
                        @endif

                        @if($job->application_deadline)
                        <p class="mb-3"><strong>Deadline:</strong> {{ $job->application_deadline->format('M d, Y') }}
                        </p>
                        @endif

                        <div class="d-flex gap-2">
                            <a href="{{ route('careers.show', $job) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                            <a href="{{ route('careers.show', $job) }}#apply" class="btn btn-success btn-sm">
                                <i class="fas fa-paper-plane me-1"></i>Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5" data-aos="fade-up">
            <div class="empty-state">
                <i class="fas fa-briefcase fa-4x text-muted mb-4"></i>
                <h3 class="text-muted mb-3">No Open Positions</h3>
                <p class="text-muted mb-4">We're always looking for great talent. Check back soon for new opportunities!
                </p>
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    <i class="fas fa-envelope me-2"></i>Contact Us
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection

<style>
.hero-section {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4));
}

.benefit-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.benefit-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.benefit-icon {
    transition: transform 0.3s ease;
}

.benefit-card:hover .benefit-icon {
    transform: scale(1.1);
}

.culture-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.job-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.empty-state {
    max-width: 500px;
    margin: 0 auto;
}

.cta-section {
    background: linear-gradient(135deg, #198754 0%, #157347 100%);
}
</style>
