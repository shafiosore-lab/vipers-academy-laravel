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
<!-- Bulk Add Teams Section -->
<div class="card mb-3">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0"><i class="fas fa-users me-2"></i>Bulk Add Teams</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.tournaments.teams.bulk-add', $tournament->id) }}" id="bulkAddForm">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Team Names (one per line)</label>
                        <textarea name="team_names" id="teamNamesInput" class="form-control" rows="6"
                            placeholder="Enter team names, one per line&#10;Example:&#10;FC Barcelona&#10;Real Madrid&#10;Manchester United"></textarea>
                        <div class="form-text">Enter each team name on a separate line. Duplicates will be automatically detected.</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Validation Status</label>
                        <div id="validationStatus" class="border rounded p-3 bg-light" style="min-height: 120px;">
                            <div class="text-muted text-center">
                                <i class="fas fa-info-circle me-1"></i>
                                Enter team names to see validation
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="auto_approve" id="autoApproveBulk" value="1">
                            <label class="form-check-label" for="autoApproveBulk">
                                Auto-approve all teams
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.tournaments.teams.index', $tournament->id) }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm" id="bulkAddBtn">
                    <i class="fas fa-plus me-1"></i>Add Teams in Bulk
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center my-4">
    <span class="badge bg-secondary px-3 py-2">OR</span>
</div>

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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const teamNamesInput = document.getElementById('teamNamesInput');
    const validationStatus = document.getElementById('validationStatus');
    const bulkAddBtn = document.getElementById('bulkAddBtn');
    let validationTimeout;

    // Debounce function
    function debounce(func, wait) {
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(validationTimeout);
                func(...args);
            };
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(later, wait);
        };
    }

    // Validate team names
    const validateTeamNames = debounce(function() {
        const teamNames = teamNamesInput.value
            .split('\n')
            .map(name => name.trim())
            .filter(name => name.length > 0);

        if (teamNames.length === 0) {
            validationStatus.innerHTML = `
                <div class="text-muted text-center">
                    <i class="fas fa-info-circle me-1"></i>
                    Enter team names to see validation
                </div>
            `;
            bulkAddBtn.disabled = true;
            return;
        }

        // Check for duplicates within the input
        const duplicates = [];
        const uniqueNames = [];
        const seen = new Set();

        teamNames.forEach(name => {
            const lowerName = name.toLowerCase();
            if (seen.has(lowerName)) {
                duplicates.push(name);
            } else {
                seen.add(lowerName);
                uniqueNames.push(name);
            }
        });

        // Show immediate feedback while AJAX is pending
        let immediateHtml = '<div class="small">';
        let immediateValidCount = uniqueNames.length;

        if (duplicates.length > 0) {
            immediateHtml += '<div class="text-warning mb-2"><i class="fas fa-exclamation-triangle me-1"></i><strong>Duplicates in input:</strong></div>';
            duplicates.forEach(name => {
                immediateHtml += `<div class="text-warning ms-2">⚠ ${name}</div>`;
            });
        }

        if (immediateValidCount > 0) {
            immediateHtml += '<div class="text-info mb-2 mt-2"><i class="fas fa-spinner fa-spin me-1"></i><strong>Checking availability...</strong></div>';
            immediateHtml += `<div class="text-info ms-2">${immediateValidCount} team(s) to check</div>`;
        }

        immediateHtml += '</div>';
        validationStatus.innerHTML = immediateHtml;

        // Enable button immediately if there are valid team names (even before AJAX completes)
        bulkAddBtn.disabled = immediateValidCount === 0;

        // Make AJAX request to check existing teams
        fetch('{{ route("admin.tournaments.teams.check-existing", $tournament->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ team_names: uniqueNames })
        })
        .then(response => {
            console.log('AJAX response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('AJAX response data:', data);
            let html = '<div class="small">';
            let validCount = 0;
            let duplicateCount = duplicates.length;
            let existingCount = data.existing ? data.existing.length : 0;

            // Show duplicates within input
            if (duplicates.length > 0) {
                html += '<div class="text-warning mb-2"><i class="fas fa-exclamation-triangle me-1"></i><strong>Duplicates in input:</strong></div>';
                duplicates.forEach(name => {
                    html += `<div class="text-warning ms-2">⚠ ${name}</div>`;
                });
            }

            // Show existing teams
            if (data.existing && data.existing.length > 0) {
                html += '<div class="text-danger mb-2 mt-2"><i class="fas fa-times-circle me-1"></i><strong>Already registered:</strong></div>';
                data.existing.forEach(name => {
                    html += `<div class="text-danger ms-2">✗ ${name}</div>`;
                });
            }

            // Show valid teams
            const validTeams = data.available || [];
            if (validTeams.length > 0) {
                html += '<div class="text-success mb-2 mt-2"><i class="fas fa-check-circle me-1"></i><strong>Available:</strong></div>';
                validTeams.forEach(name => {
                    html += `<div class="text-success ms-2">✓ ${name}</div>`;
                    validCount++;
                });
            }

            html += '</div>';

            // Add summary
            let summary = '<div class="border-top pt-2 mt-2 small">';
            summary += `<div class="text-success">✓ ${validCount} available</div>`;
            if (duplicateCount > 0) {
                summary += `<div class="text-warning">⚠ ${duplicateCount} duplicate(s)</div>`;
            }
            if (existingCount > 0) {
                summary += `<div class="text-danger">✗ ${existingCount} already registered</div>`;
            }
            summary += '</div>';

            validationStatus.innerHTML = html + summary;

            // Enable/disable submit button based on validation results
            bulkAddBtn.disabled = validCount === 0;
        })
        .catch(error => {
            console.error('Validation AJAX error:', error);
            // Fallback: Enable button if there are team names (even if AJAX fails)
            // This allows the form to be submitted and server-side validation will catch duplicates
            validationStatus.innerHTML = `
                <div class="small">
                    <div class="text-warning mb-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Validation service unavailable</strong>
                    </div>
                    <div class="text-muted ms-2">
                        ${uniqueNames.length} team(s) will be checked on submission
                    </div>
                    ${duplicates.length > 0 ? `
                        <div class="text-warning mt-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            ${duplicates.length} duplicate(s) in input
                        </div>
                    ` : ''}
                </div>
            `;
            // Enable button so user can submit (server will validate)
            bulkAddBtn.disabled = false;
        });
    }, 500);

    // Add event listener
    teamNamesInput.addEventListener('input', validateTeamNames);
});
</script>
@endsection
