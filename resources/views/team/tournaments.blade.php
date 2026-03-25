@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'Available Tournaments')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Available Tournaments</h1>
            <p class="text-muted">Browse and register for upcoming tournaments</p>
        </div>
        <a href="{{ route('team.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Tournament List -->
    @if($tournaments->count() > 0)
        <div class="row">
            @foreach($tournaments as $tournament)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $tournament->name }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-building"></i> {{ $tournament->organization->name ?? 'N/A' }}
                            </p>
                            <p class="card-text">
                                <strong>Season:</strong> {{ $tournament->season ?? 'N/A' }}<br>
                                <strong>Venue:</strong> {{ $tournament->venue ?? 'TBA' }}<br>
                                <strong>Registration Deadline:</strong>
                                {{ $tournament->registration_deadline ? $tournament->registration_deadline->format('M d, Y') : 'Open' }}
                            </p>
                            <div class="mb-2">
                                <span class="badge bg-info">{{ ucfirst($tournament->status) }}</span>
                                @if($tournament->max_teams)
                                    <span class="badge bg-secondary">{{ $tournament->getRegisteredTeamsCount() }}/{{ $tournament->max_teams }} Teams</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('team.tournament.register', $tournament->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-plus"></i> Register Team
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $tournaments->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                <h4>No Tournaments Available</h4>
                <p class="text-muted">There are no open tournaments for registration at the moment.</p>
                <a href="{{ route('team.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    @endif
</div>
@endsection
