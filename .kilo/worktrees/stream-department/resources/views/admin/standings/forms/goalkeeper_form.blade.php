<form action="{{ isset($action) && $action === 'edit' ? route('admin.standings.update', $item) : route('admin.standings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($action) && $action === 'edit')
        @method('PUT')
    @endif
    <input type="hidden" name="type" value="{{ $type }}">

    <!-- Basic Information -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Goalkeeper Information</h5>
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
                        <div class="col-md-6 mb-3">
                            <label for="goalkeeper_name" class="form-label">Goalkeeper Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('goalkeeper_name') is-invalid @enderror"
                                   id="goalkeeper_name" name="goalkeeper_name" value="{{ old('goalkeeper_name', $item->goalkeeper_name ?? '') }}"
                                   placeholder="e.g. Alisson Becker" required>
                            @error('goalkeeper_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror"
                                   id="position" name="position" value="{{ old('position', $item->position ?? '') }}"
                                   min="1" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="dominant_hand" class="form-label">Dominant Hand <span class="text-danger">*</span></label>
                            <select class="form-control @error('dominant_hand') is-invalid @enderror" id="dominant_hand" name="dominant_hand" required>
                                <option value="">Select Hand</option>
                                <option value="right" {{ old('dominant_hand', $item->dominant_hand ?? '') === 'right' ? 'selected' : '' }}>Right</option>
                                <option value="left" {{ old('dominant_hand', $item->dominant_hand ?? '') === 'left' ? 'selected' : '' }}>Left</option>
                            </select>
                            @error('dominant_hand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                   id="nationality" name="nationality" value="{{ old('nationality', $item->nationality ?? '') }}"
                                   placeholder="e.g. Brazilian">
                            @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror"
                                   id="age" name="age" value="{{ old('age', $item->age ?? '') }}"
                                   min="15" max="50">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="height_cm" class="form-label">Height (cm)</label>
                            <input type="number" step="0.1" class="form-control @error('height_cm') is-invalid @enderror"
                                   id="height_cm" name="height_cm" value="{{ old('height_cm', $item->height_cm ?? '') }}"
                                   min="150" max="220">
                            @error('height_cm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Goalkeeper Image Upload -->
                    <div class="mb-3">
                        <label for="goalkeeper_image" class="form-label">Goalkeeper Image</label>
                        <input type="file" class="form-control @error('goalkeeper_image') is-invalid @enderror"
                               id="goalkeeper_image" name="goalkeeper_image" accept="image/*">
                        @if(isset($item) && $item->goalkeeper_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $item->goalkeeper_image) }}"
                                     alt="Current photo" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                <small class="text-muted">Current photo will be replaced</small>
                            </div>
                        @endif
                        @error('goalkeeper_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Information -->
        <div class="col-lg-4">
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
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="appearances" class="form-label">Apps <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('appearances') is-invalid @enderror"
                                   id="appearances" name="appearances" value="{{ old('appearances', $item->appearances ?? '') }}"
                                   min="0" required>
                            @error('appearances')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="minutes_played" class="form-label">Minutes <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('minutes_played') is-invalid @enderror"
                                   id="minutes_played" name="minutes_played" value="{{ old('minutes_played', $item->minutes_played ?? '') }}"
                                   min="0" required>
                            @error('minutes_played')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="saves" class="form-label">Saves <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('saves') is-invalid @enderror"
                                   id="saves" name="saves" value="{{ old('saves', $item->saves ?? '') }}"
                                   min="0" required>
                            @error('saves')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="goals_conceded" class="form-label">Goals Conceded <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('goals_conceded') is-invalid @enderror"
                                   id="goals_conceded" name="goals_conceded" value="{{ old('goals_conceded', $item->goals_conceded ?? '') }}"
                                   min="0" required>
                            @error('goals_conceded')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="clean_sheets" class="form-label">Clean Sheets <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('clean_sheets') is-invalid @enderror"
                                   id="clean_sheets" name="clean_sheets" value="{{ old('clean_sheets', $item->clean_sheets ?? '') }}"
                                   min="0" required>
                            @error('clean_sheets')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="overall_rating" class="form-label">Overall Rating</label>
                            <input type="number" step="0.1" class="form-control @error('overall_rating') is-invalid @enderror"
                                   id="overall_rating" name="overall_rating" value="{{ old('overall_rating', $item->overall_rating ?? '') }}"
                                   min="0" max="10">
                            @error('overall_rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="shots_faced" class="form-label">Shots Faced</label>
                            <input type="number" class="form-control @error('shots_faced') is-invalid @enderror"
                                   id="shots_faced" name="shots_faced" value="{{ old('shots_faced', $item->shots_faced ?? '') }}"
                                   min="0">
                            @error('shots_faced')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="shots_on_target_faced" class="form-label">Shots Target Faced</label>
                            <input type="number" class="form-control @error('shots_on_target_faced') is-invalid @enderror"
                                   id="shots_on_target_faced" name="shots_on_target_faced" value="{{ old('shots_on_target_faced', $item->shots_on_target_faced ?? '') }}"
                                   min="0">
                            @error('shots_on_target_faced')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="penalties_saved" class="form-label">Pens Saved</label>
                            <input type="number" class="form-control @error('penalties_saved') is-invalid @enderror"
                                   id="penalties_saved" name="penalties_saved" value="{{ old('penalties_saved', $item->penalties_saved ?? '') }}"
                                   min="0">
                            @error('penalties_saved')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="penalties_faced" class="form-label">Pens Faced</label>
                            <input type="number" class="form-control @error('penalties_faced') is-invalid @enderror"
                                   id="penalties_faced" name="penalties_faced" value="{{ old('penalties_faced', $item->penalties_faced ?? '') }}"
                                   min="0">
                            @error('penalties_faced')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="catches" class="form-label">Catches</label>
                            <input type="number" class="form-control @error('catches') is-invalid @enderror"
                                   id="catches" name="catches" value="{{ old('catches', $item->catches ?? '') }}"
                                   min="0">
                            @error('catches')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <label for="punches" class="form-label">Punches</label>
                            <input type="number" class="form-control @error('punches') is-invalid @enderror"
                                   id="punches" name="punches" value="{{ old('punches', $item->punches ?? '') }}"
                                   min="0">
                            @error('punches')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label for="distribution_completed" class="form-label">Distribution Completed</label>
                            <input type="number" class="form-control @error('distribution_completed') is-invalid @enderror"
                                   id="distribution_completed" name="distribution_completed" value="{{ old('distribution_completed', $item->distribution_completed ?? '') }}"
                                   min="0">
                            @error('distribution_completed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label for="distribution_attempted" class="form-label">Distribution Attempted</label>
                            <input type="number" class="form-control @error('distribution_attempted') is-invalid @enderror"
                                   id="distribution_attempted" name="distribution_attempted" value="{{ old('distribution_attempted', $item->distribution_attempted ?? '') }}"
                                   min="0">
                            @error('distribution_attempted')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label for="crosses_claimed" class="form-label">Crosses Claimed</label>
                            <input type="number" class="form-control @error('crosses_claimed') is-invalid @enderror"
                                   id="crosses_claimed" name="crosses_claimed" value="{{ old('crosses_claimed', $item->crosses_claimed ?? '') }}"
                                   min="0">
                            @error('crosses_claimed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label for="errors_leading_to_goal" class="form-label">Errors Leading to Goal</label>
                            <input type="number" class="form-control @error('errors_leading_to_goal') is-invalid @enderror"
                                   id="errors_leading_to_goal" name="errors_leading_to_goal" value="{{ old('errors_leading_to_goal', $item->errors_leading_to_goal ?? '') }}"
                                   min="0">
                            @error('errors_leading_to_goal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                  rows="3" placeholder="Additional notes about the goalkeeper">{{ old('notes', $item->notes ?? '') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Calculated Fields Info -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-calculator me-2"></i>Automatically Calculated Fields</h6>
                        <ul class="mb-0 small">
                            <li><strong>Goals Conceded per Game:</strong> Total Goals Conceded ÷ Appearances</li>
                            <li><strong>Save Percentage:</strong> Saves ÷ Shots on Target Faced × 100%</li>
                            <li><strong>Clean Sheet Percentage:</strong> Clean Sheets ÷ Appearances × 100%</li>
                            <li><strong>Penalty Save Percentage:</strong> Penalties Saved ÷ Penalties Faced × 100%</li>
                            <li><strong>Distribution Accuracy:</strong> Distribution Completed ÷ Distribution Attempted × 100%</li>
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
                            <i class="fas fa-save me-2"></i>Create Goalkeeper Entry
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Goalkeeper Entry
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
