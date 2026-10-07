@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'Add Player')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Register New Player</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('team.players.store', $tournament->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Player Identification -->
                        <h6 class="border-bottom pb-2 mb-3">Identification</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_type" class="form-label">ID Type *</label>
                                <select class="form-select @error('id_type') is-invalid @enderror"
                                        id="id_type" name="id_type" required>
                                    <option value="">Select ID Type</option>
                                    <option value="national_id" {{ old('id_type') == 'national_id' ? 'selected' : '' }}>National ID</option>
                                    <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                                    <option value="birth_certificate" {{ old('id_type') == 'birth_certificate' ? 'selected' : '' }}>Birth Certificate</option>
                                    <option value="other" {{ old('id_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('id_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="id_number" class="form-label">ID Number *</label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                       id="id_number" name="id_number"
                                       value="{{ old('id_number') }}" required>
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                       id="middle_name" name="middle_name"
                                       value="{{ old('middle_name') }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                       id="date_of_birth" name="date_of_birth"
                                       value="{{ old('date_of_birth') }}"
                                       max="{{ date('Y-m-d') }}" required>
                                <small class="text-muted">Used to calculate exact age</small>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender *</label>
                                <select class="form-select @error('gender') is-invalid @enderror"
                                        id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="city" class="form-label">City/Location *</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city"
                                   value="{{ old('city') }}" required>
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
                                    <option value="Goalkeeper" {{ old('position') == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                    <option value="Defender" {{ old('position') == 'Defender' ? 'selected' : '' }}>Defender</option>
                                    <option value="Midfielder" {{ old('position') == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                    <option value="Striker" {{ old('position') == 'Striker' ? 'selected' : '' }}>Striker</option>
                                </select>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jersey_number" class="form-label">Jersey Number</label>
                                <input type="number" class="form-control @error('jersey_number') is-invalid @enderror"
                                       id="jersey_number" name="jersey_number"
                                       value="{{ old('jersey_number') }}" min="1" max="99">
                                @error('jersey_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Passport Photo -->
                        <h6 class="border-bottom pb-2 mb-3">Identity Verification</h6>

                        <div class="mb-3">
                            <label for="passport_photo" class="form-label">Passport Photograph *</label>
                            <input type="file" class="form-control @error('passport_photo') is-invalid @enderror"
                                   id="passport_photo" name="passport_photo"
                                   accept="image/jpeg,image/png,image/jpg" required>
                            <small class="text-muted">Upload a clear passport-sized photo (JPEG/PNG, max 2MB, min 200x200px)</small>
                            @error('passport_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('team.players', $tournament->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Register Player
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
