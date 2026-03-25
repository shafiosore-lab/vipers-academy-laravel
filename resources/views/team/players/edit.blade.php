@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'Edit Player')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Player - {{ $squad->player->full_name }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('team.players.update', [$tournament->id, $squad->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Player Identification -->
                        <h6 class="border-bottom pb-2 mb-3">Identification</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_type" class="form-label">ID Type</label>
                                <select class="form-select @error('id_type') is-invalid @enderror"
                                        id="id_type" name="id_type">
                                    <option value="national_id" {{ $squad->player->id_type == 'national_id' ? 'selected' : '' }}>National ID</option>
                                    <option value="passport" {{ $squad->player->id_type == 'passport' ? 'selected' : '' }}>Passport</option>
                                    <option value="birth_certificate" {{ $squad->player->id_type == 'birth_certificate' ? 'selected' : '' }}>Birth Certificate</option>
                                    <option value="other" {{ $squad->player->id_type == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('id_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="id_number" class="form-label">ID Number</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                       id="id_number" name="id_number"
                                       value="{{ old('id_number', $squad->player->id_number) }}">
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name', $squad->player->first_name) }}">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                       id="middle_name" name="middle_name"
                                       value="{{ old('middle_name', $squad->player->middle_name) }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name', $squad->player->last_name) }}">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                       id="date_of_birth" name="date_of_birth"
                                       value="{{ old('date_of_birth', $squad->player->date_of_birth ? $squad->player->date_of_birth->format('Y-m-d') : '') }}"
                                       max="{{ date('Y-m-d') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select @error('gender') is-invalid @enderror"
                                        id="gender" name="gender">
                                    <option value="male" {{ $squad->player->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $squad->player->gender == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">City/Location</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city"
                                   value="{{ old('city', $squad->player->city) }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Player Details -->
                        <h6 class="border-bottom pb-2 mb-3">Player Details</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select @error('position') is-invalid @enderror"
                                        id="position" name="position">
                                    <option value="">Select Position</option>
                                    <option value="Goalkeeper" {{ $squad->position == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                    <option value="Defender" {{ $squad->position == 'Defender' ? 'selected' : '' }}>Defender</option>
                                    <option value="Midfielder" {{ $squad->position == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                    <option value="Striker" {{ $squad->position == 'Striker' ? 'selected' : '' }}>Striker</option>
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jersey_number" class="form-label">Jersey Number</label>
                                <input type="number" class="form-control @error('jersey_number') is-invalid @enderror"
                                       id="jersey_number" name="jersey_number"
                                       value="{{ old('jersey_number', $squad->jersey_number) }}" min="1" max="99">
                                @error('jersey_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Passport Photo -->
                        <h6 class="border-bottom pb-2 mb-3">Identity Verification</h6>

                        <div class="mb-3">
                            <label for="passport_photo" class="form-label">Passport Photograph</label>
                            @if($squad->player->passport_photo_path)
                                <div class="mb-2">
                                    <img src="{{ $squad->player->passport_photo_url }}" alt="Current photo" class="img-thumbnail" style="max-height: 150px;">
                                    <small class="d-block text-muted">Current photo on file</small>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('passport_photo') is-invalid @enderror"
                                   id="passport_photo" name="passport_photo"
                                   accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Upload a new photo to replace the current one (JPEG/PNG, max 2MB)</small>
                            @error('passport_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('team.players', $tournament->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Player
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
