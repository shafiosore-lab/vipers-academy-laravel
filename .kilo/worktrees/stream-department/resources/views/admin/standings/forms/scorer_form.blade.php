<form action="{{ isset($action) && $action === 'edit' ? route('admin.standings.update', $item) : route('admin.standings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($action) && $action === 'edit')
        @method('PUT')
    @endif
    <input type="hidden" name="type" value="{{ $type }}">

    <div class="row">
        <!-- Basic Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Player Information</h5>
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

                    <div class="mb-3">
                        <label for="player_name" class="form-label">Player Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('player_name') is-invalid @enderror"
                               id="player_name" name="player_name" value="{{ old('player_name', $item->player_name ?? '') }}"
                               placeholder="e.g. Mohamed Salah" required>
                        @error('player_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                   id="nationality" name="nationality" value="{{ old('nationality', $item->nationality ?? '') }}"
                                   placeholder="e.g. Egyptian">
                            @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror"
                                   id="age" name="age" value="{{ old('age', $item->age ?? '') }}"
                                   min="15" max="50">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Player Image Upload -->
                    <div class="mb-3">
                        <label for="player_image" class="form-label">Player Image</label>
                        <input type="file" class="form-control @error('player_image') is-invalid @enderror"
                               id="player_image" name="player_image" accept="image/*">
                        @if(isset($item) && $item->player_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $item->player_image) }}"
                                     alt="Current photo" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                <small class="text-muted">Current photo will be replaced</small>
                            </div>
                        @endif
                        @error('player_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Team Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="team_name" class="form-label">Team Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('team_name') is-invalid @enderror"
                               id="team_name" name="team_name" value="{{ old('team_name', $item->team_name ?? '') }}"
                               placeholder="e.g. Liverpool FC" required>
                        @error('team_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>

                    <div class="mb-3">
                        <label for="player_position" class="form-label">Position <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('player_position') is-invalid @enderror"
                               id="player_position" name="player_position" value="{{ old('player_position', $item->player_position ?? '') }}"
                               placeholder="e.g. Forward, Midfielder" required>
                        @error('player_position')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_vipers_player" name="is_vipers_player"
                                   value="1" {{ old('is_vipers_player', $item->is_vipers_player ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_vipers_player">
                                This is a Vipers player
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Performance Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="ranking_position" class="form-label">Rank <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('ranking_position') is-invalid @enderror"
                                   id="ranking_position" name="ranking_position" value="{{ old('ranking_position', $item->ranking_position ?? '') }}"
                                   min="1" required>
                            @error('ranking_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="goals" class="form-label">Goals <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('goals') is-invalid @enderror"
                                   id="goals" name="goals" value="{{ old('goals', $item->goals ?? '') }}"
                                   min="0" required>
                            @error('goals')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="assists" class="form-label">Assists <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('assists') is-invalid @enderror"
                                   id="assists" name="assists" value="{{ old('assists', $item->assists ?? '') }}"
                                   min="0" required>
                            @error('assists')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="appearances" class="form-label">Apps <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('appearances') is-invalid @enderror"
                                   id="appearances" name="appearances" value="{{ old('appearances', $item->appearances ?? '') }}"
                                   min="0" required>
                            @error('appearances')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="minutes_played" class="form-label">Minutes <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('minutes_played') is-invalid @enderror"
                                   id="minutes_played" name="minutes_played" value="{{ old('minutes_played', $item->minutes_played ?? '') }}"
                                   min="0" required>
                            @error('minutes_played')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="shots_on_target" class="form-label">Shots Target</label>
                            <input type="number" class="form-control @error('shots_on_target') is-invalid @enderror"
                                   id="shots_on_target" name="shots_on_target" value="{{ old('shots_on_target', $item->shots_on_target ?? '') }}"
                                   min="0">
                            @error('shots_on_target')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="shots_total" class="form-label">Total Shots</label>
                            <input type="number" class="form-control @error('shots_total') is-invalid @enderror"
                                   id="shots_total" name="shots_total" value="{{ old('shots_total', $item->shots_total ?? '') }}"
                                   min="0">
                            @error('shots_total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="penalties_scored" class="form-label">Pens Scored</label>
                            <input type="number" class="form-control @error('penalties_scored') is-invalid @enderror"
                                   id="penalties_scored" name="penalties_scored" value="{{ old('penalties_scored', $item->penalties_scored ?? '') }}"
                                   min="0">
                            @error('penalties_scored')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="penalties_missed" class="form-label">Pens Missed</label>
                            <input type="number" class="form-control @error('penalties_missed') is-invalid @enderror"
                                   id="penalties_missed" name="penalties_missed" value="{{ old('penalties_missed', $item->penalties_missed ?? '') }}"
                                   min="0">
                            @error('penalties_missed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="free_kicks" class="form-label">Free Kicks</label>
                            <input type="number" class="form-control @error('free_kicks') is-invalid @enderror"
                                   id="free_kicks" name="free_kicks" value="{{ old('free_kicks', $item->free_kicks ?? '') }}"
                                   min="0">
                            @error('free_kicks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="headers" class="form-label">Headers</label>
                            <input type="number" class="form-control @error('headers') is-invalid @enderror"
                                   id="headers" name="headers" value="{{ old('headers', $item->headers ?? '') }}"
                                   min="0">
                            @error('headers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="left_foot" class="form-label">Left Foot</label>
                            <input type="number" class="form-control @error('left_foot') is-invalid @enderror"
                                   id="left_foot" name="left_foot" value="{{ old('left_foot', $item->left_foot ?? '') }}"
                                   min="0">
                            @error('left_foot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="right_foot" class="form-label">Right Foot</label>
                            <input type="number" class="form-control @error('right_foot') is-invalid @enderror"
                                   id="right_foot" name="right_foot" value="{{ old('right_foot', $item->right_foot ?? '') }}"
                                   min="0">
                            @error('right_foot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="inside_box" class="form-label">Inside Box</label>
                            <input type="number" class="form-control @error('inside_box') is-invalid @enderror"
                                   id="inside_box" name="inside_box" value="{{ old('inside_box', $item->inside_box ?? '') }}"
                                   min="0">
                            @error('inside_box')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <label for="outside_box" class="form-label">Outside Box</label>
                            <input type="number" class="form-control @error('outside_box') is-invalid @enderror"
                                   id="outside_box" name="outside_box" value="{{ old('outside_box', $item->outside_box ?? '') }}"
                                   min="0">
                            @error('outside_box')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                  rows="3" placeholder="Additional notes about the player">{{ old('notes', $item->notes ?? '') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Calculated Fields Info -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-calculator me-2"></i>Automatically Calculated Fields</h6>
                        <ul class="mb-0 small">
                            <li><strong>Goals per Game:</strong> Total Goals ÷ Appearances</li>
                            <li><strong>Shot Accuracy:</strong> Shots on Target ÷ Total Shots × 100%</li>
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
                            <i class="fas fa-save me-2"></i>Create Scorer Entry
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Scorer Entry
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
