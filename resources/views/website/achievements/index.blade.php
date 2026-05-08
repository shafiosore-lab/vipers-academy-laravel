@extends('layouts.academy')

@section('title', 'Achievements - Vipers Academy Kenya')
@section('meta_description', 'Celebrating the achievements and community impact of Vipers Academy\'s youth development programs.')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="fw-bold mb-3">
            @php
                $heroTitle = isset($pageContent['hero']) ? $pageContent['hero']->firstWhere('key', 'title') : null;
            @endphp
            {{ $heroTitle?->value ?: 'Our Achievements' }}
        </h1>
        <p class="text-muted mx-auto" style="max-width: 700px; font-size: 1.05rem;">
            @php
                $heroSubtitle = isset($pageContent['hero']) ? $pageContent['hero']->firstWhere('key', 'subtitle') : null;
            @endphp
            {{ $heroSubtitle?->value ?: 'Celebrating the milestones and community impact of Vipers Academy\'s journey in developing youth through football, education, and digital skills.' }}
        </p>
    </div>

    <!-- Achievements Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 2.5rem;">🏆</div>
                    <h5 class="fw-bold mb-2">Professional Pathways Created</h5>
                    <p class="text-muted mb-0">Over 50 players have progressed to professional football clubs and national team selections through our community-based programs.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 2.5rem;">🌍</div>
                    <h5 class="fw-bold mb-2">International Recognition</h5>
                    <p class="text-muted mb-0">Players representing Kenya at various international youth tournaments and competitions.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 2.5rem;">📚</div>
                    <h5 class="fw-bold mb-2">Academic Excellence</h5>
                    <p class="text-muted mb-0">Maintaining high academic standards while pursuing football excellence, with 85% success rate among scholarship recipients.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 2.5rem;">⚽</div>
                    <h5 class="fw-bold mb-2">Tournament Success</h5>
                    <p class="text-muted mb-0">Multiple championship wins in regional and national youth competitions, with 15+ tournament titles.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 2.5rem;">🤝</div>
                    <h5 class="fw-bold mb-2">Community Impact</h5>
                    <p class="text-muted mb-0">Contributing to grassroots youth development through football, digital skills training, and mentorship programs.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3" style="font-size: 2.5rem;">🎓</div>
                    <h5 class="fw-bold mb-2">Player Development</h5>
                    <p class="text-muted mb-0">Comprehensive development programs focusing on technical, tactical, academic, and digital skills.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="card border-0 shadow-sm p-5 text-center" data-aos="fade-up">
        <h2 class="fw-bold mb-4">
            @php
                $statsTitle = isset($pageContent['stats']) ? $pageContent['stats']->firstWhere('key', 'title') : null;
            @endphp
            {{ $statsTitle?->value ?: 'By the Numbers' }}
        </h2>
        <div class="row g-3 justify-content-center">
            @php
                $statsContent = isset($pageContent['stats']) ? $pageContent['stats']->where('key', 'not_like', 'title%') : collect();
            @endphp
            @if($statsContent->count() > 0)
                @foreach($statsContent as $index => $stat)
                    @php
                        $statData = json_decode($stat->value, true);
                    @endphp
                    @if($statData)
                    <div class="col-6 col-md-3">
                        <div class="p-4 rounded-3 text-white" style="background: var(--primary, #ea1c4d);">
                            <div class="fw-bold" style="font-size: 2rem;">{{ $statData['number'] ?? '0' }}</div>
                            <div class="fw-medium" style="font-size: 0.9rem;">{{ $statData['label'] ?? 'Stat' }}</div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
                <!-- Fallback stats -->
                <div class="col-6 col-md-3">
                    <div class="p-4 rounded-3 text-white" style="background: var(--primary, #ea1c4d);">
                        <div class="fw-bold" style="font-size: 2rem;">500+</div>
                        <div class="fw-medium" style="font-size: 0.9rem;">Youth Empowered</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-4 rounded-3 text-white" style="background: var(--primary, #ea1c4d);">
                        <div class="fw-bold" style="font-size: 2rem;">50+</div>
                        <div class="fw-medium" style="font-size: 0.9rem;">Professional Pathways</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-4 rounded-3 text-white" style="background: var(--primary, #ea1c4d);">
                        <div class="fw-bold" style="font-size: 2rem;">10+</div>
                        <div class="fw-medium" style="font-size: 0.9rem;">Years of Impact</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-4 rounded-3 text-white" style="background: var(--primary, #ea1c4d);">
                        <div class="fw-bold" style="font-size: 2rem;">15+</div>
                        <div class="fw-medium" style="font-size: 0.9rem;">Championships</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
