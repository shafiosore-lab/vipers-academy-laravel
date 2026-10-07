@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'Player Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Player Photo -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if($squad->player->passport_photo_path)
                        <img src="{{ $squad->player->passport_photo_url }}" alt="Passport Photo" class="img-fluid rounded" style="max-height: 300px;">
                    @else
                        <div class="py-5">
                            <i class="fas fa-id-card fa-4x text-muted"></i>
                            <p class="text-muted mt-2">No photo on file</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Player Information -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Player Information</h5>
                    <span class="badge bg-{{ $squad->isVerified() ? 'success' : ($squad->isPending() ? 'warning' : 'danger') }}">
                        {{ ucfirst($squad->verification_status) }}
                    </span>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Full Name:</th>
                            <td>{{ $squad->player->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td>{{ $squad->player->date_of_birth ? $squad->player->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Age:</th>
                            <td>{{ $squad->player->age ?? 'N/A' }} years</td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td>{{ ucfirst($squad->player->gender ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <th>City:</th>
                            <td>{{ $squad->player->city ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Identification -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Identification</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">ID Type:</th>
                            <td>{{ ucfirst($squad->player->id_type ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <th>ID Number:</th>
                            <td>{{ $squad->player->id_number ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Squad Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Tournament Squad Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Position:</th>
                            <td>{{ $squad->position ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Jersey Number:</th>
                            <td>{{ $squad->jersey_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Registration Date:</th>
                            <td>{{ $squad->registration_date ? $squad->registration_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @if($squad->verified_at)
                            <tr>
                                <th>Verified Date:</th>
                                <td>{{ $squad->verified_at->format('M d, Y') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2">
                <a href="{{ route('team.players', $tournament->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                @if(!$tournamentTeam->isSquadLocked())
                    <a href="{{ route('team.players.edit', [$tournament->id, $squad->id]) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Player
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
