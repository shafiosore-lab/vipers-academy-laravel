@extends('layouts.academy')

@section('title', $match->tournament_name ?: $match->opponent . ' - Match Center')

@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('match-center') }}">Match Center</a></li>
                    <li class="breadcrumb-item active">{{ $match->tournament_name ?: $match->opponent }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Match Images -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body p-0">
                    @if($match->images && count($match->images) > 0)
                        <div id="matchCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($match->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="{{ $match->opponent }}" style="height: 400px; object-fit: cover;">
                                </div>
                                @endforeach
                            </div>
                            @if(count($match->images) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#matchCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#matchCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            @endif
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                            <div class="text-center">
                                <i class="fas fa-futbol fa-5x text-muted mb-3"></i>
                                <h4>{{ $match->tournament_name ?: $match->opponent }}</h4>
                                <p class="text-muted">{{ $match->type === 'tournament' ? 'Tournament' : 'Match' }} Details</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Match Details -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    @if($match->type === 'tournament')
                        <!-- Tournament Details -->
                        <h3 class="card-title text-primary fw-bold">{{ $match->tournament_name }}</h3>
                        <div class="match-info mb-4">
                            <div class="info-item">
                                <strong>Type:</strong> <span class="badge bg-danger">Tournament</span>
                            </div>
                            <div class="info-item">
                                <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}
                            </div>
                            <div class="info-item">
                                <strong>Venue:</strong> {{ $match->venue }}
                            </div>
                            @if($match->registration_deadline)
                            <div class="info-item">
                                <strong>Registration Deadline:</strong> {{ \Carbon\Carbon::parse($match->registration_deadline)->format('d M Y') }}
                            </div>
                            @endif
                        </div>

                        @if($match->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p>{{ $match->description }}</p>
                        </div>
                        @endif

                        <div class="action-buttons">
                            @if($match->registration_open)
                            <a href="#" class="btn btn-warning btn-lg w-100 mb-2">
                                <i class="fas fa-user-plus me-2"></i>Register Now
                            </a>
                            @else
                            <button class="btn btn-secondary btn-lg w-100 mb-2" disabled>
                                <i class="fas fa-clock me-2"></i>Registration Closed
                            </button>
                            @endif
                            <a href="{{ route('match-center') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Back to Match Center
                            </a>
                        </div>
                    @else
                        <!-- Match Details -->
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-danger">Vipers Academy</h3>
                            <div class="vs-display">
                                <span class="score-display">{{ $match->status === 'completed' ? ($match->vipers_score ?? 0) : '-' }}</span>
                                <span class="vs-badge">VS</span>
                                <span class="score-display">{{ $match->status === 'completed' ? ($match->opponent_score ?? 0) : '-' }}</span>
                            </div>
                            <h3 class="fw-bold text-primary">{{ $match->opponent }}</h3>
                        </div>

                        <div class="match-info mb-4">
                            <div class="info-item">
                                <strong>Type:</strong>
                                <span class="badge bg-{{ $match->type === 'friendly' ? 'success' : 'info' }}">
                                    {{ ucfirst($match->type) }}
                                </span>
                            </div>
                            <div class="info-item">
                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($match->match_date)->format('d M Y, h:i A') }}
                            </div>
                            <div class="info-item">
                                <strong>Venue:</strong> {{ $match->venue }}
                            </div>
                            <div class="info-item">
                                <strong>Status:</strong>
                                <span class="badge bg-{{ $match->status === 'completed' ? 'secondary' : ($match->status === 'upcoming' ? 'success' : 'warning') }}">
                                    {{ ucfirst($match->status) }}
                                </span>
                            </div>
                        </div>

                        @if($match->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p>{{ $match->description }}</p>
                        </div>
                        @endif

                        @if($match->match_summary)
                        <div class="mb-4">
                            <h5>Match Summary</h5>
                            <p>{{ $match->match_summary }}</p>
                        </div>
                        @endif

                        <div class="action-buttons">
                            @if($match->status === 'upcoming' && $match->live_link)
                            <a href="{{ $match->live_link }}" class="btn btn-success btn-lg w-100 mb-2" target="_blank">
                                <i class="fas fa-play me-2"></i>Watch Live
                            </a>
                            @endif
                            @if($match->status === 'completed' && $match->highlights_link)
                            <a href="{{ $match->highlights_link }}" class="btn btn-warning btn-lg w-100 mb-2" target="_blank">
                                <i class="fas fa-video me-2"></i>View Highlights
                            </a>
                            @endif
                            <a href="{{ route('match-center') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Back to Match Center
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Matches -->
    @if($relatedMatches->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4 text-center">Related {{ $match->type === 'tournament' ? 'Tournaments' : 'Matches' }}</h3>
            <div class="row g-4">
                @foreach($relatedMatches as $relatedMatch)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card match-card h-100 shadow-sm">
                        @if($relatedMatch->images && count($relatedMatch->images) > 0)
                            <img src="{{ asset('storage/' . $relatedMatch->images[0]) }}" class="card-img-top" alt="{{ $relatedMatch->opponent }}" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-futbol fa-2x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column text-center">
                            <h6 class="card-title fw-bold">{{ $relatedMatch->tournament_name ?: $relatedMatch->opponent }}</h6>
                            <p class="card-text small text-muted mb-2">{{ \Carbon\Carbon::parse($relatedMatch->match_date)->format('d M Y') }}</p>
                            <span class="badge bg-{{ $relatedMatch->status === 'completed' ? 'secondary' : ($relatedMatch->status === 'upcoming' ? 'success' : 'warning') }} mb-3">
                                {{ ucfirst($relatedMatch->status) }}
                            </span>
                            <a href="{{ route('match-center.show', $relatedMatch) }}" class="btn btn-primary btn-sm mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.match-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.match-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.vs-display {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 1rem 0;
}

.score-display {
    font-size: 2.5rem;
    font-weight: bold;
    color: #ea1c4d;
    margin: 0 1rem;
}

.vs-badge {
    background: linear-gradient(45deg, #ea1c4d, #d3173a);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: bold;
    font-size: 0.9rem;
}

.info-item {
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.action-buttons .btn {
    margin-bottom: 0.5rem;
}

.action-buttons .btn:last-child {
    margin-bottom: 0;
}

.carousel-item img {
    border-radius: 10px 10px 0 0;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.breadcrumb {
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border-radius: 10px;
    padding: 0.75rem 1rem;
}
</style>
@endsection
