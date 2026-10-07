@extends('layouts.admin')

@section('title', __('Record Attendance - Vipers Academy Admin'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Record Attendance') }}</h1>
                    <p class="text-muted">{{ __('Record player attendance for training sessions or matches') }}</p>
                </div>
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Attendance') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2 text-primary"></i>{{ __('Attendance Details') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.attendance.store') }}">
                        @csrf

                        <!-- Session Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-calendar me-2"></i>{{ __('Session Information') }}</h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="session_date" class="form-label">{{ __('Session Date') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('session_date') is-invalid @enderror"
                                       id="session_date" name="session_date"
                                       value="{{ old('session_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                @error('session_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Select the date of the training session or match') }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="session_type" class="form-label">{{ __('Session Type') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('session_type') is-invalid @enderror"
                                        id="session_type" name="session_type" required>
                                    <option value="">{{ __('Select Session Type') }}</option>
                                    <option value="training" {{ old('session_type') == 'training' ? 'selected' : '' }}>{{ __('Training Session') }}</option>
                                    <option value="match" {{ old('session_type') == 'match' ? 'selected' : '' }}>{{ __('Match') }}</option>
                                </select>
                                @error('session_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Player Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-users me-2"></i>{{ __('Player Selection') }}</h6>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="player_id" class="form-label">{{ __('Select Player') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('player_id') is-invalid @enderror"
                                        id="player_id" name="player_id" required>
                                    <option value="">{{ __('Choose a player...') }}</option>
                                    @foreach($players as $player)
                                        <option value="{{ $player->id }}" {{ old('player_id') == $player->id ? 'selected' : '' }}>
                                            {{ $player->full_name }} ({{ $player->player_id ?? 'ID: ' . $player->id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('player_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($players->isEmpty())
                                    <div class="alert alert-warning mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        {{ __('No active players found. You need to add players before recording attendance.') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>{{ __('Attendance Recording') }}</h6>
                                    <p class="mb-2">{{ __('This will create an attendance record for the selected player and session.') }}</p>
                                    <ul class="mb-0 small">
                                        <li>{{ __('Players can check in/out later using the attendance management page') }}</li>
                                        <li>{{ __('You can record attendance for past dates (up to today)') }}</li>
                                        <li>{{ __('Duplicate attendance records for the same player/session will be prevented') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary" @if($players->isEmpty()) disabled @endif>
                                <i class="fas fa-save me-2"></i>{{ __('Record Attendance') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2 text-info"></i>{{ __('Help & Tips') }}</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">{{ __('What happens when you record attendance?') }}</h6>
                    <p class="small mb-3">{{ __('An attendance record is created showing the player was scheduled for the session. The player can then check in and out as needed.') }}</p>

                    <h6 class="mb-2">{{ __('Session Types') }}</h6>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-info me-2">{{ __('Training') }}</span>
                            <small>{{ __('Regular training sessions') }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success me-2">{{ __('Match') }}</span>
                            <small>{{ __('Official matches and games') }}</small>
                        </div>
                    </div>

                    <div class="alert alert-light">
                        <h6 class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i>{{ __('Pro Tip') }}</h6>
                        <p class="small mb-0">{{ __('You can bulk record attendance by creating multiple records, or use the check-in/out features on the attendance list for quicker updates.') }}</p>
                    </div>

                    @if(!$players->isEmpty())
                        <div class="mt-3 p-3 bg-success bg-opacity-10 rounded">
                            <h6 class="mb-2"><i class="fas fa-check-circle me-2 text-success"></i>{{ __('Ready to Record') }}</h6>
                            <p class="small mb-0">{{ __('You have') }} {{ $players->count() }} {{ __('active players available for attendance recording.') }}</p>
                        </div>
                    @else
                        <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded">
                            <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>{{ __('No Players Available') }}</h6>
                            <p class="small mb-0">{{ __('Add some players first before recording attendance.') }}</p>
                            <a href="{{ route('admin.players.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>{{ __('Add Player') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
