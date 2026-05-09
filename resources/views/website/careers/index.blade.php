@extends('layouts.academy')

@section('title', 'Careers - Join Mumias Vipers Academy Team')

@section('meta_description', 'Explore exciting career opportunities at Mumias Vipers Academy. Join our team of football professionals and contribute to youth development.')

Join Mumias Vipers Academy and be part of Kenya's premier football development institution.
                        We're looking for passionate professionals to help us nurture the next generation of football stars.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="#openings" class="btn btn-lg px-4 py-3 fw-semibold" style="background: var(--accent); border-color: var(--accent); color: #1a1a1a;">
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

{{-- ===================== Why Join Us Section ===================== --}}
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
                        <div class="benefit-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: rgba(234,28,77,0.1);">
                            <i class="fas fa-trophy fa-2x" style="color: var(--primary);"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Impact Lives</h4>
                        <p class="card-text text-muted">Make a meaningful impact on the next generation of football talent and help shape their futures.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-3">
                        <div class="benefit-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: rgba(101,193,110,0.1);">
                            <i class="fas fa-users fa-2x" style="color: var(--accent);"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Great Team</h4>
                        <p class="card-text text-muted">Work alongside passionate professionals who share your love for football and youth development.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-card card border-0 shadow-sm h-100 text-center">
                    <div class="card-body p-3">
                        <div class="benefit-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px; background: rgba(251,199,97,0.1);">
                            <i class="fas fa-rocket fa-2x" style="color: var(--highlight);"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">Growth Opportunities</h4>
                        <p class="card-text text-muted">Continuous learning, professional development, and career advancement in a growing organization.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== Culture Section ===================== --}}
<section id="culture" class="culture-section py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Our Culture</h2>
            <p class="lead text-muted">The values that drive everything we do</p>
        </div>

        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h3 class="fw-bold mb-4">Excellence in Everything</h3>
                <p class="mb-4">We strive for excellence in coaching, facilities, and player development. Our commitment to quality ensures that every player who walks through our doors gets the best possible experience.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--accent);"></i>Professional coaching staff</li>
                    <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--accent);"></i>State-of-the-art facilities</li>
                    <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--accent);"></i>Comprehensive development programs</li>
                </ul>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img
                    src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                    alt="Team culture"
                    class="img-fluid rounded shadow"
                >
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <h3 class="fw-bold mb-4">Community &amp; Respect</h3>
                <p class="mb-4">We foster a culture of respect, teamwork, and community involvement. Our players learn not just football skills, but also life skills that will serve them throughout their lives.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--accent);"></i>Respect for all individuals</li>
                    <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--accent);"></i>Teamwork and collaboration</li>
                    <li class="mb-2"><i class="fas fa-check me-2" style="color: var(--accent);"></i>Community engagement</li>
                </ul>
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <img
                    src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                    alt="Community values"
                    class="img-fluid rounded shadow"
                >
            </div>
        </div>
    </div>
</section>

{{-- ===================== Benefits Section ===================== --}}
<section class="benefits-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Benefits &amp; Perks</h2>
            <p class="lead text-muted">We offer competitive packages to attract the best talent</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-item text-center">
                    <div class="benefit-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px; background: var(--accent); color: #1a1a1a;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h5 class="fw-bold">Competitive Salary</h5>
                    <p class="text-muted small">Market-leading compensation packages</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-item text-center">
                    <div class="benefit-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px; background: var(--primary); color: white;">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h5 class="fw-bold">Health Insurance</h5>
                    <p class="text-muted small">Comprehensive medical coverage</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-item text-center">
                    <div class="benefit-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px; background: var(--highlight); color: #1a1a1a;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5 class="fw-bold">Professional Development</h5>
                    <p class="text-muted small">Ongoing training and certifications</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="benefit-item text-center">
                    <div class="benefit-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 60px; height: 60px; background: var(--info); color: white;">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h5 class="fw-bold">Football Passion</h5>
                    <p class="text-muted small">Work in your passion every day</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== Search & Filter Section ===================== --}}
<section class="search-filter-section py-4 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="search-filter-card card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('careers.index') }}" class="row g-3">

                            <div class="col-lg-6">
                                <label for="search" class="form-label fw-semibold">Search Jobs</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="search"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Search by title, description, or location..."
                                    >
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <label for="type" class="form-label fw-semibold">Job Type</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">All Types</option>
                                    @foreach($types as $typeOption)
                                        <option value="{{ $typeOption }}" {{ request('type') == $typeOption ? 'selected' : '' }}>
                                            {{ ucfirst($typeOption) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label for="location" class="form-label fw-semibold">Location</label>
                                <select class="form-select" id="location" name="location">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $locationOption)
                                        <option value="{{ $locationOption }}" {{ request('location') == $locationOption ? 'selected' : '' }}>
                                            {{ $locationOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label for="department" class="form-label fw-semibold">Department</label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $deptOption)
                                        <option value="{{ $deptOption }}" {{ request('department') == $deptOption ? 'selected' : '' }}>
                                            {{ $deptOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="submit" class="btn px-4" style="background: var(--accent); border-color: var(--accent); color: #1a1a1a;">
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

{{-- ===================== Open Positions Section ===================== --}}
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
                                        <p class="text-muted mb-2">{{ $job->location }} &bull; {{ ucfirst($job->type) }}</p>
                                    </div>
                                    <span class="badge mb-2" style="background: var(--accent); color: #1a1a1a;">Open</span>
                                </div>

                                <p class="card-text text-muted mb-3">{{ Str::limit($job->description, 120) }}</p>

                                @if($job->salary)
                                    <p class="mb-2"><strong>Salary:</strong> {{ $job->salary }}</p>
                                @endif

                                @if($job->application_deadline)
                                    <p class="mb-3"><strong>Deadline:</strong> {{ $job->application_deadline->format('M d, Y') }}</p>
                                @endif

                                <div class="d-flex gap-2">
                                    <a href="{{ route('careers.show', $job) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                    <a href="{{ route('careers.show', $job) }}#apply" class="btn btn-sm px-3" style="background: var(--accent); border-color: var(--accent); color: #1a1a1a;">
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
                    <p class="text-muted mb-4">We're always looking for great talent. Check back soon for new opportunities!</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection

@push('styles')
<style>
    /* Hero Section - Account for fixed navbar */
    .hero-section {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 100%), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-position: center;
    }

    /* Desktop - Account for 80px navbar + 40px topbar */
    @media (min-width: 1024px) {
        .hero-section {
            padding-top: 120px;
        }
    }

    /* Tablet */
    @media (min-width: 768px) and (max-width: 1023px) {
        .hero-section {
            padding-top: 100px;
        }
    }

    /* Mobile - Topbar hidden, navbar is 60px */
    @media (max-width: 767px) {
        .hero-section {
            padding-top: 70px;
        }
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
        background: linear-gradient(135deg, #65c16e 0%, #4a8c52 100%);
    }
</style>
@endpush
