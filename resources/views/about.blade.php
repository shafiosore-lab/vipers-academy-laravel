@extends('layouts.academy')

@section('title', 'About Us - Vipers Academy')

@section('content')
<div class="about-page">

    <!-- Hero Section -->
    <section class="about-hero position-relative"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 120px 0 100px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hero-content p-4 rounded-3"
                        style="background: rgba(0,0,0,0.35); backdrop-filter: blur(8px);">
                        <h1 class="display-4 fw-bold text-white mb-4">About Vipers Academy</h1>
                        <p class="lead text-white-50 mb-4">
                            We are dedicated to developing young football talent through comprehensive training
                            programs, professional coaching, and a commitment to excellence that shapes the future of
                            football.
                        </p>
                        <div class="hero-stats d-flex flex-wrap gap-4 mb-4">
                            <div class="stat-item text-center">
                                <div class="stat-number display-6 fw-bold text-white">500+</div>
                                <div class="stat-label text-white-50">Players Trained</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-number display-6 fw-bold text-white">15+</div>
                                <div class="stat-label text-white-50">Years Experience</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-number display-6 fw-bold text-white">50+</div>
                                <div class="stat-label text-white-50">Professional Graduates</div>
                            </div>
                        </div>
                        <a href="{{ route('register.player') }}" class="btn btn-light btn-lg px-5 py-3 shadow-sm">
                            <i class="fas fa-user-plus me-2"></i>Join Our Academy
                        </a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="hero-image-container">
                        <img src="https://img.freepik.com/free-photo/full-shot-kids-playing-together_28476563.jpg"
                            alt="Kids Playing Football Together" class="img-fluid rounded-3 shadow-lg">
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Shapes -->
        <div class="hero-bg-elements">
            <div class="floating-shape shape-1"></div>
            <div class="floating-shape shape-2"></div>
            <div class="floating-shape shape-3"></div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mission-vision py-5 bg-light">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="mission-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <i class="fas fa-bullseye fa-3x text-primary mb-4"></i>
                            <h3 class="fw-bold mb-4">Our Mission</h3>
                            <p class="lead">
                                To provide world-class football training and development opportunities for young
                                players, fostering growth on and off the field.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="vision-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <i class="fas fa-eye fa-3x text-success mb-4"></i>
                            <h3 class="fw-bold mb-4">Our Vision</h3>
                            <p class="lead">
                                To be the leading football academy in the region, producing professional players who
                                excel at the highest levels while building discipline and leadership.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="values-section py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-4">Our Core Values</h2>
                    <p class="lead text-muted">The principles that guide everything we do at Vipers Academy</p>
                </div>
            </div>
            <div class="row g-3">
                @php
                $values = [
                ['icon'=>'fa-trophy','title'=>'Excellence','color'=>'text-warning','desc'=>'Striving for the highest
                standards in training and development'],
                ['icon'=>'fa-users','title'=>'Teamwork','color'=>'text-primary','desc'=>'Building strong relationships
                and collaborative spirit'],
                ['icon'=>'fa-heart','title'=>'Passion','color'=>'text-danger','desc'=>'Fostering love for the game and
                dedication to improvement'],
                ['icon'=>'fa-shield-alt','title'=>'Integrity','color'=>'text-info','desc'=>'Promoting honesty, respect,
                and ethical behavior'],
                ];
                @endphp
                @foreach($values as $value)
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="value-card text-center p-3 h-100">
                        <i class="fas {{ $value['icon'] }} fa-2x {{ $value['color'] }} mb-3"></i>
                        <h5 class="fw-bold mb-3">{{ $value['title'] }}</h5>
                        <p class="text-muted">{{ $value['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row text-center">
                @php
                $stats = [
                ['number'=>'500+','label'=>'Players Trained'],
                ['number'=>'15+','label'=>'Years of Excellence'],
                ['number'=>'50+','label'=>'Professional Players'],
                ['number'=>'25+','label'=>'Coaching Staff'],
                ];
                @endphp
                @foreach($stats as $stat)
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="stat-item">
                        <div class="stat-number display-4 fw-bold mb-2">{{ $stat['number'] }}</div>
                        <div class="stat-label h5">{{ $stat['label'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="history-section py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-4">Our Journey</h2>
                    <p class="lead text-muted">From humble beginnings to becoming a premier football academy</p>
                </div>
            </div>
            <div class="timeline">
                @php
                $timeline = [
                ['year'=>'2010','title'=>'Foundation','desc'=>'Vipers Academy was established with a vision to nurture
                young football talent.'],
                ['year'=>'2015','title'=>'Growth & Expansion','desc'=>'Expanded facilities and coaching staff to meet
                growing demand.'],
                ['year'=>'2020','title'=>'Professional Partnerships','desc'=>'Established partnerships with professional
                clubs and international academies for player development.'],
                ['year'=>'2025','title'=>'Excellence Today','desc'=>'Continuing our legacy of producing world-class
                players and champions in football.'],
                ];
                @endphp
                @foreach($timeline as $index => $item)
                <div class="timeline-item" data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}">
                    <div class="timeline-content p-4 border rounded-3 shadow-sm mb-4">
                        <div class="timeline-year fw-bold text-primary mb-2">{{ $item['year'] }}</div>
                        <h5 class="mb-2">{{ $item['title'] }}</h5>
                        <p class="text-muted">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-4">Meet Our Leadership</h2>
                    <p class="lead text-muted">Experienced professionals dedicated to your success</p>
                </div>
            </div>
            <div class="row g-3">
                @php
                $team = [
                ['name'=>'Kofi Mensah','role'=>'Academy
                Director','img'=>'https://img.freepik.com/free-photo/portrait-african-football-coach_23-2149094872.jpg?w=300','desc'=>'Former
                professional player with 20+ years of coaching experience in African football'],
                ['name'=>'Amina Okafor','role'=>'Head
                Coach','img'=>'https://img.freepik.com/free-photo/african-football-coach-training-session_23-2149094875.jpg?w=300','desc'=>'CAF
                licensed coach specializing in African youth football development'],
                ['name'=>'Jelani Nkosi','role'=>'Technical
                Director','img'=>'https://img.freepik.com/free-photo/african-sports-scientist-analyzing-performance-data_23-2149094878.jpg?w=300','desc'=>'Sports
                scientist and performance analyst specializing in African football talent development'],
                ];
                @endphp
                @foreach($team as $index => $member)
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="team-card card border-0 shadow-sm text-center h-100">
                        <div class="card-body p-3">
                            <div class="team-avatar mb-4">
                                <img src="{{ $member['img'] }}" alt="{{ $member['name'] }}" class="rounded-circle"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <h5 class="fw-bold mb-2">{{ $member['name'] }}</h5>
                            <p class="text-primary mb-3">{{ $member['role'] }}</p>
                            <p class="text-muted small">{{ $member['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-gradient-primary text-bg-black text-center">
        <div class="container">
            <h2 class="fw-bold mb-3">Ready to Start Your Journey?</h2>
            <p class="mb-4">Join Vipers Academy and discover your potential on and off the field</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register.player') }}" class="btn btn-light btn-lg px-4 py-2">
                    <i class="fas fa-user-plus me-1"></i>Register as Player
                </a>
                <a href="{{ route('register.partner') }}" class="btn btn-outline-light btn-lg px-4 py-2">
                    <i class="fas fa-handshake me-1"></i>Become a Partner
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
@push('styles')
<style>
/* ===== Hero & Floating Shapes ===== */
.about-hero {
    position: relative;
    overflow: hidden;
}

.hero-content .stat-item {
    min-width: 120px;
}

.hero-content .stat-number {
    font-size: 2.5rem;
    line-height: 1;
}

.hero-content .stat-label {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hero-image-container img {
    border: 8px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
}

.hero-image-container img:hover {
    transform: scale(1.05);
}

.hero-bg-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
}

.floating-shape {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    top: 10%;
    left: 5%;
    animation-delay: 0s;
}

.shape-2 {
    width: 60px;
    height: 60px;
    top: 70%;
    right: 10%;
    animation-delay: 3s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    bottom: 20%;
    left: 80%;
    animation-delay: 6s;
}

/* ===== Cards ===== */
.mission-card,
.vision-card {
    border-radius: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: linear-gradient(145deg, #fff, #f8f9fa);
}

.mission-card:hover,
.vision-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.icon-container {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
}

.values-section {
    background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
}

.value-card {
    border-radius: 15px;
    transition: all 0.3s ease;
    background: white;
    border: 1px solid #e9ecef;
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.value-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
}

/* ===== Stats ===== */
.stats-section .stat-item {
    padding: 2rem 0;
}

.stats-section .stat-number {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

/* ===== Timeline ===== */
.timeline {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    width: 2px;
    height: 100%;
    background: linear-gradient(to bottom, #667eea, #764ba2);
    transform: translateX(-50%);
}

.timeline-item {
    margin-bottom: 2rem;
    position: relative;
}

.timeline-item:nth-child(even) .timeline-content {
    left: 0;
    text-align: right;
}

.timeline-item:nth-child(odd) .timeline-content {
    left: 50%;
    text-align: left;
}

.timeline-content {
    position: relative;
    width: 45%;
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    margin-left: 2rem;
}

.timeline-year {
    font-size: 1.5rem;
    font-weight: bold;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 1.5rem;
    width: 20px;
    height: 20px;
    background: #667eea;
    border-radius: 50%;
    transform: translateX(-50%);
    border: 4px solid white;
    box-shadow: 0 0 0 4px #667eea;
}

/* ===== Team ===== */
.team-card {
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.team-card:hover {
    transform: translateY(-10px);
}

.team-avatar img {
    border: 4px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.team-card:hover .team-avatar img {
    border-color: #667eea;
}

/* ===== CTA Section ===== */
.cta-section {
    position: relative;
    overflow: hidden;
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 5rem 1rem;
    color: #fff;
}

.cta-section .btn-light,
.cta-section .btn-outline-light {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    font-weight: 600;
}

.cta-section .btn-light:hover,
.cta-section .btn-outline-light:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

.floating-shape.shape-cta-1,
.floating-shape.shape-cta-2 {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    animation: float 10s ease-in-out infinite;
}

.shape-cta-1 {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 10%;
}

.shape-cta-2 {
    width: 150px;
    height: 150px;
    bottom: 15%;
    right: 5%;
}

/* ===== Animations ===== */
@keyframes float {

    0%,
    100% {
        transform: translateY(0px) rotate(0deg);
    }

    50% {
        transform: translateY(-15px) rotate(180deg);
    }
}

/* ===== Responsive ===== */
@media(max-width:768px) {
    .timeline::before {
        left: 30px;
    }

    .timeline-item:nth-child(even) .timeline-content,
    .timeline-item:nth-child(odd) .timeline-content {
        left: 60px;
        width: calc(100% - 80px);
        text-align: left;
    }

    .timeline-item::before {
        left: 30px;
    }

    .hero-content .stat-item {
        min-width: 80px;
    }

    .hero-content .stat-number {
        font-size: 2rem;
    }
}

@media(max-width:576px) {
    .cta-section .btn-lg {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush
