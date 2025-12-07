@extends('layouts.admin')

@section('title', 'Edit Website Player - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Player: {{ $websitePlayer->full_name }}</h3>
                    <a href="{{ route('admin.website-players.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back to Players
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.website-players.update', $websitePlayer) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name', $websitePlayer->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ old('last_name', $websitePlayer->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category">Category *</label>
                                    <select class="form-control @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="u9" {{ old('category', $websitePlayer->category) == 'u9' ? 'selected' : '' }}>Under 9</option>
                                        <option value="u11" {{ old('category', $websitePlayer->category) == 'u11' ? 'selected' : '' }}>Under 11</option>
                                        <option value="u13" {{ old('category', $websitePlayer->category) == 'u13' ? 'selected' : '' }}>Under 13</option>
                                        <option value="u15" {{ old('category', $websitePlayer->category) == 'u15' ? 'selected' : '' }}>Under 15</option>
                                        <option value="u17" {{ old('category', $websitePlayer->category) == 'u17' ? 'selected' : '' }}>Under 17</option>
                                        <option value="u19" {{ old('category', $websitePlayer->category) == 'u19' ? 'selected' : '' }}>Under 19</option>
                                        <option value="senior" {{ old('category', $websitePlayer->category) == 'senior' ? 'selected' : '' }}>Senior</option>
                                        <option value="academy" {{ old('category', $websitePlayer->category) == 'academy' ? 'selected' : '' }}>Academy</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="position">Position *</label>
                                    <select class="form-control @error('position') is-invalid @enderror"
                                            id="position" name="position" required>
                                        <option value="">Select Position</option>
                                        <option value="goalkeeper" {{ old('position', $websitePlayer->position) == 'goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                        <option value="defender" {{ old('position', $websitePlayer->position) == 'defender' ? 'selected' : '' }}>Defender</option>
                                        <option value="midfielder" {{ old('position', $websitePlayer->position) == 'midfielder' ? 'selected' : '' }}>Midfielder</option>
                                        <option value="striker" {{ old('position', $websitePlayer->position) == 'striker' ? 'selected' : '' }}>Striker</option>
                                    </select>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="age">Age *</label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror"
                                           id="age" name="age" value="{{ old('age', $websitePlayer->age) }}" min="6" max="25" required>
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jersey_number">Jersey Number</label>
                                    <input type="number" class="form-control @error('jersey_number') is-invalid @enderror"
                                           id="jersey_number" name="jersey_number" value="{{ old('jersey_number', $websitePlayer->jersey_number) }}" min="1" max="99">
                                    @error('jersey_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Player Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Upload a new image to replace the current one (max 5MB). Will be saved as: {{ strtolower($websitePlayer->first_name . '-' . $websitePlayer->last_name . '-' . $websitePlayer->category . '-' . $websitePlayer->position . '-' . $websitePlayer->age . '.jpg') }}</small>
                                    @if($websitePlayer->image_url)
                                        <div class="mt-2">
                                            <img src="{{ $websitePlayer->image_url }}" alt="Current image" class="img-thumbnail" style="width: 100px; height: 100px;">
                                            <p class="text-muted small mt-1">Current image</p>
                                        </div>
                                    @endif
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bio">Biography</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                      id="bio" name="bio" rows="3">{{ old('bio', $websitePlayer->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="goals">Goals</label>
                                    <input type="number" class="form-control @error('goals') is-invalid @enderror"
                                           id="goals" name="goals" value="{{ old('goals', $websitePlayer->goals ?? 0) }}" min="0">
                                    @error('goals')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="assists">Assists</label>
                                    <input type="number" class="form-control @error('assists') is-invalid @enderror"
                                           id="assists" name="assists" value="{{ old('assists', $websitePlayer->assists ?? 0) }}" min="0">
                                    @error('assists')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="appearances">Appearances</label>
                                    <input type="number" class="form-control @error('appearances') is-invalid @enderror"
                                           id="appearances" name="appearances" value="{{ old('appearances', $websitePlayer->appearances ?? 0) }}" min="0">
                                    @error('appearances')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yellow_cards">Yellow Cards</label>
                                    <input type="number" class="form-control @error('yellow_cards') is-invalid @enderror"
                                           id="yellow_cards" name="yellow_cards" value="{{ old('yellow_cards', $websitePlayer->yellow_cards ?? 0) }}" min="0">
                                    @error('yellow_cards')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="red_cards">Red Cards</label>
                                    <input type="number" class="form-control @error('red_cards') is-invalid @enderror"
                                           id="red_cards" name="red_cards" value="{{ old('red_cards', $websitePlayer->red_cards ?? 0) }}" min="0">
                                    @error('red_cards')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube_url">YouTube URL</label>
                                    <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                                           id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $websitePlayer->youtube_url) }}"
                                           placeholder="https://www.youtube.com/watch?v=...">
                                    @error('youtube_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Player
                            </button>
                            <a href="{{ route('admin.website-players.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
