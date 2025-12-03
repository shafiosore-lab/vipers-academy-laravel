@extends('layouts.academy')

@section('title', 'About Us - Vipers Academy Kenya')
@section('meta_description', 'Learn about Vipers Academy, founded in 2017, building the next generation of elite Kenyan footballers through modern training, discipline, and development.')

@section('content')

<!-- HERO -->
<section class="vip-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <h1 class="vip-title">Built for Champions.</h1>
                <p class="vip-sub">
                    Founded in <strong>2017</strong>, Vipers Academy is shaping the future of Kenyan football through elite coaching, discipline, and a development-first approach.
                </p>

                <div class="vip-stats">
                    <div class="vip-stat-box">
                        <h3>2017</h3>
                        <p>Year Founded</p>
                    </div>
                    <div class="vip-stat-box">
                        <h3>500+</h3>
                        <p>Players Trained</p>
                    </div>
                    <div class="vip-stat-box">
                        <h3>50+</h3>
                        <p>Pro Graduates</p>
                    </div>
                </div>

                <a href="{{ route('register.player') }}" class="vip-btn">Join Vipers Academy</a>
            </div>

            <div class="col-lg-6">
                <div class="vip-image-wrap">
                    <img src="{{ asset('assets/img/gallery/kids.jpeg') }}" class="vip-image" alt="Vipers Academy Players">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- IDENTITY -->
<section class="vip-identity">
    <div class="container">
        <h2 class="section-title">Who We Are</h2>
        <p class="identity-text">
            Vipers Academy is a modern football development institution committed to raising disciplined, skilled, and mentally strong players.
            We integrate structured training, character building, sports science principles, and academic balance to prepare young athletes for elite performance.
        </p>

        <div class="row mt-4 g-4">
            <div class="col-md-4">
                <div class="identity-card">
                    <h4>⚽ Elite Player Development</h4>
                    <p>European-inspired technical, tactical, physical, and mental training.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="identity-card">
                    <h4>📘 Discipline & Character</h4>
                    <p>Respect, leadership, teamwork, and emotional intelligence embedded in every session.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="identity-card">
                    <h4>🎓 Education Friendly</h4>
                    <p>Training designed to complement school life and responsible academic focus.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TIMELINE -->
<section class="vip-timeline">
    <div class="container">
        <h2 class="section-title">Our Journey Since 2017</h2>

        <div class="timeline">
            <div class="timeline-item">
                <span class="year">2017</span>
                <p>Vipers Academy officially founded with the goal of transforming grassroots football.</p>
            </div>

            <div class="timeline-item">
                <span class="year">2019</span>
                <p>Expanded to multiple training centers and introduced structured academy programs.</p>
            </div>

            <div class="timeline-item">
                <span class="year">2021</span>
                <p>Developed the first batch of players progressing to national and club levels.</p>
            </div>

            <div class="timeline-item">
                <span class="year">2023</span>
                <p>Launched junior development, holiday camps, and coaching education programs.</p>
            </div>

            <div class="timeline-item">
                <span class="year">2024+</span>
                <p>Moving toward a fully equipped academy, sports science integration, and global partnerships.</p>
            </div>
        </div>
    </div>
</section>

<!-- VALUES -->
<section class="vip-values">
    <div class="container">
        <h2 class="section-title">Our Values</h2>

        <div class="row g-4 mt-3">
            <div class="col-md-3"><div class="value-card">Discipline</div></div>
            <div class="col-md-3"><div class="value-card">Respect</div></div>
            <div class="col-md-3"><div class="value-card">Hard Work</div></div>
            <div class="col-md-3"><div class="value-card">Excellence</div></div>
        </div>
    </div>
</section>

<!-- FINAL CTA -->
<section class="vip-cta">
    <div class="container text-center">
        <h2>Become a Viper. Become Unstoppable.</h2>
        <a href="{{ route('register.player') }}" class="vip-btn big">Enroll Today</a>
    </div>
</section>

@endsection
