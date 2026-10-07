@extends('layouts.staff')

@section('title', 'Edit Player - Partner Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Edit Player</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('partner.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('partner.players') }}">Players</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Player Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('partner.player.update', $player->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $player->first_name ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $player->last_name ?? '') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $player->date_of_birth ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender *</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="male" {{ old('gender', $player->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $player->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position *</label>
                                <select class="form-select" id="position" name="position" required>
                                    <option value="Goalkeeper" {{ old('position', $player->position ?? '') == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                    <option value="Defender" {{ old('position', $player->position ?? '') == 'Defender' ? 'selected' : '' }}>Defender</option>
                                    <option value="Midfielder" {{ old('position', $player->position ?? '') == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                    <option value="Forward" {{ old('position', $player->position ?? '') == 'Forward' ? 'selected' : '' }}>Forward</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="program_id" class="form-label">Program *</label>
                                <select class="form-select" id="program_id" name="program_id" required>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ old('program_id', $player->program_id ?? '') == $program->id ? 'selected' : '' }}>{{ $program->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $player->email ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $player->phone ?? '') }}">
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Guardian Information</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="guardian_name" class="form-label">Guardian Name</label>
                                <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $player->guardian_name ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guardian_phone" class="form-label">Guardian Phone</label>
                                <input type="text" class="form-control" id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone', $player->guardian_phone ?? '') }}">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Player
                            </button>
                            <a href="{{ route('partner.players') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
