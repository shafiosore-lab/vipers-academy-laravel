@extends('layouts.academy')

@section('title', 'About Us - Vipers Academy Kenya')
@section('meta_description', 'Learn about Vipers Academy, founded in 2017, building the next generation of elite Kenyan footballers.')

@section('content')
<style>
    :root {
        --primary: #ea1c4d;
        --primary-dark: #c0173f;
        --dark: #1a1a1a;
        --gray-900: #333;
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

    /* Container */
    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Hero Section */
    .hero-section {
        text-align: center;
        padding: 40px 20px 30px;
        margin-bottom: 50px;
    }

    .hero-section h1 {
        font-size: clamp(2rem, 4vw, 2.5rem);
        color: var(--dark);
        margin-bottom: 15px;
        font-weight: 700;
    }

    .hero-section p {
        font-size: 1.1rem;
        color: var(--gray-600);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 20px;
        margin: 30px auto 0;
        max-width: 600px;
    }

    .stat-card {
        background: var(--white);
        padding: 25px 15px;
        border-radius: var(--radius);
        text-align: center;
        border: 1px solid var(--gray-300);
        transition: var(--transition);
    }

    .stat-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow);
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--gray-600);
        font-weight: 500;
    }

    /* CTA Button */
    .cta-button {
        display: inline-block;
        padding: 14px 32px;
        background: var(--primary);
        color: var(--white);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        transition: var(--transition);
        margin-top: 20px;
    }

    .cta-button:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* Section Base */
    .section {
        background: var(--white);
        padding: 50px 30px;
        border-radius: var(--radius);
        margin-bottom: 30px;
        border: 1px solid var(--gray-300);
    }

    .section-title {
        font-size: 1.9rem;
        color: var(--dark);
        margin-bottom: 20px;
        text-align: center;
        font-weight: 700;
    }

    .section-description {
        font-size: 1.05rem;
        color: var(--gray-600);
        text-align: center;
        max-width: 800px;
        margin: 0 auto 35px;
        line-height: 1.7;
    }

    /* Features Grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .feature-card {
        background: var(--bg-pink);
        padding: 25px;
        border-radius: 10px;
        border-left: 3px solid var(--primary);
        transition: var(--transition);
    }

    .feature-card:hover {
        background: #fce7e7;
        transform: translateX(5px);
    }

    .feature-icon {
        font-size: 2rem;
        margin-bottom: 12px;
    }

    .feature-card h3 {
        font-size: 1.2rem;
        color: var(--dark);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .feature-card p {
        font-size: 1rem;
        color: var(--gray-600);
        line-height: 1.6;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 40px;
        max-width: 700px;
        margin: 0 auto;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--gray-300);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-left: 25px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -35px;
        top: 3px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--primary);
        border: 2px solid var(--white);
        box-shadow: 0 0 0 2px var(--primary);
    }

    .timeline-year {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 6px;
    }

    .timeline-content {
        font-size: 1rem;
        color: var(--gray-600);
        line-height: 1.6;
    }

    /* Professional Values Section */
    .values-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 60px 30px;
        border-radius: var(--radius);
        border: 1px solid var(--gray-300);
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .value-card {
        background: var(--white);
        padding: 35px 25px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-300);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        transform: scaleX(0);
        transition: var(--transition);
    }

    .value-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    .value-card:hover::before {
        transform: scaleX(1);
    }

    .value-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: var(--white);
        box-shadow: 0 4px 15px rgba(234, 28, 77, 0.3);
    }

    .value-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 12px;
        text-align: center;
    }

    .value-description {
        font-size: 1rem;
        color: var(--gray-600);
        line-height: 1.7;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .about-container {
            padding: 15px;
        }

        .section {
            padding: 35px 20px;
        }

        .values-section {
            padding: 45px 20px;
        }

        .timeline {
            padding-left: 30px;
        }

        .timeline-item {
            padding-left: 20px;
        }

        .values-grid,
        .features-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .stat-number {
            font-size: 2rem;
        }

        .value-icon {
            width: 60px;
            height: 60px;
            font-size: 1.7rem;
        }

        .value-title {
            font-size: 1.25rem;
        }
    }
</style>

<div class="about-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <h1>Built for Champions</h1>
        <p>Founded in 2017, Vipers Academy is shaping the future of Kenyan football through elite coaching, discipline, and a development-first approach.</p>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">2017</div>
                <div class="stat-label">Year Founded</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">Players Trained</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">50+</div>
                <div class="stat-label">Pro Graduates</div>
            </div>
        </div>

        <a href="{{ route('register') }}" class="cta-button">Join Vipers Academy</a>
    </section>

    <!-- Who We Are -->
    <section class="section">
        <h2 class="section-title">Who We Are</h2>
        <p class="section-description">
            Vipers Academy is a modern football development institution committed to raising disciplined, skilled, and mentally strong players. We integrate structured training, character building, sports science principles, and academic balance to prepare young athletes for elite performance.
        </p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">⚽</div>
                <h3>Elite Player Development</h3>
                <p>European-inspired technical, tactical, physical, and mental training.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📘</div>
                <h3>Discipline & Character</h3>
                <p>Respect, leadership, teamwork, and emotional intelligence embedded in every session.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎓</div>
                <h3>Education Friendly</h3>
                <p>Training designed to complement school life and responsible academic focus.</p>
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="section">
        <h2 class="section-title">Our Journey Since 2017</h2>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-year">2017</div>
                <div class="timeline-content">Vipers Academy officially founded with the goal of transforming grassroots football.</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2019</div>
                <div class="timeline-content">Expanded to multiple training centers and introduced structured academy programs.</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2021</div>
                <div class="timeline-content">Developed the first batch of players progressing to national and club levels.</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2023</div>
                <div class="timeline-content">Launched junior development, holiday camps, and coaching education programs.</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-year">2024+</div>
                <div class="timeline-content">Moving toward a fully equipped academy, sports science integration, and global partnerships.</div>
            </div>
        </div>
    </section>

    <!-- Professional Values -->
    <section class="values-section">
        <h2 class="section-title">Our Core Values</h2>
        <p class="section-description">
            These principles guide everything we do, from training sessions to life lessons beyond the pitch.
        </p>

        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">🎯</div>
                <h3 class="value-title">Discipline</h3>
                <p class="value-description">
                    We instill self-control, consistency, and accountability in every player, building champions on and off the field.
                </p>
            </div>

            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3 class="value-title">Respect</h3>
                <p class="value-description">
                    Honoring teammates, coaches, opponents, and the game itself forms the foundation of our academy culture.
                </p>
            </div>

            <div class="value-card">
                <div class="value-icon">💪</div>
                <h3 class="value-title">Hard Work</h3>
                <p class="value-description">
                    Dedication, effort, and perseverance are non-negotiable. Success comes to those who consistently push their limits.
                </p>
            </div>

            <div class="value-card">
                <div class="value-icon">⭐</div>
                <h3 class="value-title">Excellence</h3>
                <p class="value-description">
                    We pursue the highest standards in training, performance, and personal development, refusing to settle for mediocrity.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection
