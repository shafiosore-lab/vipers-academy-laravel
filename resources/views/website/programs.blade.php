@extends('layouts.academy')

@section('title', 'Programs - Vipers Academy')

@section('content')

<!-- Program Page Header -->
<section class="programs-hero position-relative overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="hero-background">
        <div class="hero-image-layer"></div>
        <div class="hero-overlay"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="hero-decoration">
        <div class="floating-ball ball-1">
            <i class="fas fa-futbol"></i>
        </div>
        <div class="floating-ball ball-2">
            <i class="fas fa-futbol"></i>
        </div>
        <div class="floating-ball ball-3">
            <i class="fas fa-futbol"></i>
        </div>
    </div>

    <!-- Main Hero Content -->
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="hero-content text-center text-white py-3" data-aos="fade-up">


                    <!-- Main Heading -->
                    <h1 class="display-3 fw-bold mb-4 hero-title">Our Programs</h1>

                    <!-- Subtitle with enhanced styling -->
                    <div class="hero-subtitle-container">
                        <p class="lead fs-4 mb-4 hero-subtitle">Choose the perfect program to develop your child's
                            potential</p>
                        <div class="subtitle-accent"></div>
                    </div>

                    <!-- Call-to-Action Buttons -->
                    <div class="hero-actions mt-5">
                        <a href="#programs-section" class="btn btn-warning btn-lg me-3 mb-3">
                            <i class="fas fa-search me-2"></i>Explore Programs
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg mb-3">
                            </i>Enroll Now
                        </a>
                    </div>




</section>

<!-- Main Program Cards Section -->
<section id="programs-section" class="py-3 px-0">
    <div class="container-fluid m-0 p-0">
        <div class="programs-grid">

            <!-- Program Card 1: Weekend Football & Life-Skills Program -->
            <article class="program-card-container">
                <div class="card program-card-50-50 h-100 shadow border-0">
                    <div class="program-split-layout">
                        <!-- Text Content Section -->
                        <div class="program-content">
                            <div class="program-content-inner">
                                <!-- Program Category Badge -->
                                <div class="program-category">
                                    <span class="badge bg-primary rounded-pill">Football & Life Skills</span>
                                </div>

                                <!-- Program Title -->
                                <h3 class="program-title">Weekend Football & Life-Skills Program</h3>

                                <!-- Program Details -->
                                <div class="program-details">
                                    <div class="detail-item">
                                        <i class="fas fa-dollar-sign text-primary"></i>
                                        <span>Fee: KSH 500/month</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-calendar text-primary"></i>
                                        <span>Duration: Full Year</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-users text-primary"></i>
                                        <span>Ages: 6–18 years</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <p class="program-description">
                                    A year-round weekend program combining structured football training, academic
                                    discipline, digital literacy, character development, and CBC-aligned mentorship.
                                </p>

                                <!-- Action Buttons -->
                                <div class="program-actions">
                                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#weekendProgramModal">
                                        <i class="fas fa-info-circle me-2"></i>Learn More
                                    </button>
                                    <a href="{{ route('enroll') }}" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Enroll Now
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Image Section -->
                        <div class="program-image">
                            <div class="program-image-container">
                                <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                    alt="Weekend Football & Life-Skills Program - Students training on football field with life skills activities"
                                    class="program-img" loading="lazy">

                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Program Card 2: Long Holiday Intensive Camp -->
            <article class="program-card-container">
                <div class="card program-card-50-50 h-100 shadow border-0">
                    <div class="program-split-layout">
                        <!-- Image Section -->
                        <div class="program-image">
                            <div class="program-image-container">
                                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                    alt="Long Holiday Intensive Camp - Students engaging in outdoor activities and football training during school holidays"
                                    class="program-img" loading="lazy">

                            </div>
                        </div>

                        <!-- Text Content Section -->
                        <div class="program-content">
                            <div class="program-content-inner">
                                <!-- Program Category Badge -->
                                <div class="program-category">
                                    <span class="badge bg-warning text-dark rounded-pill">Holiday Intensive Camp</span>
                                </div>

                                <!-- Program Title -->
                                <h3 class="program-title">Long Holiday Intensive Camp</h3>

                                <!-- Program Details -->
                                <div class="program-details">
                                    <div class="detail-item">
                                        <i class="fas fa-dollar-sign text-warning"></i>
                                        <span>Fee: KSH 5,000/holiday</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-calendar text-warning"></i>
                                        <span>Duration: April/Aug/Dec</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-users text-warning"></i>
                                        <span>Ages: 7–17 years</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <p class="program-description">
                                    A fully immersive holiday camp blending football training, academic mentorship,
                                    computer exposure, tournaments, teamwork, and life-skills development.
                                </p>

                                <!-- Action Buttons -->
                                <div class="program-actions">
                                    <button class="btn btn-warning btn-lg text-white" data-bs-toggle="modal"
                                        data-bs-target="#holidayCampModal">
                                        <i class="fas fa-info-circle me-2"></i>Learn More
                                    </button>
                                    <a href="{{ route('enroll') }}" class="btn btn-outline-warning btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Enroll Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Program Card 3: Computer & Coding Classes -->
            <article class="program-card-container">
                <div class="card program-card-50-50 h-100 shadow border-0">
                    <div class="program-split-layout">
                        <!-- Text Content Section -->
                        <div class="program-content">
                            <div class="program-content-inner">
                                <!-- Program Category Badge -->
                                <div class="program-category">
                                    <span class="badge bg-success rounded-pill">Technology & Coding</span>
                                </div>

                                <!-- Program Title -->
                                <h3 class="program-title">Computer & Coding Classes</h3>

                                <!-- Program Details -->
                                <div class="program-details">
                                    <div class="detail-item">
                                        <i class="fas fa-dollar-sign text-success"></i>
                                        <span>Fee: KSH 3,500/month</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-clock text-success"></i>
                                        <span>Flexible Schedule</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-users text-success"></i>
                                        <span>Ages: 7–18 years</span>
                                    </div>
                                </div>

                                <!-- Description -->
                                <p class="program-description">
                                    Beginner-friendly tech classes where children learn coding, software basics,
                                    problem-solving, and digital creativity — preparing them for future tech careers.
                                </p>

                                <!-- Action Buttons -->
                                <div class="program-actions">
                                    <button class="btn btn-success btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#codingClassesModal">
                                        <i class="fas fa-info-circle me-2"></i>Learn More
                                    </button>
                                    <a href="{{ route('enroll') }}" class="btn btn-outline-success btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Enroll Now
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Image Section -->
                        <div class="program-image">
                            <div class="program-image-container">
                                <img src="https://images.unsplash.com/photo-1515879218367-8466d910aaa4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                    alt="Computer & Coding Classes - Students learning programming and technology skills in modern computer lab"
                                    class="program-img" loading="lazy">

                            </div>
                        </div>
                    </div>
                </div>
            </article>

        </div>
    </div>
</section>

<!-- Learn More Modals -->
<!-- Weekend Program Modal -->
<div class="modal fade" id="weekendProgramModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Weekend Football & Life-Skills Program</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <span class="badge bg-primary fs-6 mb-2">Fee: KSH 500/month</span><br>
                            <span class="badge bg-secondary fs-6 mb-2">Full Year Duration</span><br>
                            <span class="badge bg-info fs-6">Ages: 6–18 years</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="fw-bold text-primary">Program Overview</h6>
                        <p>The Weekend Football & Life-Skills Program is built to provide consistent development, strong
                            discipline, and a holistic foundation for young athletes. The program supports the CBC
                            model, focusing on practical skills, academic responsibility, emotional intelligence, and
                            physical development.</p>
                        <p><strong>This is not just football — it's a structured journey that shapes character,
                                discipline, confidence, digital awareness, and academic excellence.</strong></p>
                    </div>
                </div>

                <h6 class="fw-bold text-primary mt-4">What Your Child Will Learn</h6>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-futbol text-success me-2"></i>1. Football Practical Training (On-Field)
                        </h6>
                        <ul class="small">
                            <li>Ball control, dribbling, passing, shooting</li>
                            <li>Position-specific development</li>
                            <li>Agility, speed & coordination drills</li>
                            <li>Small-sided games and tactical sessions</li>
                            <li>Weekend match simulations</li>
                        </ul>

                        <h6><i class="fas fa-book text-info me-2"></i>2. Football Theory Classes (Off-Field)</h6>
                        <ul class="small">
                            <li>Understanding positions and roles</li>
                            <li>Decision-making and game intelligence</li>
                            <li>Match analysis using video and demonstrations</li>
                            <li>Player responsibilities & teamwork principles</li>
                        </ul>

                        <h6><i class="fas fa-graduation-cap text-warning me-2"></i>3. Academic Mentorship (CBC
                            Compliant)</h6>
                        <ul class="small">
                            <li>Study discipline & weekly academic check-ins</li>
                            <li>Homework planning support</li>
                            <li>Learning habits, time management & personal organization</li>
                            <li>Behavior evaluation and mentorship</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-laptop text-primary me-2"></i>4. Digital & Computer Literacy</h6>
                        <p class="small"><strong>Every child will learn early-age digital skills:</strong></p>
                        <ul class="small">
                            <li>Basic computers</li>
                            <li>Internet safety</li>
                            <li>Exposure to coding concepts</li>
                            <li>Productivity apps</li>
                            <li>Digital creativity (presentations, typing, simple projects)</li>
                        </ul>

                        <h6><i class="fas fa-heart text-danger me-2"></i>5. Life-Skills & Character Formation</h6>
                        <ul class="small">
                            <li>Communication skills</li>
                            <li>Leadership development</li>
                            <li>Confidence building</li>
                            <li>Respect, responsibility & teamwork</li>
                            <li>Mental wellness awareness</li>
                        </ul>

                        <h6><i class="fas fa-dumbbell text-secondary me-2"></i>6. Health, Nutrition & Sports Science
                        </h6>
                        <ul class="small">
                            <li>Basic athlete nutrition</li>
                            <li>Body care and injury prevention</li>
                            <li>Fitness routines</li>
                        </ul>

                        <h6><i class="fas fa-trophy text-warning me-2"></i>7. Tournaments & Exchange Opportunities</h6>
                        <ul class="small">
                            <li>Local friendly matches</li>
                            <li>Age-group tournaments</li>
                            <li>Regional exchange programs where parents contribute for travel</li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-success mt-4">
                    <h6 class="fw-bold">Why Parents Choose This Program</h6>
                    <ul class="mb-0">
                        <li>Extremely affordable (KSH 500 per month)</li>
                        <li>Covers both education and sports</li>
                        <li>Safe environment</li>
                        <li>Develops discipline, confidence, and social skills</li>
                        <li>Supports CBC and improves academic focus</li>
                        <li>Builds strong physical, mental & digital foundations</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('enroll') }}" class="btn btn-primary">Enroll Now</a>
            </div>
        </div>
    </div>
</div>

<!-- Holiday Camp Modal -->
<div class="modal fade" id="holidayCampModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Long Holiday Intensive Camp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <span class="badge bg-warning fs-6 mb-2">Fee: KSH 5,000/holiday</span><br>
                            <span class="badge bg-secondary fs-6 mb-2">April/August/December</span><br>
                            <span class="badge bg-info fs-6">Ages: 7–17 years</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="fw-bold text-warning">Program Overview</h6>
                        <p>The Long Holiday Camp is an intensive, full-day training experience designed to keep children
                            active, learning, and growing during school breaks. It combines advanced football
                            development, academic mentorship, digital skills, mental discipline, and fun social
                            experiences.</p>
                        <p><strong>This camp is ideal for parents who want their children to stay productive and avoid
                                negative holiday influences.</strong></p>
                    </div>
                </div>

                <h6 class="fw-bold text-warning mt-4">What the Camp Includes</h6>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-futbol text-success me-2"></i>1. Intensive Football Training</h6>
                        <ul class="small">
                            <li>Daily fitness & conditioning</li>
                            <li>Technical & tactical mastery</li>
                            <li>Position-specific workshops</li>
                            <li>Team formations & match intelligence</li>
                            <li>Friendly matches & tournament days</li>
                        </ul>

                        <h6><i class="fas fa-graduation-cap text-info me-2"></i>2. Academic Mentorship & Study Habits
                        </h6>
                        <ul class="small">
                            <li>Holiday academic clinics</li>
                            <li>Personalized study guidance</li>
                            <li>Discipline training and behavior tracking</li>
                            <li>Reading and learning challenges</li>
                        </ul>

                        <h6><i class="fas fa-laptop text-primary me-2"></i>3. Computer Literacy & Technology Exposure
                        </h6>
                        <p class="small"><strong>Children get hands-on digital learning:</strong></p>
                        <ul class="small">
                            <li>Computer basics</li>
                            <li>Typing skills</li>
                            <li>Coding for beginners</li>
                            <li>Simple software usage</li>
                            <li>Creative digital projects</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-users text-warning me-2"></i>4. Character & Leadership Building Activities
                        </h6>
                        <ul class="small">
                            <li>Leadership challenges</li>
                            <li>Team-building games</li>
                            <li>Communication workshops</li>
                            <li>Confidence coaching</li>
                            <li>Emotional management</li>
                        </ul>

                        <h6><i class="fas fa-heart text-danger me-2"></i>5. Life-Skills & Personal Development</h6>
                        <ul class="small">
                            <li>Self-discipline</li>
                            <li>Respect & social behavior</li>
                            <li>Personal hygiene</li>
                            <li>Financial basics (age-appropriate)</li>
                        </ul>

                        <h6><i class="fas fa-plane text-info me-2"></i>6. Exchange Programs & Excursions</h6>
                        <p class="small"><strong>Parents may contribute to optional activities such as:</strong></p>
                        <ul class="small">
                            <li>Out-of-town tournaments</li>
                            <li>Inter-county friendly matches</li>
                            <li>Educational trips</li>
                            <li>Motivational events</li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-warning mt-4">
                    <h6 class="fw-bold">Why Parents Love This Program</h6>
                    <ul class="mb-0">
                        <li>Keeps children productive</li>
                        <li>Blends fun, education & discipline</li>
                        <li>Safe and well-supervised</li>
                        <li>Builds strong football and academic foundations</li>
                        <li>Introduces computer skills early</li>
                        <li>Helps shy children build confidence</li>
                        <li>Reduces negative peer influence during holidays</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('enroll') }}" class="btn btn-warning">Enroll Now</a>
            </div>
        </div>
    </div>
</div>

<!-- Coding Classes Modal -->
<div class="modal fade" id="codingClassesModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Computer & Coding Classes</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <span class="badge bg-success fs-6 mb-2">Fee: KSH 3,500/month</span><br>
                            <span class="badge bg-secondary fs-6 mb-2">Flexible Schedule</span><br>
                            <span class="badge bg-info fs-6">Ages: 7–18 years</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="fw-bold text-success">Program Overview</h6>
                        <p>This program prepares children for the digital world by teaching them computer skills,
                            creativity, and safe use of technology from a young age. It supports CBC learning through
                            hands-on digital exploration, problem-solving, and innovation.</p>
                        <p><strong>Children learn through step-by-step, fun, practical lessons — no prior knowledge
                                needed.</strong></p>
                    </div>
                </div>

                <h6 class="fw-bold text-success mt-4">What Your Child Will Learn</h6>
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-desktop text-primary me-2"></i>1. Computer Foundations</h6>
                        <ul class="small">
                            <li>Understanding the computer</li>
                            <li>Typing skills</li>
                            <li>Working with documents</li>
                            <li>Saving and organizing files</li>
                            <li>Basic troubleshooting</li>
                        </ul>

                        <h6><i class="fas fa-shield-alt text-warning me-2"></i>2. Internet & Digital Safety</h6>
                        <ul class="small">
                            <li>Safe browsing</li>
                            <li>Avoiding online risks</li>
                            <li>Responsible digital behavior</li>
                            <li>How to use the internet for learning</li>
                        </ul>

                        <h6><i class="fas fa-code text-info me-2"></i>3. Beginner Coding</h6>
                        <p class="small"><strong>Kids will learn:</strong></p>
                        <ul class="small">
                            <li>Logical thinking</li>
                            <li>Coding basics (Scratch, Blockly or simplified Python depending on age)</li>
                            <li>Making animations & simple games</li>
                            <li>Algorithms for kids</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-tools text-secondary me-2"></i>4. Software Literacy</h6>
                        <ul class="small">
                            <li>Word processing</li>
                            <li>Presentations</li>
                            <li>Basic design tools</li>
                            <li>Educational apps</li>
                            <li>Online learning tools</li>
                        </ul>

                        <h6><i class="fas fa-palette text-purple me-2"></i>5. Creative Digital Projects</h6>
                        <ul class="small">
                            <li>Build a simple game</li>
                            <li>Create a digital story</li>
                            <li>Make a mini website (older kids)</li>
                            <li>Robotics & STEM demos</li>
                            <li>Group tech challenges</li>
                        </ul>

                        <h6><i class="fas fa-brain text-danger me-2"></i>6. Life Skills in Technology</h6>
                        <ul class="small">
                            <li>Problem-solving</li>
                            <li>Collaboration</li>
                            <li>Innovation mindset</li>
                            <li>Project management</li>
                            <li>Critical thinking</li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-success mt-4">
                    <h6 class="fw-bold">How This Program Supports Their Future</h6>
                    <ul class="mb-0">
                        <li>Builds digital confidence</li>
                        <li>Introduces STEM early</li>
                        <li>Supports schoolwork & CBC requirements</li>
                        <li>Prepares them for high-school computer studies</li>
                        <li>Sparks interest in tech careers</li>
                        <li>Makes learning fun and practical</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('enroll') }}" class="btn btn-success">Enroll Now</a>
            </div>
        </div>
    </div>
</div>


@endsection

@push('styles')
<style>
/* Program Grid Layout - Single Column Full Width */
.programs-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    width: 100%;
    max-width: none;
    margin: 0;
    padding: 1rem 0.25rem;
}

/* ===== 50/50 SPLIT PROGRAM CARDS ===== */
.program-card-50-50 {
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: none !important;
}

.program-card-50-50:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
}

.program-split-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 300px;
}



/* Program Content Section */
.program-content {
    padding: 2rem;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.program-content-inner {
    width: 100%;
}

.program-category {
    margin-bottom: 1rem;
}

.program-category .badge {
    font-size: 0.85rem;
    font-weight: 600;
    padding: 0.4rem 0.8rem;
}

.program-title {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.25rem;
    color: #2c3e50;
}

.program-details {
    margin-bottom: 1.25rem;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.detail-item i {
    width: 18px;
    margin-right: 0.5rem;
    font-size: 1rem;
}

.program-description {
    font-size: 0.95rem;
    line-height: 1.5;
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.program-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.program-actions .btn {
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    flex: 1;
    min-width: 120px;
    padding: 0.6rem 1.2rem;
    font-size: 0.9rem;
}

.program-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* Program Image Section */
.program-image {
    position: relative;
    overflow: hidden;
}

.program-image-container {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 300px;
}

.program-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.program-card-50-50:hover .program-img {
    transform: scale(1.05);
}

.program-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.3) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.program-card-50-50:hover .program-image-overlay {
    opacity: 1;
}

.image-icon {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    padding: 1.5rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

/* Program Card Container - Full Width */
.program-card-container {
    width: 100%;
    max-width: none;
    display: flex;
    align-items: stretch;
}

.hero-section {
    background-attachment: fixed;
    background-size: cover;
    width: 100%;
}

/* ===== COMPREHENSIVE RESPONSIVE DESIGN FOR ALL VIEWPORTS ===== */

/* Ultra-wide screens (1600px and above) */
@media (min-width: 1600px) {
    .programs-hero {
        min-height: 400px;
    }

    .program-content {
        padding: 3rem;
    }

    .program-title {
        font-size: 1.75rem;
    }

    .program-description {
        font-size: 1.05rem;
    }

    .hero-title {
        font-size: 4rem !important;
    }

    .hero-subtitle {
        font-size: 1.5rem !important;
    }
}

/* Extra large screens (1200px - 1599px) */
@media (min-width: 1200px) and (max-width: 1599.98px) {
    .programs-hero {
        min-height: 380px;
    }

    .program-content {
        padding: 2.5rem;
    }

    .program-title {
        font-size: 1.6rem;
    }

    .hero-title {
        font-size: 3.5rem !important;
    }
}

/* Large desktop (992px - 1199px) */
@media (min-width: 992px) and (max-width: 1199.98px) {
    .programs-hero {
        min-height: 360px;
    }

    .program-content {
        padding: 2.25rem;
    }

    .program-title {
        font-size: 1.5rem;
    }

    .hero-title {
        font-size: 3rem !important;
    }
}

/* Standard tablet landscape (768px - 991px) */
@media (min-width: 768px) and (max-width: 991.98px) {
    .program-split-layout {
        grid-template-columns: 1fr 1fr;
        min-height: 280px;
    }

    .program-content {
        padding: 2rem;
    }

    .program-title {
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }

    .program-description {
        font-size: 0.95rem;
        margin-bottom: 1.25rem;
    }

    .program-image {
        min-height: 280px;
    }

    .hero-title {
        font-size: 2.75rem !important;
    }

    .hero-subtitle {
        font-size: 1.25rem !important;
    }
}

/* Tablet portrait (576px - 767px) */
@media (min-width: 576px) and (max-width: 767.98px) {
    .program-split-layout {
        grid-template-columns: 1fr;
        min-height: auto;
    }

    .program-image {
        order: -1;
        height: 220px;
        min-height: 220px;
    }

    .program-content {
        padding: 1.75rem;
    }

    .program-title {
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }

    .program-description {
        font-size: 0.9rem;
        margin-bottom: 1.25rem;
    }

    .program-actions {
        flex-direction: column;
        gap: 0.75rem;
    }

    .program-actions .btn {
        min-width: auto;
        width: 100%;
        font-size: 0.9rem;
        padding: 0.7rem 1.5rem;
    }

    .hero-title {
        font-size: 2.5rem !important;
    }

    .hero-subtitle {
        font-size: 1.1rem !important;
    }
}

/* Mobile landscape (481px - 575px) */
@media (min-width: 481px) and (max-width: 575.98px) {
    .programs-grid {
        gap: 1.25rem;
        padding: 1.25rem 0.5rem;
    }

    .program-content {
        padding: 1.5rem;
    }

    .program-title {
        font-size: 1.2rem;
        margin-bottom: 0.875rem;
    }

    .program-description {
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }

    .program-image {
        height: 200px;
        min-height: 200px;
    }

    .detail-item {
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
    }

    .detail-item i {
        width: 16px;
        margin-right: 0.5rem;
        font-size: 0.95rem;
    }

    .image-icon {
        padding: 1.25rem;
    }

    .image-icon .fa-3x {
        font-size: 1.75rem !important;
    }

    .hero-title {
        font-size: 2.25rem !important;
    }

    .hero-subtitle {
        font-size: 1rem !important;
    }
}

/* Mobile portrait (320px - 480px) */
@media (max-width: 480.98px) {
    .programs-grid {
        gap: 1rem;
        padding: 1rem 0.25rem;
    }

    .program-content {
        padding: 1.25rem 0.875rem;
    }

    .program-title {
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .program-description {
        font-size: 0.85rem;
        line-height: 1.4;
        margin-bottom: 1rem;
    }

    .program-image {
        height: 180px;
        min-height: 180px;
    }

    .detail-item {
        font-size: 0.8rem;
        margin-bottom: 0.375rem;
    }

    .detail-item i {
        width: 14px;
        margin-right: 0.375rem;
        font-size: 0.9rem;
    }

    .program-category .badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }

    .image-icon {
        padding: 1rem;
    }

    .image-icon .fa-3x {
        font-size: 1.5rem !important;
    }

    .hero-title {
        font-size: 2rem !important;
    }

    .hero-subtitle {
        font-size: 0.95rem !important;
    }

    .hero-stats .stat-number {
        font-size: 1.5rem !important;
    }
}

/* Extra small devices (320px and below) */
@media (max-width: 319.98px) {
    .programs-grid {
        gap: 0.75rem;
        padding: 0.75rem 0.125rem;
    }

    .program-content {
        padding: 1rem 0.75rem;
    }

    .program-title {
        font-size: 1rem;
        margin-bottom: 0.625rem;
    }

    .program-description {
        font-size: 0.8rem;
        line-height: 1.35;
        margin-bottom: 0.875rem;
    }

    .program-image {
        height: 160px;
        min-height: 160px;
    }

    .hero-title {
        font-size: 1.75rem !important;
    }

    .hero-subtitle {
        font-size: 0.9rem !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .program-card-50-50 {
        border-width: 3px !important;
    }

    .program-content {
        border: 2px solid #000;
    }

    .btn {
        border-width: 2px;
    }

    .program-img {
        filter: contrast(1.2);
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {

    .program-card-50-50,
    .program-img,
    .image-icon,
    .hero-icon,
    .floating-ball,
    .scroll-indicator {
        animation: none !important;
        transition: none !important;
    }
}

/* Ensure no horizontal overflow */
body,
html {
    overflow-x: hidden;
}

.container,
.container-fluid {
    max-width: none !important;
}

/* Maximum width utilization */
section.py-5 {
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}

.container-fluid {
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* ===== PROGRAMS HERO SECTION ===== */
.programs-hero {
    position: relative;
    min-height: 350px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

/* Background Image Layer */
.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-image-layer {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(13, 71, 161, 0.9) 0%, rgba(25, 118, 210, 0.8) 100%),
        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    background-size: cover, 50px 50px;
    background-position: center, center;
    animation: backgroundShift 20s ease-in-out infinite;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle at 30% 70%, rgba(255, 193, 7, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 70% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
}

/* Floating Football Animation */
.hero-decoration {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 2;
}

.floating-ball {
    position: absolute;
    color: rgba(255, 255, 255, 0.2);
    font-size: 2rem;
    animation: float 6s ease-in-out infinite;
}

.ball-1 {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.ball-2 {
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.ball-3 {
    bottom: 20%;
    left: 20%;
    animation-delay: 4s;
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.3;
    }

    50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 0.6;
    }
}

@keyframes backgroundShift {

    0%,
    100% {
        background-position: center, center;
    }

    50% {
        background-position: center, 20px 20px;
    }
}

/* Hero Content */
.hero-content {
    position: relative;
    z-index: 3;
}

.hero-icon {
    animation: iconBounce 2s ease-in-out infinite;
}

.icon-container {
    display: inline-block;
    padding: 1.5rem;
    border-radius: 50%;
    background: rgba(255, 193, 7, 0.2);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 193, 7, 0.3);
}

@keyframes iconBounce {

    0%,
    100% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-10px);
    }
}

.hero-title {
    background: linear-gradient(45deg, #ffffff, #ffc107);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    position: relative;
}

.hero-subtitle-container {
    position: relative;
}

.hero-subtitle {
    position: relative;
    z-index: 2;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.subtitle-accent {
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, #ffc107, #ff8f00);
    border-radius: 2px;
}

/* Hero Actions */
.hero-actions {
    animation: fadeInUp 1s ease-out 0.5s both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-actions .btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hero-actions .btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.hero-actions .btn:hover::before {
    left: 100%;
}

.hero-actions .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

/* Hero Stats */
.hero-stats {
    animation: fadeInUp 1s ease-out 0.7s both;
}

.stat-item {
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateY(-5px);
}

.stat-number {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.stat-label {
    opacity: 0.9;
    font-weight: 500;
}

/* Scroll Indicator */
.scroll-indicator {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
    animation: scrollBounce 2s infinite;
}

.scroll-arrow {
    width: 40px;
    height: 40px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.7);
    cursor: pointer;
    transition: all 0.3s ease;
}

.scroll-arrow:hover {
    border-color: #ffc107;
    color: #ffc107;
    transform: translateX(-50%) scale(1.1);
}

@keyframes scrollBounce {

    0%,
    100% {
        transform: translateX(-50%) translateY(0);
    }

    50% {
        transform: translateX(-50%) translateY(-10px);
    }
}

/* Responsive Design for Hero */
@media (max-width: 768px) {
    .programs-hero {
        min-height: 400px;
    }

    .hero-title {
        font-size: 2.5rem !important;
    }

    .hero-subtitle {
        font-size: 1.25rem !important;
    }

    .hero-icon .fa-4x {
        font-size: 3rem !important;
    }

    .hero-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }

    .floating-ball {
        font-size: 1.5rem;
    }

    .stat-item {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .programs-hero {
        min-height: 350px;
    }

    .hero-title {
        font-size: 2rem !important;
    }

    .hero-subtitle {
        font-size: 1.1rem !important;
    }

    .icon-container {
        padding: 1rem;
    }

    .scroll-indicator {
        bottom: 20px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Hero Section Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for hero buttons
    const exploreBtn = document.querySelector('a[href="#programs-section"]');
    if (exploreBtn) {
        exploreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId) || document.querySelector(
                '.programs-grid');
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }

    // Scroll indicator functionality
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function() {
            const targetElement = document.querySelector('.programs-grid');
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }
});

// Add smooth animations when modals are shown
document.querySelectorAll('[data-bs-target^="#"]').forEach(button => {
    button.addEventListener('click', function() {
        const modalId = this.getAttribute('data-bs-target');
        const modal = document.querySelector(modalId);
        if (modal) {
            modal.addEventListener('shown.bs.modal', function() {
                // Add any modal-specific animations here
            });
        }
    });
});

// Scroll animations for cards with new layout
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Initially hide program cards and observe them
document.querySelectorAll('.program-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(50px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});

// Ensure full width responsive behavior
window.addEventListener('resize', function() {
    const cards = document.querySelectorAll('.program-card');
    cards.forEach(card => {
        // Ensure consistent height calculation
        card.style.height = 'auto';
        card.style.minHeight = '400px';
    });
});

// Initial setup
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.program-card');
    cards.forEach(card => {
        card.style.minHeight = '400px';
    });
});
</script>
@endpush
