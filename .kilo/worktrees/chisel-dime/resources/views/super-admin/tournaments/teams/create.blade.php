@extends('layouts.admin')

@section('title', 'Add Team - ' . $tournament->name . ' - Super Admin')

@section('content')
<div class="container-fluid px-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-0">Add Team to {{ $tournament->name }}</h1>
            <small class="text-muted">
                <a href="{{ route('super-admin.tournaments.index') }}">Tournaments</a>
                <span class="mx-1">/</span>
                <a href="{{ route('super-admin.tournaments.show', $tournament->id) }}">{{ $tournament->name }}</a>
                <span class="mx-1">/</span>
                <a href="{{ route('super-admin.tournaments.teams.index', $tournament->id) }}">Teams</a>
            </small>
        </div>
        <a href="{{ route('super-admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Teams
        </a>
    </div>

    <!-- Tournament Info -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <div class="row">
                <div class="col-md-3">
                    <span class="text-muted small">Organization:</span>
                    <span class="fw-bold">{{ $tournament->organization->name ?? 'N/A' }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small">Status:</span>
                    <span class="badge bg-{{ $tournament->status === 'open' ? 'success' : 'secondary' }}">{{ ucfirst($tournament->status) }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small">Registered Teams:</span>
                    <span class="fw-bold">{{ $tournament->teams()->count() }} / {{ $tournament->max_teams ?? 'Unlimited' }}</span>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small">Squad Limit:</span>
                    <span class="fw-bold">{{ $tournament->squad_limit }} players</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Team Form -->
    <div class="card shadow-sm border-0">
        <div class="card-header py-2 bg-white">
            <h6 class="m-0 font-weight-bold text-primary small">Register Team</h6>
        </div>
        <div class="card-body">
            @php
                // Pre-load registered team IDs to avoid N+1 queries
                $registeredTeamIds = $tournament->teams()->whereNotNull('team_id')->pluck('team_id')->toArray();
            @endphp
            <form method="POST" action="{{ route('super-admin.tournaments.teams.store', $tournament->id) }}">
                @csrf

                <!-- Team Selection -->
                <div class="mb-3">
                    <label for="team_id" class="form-label fw-bold">Select Team <span class="text-danger">*</span></label>
                    <select class="form-select @error('team_id') is-invalid @enderror" id="team_id" name="team_id" required>
                        <option value="">-- Select a Team --</option>
                        @forelse($teams as $team)
                            @php
                                $isRegistered = in_array($team->id, $registeredTeamIds);
                            @endphp
                            <option value="{{ $team->id }}" {{ $isRegistered ? 'disabled' : '' }}>
                                {{ $team->name }} ({{ $team->organization->name ?? 'No Organization' }})
                                {{ $isRegistered ? '- Already Registered' : '' }}
                            </option>
                        @empty
                            <option value="">No teams available</option>
                        @endforelse
                    </select>
                    @error('team_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Select a team from the dropdown. Already registered teams are disabled.</div>
                </div>

                <!-- Team Contact Information -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="team_contact_name" class="form-label">Contact Person</label>
                        <input type="text" class="form-control @error('team_contact_name') is-invalid @enderror"
                               id="team_contact_name" name="team_contact_name"
                               value="{{ old('team_contact_name', auth()->user()->name) }}"
                               placeholder="Enter contact name">
                        @error('team_contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="team_contact_email" class="form-label">Contact Email</label>
                        <input type="email" class="form-control @error('team_contact_email') is-invalid @enderror"
                               id="team_contact_email" name="team_contact_email"
                               value="{{ old('team_contact_email', auth()->user()->email) }}"
                               placeholder="Enter contact email">
                        @error('team_contact_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="team_contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" class="form-control @error('team_contact_phone') is-invalid @enderror"
                               id="team_contact_phone" name="team_contact_phone"
                               value="{{ old('team_contact_phone', auth()->user()->phone ?? '') }}"
                               placeholder="Enter contact phone">
                        @error('team_contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Auto-approve Option -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="auto_approve" name="auto_approve" value="1" {{ old('auto_approve') ? 'checked' : '' }}>
                    <label class="form-check-label" for="auto_approve">
                        Auto-approve team registration (skip pending status)
                    </label>
                </div>

                <!-- Dynamic Location Fields (based on tournament's organization location level) -->
                @if(!empty($locationFields))
                <div class="card bg-light mb-3">
                    <div class="card-header py-2">
                        <h6 class="m-0 small font-weight-bold text-primary">Team Location</h6>
                    </div>
                    <div class="card-body py-2">
                        <p class="small text-muted mb-2">
                            <i class="fas fa-info-circle"></i>
                            Location fields are determined by the tournament's organization level ({{ $locationLevel }}).
                        </p>
                        <div class="row">
                            @if(in_array('country', $locationFields))
                            <div class="col-md-6 mb-2">
                                <label for="country" class="form-label small fw-bold">Country <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm @error('country') is-invalid @enderror"
                                        id="country" name="country" required>
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $code => $name)
                                        <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            @if(in_array('county', $locationFields))
                            <div class="col-md-6 mb-2">
                                <label for="county" class="form-label small fw-bold">County</label>
                                <input type="text" class="form-control form-control-sm @error('county') is-invalid @enderror"
                                       id="county" name="county" value="{{ old('county') }}"
                                       placeholder="Enter county name"
                                       list="countyOptions">
                                @if(!empty($locationOptions['counties']))
                                <datalist id="countyOptions">
                                    @foreach($locationOptions['counties'] as $county)
                                        <option value="{{ $county }}">
                                    @endforeach
                                </datalist>
                                @endif
                                @error('county')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            @if(in_array('sub_county', $locationFields))
                            <div class="col-md-6 mb-2">
                                <label for="sub_county" class="form-label small fw-bold">Sub-County</label>
                                <input type="text" class="form-control form-control-sm @error('sub_county') is-invalid @enderror"
                                       id="sub_county" name="sub_county" value="{{ old('sub_county') }}"
                                       placeholder="Enter sub-county name"
                                       list="subCountyOptions">
                                @if(!empty($locationOptions['sub_counties']))
                                <datalist id="subCountyOptions">
                                    @foreach($locationOptions['sub_counties'] as $subCounty)
                                        <option value="{{ $subCounty }}">
                                    @endforeach
                                </datalist>
                                @endif
                                @error('sub_county')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            @if(in_array('ward', $locationFields))
                            <div class="col-md-6 mb-2">
                                <label for="ward" class="form-label small fw-bold">Ward</label>
                                <input type="text" class="form-control form-control-sm @error('ward') is-invalid @enderror"
                                       id="ward" name="ward" value="{{ old('ward') }}"
                                       placeholder="Enter ward name"
                                       list="wardOptions">
                                @if(!empty($locationOptions['wards']))
                                <datalist id="wardOptions">
                                    @foreach($locationOptions['wards'] as $ward)
                                        <option value="{{ $ward }}">
                                    @endforeach
                                </datalist>
                                @endif
                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('super-admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
