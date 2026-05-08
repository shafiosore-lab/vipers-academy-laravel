@extends('layouts.academy')

@section('title', 'Events - Vipers Academy Kenya')
@section('meta_description', 'Upcoming events, tournaments, and activities at Vipers Academy Kenya.')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="text-center mb-5" data-aos="fade-up">
        <h1 class="fw-bold mb-3">
            @php
                $heroTitle = isset($pageContent['hero']) ? $pageContent['hero']->firstWhere('key', 'title') : null;
            @endphp
            {{ $heroTitle?->value ?: 'Upcoming Events' }}
        </h1>
        <p class="text-muted mx-auto" style="max-width: 700px; font-size: 1.05rem;">
            @php
                $heroSubtitle = isset($pageContent['hero']) ? $pageContent['hero']->firstWhere('key', 'subtitle') : null;
            @endphp
            {{ $heroSubtitle?->value ?: 'Stay updated with our latest tournaments, training camps, and academy events.' }}
        </p>
    </div>

    <!-- Events Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                <div class="p-4 text-white text-center fw-bold" style="background: var(--primary, #ea1c4d);">
                    <div style="font-size: 1.2rem;">March 15, 2025</div>
                    <div style="font-size: 1.1rem;">Youth Tournament</div>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">Annual youth football tournament featuring teams from across Kenya.</p>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>📍 Nairobi</span>
                        <span>⏰ 9:00 AM</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                <div class="p-4 text-white text-center fw-bold" style="background: var(--primary, #ea1c4d);">
                    <div style="font-size: 1.2rem;">April 5, 2025</div>
                    <div style="font-size: 1.1rem;">Training Camp</div>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">Intensive training camp for advanced players focusing on technical skills.</p>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>📍 Academy Grounds</span>
                        <span>⏰ 8:00 AM</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                <div class="p-4 text-white text-center fw-bold" style="background: var(--primary, #ea1c4d);">
                    <div style="font-size: 1.2rem;">May 20, 2025</div>
                    <div style="font-size: 1.1rem;">Open Day</div>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">Annual open day showcasing academy facilities and player demonstrations.</p>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>📍 Main Stadium</span>
                        <span>⏰ 10:00 AM</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                <div class="p-4 text-white text-center fw-bold" style="background: var(--primary, #ea1c4d);">
                    <div style="font-size: 1.2rem;">June 10, 2025</div>
                    <div style="font-size: 1.1rem;">Parent Workshop</div>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">Educational workshop for parents on player development and academy programs.</p>
                    <div class="d-flex justify-content-between text-muted small">
                        <span>📍 Conference Room</span>
                        <span>⏰ 2:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- More Events Section -->
    <div class="card border-0 shadow-sm p-5 text-center" data-aos="fade-up">
        <h2 class="fw-bold mb-3">
            @php
                $eventsTitle = isset($pageContent['events']) ? $pageContent['events']->firstWhere('key', 'title') : null;
            @endphp
            {{ $eventsTitle?->value ?: 'More Events Coming Soon' }}
        </h2>
        <p class="text-muted mb-4">
            @php
                $eventsSubtitle = isset($pageContent['events']) ? $pageContent['events']->firstWhere('key', 'subtitle') : null;
            @endphp
            {{ $eventsSubtitle?->value ?: 'Stay tuned for more exciting events and tournaments throughout the year.' }}
        </p>
        <a href="/contact" class="btn btn-primary px-4 py-2 fw-semibold">
            @php
                $eventsCta = isset($pageContent['events']) ? $pageContent['events']->firstWhere('key', 'cta_text') : null;
            @endphp
            {{ $eventsCta?->value ?: 'Get Event Updates' }}
        </a>
    </div>
</div>
@endsection
