@extends('layouts.academy')

@section('title', 'Programs - Vipers Academy - World-Class Football Training in Kenya')

@section('meta_description', 'Discover Vipers Academy programs inspired by international clubs. Futuristic training methodologies combined with Kenyan football excellence.')

@section('content')
<!-- Hero Section -->
<section class="programs-hero position-relative overflow-hidden" style="background-image: url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; padding: 100px 0; margin-top: -80px; padding-top: 180px; min-height: 70vh;">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%);"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8 text-white">
                <div data-aos="fade-right">
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 mb-3">🏆 Elite Training Programs</span>
                    <h1 class="display-4 fw-bold mb-4">World-Class Football Programs</h1>
                    <p class="lead mb-4 fs-5 opacity-90">
                        Experience training methodologies used by top international clubs, adapted for Kenya's brightest football talents. Our futuristic approach combines cutting-edge technology with traditional Kenyan football excellence.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-globe-africa text-warning me-2"></i>
                            <span>Kenyans Leading Globally</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-robot text-info me-2"></i>
                            <span>AI-Powered Training</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-trophy text-success me-2"></i>
                            <span>Pro Career Pathways</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-left">
                <div class="hero-stats bg-white bg-opacity-10 backdrop-blur rounded-3 p-4 text-white border border-white border-opacity-20">
                    <h4 class="mb-3 fw-bold">Program Success</h4>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Players Trained</span>
                        <strong class="text-warning">{{ \App\Models\Player::count() }}+</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Pro Graduates</span>
                        <strong class="text-success">25+</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Success Rate</span>
                        <strong class="text-info">94%</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Grid -->
<section class="programs-section py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Our Elite Programs</h2>
            <p class="lead text-muted">Inspired by international giants, designed for Kenyan champions</p>
        </div>

        <div class="row g-4">
            @foreach($programs as $index => $program)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="program-card card border-0 shadow-lg h-100 position-relative overflow-hidden">
                    <div class="program-header bg-primary text-white p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="program-icon">
                                @if(str_contains($program->title, 'Youth'))
                                    <i class="fas fa-seedling fa-2x"></i>
                                @elseif(str_contains($program->title, 'Advanced') || str_contains($program->title, 'Skills'))
                                    <i class="fas fa-rocket fa-2x"></i>
                                @elseif(str_contains($program->title, 'Goalkeeper'))
                                    <i class="fas fa-shield-alt fa-2x"></i>
                                @else
                                    <i class="fas fa-futbol fa-2x"></i>
                                @endif
                            </div>
                            <span class="badge bg-light text-primary">
                                @if($index == 0) Most Popular @elseif($index == 1) Elite Level @else Specialized @endif
                            </span>
                        </div>
                        <h4 class="mb-2">{{ $program->title }}</h4>
                        <p class="mb-0 opacity-75">{{ $program->age_group }} • {{ $program->duration ?? 'Foundation Building' }}</p>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-3">
                            <h6 class="text-primary fw-bold mb-2">
                                @if(str_contains($program->title, 'Youth'))
                                    Inspired by Barcelona's La Masia
                                @elseif(str_contains($program->title, 'Advanced'))
                                    Inspired by Ajax's Total Football
                                @elseif(str_contains($program->title, 'Goalkeeper'))
                                    Inspired by Chelsea's GK Development
                                @else
                                    World-Class Training Methodology
                                @endif
                            </h6>
                            <p class="text-muted small">{{ $program->description }}</p>
                        </div>

                        <div class="program-features mb-4">
                            <div class="feature-item d-flex align-items-center mb-2">
                                <i class="fas fa-brain text-primary me-2"></i>
                                <span class="small">AI Performance Analytics</span>
                            </div>
                            <div class="feature-item d-flex align-items-center mb-2">
                                <i class="fas fa-users text-success me-2"></i>
                                <span class="small">Small Group Training</span>
                            </div>
                            <div class="feature-item d-flex align-items-center mb-2">
                                <i class="fas fa-graduation-cap text-info me-2"></i>
                                <span class="small">Academic Support</span>
                            </div>
                            <div class="feature-item d-flex align-items-center mb-2">
                                <i class="fas fa-heartbeat text-danger me-2"></i>
                                <span class="small">Sports Science & Nutrition</span>
                            </div>
                        </div>

                        <div class="program-details mb-3">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="fw-bold text-primary">
                                        @if(str_contains($program->schedule, '5x') || str_contains($program->schedule, 'Tuesday'))
                                            5x/Week
                                        @elseif(str_contains($program->schedule, '4x') || str_contains($program->schedule, 'Monday, Wednesday, Friday'))
                                            3x/Week
                                        @else
                                            4x/Week
                                        @endif
                                    </div>
                                    <small class="text-muted">Training Sessions</small>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold text-success">KSH {{ number_format($program->regular_fee ?? 15000) }}</div>
                                    <small class="text-muted">Per Month</small>
                                    @if($program->mumias_fee && $program->mumias_fee < $program->regular_fee)
                                        <div class="mt-1">
                                            <small class="text-warning fw-bold">Mumias: KSH {{ number_format($program->mumias_fee) }} ({{ $program->mumias_discount_percentage }}% off)</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('enroll') }}" class="btn btn-primary">Enroll Now</a>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#programModal{{ $index + 1 }}">Learn More</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Futuristic Training Section -->
<section class="futuristic-section py-5 bg-dark text-white">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold mb-3">Futuristic Training Technology</h2>
            <p class="lead opacity-75">Revolutionary methods used by top European clubs, now in Kenya</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="tech-card text-center p-4 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25">
                    <div class="tech-icon mb-3">
                        <i class="fas fa-robot fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">AI Performance Analytics</h5>
                    <p class="small opacity-75">Real-time performance tracking with predictive analytics, just like Bayern Munich's system</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="tech-card text-center p-4 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25">
                    <div class="tech-icon mb-3">
                        <i class="fas fa-vr-cardboard fa-3x text-success"></i>
                    </div>
                    <h5 class="fw-bold">VR Tactical Training</h5>
                    <p class="small opacity-75">Virtual reality scenarios for match preparation, inspired by Premier League innovations</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="tech-card text-center p-4 bg-info bg-opacity-10 rounded-3 border border-info border-opacity-25">
                    <div class="tech-icon mb-3">
                        <i class="fas fa-dna fa-3x text-info"></i>
                    </div>
                    <h5 class="fw-bold">Genetic Performance Profiling</h5>
                    <p class="small opacity-75">DNA analysis for personalized training programs, used by top Spanish and Italian clubs</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="tech-card text-center p-4 bg-warning bg-opacity-10 rounded-3 border border-warning border-opacity-25">
                    <div class="tech-icon mb-3">
                        <i class="fas fa-brain fa-3x text-warning"></i>
                    </div>
                    <h5 class="fw-bold">Neuro Training</h5>
                    <p class="small opacity-75">Cognitive enhancement techniques from neuroscience research at elite European academies</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kenyan Football Heritage Section -->
<section class="heritage-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-4">Proudly Kenyan, Globally Competitive</h2>
                <p class="lead text-muted mb-4">
                    While we implement world-class methodologies from international clubs, we remain deeply rooted in Kenyan football culture and values.
                </p>

                <div class="kenyan-values mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="value-icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Community First</h6>
                            <small class="text-muted">Building stronger communities through football</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="value-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Ubuntu Spirit</h6>
                            <small class="text-muted">I am because we are - collective success</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="value-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">National Pride</h6>
                            <small class="text-muted">Developing players to represent Kenya globally</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="success-stories">
                    <div class="story-card card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Success Story" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 fw-bold">Victor Wanyama</h6>
                                    <small class="text-muted">Former Captain, Southampton FC</small>
                                </div>
                            </div>
                            <blockquote class="mb-0">
                                <p class="fst-italic">"The foundation I received in Kenya prepared me for the highest levels of professional football. Vipers Academy continues this legacy."</p>
                            </blockquote>
                        </div>
                    </div>

                    <div class="stats-highlight bg-primary bg-opacity-10 rounded-3 p-4 text-center">
                        <h3 class="text-primary mb-2">15+</h3>
                        <p class="mb-0 fw-semibold">Kenyan Players in European Leagues</p>
                        <small class="text-muted">Graduates of similar programs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Program Enrollment CTA -->
<section class="enrollment-cta py-5 bg-primary text-white">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="display-5 fw-bold mb-4">Ready to Start Your Journey?</h2>
        <p class="lead mb-4 opacity-75">Join Vipers Academy and become part of Kenya's football excellence</p>

        <div class="d-flex flex-column flex-lg-row gap-3 justify-content-center align-items-center">
            <a href="{{ route('enroll') }}" class="btn btn-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-graduation-cap me-2"></i>Enroll in a Program
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-phone me-2"></i>Schedule a Visit
            </a>
        </div>

        <div class="mt-4">
            <small class="opacity-75">
                🚀 Limited spots available • Professional coaching • Modern facilities • Kenyan excellence
            </small>
        </div>
    </div>
</section>

<!-- Registration CTA -->
<section class="registration-cta py-5 bg-primary text-white">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="display-5 fw-bold mb-4">Start Your Journey to Football Excellence</h2>
        <p class="lead mb-4 opacity-75">Join Kenya's most advanced football development program</p>

        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="registration-benefits d-flex flex-wrap justify-content-center gap-3 mb-4">
                    <span class="badge bg-light text-primary px-3 py-2">✨ World-Class Training</span>
                    <span class="badge bg-light text-success px-3 py-2">🎯 Pro Career Pathways</span>
                    <span class="badge bg-light text-info px-3 py-2">🤖 AI Performance Tracking</span>
                    <span class="badge bg-light text-warning px-3 py-2">🌍 International Exposure</span>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-lg-row gap-3 justify-content-center align-items-center">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-rocket me-2"></i>Register for Free Trial
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-semibold">
                <i class="fas fa-phone me-2"></i>Schedule a Visit
            </a>
        </div>

        <div class="mt-4">
            <small class="opacity-75">
                🚀 Limited spots available • Professional coaching • Modern facilities • Kenyan excellence
            </small>
        </div>
    </div>
</section>

<!-- Program Detail Modals -->
@foreach($programs as $index => $program)
<div class="modal fade" id="programModal{{ $index + 1 }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ $program->title }} - Complete Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Program Overview</h6>
                <p>{{ $program->description }}</p>

                <h6>Key Features</h6>
                <ul>
                    <li>Small group training (max 8 players)</li>
                    <li>AI-powered performance analytics</li>
                    <li>Academic support and mentoring</li>
                    <li>Character development workshops</li>
                    <li>Regular parent-teacher conferences</li>
                </ul>

                <h6>Schedule</h6>
                <p>{{ $program->schedule }}</p>

                <h6>Duration</h6>
                <p>{{ $program->duration ?? 'Ongoing' }}</p>

                <h6>Investment</h6>
                <p class="fw-bold text-primary">Regular: KSH {{ number_format($program->regular_fee ?? 15000) }} per month</p>
                @if($program->mumias_fee && $program->mumias_fee < $program->regular_fee)
                    <p class="fw-bold text-warning">Mumias Community: KSH {{ number_format($program->mumias_fee) }} per month ({{ $program->mumias_discount_percentage }}% discount)</p>
                    <small class="text-muted">*Discount available for Mumias Sugar Company employees and their families</small>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
function scrollToRegister() {
    document.getElementById('register').scrollIntoView({ behavior: 'smooth' });
}

// Handle program selection and fee display
document.getElementById('program_id')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const regularFee = selectedOption.getAttribute('data-fee');
    const mumiasFee = selectedOption.getAttribute('data-mumias-fee');

    if (regularFee && mumiasFee) {
        document.getElementById('regular-fee').textContent = 'KSH ' + parseInt(regularFee).toLocaleString();
        document.getElementById('mumias-fee').textContent = 'KSH ' + parseInt(mumiasFee).toLocaleString();
    } else {
        document.getElementById('regular-fee').textContent = 'Select a program to see fees';
        document.getElementById('mumias-fee').textContent = 'Select a program to see fees';
    }
});
</script>
@endpush

<style>
.programs-hero {
    background-attachment: fixed;
    background-size: cover;
}

.hero-stats {
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.program-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.program-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.program-header {
    position: relative;
    overflow: hidden;
}

.program-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.feature-item {
    transition: all 0.2s ease;
}

.feature-item:hover {
    color: var(--bs-primary) !important;
}

.tech-card {
    transition: all 0.3s ease;
    height: 100%;
}

.tech-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.registration-benefits .badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
}

.value-icon {
    transition: all 0.3s ease;
}

.value-icon:hover {
    transform: scale(1.1);
}

.story-card {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-left: 4px solid var(--bs-primary);
}

.registration-cta {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.registration-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    opacity: 0.1;
}

.futuristic-section {
    background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 100%);
}

.heritage-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}
</style>
