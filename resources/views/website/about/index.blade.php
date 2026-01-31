@extends('layouts.academy')

@section('title', 'About Us - Mumias Vipers Academy')
@section('meta_description', 'Mumias Vipers Academy is a community-based youth development organization using football to unlock education, discipline, and opportunity. Over 20 players on high school sports scholarships.')

@section('content')
<style>
    :root {
        --primary: #ea1c4d;
        --primary-dark: #c0173f;
        --dark: #1a1a1a;
        --gray-600: #6b7280;
        --gray-300: #e5e7eb;
        --white: #ffffff;
        --bg-light: #f8f9fa;
        --bg-pink: #fef2f2;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
        --radius: 12px;
        --transition: 0.3s ease;
    }

    .about-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    .hero-section {
        text-align: center;
        padding: 50px 20px;
    }

    .hero-section h1 {
        font-size: clamp(2.2rem, 4vw, 2.8rem);
        font-weight: 800;
        margin-bottom: 15px;
        color: #000;
    }

    .hero-section p {
        max-width: 780px;
        margin: auto;
        font-size: 1.1rem;
        color: var(--gray-600);
        line-height: 1.7;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 20px;
        max-width: 700px;
        margin: 35px auto 0;
    }

    .stat-card {
        background: var(--white);
        padding: 25px;
        border-radius: var(--radius);
        border: 1px solid var(--gray-300);
        text-align: center;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary);
        box-shadow: var(--shadow);
    }

    .stat-number {
        font-size: 2.3rem;
        font-weight: 800;
        color: var(--primary);
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--gray-600);
    }

    .cta-button {
        display: inline-block;
        margin: 25px 8px 0;
        padding: 14px 34px;
        background: var(--primary);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .cta-button.secondary {
        background: #222;
    }

    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .section {
        background: white;
        padding: 55px 30px;
        border-radius: var(--radius);
        margin-bottom: 35px;
        border: 1px solid var(--gray-300);
    }

    .section-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .section-description {
        text-align: center;
        max-width: 850px;
        margin: auto auto 40px;
        color: var(--gray-600);
        line-height: 1.8;
    }

    .features-grid,
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .feature-card {
        background: var(--bg-pink);
        padding: 25px;
        border-left: 4px solid var(--primary);
        border-radius: 10px;
    }

    .feature-card h3 {
        margin-bottom: 10px;
    }

    .timeline {
        max-width: 750px;
        margin: auto;
        padding-left: 35px;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gray-300);
    }

    .timeline-item {
        margin-bottom: 30px;
        position: relative;
        padding-left: 25px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -35px;
        top: 4px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--primary);
    }

    .timeline-year {
        font-weight: 800;
        color: var(--primary);
        font-size: 1.3rem;
    }

    .values-section {
        background: linear-gradient(135deg, #f8f9fa, #fff);
        padding: 60px 30px;
        border-radius: var(--radius);
        border: 1px solid var(--gray-300);
    }

    .value-card {
        background: white;
        padding: 30px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        text-align: center;
    }

    .value-icon {
        font-size: 2rem;
        margin-bottom: 15px;
        color: var(--primary);
    }

    @media (max-width: 768px) {
        .section { padding: 40px 20px; }
    }
</style>

<div class="about-container">

    <!-- HERO -->
    <section class="hero-section">
        <h1>Using Football to Unlock Education & Opportunity</h1>
        <p>
            Founded in 2017, Mumias Vipers Academy is a community-based youth development organization
            using football to build discipline, life skills, and access to education —
            with <strong>over 20 players currently on high school sports scholarships</strong>.
        </p>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">2017</div>
                <div class="stat-label">Founded</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">Youth Served</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Scholarship Beneficiaries</div>
            </div>
        </div>

        <a href="{{ route('enrol') }}" class="cta-button">Enroll a Player</a>
        <a href="{{ route('contact') }}" class="cta-button secondary">Partner With Us</a>
    </section>

    <!-- WHO WE ARE -->
    <section class="section">
        <h2 class="section-title">Who We Are</h2>
        <p class="section-description">
            Mumias Vipers Academy exists to ensure talented young people — regardless of background —
            can access structured football, mentorship, and education.
            Football is our tool; youth development is our mission.
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <h3>Structured Player Development</h3>
                <p>Age-appropriate training focused on technical, physical, and mental growth.</p>
            </div>
            <div class="feature-card">
                <h3>Education First</h3>
                <p>Training schedules respect school commitments and academic responsibility.</p>
            </div>
            <div class="feature-card">
                <h3>Life Skills & Discipline</h3>
                <p>Leadership, respect, accountability, and emotional resilience.</p>
            </div>
        </div>
    </section>

    <!-- EDUCATION THROUGH SPORT -->
    <section class="section">
        <h2 class="section-title">Education Through Sport</h2>
        <p class="section-description">
            Football is not the final destination — education is.
            Through talent, discipline, and mentorship,
            <strong>over 20 Vipers players have secured high school sports scholarships</strong>,
            easing financial burden on families and creating long-term opportunity.
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <h3>🎓 Sports Scholarships</h3>
                <p>Direct pathways to secondary education through football performance.</p>
            </div>
            <div class="feature-card">
                <h3>👨‍👩‍👦 Community Impact</h3>
                <p>Strong parent engagement and reduced household education costs.</p>
            </div>
            <div class="feature-card">
                <h3>📈 Measurable Outcomes</h3>
                <p>Clear tracking of player progression and school placement.</p>
            </div>
        </div>
    </section>

    <!-- TIMELINE -->
    <section class="section">
        <h2 class="section-title">Our Journey</h2>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-year">2017</div>
                <p>Founded as a grassroots initiative to nurture football talent and discipline.</p>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2019</div>
                <p>Expanded training programs and community engagement.</p>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2021</div>
                <p>First players progressed to competitive clubs and secondary schools.</p>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2024+</div>
                <p>20+ players on sports scholarships; expanding partnerships and facilities.</p>
            </div>
        </div>
    </section>

    <!-- VALUES -->
    <section class="values-section">
        <h2 class="section-title">Our Core Values</h2>

        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">🎯</div>
                <h3>Discipline</h3>
                <p>Consistency, responsibility, and self-control on and off the pitch.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3>Respect</h3>
                <p>For teammates, parents, coaches, and the wider community.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">💪</div>
                <h3>Hard Work</h3>
                <p>Growth through commitment, effort, and perseverance.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🛡️</div>
                <h3>Child Safety & Accountability</h3>
                <p>Safe environments, parental involvement, and transparent operations.</p>
            </div>
        </div>
    </section>

</div>
@endsection
