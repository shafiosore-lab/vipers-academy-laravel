@extends('layouts.academy')

@section('title', 'Programs - Vipers Academy')

@section('content')


<!-- Programs Section -->
<section id="programs-section" class="programs-section">
    <div class="container-fluid">
        <div class="programs-grid">

            @forelse($programs as $index => $program)
            <!-- Program {{ $index + 1 }}: {{ $program->title }} -->
            <article class="program-card">
                <div class="program-layout {{ $index % 2 === 1 ? 'reverse' : '' }}">
                    <div class="program-content">
                        <span class="program-badge badge-{{ $index === 0 ? 'primary' : ($index === 1 ? 'warning' : 'success') }}">
                            {{ $program->category ?? 'Program' }}
                        </span>
                        <h3 class="program-title">{{ $program->title }}</h3>

                        <div class="program-details">
                            <div class="detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>{{ $program->fee_display ?? 'Contact for pricing' }}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $program->schedule_display ?? $program->duration ?? 'Flexible' }}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $program->age_range ?? $program->age_group ?? 'All Ages' }}</span>
                            </div>
                        </div>

                        <p class="program-description">
                            {{ $program->description }}
                        </p>

                        <div class="program-actions">
                            <button class="btn btn-{{ $index === 0 ? 'primary' : ($index === 1 ? 'warning' : 'success') }}" data-bs-toggle="modal" data-bs-target="#programModal{{ $program->id }}">
                                <i class="fas fa-info-circle me-2"></i>Learn More
                            </button>
                            <a href="{{ route('enrol') }}" class="btn btn-outline-{{ $index === 0 ? 'primary' : ($index === 1 ? 'warning' : 'success') }}">
                                <i class="fas fa-user-plus me-2"></i>Enroll Now
                            </a>
                        </div>
                    </div>

                    <div class="program-image">
                        @if($program->image)
                        <img src="{{ asset('storage/' . $program->image) }}"
                             alt="{{ $program->title }}"
                             loading="lazy">
                        @else
                        <img src="{{ asset('assets/img/gallery/' . ($index === 0 ? 'kids.jpeg' : ($index === 1 ? 'co.jpeg' : 'coding.jpeg'))) }}"
                             alt="{{ $program->title }}"
                             loading="lazy">
                        @endif
                    </div>
                </div>
            </article>

            <!-- Program Modal -->
            <div class="modal fade" id="programModal{{ $program->id }}" tabindex="-1" aria-labelledby="programModalLabel{{ $program->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-{{ $index === 0 ? 'primary' : ($index === 1 ? 'warning' : 'success') }} text-{{ $index === 1 ? 'dark' : 'white' }}">
                            <h5 class="modal-title" id="programModalLabel{{ $program->id }}">{{ $program->title }}</h5>
                            <button type="button" class="btn-{{ $index === 1 ? 'dark' : 'light' }}" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6 class="fw-bold text-{{ $index === 0 ? 'primary' : ($index === 1 ? 'warning' : 'success') }}">Program Overview</h6>
                            <p>{{ $program->description }}</p>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-dollar-sign me-2"></i>Fee</h6>
                                    <p>{{ $program->fee_display ?? 'KSH ' . number_format($program->regular_fee ?? 0, 0) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-clock me-2"></i>Duration</h6>
                                    <p>{{ $program->duration ?? 'Flexible' }}</p>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-calendar me-2"></i>Schedule</h6>
                                    <p>{{ $program->schedule ?? $program->schedule_display ?? 'Flexible' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-users me-2"></i>Ages</h6>
                                    <p>{{ $program->age_range ?? $program->age_group ?? 'All ages' }}</p>
                                </div>
                            </div>

                            @if($program->objectives)
                            <hr>
                            <h6><i class="fas fa-bullseye me-2"></i>Objectives</h6>
                            <p>{{ $program->objectives }}</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a href="{{ route('enrol') }}" class="btn btn-{{ $index === 0 ? 'primary' : ($index === 1 ? 'warning' : 'success') }}">
                                <i class="fas fa-user-plus me-2"></i>Enroll Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info">
                <p class="mb-0">No programs available at the moment. Please check back later.</p>
            </div>
            @endforelse

        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Programs Section */
.programs-section {
    padding: 3rem 0;
}

.programs-grid {
    display: grid;
    gap: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Program Card */
.program-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.program-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.program-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 400px;
}

.program-layout.reverse {
    direction: rtl;
}

.program-layout.reverse > * {
    direction: ltr;
}

/* Program Content */
.program-content {
    padding: 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.program-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
    width: fit-content;
}

.badge-primary {
    background: #0d47a1;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #000;
}

.badge-success {
    background: #28a745;
    color: white;
}

.program-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
    color: #2c3e50;
}

.program-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
    color: #555;
}

.detail-item i {
    width: 20px;
    color: #0d47a1;
}

.program-description {
    font-size: 1rem;
    line-height: 1.6;
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.program-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.program-actions .btn {
    flex: 1;
    min-width: 140px;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.program-actions .btn:hover {
    transform: translateY(-2px);
}

/* Program Image */
.program-image {
    position: relative;
    overflow: hidden;
}

.program-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.program-card:hover .program-image img {
    transform: scale(1.05);
}

/* Responsive Design */
@media (max-width: 991px) {
    .program-layout,
    .program-layout.reverse {
        grid-template-columns: 1fr;
        direction: ltr;
    }

    .program-image {
        order: -1;
        min-height: 250px;
    }

    .program-content {
        padding: 2rem;
    }
}

@media (max-width: 768px) {
    .program-title {
        font-size: 1.5rem;
    }

    .program-actions {
        flex-direction: column;
    }

    .program-actions .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .programs-section {
        padding: 2rem 0;
    }

    .programs-grid {
        gap: 1.5rem;
    }

    .program-content {
        padding: 1.5rem;
    }

    .program-title {
        font-size: 1.3rem;
    }

    .program-image {
        min-height: 200px;
    }
}

/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll to programs
    const exploreBtn = document.querySelector('a[href="#programs-section"]');
    if (exploreBtn) {
        exploreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('programs-section').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }

    // Scroll animations
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

    document.querySelectorAll('.program-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush
