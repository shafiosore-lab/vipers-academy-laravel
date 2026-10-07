@extends('layouts.academy')

@section('title', 'Youth Development Programs - Mumias Vipers Football Academy')

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
                        <span class="program-badge badge-{{ ['primary', 'warning', 'success', 'info', 'secondary'][$index] ?? 'primary' }}">
                            {{ $program->category ?? 'Program' }}
                        </span>
                        <h3 class="program-title">{{ $program->title }}</h3>

                        <div class="program-details">
                             <div class="detail-item">
                                 <i class="fas fa-clock"></i>
                                 <span>{{ $program->schedule_display ?? $program->duration ?? 'Flexible' }}</span>
                             </div>
                             <div class="detail-item">
                                 <i class="fas fa-users"></i>
                                 <span>{{ $program->age_range ?? $program->age_group ?? 'All Ages' }}</span>
                             </div>
                         </div>

                        <p class="program-description">
                            {{ Str::limit($program->description, 150, '...') }}
                        </p>

                        <div class="program-actions">
                             <button class="btn btn-{{ ['primary', 'warning', 'success', 'info', 'secondary'][$index] ?? 'primary' }}" data-bs-toggle="modal" data-bs-target="#programModal{{ $program->id }}">
                                <i class="fas fa-info-circle me-2"></i>Learn More
                             </button>
                             <a href="{{ route('enrol') }}" class="btn btn-outline-{{ ['primary', 'warning', 'success', 'info', 'secondary'][$index] ?? 'primary' }}">
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
                         <img src="{{ asset('assets/img/gallery/' . ($index === 0 ? 'coding.jpg' : ($index === 1 ? 'arduino.jpg' : ($index === 2 ? 'lifeskills.jpg' : ($index === 3 ? 'academics.jpg' : 'co.jpg'))))) }}"
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
                        <div class="modal-header bg-{{ ['primary', 'warning', 'success', 'info', 'secondary'][$index] ?? 'primary' }} text-white">
                            <h5 class="modal-title" id="programModalLabel{{ $program->id }}">{{ $program->title }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6 class="fw-bold text-{{ ['primary', 'warning', 'success', 'info', 'secondary'][$index] ?? 'primary' }}">Program Overview</h6>
                            <p>{{ $program->description }}</p>

                            <hr>

                            <div class="row">
                                 <div class="col-md-6">
                                     <h6><i class="fas fa-check-circle me-2"></i>Cost</h6>
                                     <p>No participant fees — community-supported program</p>
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
                            <a href="{{ route('enrol') }}" class="btn btn-{{ ['primary', 'warning', 'success', 'info', 'secondary'][$index] ?? 'primary' }}">
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
    padding: 2rem 0;
}

.programs-grid {
    display: grid;
    gap: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Program Card */
.program-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.program-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
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
    padding: 2rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.program-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
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

.badge-info {
    background: #17a2b8;
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.program-title {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #2c3e50;
    line-height: 1.2;
}

.program-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #555;
}

.detail-item i {
    width: 18px;
    font-size: 0.9rem;
    color: #0d47a1;
}

.program-description {
    font-size: 0.95rem;
    line-height: 1.5;
    color: #6c757d;
    margin-bottom: 1.25rem;
}

.program-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.program-actions .btn {
    flex: 1;
    min-width: 120px;
    border-radius: 6px;
    font-weight: 600;
    padding: 0.6rem 1.2rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.program-actions .btn:hover {
    transform: translateY(-1px);
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
        min-height: 200px;
    }

    .program-content {
        padding: 1.5rem;
    }

    .program-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    .programs-section {
        padding: 1.5rem 0;
    }

    .programs-grid {
        gap: 1rem;
        padding: 0 0.5rem;
    }

    .program-card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
    }

    .program-title {
        font-size: 1.4rem;
        margin-bottom: 0.75rem;
    }

    .program-details {
        margin-bottom: 0.75rem;
        gap: 0.5rem;
    }

    .detail-item {
        font-size: 0.85rem;
    }

    .program-description {
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .program-actions {
        flex-direction: column;
        gap: 0.5rem;
    }

    .program-actions .btn {
        width: 100%;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        border-radius: 6px;
    }

    .program-image {
        min-height: 150px;
    }

    .program-content {
        padding: 1.25rem;
    }

    .program-badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.75rem;
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 576px) {
    .programs-section {
        padding: 1rem 0;
    }

    .programs-grid {
        gap: 0.75rem;
        padding: 0 0.25rem;
    }

    .program-card {
        border-radius: 8px;
    }

    .program-title {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .program-details {
        margin-bottom: 0.5rem;
    }

    .detail-item {
        font-size: 0.8rem;
    }

    .program-description {
        font-size: 0.85rem;
        -webkit-line-clamp: 2;
        margin-bottom: 0.75rem;
    }

    .program-actions {
        gap: 0.375rem;
    }

    .program-actions .btn {
        padding: 0.4rem 0.75rem;
        font-size: 0.85rem;
        min-height: 36px;
    }

    .program-image {
        min-height: 120px;
    }

    .program-content {
        padding: 1rem;
    }

    .program-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 480px) {
    .programs-section {
        padding: 0.75rem 0;
    }

    .programs-grid {
        gap: 0.5rem;
        padding: 0;
    }

    .program-card {
        border-radius: 6px;
    }

    .program-title {
        font-size: 1.1rem;
    }

    .program-description {
        -webkit-line-clamp: 2;
        margin-bottom: 0.5rem;
    }

    .program-actions {
        gap: 0.25rem;
    }

    .program-actions .btn {
        padding: 0.35rem 0.5rem;
        font-size: 0.8rem;
        min-height: 32px;
    }

    .program-image {
        min-height: 100px;
    }

    .program-content {
        padding: 0.75rem;
    }
}

/* Modal Mobile Optimization */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100vw - 1rem);
    }

    .modal-content {
        border-radius: 8px;
    }

    .modal-header {
        padding: 1rem 1.25rem 0.75rem;
    }

    .modal-body {
        padding: 1rem 1.25rem;
    }

    .modal-footer {
        padding: 0.75rem 1.25rem 1rem;
        gap: 0.5rem;
    }

    .modal-footer .btn {
        flex: 1;
        min-height: 40px;
    }
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
