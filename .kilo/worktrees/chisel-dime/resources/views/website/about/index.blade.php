@extends('layouts.academy')

@section('title', 'About Us - Vipers Football Academy')
@section('meta_description', 'Learn about Vipers Football Academy\'s mission, vision, values, and our journey in developing young football talent.')

@section('content')
<style>
:root {
    --primary: #ea1c4d;
    --primary-dark: #c0173f;
    --accent: #ffd700;
    --dark: #1a1a1a;
    --gray-900: #1e293b;
    --gray-600: #64748b;
    --gray-300: #e2e8f0;
    --gray-100: #f8fafc;
    --white: #ffffff;
    --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
    --radius: 16px;
    --transition: 0.3s ease;
}

/* Section Spacing */
.section {
    padding: 80px 20px;
}

.section-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 60px;
}

.section-title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--accent));
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.2rem;
    color: var(--gray-600);
    line-height: 1.6;
    margin-top: 1.5rem;
}

/* Mission Vision Section */
.mission-vision-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 3rem;
    margin-bottom: 4rem;
}

.mission-card, .vision-card {
    background: var(--white);
    padding: 3rem 2rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    text-align: center;
    transition: var(--transition);
}

.mission-card:hover, .vision-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.mission-card h3, .vision-card h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.mission-card p, .vision-card p {
    font-size: 1rem;
    color: var(--gray-600);
    line-height: 1.7;
}

/* Values Section */
.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}

.value-card {
    background: var(--white);
    padding: 2.5rem 2rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    text-align: center;
    transition: var(--transition);
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.value-icon {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1.5rem;
    display: block;
}

.value-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 1rem;
}

.value-description {
    font-size: 1rem;
    color: var(--gray-600);
    line-height: 1.6;
}

/* Journey Section */
.journey-section {
    background: linear-gradient(135deg, var(--gray-100) 0%, var(--white) 100%);
    padding: 80px 20px;
}

.journey-container {
    max-width: 1000px;
    margin: 0 auto;
}

.journey-timeline {
    position: relative;
    padding-left: 50px;
}

.journey-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--primary);
}

.journey-item {
    position: relative;
    margin-bottom: 3rem;
    padding-left: 50px;
}

.journey-item::before {
    content: '';
    position: absolute;
    left: -35px;
    top: 10px;
    width: 12px;
    height: 12px;
    background: var(--primary);
    border-radius: 50%;
    border: 3px solid var(--white);
    box-shadow: 0 0 0 3px var(--primary);
}

.journey-year {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.5rem;
}

.journey-description {
    font-size: 1rem;
    color: var(--gray-600);
    line-height: 1.6;
}

/* Team Section */
.team-section {
    padding: 80px 20px;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.team-member {
    background: var(--white);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.team-member:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.team-photo {
    height: 250px;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.team-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.team-member:hover .team-photo img {
    transform: scale(1.05);
}

.team-info {
    padding: 1.5rem;
    text-align: center;
}

.team-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.team-role {
    font-size: 1rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.team-bio {
    font-size: 0.9rem;
    color: var(--gray-600);
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 768px) {
    .section {
        padding: 60px 15px;
    }

    .mission-vision-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .values-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .journey-timeline {
        padding-left: 30px;
    }

    .journey-item {
        padding-left: 30px;
    }

    .journey-item::before {
        left: -25px;
    }

    .team-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .values-grid {
        grid-template-columns: 1fr;
    }

    .journey-section, .team-section {
        padding: 60px 15px;
    }

    .section-header {
        margin-bottom: 40px;
    }
}

/* Fade-in Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mission-card, .vision-card, .value-card, .journey-item, .team-member {
    animation: fadeInUp 0.6s ease-out backwards;
}

.mission-card:nth-child(1) { animation-delay: 0.1s; }
.vision-card:nth-child(1) { animation-delay: 0.2s; }
.value-card:nth-child(1) { animation-delay: 0.1s; }
.value-card:nth-child(2) { animation-delay: 0.2s; }
.value-card:nth-child(3) { animation-delay: 0.3s; }
.value-card:nth-child(4) { animation-delay: 0.4s; }
.journey-item:nth-child(1) { animation-delay: 0.1s; }
.journey-item:nth-child(2) { animation-delay: 0.2s; }
.journey-item:nth-child(3) { animation-delay: 0.3s; }
.team-member:nth-child(1) { animation-delay: 0.1s; }
.team-member:nth-child(2) { animation-delay: 0.2s; }
.team-member:nth-child(3) { animation-delay: 0.3s; }

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

<!-- Mission & Vision Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">
                @php
                    $missionVisionTitle = isset($pageContent['mission']) ? $pageContent['mission']->firstWhere('key', 'title') : null;
                @endphp
                {{ $missionVisionTitle?->value ?: 'Our Mission & Vision' }}
            </h1>
            <p class="section-subtitle">
                @php
                    $missionVisionSubtitle = isset($pageContent['mission']) ? $pageContent['mission']->firstWhere('key', 'subtitle') : null;
                @endphp
                {{ $missionVisionSubtitle?->value ?: 'Guiding principles that drive our commitment to excellence in youth football development' }}
            </p>
        </div>

        <div class="mission-vision-grid">
            <!-- Mission -->
            <div class="mission-card">
                <h3>
                    @php
                        $missionTitle = isset($pageContent['mission']) ? $pageContent['mission']->firstWhere('key', 'mission_title') : null;
                    @endphp
                    {{ $missionTitle?->value ?: 'Our Mission' }}
                </h3>
                <p>
                    @php
                        $missionContent = isset($pageContent['mission']) ? $pageContent['mission']->firstWhere('key', 'mission_content') : null;
                    @endphp
                    {{ $missionContent?->value ?: 'To nurture and develop young football talent through comprehensive training programs, fostering both technical skills and personal growth in a supportive environment that prepares players for success at every level.' }}
                </p>
            </div>

            <!-- Vision -->
            <div class="vision-card">
                <h3>
                    @php
                        $visionTitle = isset($pageContent['vision']) ? $pageContent['vision']->firstWhere('key', 'vision_title') : null;
                    @endphp
                    {{ $visionTitle?->value ?: 'Our Vision' }}
                </h3>
                <p>
                    @php
                        $visionContent = isset($pageContent['vision']) ? $pageContent['vision']->firstWhere('key', 'vision_content') : null;
                    @endphp
                    {{ $visionContent?->value ?: 'To be the leading football academy in Kenya and East Africa, recognized globally for producing exceptional players who excel professionally while embodying the values of integrity, excellence, and community service.' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">
                @php
                    $valuesTitle = isset($pageContent['values']) ? $pageContent['values']->firstWhere('key', 'title') : null;
                @endphp
                {{ $valuesTitle?->value ?: 'Our Core Values' }}
            </h1>
            <p class="section-subtitle">
                @php
                    $valuesSubtitle = isset($pageContent['values']) ? $pageContent['values']->firstWhere('key', 'subtitle') : null;
                @endphp
                {{ $valuesSubtitle?->value ?: 'The principles that guide everything we do at Vipers Football Academy' }}
            </p>
        </div>

        <div class="values-grid">
            @php
                $valuesContent = isset($pageContent['values']) ? $pageContent['values']->where('key', 'not_like', 'title%')->where('key', 'not_like', 'subtitle%') : collect();
            @endphp
            @if($valuesContent->count() > 0)
                @foreach($valuesContent as $value)
                    @php
                        $valueData = json_decode($value->value, true);
                    @endphp
                    @if($valueData)
                    <div class="value-card">
                        <span class="value-icon">{{ $valueData['icon'] ?? '⭐' }}</span>
                        <h4 class="value-title">{{ $valueData['title'] ?? 'Value' }}</h4>
                        <p class="value-description">{{ $valueData['description'] ?? '' }}</p>
                    </div>
                    @endif
                @endforeach
            @else
                <!-- Fallback values -->
                <div class="value-card">
                    <span class="value-icon">🏆</span>
                    <h4 class="value-title">Excellence</h4>
                    <p class="value-description">We strive for the highest standards in training, development, and performance, pushing both players and staff to achieve their full potential.</p>
                </div>

                <div class="value-card">
                    <span class="value-icon">🤝</span>
                    <h4 class="value-title">Integrity</h4>
                    <p class="value-description">We conduct ourselves with honesty, transparency, and ethical behavior in all our interactions and decisions.</p>
                </div>

                <div class="value-card">
                    <span class="value-icon">🌱</span>
                    <h4 class="value-title">Development</h4>
                    <p class="value-description">We are committed to holistic growth, nurturing not just football skills but also character, education, and life skills.</p>
                </div>

                <div class="value-card">
                    <span class="value-icon">🌍</span>
                    <h4 class="value-title">Community</h4>
                    <p class="value-description">We believe in giving back to our community and fostering positive relationships that extend beyond the football field.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Journey Section -->
<section class="journey-section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">
                @php
                    $journeyTitle = isset($pageContent['journey']) ? $pageContent['journey']->firstWhere('key', 'title') : null;
                @endphp
                {{ $journeyTitle?->value ?: 'Our Journey' }}
            </h1>
            <p class="section-subtitle">
                @php
                    $journeySubtitle = isset($pageContent['journey']) ? $pageContent['journey']->firstWhere('key', 'subtitle') : null;
                @endphp
                {{ $journeySubtitle?->value ?: 'Key milestones in Vipers Football Academy\'s history of excellence' }}
            </p>
        </div>

        <div class="journey-container">
            <div class="journey-timeline">
                @php
                    $journeyContent = isset($pageContent['journey']) ? $pageContent['journey']->where('key', 'like', 'year_%')->sortBy('sort_order') : collect();
                @endphp
                @if($journeyContent->count() > 0)
                    @foreach($journeyContent as $entry)
                        @php
                            $year = str_replace('year_', '', $entry->key);
                        @endphp
                        <div class="journey-item">
                            <h4 class="journey-year">{{ $year }}</h4>
                            <p class="journey-description">{{ $entry->value }}</p>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback journey -->
                    <div class="journey-item">
                        <h4 class="journey-year">2018</h4>
                        <p class="journey-description">Vipers Football Academy was founded with a vision to develop young Kenyan talent through comprehensive football training and education programs.</p>
                    </div>

                    <div class="journey-item">
                        <h4 class="journey-year">2019</h4>
                        <p class="journey-description">Launched our first youth development program, attracting players from across Nairobi and surrounding regions.</p>
                    </div>

                    <div class="journey-item">
                        <h4 class="journey-year">2020</h4>
                        <p class="journey-description">Expanded facilities and introduced specialized training programs for different age groups and skill levels.</p>
                    </div>

                    <div class="journey-item">
                        <h4 class="journey-year">2021</h4>
                        <p class="journey-description">Established partnerships with local schools and community organizations to increase access to quality football development.</p>
                    </div>

                    <div class="journey-item">
                        <h4 class="journey-year">2022</h4>
                        <p class="journey-description">Launched our professional scouting network and introduced advanced analytics to track player development and performance.</p>
                    </div>

                    <div class="journey-item">
                        <h4 class="journey-year">2023</h4>
                        <p class="journey-description">Achieved recognition as one of Kenya's premier youth football academies, with several players progressing to professional careers.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">
                @php
                    $teamTitle = isset($pageContent['team']) ? $pageContent['team']->firstWhere('key', 'title') : null;
                @endphp
                {{ $teamTitle?->value ?: 'Leadership Team' }}
            </h1>
            <p class="section-subtitle">
                @php
                    $teamSubtitle = isset($pageContent['team']) ? $pageContent['team']->firstWhere('key', 'subtitle') : null;
                @endphp
                {{ $teamSubtitle?->value ?: 'Meet the dedicated professionals leading Vipers Football Academy towards excellence' }}
            </p>
        </div>

        <div class="team-grid">
            @php
                $teamContent = isset($pageContent['team']) ? $pageContent['team']->where('key', 'not_like', 'title%')->where('key', 'not_like', 'subtitle%') : collect();
            @endphp
            @if($teamContent->count() > 0)
                @foreach($teamContent as $member)
                    @php
                        $memberData = json_decode($member->value, true);
                    @endphp
                    @if($memberData)
                    <div class="team-member">
                        <div class="team-photo">
                            @if(isset($memberData['photo']) && $memberData['photo'])
                                <img src="{{ asset('storage/' . $memberData['photo']) }}" alt="{{ $memberData['name'] ?? 'Team Member' }}">
                            @else
                                <img src="{{ asset('assets/img/gallery/kids.jpeg') }}" alt="{{ $memberData['name'] ?? 'Team Member' }}">
                            @endif
                        </div>
                        <div class="team-info">
                            <h4 class="team-name">{{ $memberData['name'] ?? 'Team Member' }}</h4>
                            <p class="team-role">{{ $memberData['role'] ?? 'Role' }}</p>
                            <p class="team-bio">{{ $memberData['bio'] ?? '' }}</p>
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
                <!-- Fallback team members -->
                <div class="team-member">
                    <div class="team-photo">
                        <img src="{{ asset('assets/img/staff/colo.jpeg') }}" alt="Dr. Collins W - Academy Director">
                    </div>
                    <div class="team-info">
                        <h4 class="team-name">Dr. Collins W</h4>
                        <p class="team-role">Academy Director</p>
                        <p class="team-bio">PhD Sports Management, UEFA Pro License. Leading strategic development and academic integration.</p>
                    </div>
                </div>

                <div class="team-member">
                    <div class="team-photo">
                        <img src="{{ asset('assets/img/gallery/kids.jpeg') }}" alt="Coach Michael Oduya - Technical Director">
                    </div>
                    <div class="team-info">
                        <h4 class="team-name">Coach Michael Oduya</h4>
                        <p class="team-role">Technical Director</p>
                        <p class="team-bio">UEFA A License, Sports Science MSc. Overseeing all technical training programs and coach development.</p>
                    </div>
                </div>

                <div class="team-member">
                    <div class="team-photo">
                        <img src="{{ asset('assets/img/gallery/kids.jpeg') }}" alt="Grace Wanjiku - Youth Development Head">
                    </div>
                    <div class="team-info">
                        <h4 class="team-name">Grace Wanjiku</h4>
                        <p class="team-role">Youth Development Head</p>
                        <p class="team-bio">Sports Psych MSc, Safeguarding Lead. Focusing on holistic player development and welfare.</p>
                    </div>
                </div>

                <div class="team-member">
                    <div class="team-photo">
                        <img src="{{ asset('assets/img/gallery/kids.jpeg') }}" alt="Ahmed Hassan - Scouting Director">
                    </div>
                    <div class="team-info">
                        <h4 class="team-name">Ahmed Hassan</h4>
                        <p class="team-role">Scouting Director</p>
                        <p class="team-bio">Data Analytics BSc, AI Specialist. Managing talent identification and performance analytics.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection