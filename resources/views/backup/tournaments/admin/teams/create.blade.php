@extends('layouts.admin')

@section('title', 'Add Team - ' . $tournament->name)

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0">Add Team</h4>
                <small class="text-muted">{{ $tournament->name }}</small>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.tournaments.teams.store', $tournament->id) }}">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Select Existing Team</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Available Teams</label>
                        <select name="team_id" class="form-select form-select-sm">
                            <option value="">-- Select a team --</option>
                            @foreach($availableTeams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Or Create New Team</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Team Name</label>
                        <input type="text" name="team_name" class="form-control form-control-sm" placeholder="Enter team name">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Contact Information</h6></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Contact Name</label>
                        <input type="text" name="team_contact_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="team_contact_email" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="team_contact_phone" class="form-control form-control-sm" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Location Fields (based on tournament's organization location level) -->
        @if(!empty($locationFields))
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Team Location</h6>
                    <small class="text-muted">Location level: {{ $locationLevel }}</small>
                </div>
                <div class="card-body">
                    @if(in_array('country', $locationFields))
                    <div class="mb-2">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
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
                    <div class="mb-2">
                        <label class="form-label">County</label>
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
                    <div class="mb-2">
                        <label class="form-label">Sub-County</label>
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
                    <div class="mb-2">
                        <label class="form-label">Ward</label>
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
    </div>
    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        <button type="submit" class="btn btn-primary btn-sm">Add Team</button>
    </div>
</form>
@endsection
