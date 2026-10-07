<form action="{{ isset($action) && $action === 'edit' ? route('admin.standings.update', $item) : route('admin.standings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($action) && $action === 'edit')
        @method('PUT')
    @endif
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="league_type" value="league">

    <div class="row">
        <!-- Basic Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">League Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="season" class="form-label">Season <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('season') is-invalid @enderror"
                                   id="season" name="season" value="{{ old('season', $item->season ?? '') }}"
                                   placeholder="e.g. 2024/2025" required>
                            @error('season')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="league_name" class="form-label">League Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('league_name') is-invalid @enderror"
                                   id="league_name" name="league_name" value="{{ old('league_name', $item->league_name ?? '') }}"
                                   placeholder="e.g. Premier League" required>
                            @error('league_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="team_name" class="form-label">Team Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('team_name') is-invalid @enderror"
                                   id="team_name" name="team_name" value="{{ old('team_name', $item->team_name ?? '') }}"
                                   placeholder="e.g. Arsenal FC" required>
                            @error('team_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror"
                                   id="position" name="position" value="{{ old('position', $item->position ?? '') }}"
                                   min="1" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Team Logo Upload -->
                    <div class="mb-3">
                        <label for="team_logo" class="form-label">Team Logo</label>
                        <input type="file" class="form-control @error('team_logo') is-invalid @enderror"
                               id="team_logo" name="team_logo" accept="image/*">
                        @if(isset($item) && $item->team_logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $item->team_logo) }}"
                                     alt="Current logo" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                <small class="text-muted">Current logo will be replaced</small>
                            </div>
                        @endif
                        @error('team_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Match Statistics -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Match Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="played" class="form-label">Played <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('played') is-invalid @enderror"
                                   id="played" name="played" value="{{ old('played', $item->played ?? '') }}"
                                   min="0" required>
                            @error('played')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 mb-3">
                            <label for="won" class="form-label">Won <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('won') is-invalid @enderror"
                                   id="won" name="won" value="{{ old('won', $item->won ?? '') }}"
                                   min="0" required>
                            @error('won')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="drawn" class="form-label">Drawn <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('drawn') is-invalid @enderror"
                                   id="drawn" name="drawn" value="{{ old('drawn', $item->drawn ?? '') }}"
                                   min="0" required>
                            @error('drawn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 mb-3">
                            <label for="lost" class="form-label">Lost <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('lost') is-invalid @enderror"
                                   id="lost" name="lost" value="{{ old('lost', $item->lost ?? '') }}"
                                   min="0" required>
                            @error('lost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="goals_for" class="form-label">GF <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('goals_for') is-invalid @enderror"
                                   id="goals_for" name="goals_for" value="{{ old('goals_for', $item->goals_for ?? '') }}"
                                   min="0" required>
                            @error('goals_for')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-6 mb-3">
                            <label for="goals_against" class="form-label">GA <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('goals_against') is-invalid @enderror"
                                   id="goals_against" name="goals_against" value="{{ old('goals_against', $item->goals_against ?? '') }}"
                                   min="0" required>
                            @error('goals_against')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="points" class="form-label">Points <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('points') is-invalid @enderror"
                               id="points" name="points" value="{{ old('points', $item->points ?? '') }}"
                               min="0" required>
                        @error('points')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Additional Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clean_sheets" class="form-label">Clean Sheets</label>
                            <input type="number" class="form-control @error('clean_sheets') is-invalid @enderror"
                                   id="clean_sheets" name="clean_sheets" value="{{ old('clean_sheets', $item->clean_sheets ?? '') }}"
                                   min="0">
                            @error('clean_sheets')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="failed_to_score" class="form-label">Failed to Score</label>
                            <input type="number" class="form-control @error('failed_to_score') is-invalid @enderror"
                                   id="failed_to_score" name="failed_to_score" value="{{ old('failed_to_score', $item->failed_to_score ?? '') }}"
                                   min="0">
                            @error('failed_to_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="form" class="form-label">Form</label>
                        <input type="text" class="form-control @error('form') is-invalid @enderror"
                               id="form" name="form" value="{{ old('form', $item->form ?? '') }}"
                               placeholder="e.g. WWDLW (W=Win, D=Draw, L=Loss)" maxlength="10">
                        @error('form')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="">Select Status</option>
                            <option value="champions" {{ old('status', $item->status ?? '') === 'champions' ? 'selected' : '' }}>Champions</option>
                            <option value="relegated" {{ old('status', $item->status ?? '') === 'relegated' ? 'selected' : '' }}>Relegated</option>
                            <option value="qualifying" {{ old('status', $item->status ?? '') === 'qualifying' ? 'selected' : '' }}>Qualifying</option>
                            <option value="safe" {{ old('status', $item->status ?? '') === 'safe' ? 'selected' : '' }}>Safe</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_vipers_team" name="is_vipers_team"
                                   value="1" {{ old('is_vipers_team', $item->is_vipers_team ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_vipers_team">
                                This is a Vipers team
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                  rows="4" placeholder="Additional notes about the team">{{ old('notes', $item->notes ?? '') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Calculated Fields Info -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-calculator me-2"></i>Automatically Calculated Fields</h6>
                        <ul class="mb-0 small">
                            <li><strong>Goal Difference:</strong> Goals For - Goals Against</li>
                            <li><strong>Points per Game:</strong> Total Points ÷ Games Played</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.standings.index', ['type' => $type]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Standings
                </a>
                <div>
                    @if(isset($action) && $action === 'create')
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create League Entry
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update League Entry
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
