@extends('layouts.academy')

@section('title', 'Search Results - Vipers Academy')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <!-- Search Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="fas fa-search me-3 text-warning"></i>
                    Search Results
                </h1>
                <p class="lead text-muted">
                    Showing results for: <strong class="text-warning">"{{ $query }}"</strong>
                </p>
                <hr class="my-4">
            </div>

            <!-- Search Form -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <form action="{{ route('search') }}" method="GET" class="position-relative">
                        <div class="input-group input-group-lg">
                            <input type="text" name="q" class="form-control form-control-lg border-0 shadow-sm"
                                   value="{{ $query }}" autocomplete="off"
                                   style="border-radius: 50px 0 0 50px; padding-left: 2rem;">
                            <button type="submit" class="btn btn-warning btn-lg px-4"
                                    style="border-radius: 0 50px 50px 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h5 class="mb-1">{{ $programs->total() }}</h5>
                                <small class="text-muted">Programs Found</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="mb-1">{{ $players->total() }}</h5>
                                <small class="text-muted">Players Found</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="mb-1">{{ $news->total() }}</h5>
                                <small class="text-muted">News Articles Found</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="mb-1">{{ $gallery->total() }}</h5>
                                <small class="text-muted">Gallery Items Found</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Programs Section -->
            @if($programs->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="h2 fw-bold text-primary mb-4">
                        <i class="fas fa-graduation-cap me-2 text-warning"></i>
                        Programs
                    </h3>
                    <div class="row">
                        @foreach($programs as $program)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; transition: transform 0.3s ease;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-futbol text-white"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-1 fw-bold">{{ $program->title }}</h5>
                                            <small class="text-muted">{{ $program->age_group }}</small>
                                        </div>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        {{ Str::limit($program->description, 100) }}
                                    </p>
                                    <a href="{{ route('programs') }}" class="btn btn-warning btn-sm rounded-pill">
                                        <i class="fas fa-eye me-1"></i>View Program
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Players Section -->
            @if($players->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="h2 fw-bold text-primary mb-4">
                        <i class="fas fa-users me-2 text-warning"></i>
                        Players
                    </h3>
                    <div class="row">
                        @foreach($players as $player)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm text-center" style="border-radius: 15px; transition: transform 0.3s ease;">
                                <div class="card-body p-4">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user text-white fa-lg"></i>
                                    </div>
                                    <h6 class="card-title fw-bold mb-1">{{ $player->name }}</h6>
                                    <p class="card-text text-muted small mb-2">{{ $player->position }}</p>
                                    <small class="text-warning">{{ $player->nationality }}</small>
                                    <br>
                                    <a href="{{ route('player.show', $player) }}" class="btn btn-outline-primary btn-sm rounded-pill mt-3">
                                        <i class="fas fa-eye me-1"></i>View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        {{ $players->links() }}
                    </div>
                </div>
            </div>
            @endif

            <!-- News Section -->
            @if($news->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="h2 fw-bold text-primary mb-4">
                        <i class="fas fa-newspaper me-2 text-warning"></i>
                        News & Updates
                    </h3>
                    <div class="row">
                        @foreach($news as $newsItem)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; transition: transform 0.3s ease;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-newspaper text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-2 fw-bold">{{ $newsItem->title }}</h5>
                                            <p class="card-text text-muted mb-3">
                                                {{ Str::limit($newsItem->content, 150) }}
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $newsItem->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    <a href="{{ route('news.show', $newsItem) }}" class="btn btn-info btn-sm rounded-pill">
                                        <i class="fas fa-arrow-right me-1"></i>Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        {{ $news->links() }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Gallery Section -->
            @if($gallery->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="h2 fw-bold text-primary mb-4">
                        <i class="fas fa-images me-2 text-warning"></i>
                        Gallery
                    </h3>
                    <div class="row">
                        @foreach($gallery as $item)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; transition: transform 0.3s ease;">
                                <div class="card-body p-4 text-center">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                         style="width: 60px; height: 60px;">
                                        @if($item->media_type == 'image')
                                            <i class="fas fa-image text-white fa-lg"></i>
                                        @elseif($item->media_type == 'video')
                                            <i class="fas fa-video text-white fa-lg"></i>
                                        @else
                                            <i class="fas fa-file text-white fa-lg"></i>
                                        @endif
                                    </div>
                                    <h6 class="card-title fw-bold mb-2">{{ $item->title }}</h6>
                                    <small class="text-muted">{{ ucfirst($item->media_type) }}</small>
                                    <br>
                                    <a href="{{ route('gallery') }}" class="btn btn-outline-success btn-sm rounded-pill mt-3">
                                        <i class="fas fa-eye me-1"></i>View Gallery
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        {{ $gallery->links() }}
                    </div>
                </div>
            </div>
            @endif

            <!-- No Results -->
            @if($programs->count() === 0 && $players->count() === 0 && $news->count() === 0 && $gallery->count() === 0)
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-search fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No Results Found</h4>
                        <p class="text-muted mb-4">
                            We couldn't find any results matching "<strong>{{ $query }}</strong>".
                            Try different keywords or check your spelling.
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-warning rounded-pill px-4">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Search Tips -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold text-primary mb-3">
                                <i class="fas fa-lightbulb me-2 text-warning"></i>
                                Search Tips
                            </h5>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <h6 class="fw-bold"><i class="fas fa-graduation-cap me-2 text-success"></i>Programs</h6>
                                    <small class="text-muted">Search by program name, age group, or description</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h6 class="fw-bold"><i class="fas fa-users me-2 text-primary"></i>Players</h6>
                                    <small class="text-muted">Search by player name, position, or nationality</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h6 class="fw-bold"><i class="fas fa-newspaper me-2 text-info"></i>News</h6>
                                    <small class="text-muted">Search by article title or content keywords</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <h6 class="fw-bold"><i class="fas fa-images me-2 text-success"></i>Gallery</h6>
                                    <small class="text-muted">Search by gallery item title</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-5px);
}

.input-group .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    border-color: #ff6b35;
}
</style>
@endsection
