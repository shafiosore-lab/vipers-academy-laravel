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

    /* Professional Journey Cards */
    .journey-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 1px solid var(--gray-300);
        border-radius: 16px;
        padding: 28px 20px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .journey-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), #ff6b6b);
    }

    .journey-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(234, 28, 77, 0.15);
        border-color: var(--primary);
    }

    .journey-year {
        font-size: 2rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 12px;
        line-height: 1;
    }

    .journey-description {
        color: var(--gray-600);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Professional Value Cards */
    .value-card-new {
        background: white;
        border-radius: 20px;
        padding: 35px 25px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        position: relative;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .value-card-new::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), #ff6b6b);
        transition: width 0.3s ease;
        border-radius: 2px;
    }

    .value-card-new:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        border-color: var(--gray-300);
    }

    .value-card-new:hover::after {
        width: 60%;
    }

    .value-icon-new {
        font-size: 3rem;
        margin-bottom: 18px;
        display: inline-block;
        padding: 16px;
        background: linear-gradient(135deg, rgba(234, 28, 77, 0.1) 0%, rgba(255, 107, 107, 0.1) 100%);
        border-radius: 50%;
        line-height: 1;
    }

    .value-card-new h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--dark);
    }

    .value-card-new p {
        color: var(--gray-600);
        font-size: 0.9rem;
        line-height: 1.7;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .section { padding: 40px 20px; }
        .journey-card { padding: 20px 15px; }
        .journey-year { font-size: 1.6rem; }
        .value-card-new { padding: 25px 20px; }
        .value-icon-new { font-size: 2.5rem; padding: 12px; }
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

    <!-- TIMELINE - Professional Card Style -->
    <section class="section">
        <h2 class="section-title">{{ \App\Models\PageContent::getTitle('about', 'journey') ?: 'Our Journey' }}</h2>

        <!-- Timeline Cards -->
        <div class="row g-4">
            @php
                $journeyEntries = \App\Models\PageContent::getJourneyEntries();
            @endphp
            @forelse($journeyEntries as $year => $description)
                <div class="col-md-6 col-lg-3">
                    <div class="journey-card h-100">
                        <div class="journey-year">{{ $year }}</div>
                        <div class="journey-description">{{ $description }}</div>
                    </div>
                </div>
            @empty
                <!-- Fallback content -->
                <div class="col-md-6 col-lg-3">
                    <div class="journey-card h-100">
                        <div class="journey-year">2017</div>
                        <div class="journey-description">Founded as a grassroots initiative to nurture football talent and discipline.</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="journey-card h-100">
                        <div class="journey-year">2019</div>
                        <div class="journey-description">Expanded training programs and community engagement.</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="journey-card h-100">
                        <div class="journey-year">2021</div>
                        <div class="journey-description">First players progressed to competitive clubs and secondary schools.</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="journey-card h-100">
                        <div class="journey-year">2024+</div>
                        <div class="journey-description">20+ players on sports scholarships; expanding partnerships and facilities.</div>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- VALUES - Professional Card Style -->
    <section class="values-section">
        <h2 class="section-title">{{ \App\Models\PageContent::getTitle('about', 'values') ?: 'Our Core Values' }}</h2>

        <!-- Values Cards -->
        <div class="row g-4">
            @php
                $valuesEntries = \App\Models\PageContent::getValuesEntries();
            @endphp
            @forelse($valuesEntries as $entry)
                @php
                    $valueData = is_array(json_decode($entry->value, true)) ? json_decode($entry->value, true) : ['icon' => '⭐', 'title' => ucfirst($entry->key), 'description' => $entry->value];
                @endphp
                <div class="col-md-6 col-lg-3">
                    <div class="value-card-new h-100">
                        <div class="value-icon-new">{{ $valueData['icon'] ?? '⭐' }}</div>
                        <h3>{{ $valueData['title'] ?? ucfirst($entry->key) }}</h3>
                        <p>{{ $valueData['description'] ?? $entry->value }}</p>
                    </div>
                </div>
            @empty
                <!-- Fallback content -->
                <div class="col-md-6 col-lg-3">
                    <div class="value-card-new h-100">
                        <div class="value-icon-new">🎯</div>
                        <h3>Discipline</h3>
                        <p>Consistency, responsibility, and self-control on and off the pitch.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="value-card-new h-100">
                        <div class="value-icon-new">🤝</div>
                        <h3>Respect</h3>
                        <p>For teammates, parents, coaches, and the wider community.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="value-card-new h-100">
                        <div class="value-icon-new">💪</div>
                        <h3>Hard Work</h3>
                        <p>Growth through commitment, effort, and perseverance.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="value-card-new h-100">
                        <div class="value-icon-new">🛡️</div>
                        <h3>Child Safety & Accountability</h3>
                        <p>Safe environments, parental involvement, and transparent operations.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection
