@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'Register for Tournament')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Register Team for Tournament</h5>
                </div>
                <div class="card-body">
                    <!-- Tournament Info -->
                    <div class="alert alert-info mb-4">
                        <h5>{{ $tournament->name }}</h5>
                        <p class="mb-0">
                            <strong>Organization:</strong> {{ $tournament->organization->name ?? 'N/A' }}<br>
                            <strong>Season:</strong> {{ $tournament->season ?? 'N/A' }}<br>
                            <strong>Squad Limit:</strong> {{ $tournament->squad_limit }} players per team<br>
                            <strong>Minimum Players:</strong> {{ $tournament->min_players }}
                        </p>
                    </div>

                    <!-- Tabs for Select or Create -->
                    <ul class="nav nav-tabs mb-4" id="teamTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="existing-tab" data-bs-toggle="tab" data-bs-target="#existing" type="button" role="tab">
                                Select Existing Team
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button" role="tab">
                                Create New Team
                            </button>
                        </li>
                    </ul>

                    <form action="{{ route('team.tournament.register.post', $tournament->id) }}" method="POST">
                        @csrf

                        <div class="tab-content" id="teamTabContent">
                            <!-- Select Existing Team -->
                            <div class="tab-pane fade show active" id="existing" role="tabpanel">
                                <div class="mb-3">
                                    <label for="team_id" class="form-label">Select a Team</label>
                                    <select class="form-select @error('team_id') is-invalid @enderror"
                                            id="team_id" name="team_id">
                                        <option value="">-- Select a team --</option>
                                        @if(isset($availableTeams))
                                            @foreach($availableTeams as $teamOption)
                                                <option value="{{ $teamOption->id }}" {{ old('team_id') == $teamOption->id ? 'selected' : '' }}>
                                                    {{ $teamOption->name }} ({{ $teamOption->category ?? 'N/A' }} - {{ $teamOption->age_group ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('team_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Create New Team -->
                            <div class="tab-pane fade" id="new" role="tabpanel">
                                <div class="mb-3">
                                    <label for="new_team_name" class="form-label">Team Name *</label>
                                    <input type="text" class="form-control @error('new_team_name') is-invalid @enderror"
                                           id="new_team_name" name="new_team_name"
                                           value="{{ old('new_team_name') }}" placeholder="Enter team name">
                                    @error('new_team_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information (for both options) -->
                        <h6 class="border-bottom pb-2 mb-3 mt-4">Contact Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="team_contact_name" class="form-label">Contact Name *</label>
                                <input type="text" class="form-control @error('team_contact_name') is-invalid @enderror"
                                       id="team_contact_name" name="team_contact_name"
                                       value="{{ old('team_contact_name', auth()->user()->name) }}" required>
                                @error('team_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="team_contact_phone" class="form-label">Contact Phone *</label>
                                <input type="text" class="form-control @error('team_contact_phone') is-invalid @enderror"
                                       id="team_contact_phone" name="team_contact_phone"
                                       value="{{ old('team_contact_phone', auth()->user()->phone ?? '') }}" required>
                                @error('team_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="team_contact_email" class="form-label">Contact Email *</label>
                            <input type="email" class="form-control @error('team_contact_email') is-invalid @enderror"
                                   id="team_contact_email" name="team_contact_email"
                                   value="{{ old('team_contact_email', auth()->user()->email) }}" required>
                            @error('team_contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('team.tournaments.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Register Team
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
