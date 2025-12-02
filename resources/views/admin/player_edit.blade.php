@extends('layouts.admin')

@section('title', 'Edit Player - FIFA Compliant Registration - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-edit fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Edit Player - FIFA Compliant Registration</h4>
                            <small class="opacity-75">Update player information: {{ $player->name }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Progress Indicator -->
                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" id="registrationProgress"></div>
                    </div>

                    <form action="{{ route('admin.players.update', $player) }}" method="POST" enctype="multipart/form-data" id="playerForm">
                        @csrf
                        @method('PUT')

                        <!-- Bootstrap Tabs for Organization -->
                        <ul class="nav nav-tabs" id="playerTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                                    <i class="fas fa-user me-1"></i>Personal Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                                    <i class="fas fa-address-book me-1"></i>Contact Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="guardian-tab" data-bs-toggle="tab" data-bs-target="#guardian" type="button" role="tab">
                                    <i class="fas fa-users-cog me-1"></i>Guardian Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab">
                                    <i class="fas fa-heartbeat me-1"></i>Medical Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="football-tab" data-bs-toggle="tab" data-bs-target="#football" type="button" role="tab">
                                    <i class="fas fa-futbol me-1"></i>Football Registration
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="academy-tab" data-bs-toggle="tab" data-bs-target="#academy" type="button" role="tab">
                                    <i class="fas fa-graduation-cap me-1"></i>Academy Compliance
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                                    <i class="fas fa-file-alt me-1"></i>Documents
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-4" id="playerTabsContent">
                            <!-- Personal Information Tab -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                <div class="card border-primary">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Personal Information (FIFA Required)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="first_name" class="form-label">First Name *</label>
                                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                                           id="first_name" name="first_name" value="{{ old('first_name', $player->first_name) }}" required>
                                                    @error('first_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="last_name" class="form-label">Last Name *</label>
                                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                                           id="last_name" name="last_name" value="{{ old('last_name', $player->last_name) }}" required>
                                                    @error('last_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $player->date_of_birth) }}" required>
                                                    @error('date_of_birth')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="place_of_birth" class="form-label">Place of Birth *</label>
                                                    <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror"
                                                           id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth', $player->place_of_birth) }}" required>
                                                    @error('place_of_birth')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="nationality" class="form-label">Nationality *</label>
                                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                                           id="nationality" name="nationality" value="{{ old('nationality', $player->nationality) }}" required>
                                                    @error('nationality')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="gender" class="form-label">Gender *</label>
                                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                                        <option value="">Select Gender</option>
                                                        <option value="Male" {{ old('gender', $player->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ old('gender', $player->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                    @error('gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="position" class="form-label">Playing Position *</label>
                                                    <select class="form-select @error('position') is-invalid @enderror" id="position" name="position" required>
                                                        <option value="">Select Position</option>
                                                        <option value="Goalkeeper" {{ old('position', $player->position) == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                                        <option value="Defender" {{ old('position', $player->position) == 'Defender' ? 'selected' : '' }}>Defender</option>
                                                        <option value="Midfielder" {{ old('position', $player->position) == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                                        <option value="Forward" {{ old('position', $player->position) == 'Forward' ? 'selected' : '' }}>Forward</option>
                                                        <option value="Winger" {{ old('position', $player->position) == 'Winger' ? 'selected' : '' }}>Winger</option>
                                                        <option value="Striker" {{ old('position', $player->position) == 'Striker' ? 'selected' : '' }}>Striker</option>
                                                    </select>
                                                    @error('position')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Tab -->
                            <div class="tab-pane fade" id="contact" role="tabpanel">
                                <div class="card border-success">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-address-book me-2"></i>Contact Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Residential Address *</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                      id="address" name="address" rows="3" required>{{ old('address', $player->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="city" class="form-label">City *</label>
                                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                                           id="city" name="city" value="{{ old('city', $player->city) }}" required>
                                                    @error('city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="country" class="form-label">Country *</label>
                                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                                           id="country" name="country" value="{{ old('country', $player->country) }}" required>
                                                    @error('country')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="postal_code" class="form-label">Postal Code</label>
                                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                                           id="postal_code" name="postal_code" value="{{ old('postal_code', $player->postal_code) }}">
                                                    @error('postal_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone Number *</label>
                                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                           id="phone" name="phone" value="{{ old('phone', $player->phone) }}" required>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email Address *</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                           id="email" name="email" value="{{ old('email', $player->email) }}" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="text-primary mb-3"><i class="fas fa-exclamation-triangle me-1"></i>Emergency Contact (FIFA Required)</h6>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name *</label>
                                                    <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                                           id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $player->emergency_contact_name) }}" required>
                                                    @error('emergency_contact_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone *</label>
                                                    <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                                           id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $player->emergency_contact_phone) }}" required>
                                                    @error('emergency_contact_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="emergency_contact_relationship" class="form-label">Relationship *</label>
                                                    <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror"
                                                           id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $player->emergency_contact_relationship) }}" required>
                                                    @error('emergency_contact_relationship')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Guardian Information Tab -->
                            <div class="tab-pane fade" id="guardian" role="tabpanel">
                                <div class="card border-info">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Guardian/Parent Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">Father's Information</h6>
                                                <div class="mb-3">
                                                    <label for="father_name" class="form-label">Father's Name</label>
                                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                                           id="father_name" name="father_name" value="{{ old('father_name', $player->father_name) }}">
                                                    @error('father_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="father_id_number" class="form-label">Father's ID Number</label>
                                                    <input type="text" class="form-control @error('father_id_number') is-invalid @enderror"
                                                           id="father_id_number" name="father_id_number" value="{{ old('father_id_number', $player->father_id_number) }}">
                                                    @error('father_id_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="father_phone" class="form-label">Father's Phone</label>
                                                    <input type="tel" class="form-control @error('father_phone') is-invalid @enderror"
                                                           id="father_phone" name="father_phone" value="{{ old('father_phone', $player->father_phone) }}">
                                                    @error('father_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="father_occupation" class="form-label">Father's Occupation</label>
                                                    <input type="text" class="form-control @error('father_occupation') is-invalid @enderror"
                                                           id="father_occupation" name="father_occupation" value="{{ old('father_occupation', $player->father_occupation) }}">
                                                    @error('father_occupation')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-primary mb-3">Mother's Information</h6>
                                                <div class="mb-3">
                                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                                           id="mother_name" name="mother_name" value="{{ old('mother_name', $player->mother_name) }}">
                                                    @error('mother_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="mother_id_number" class="form-label">Mother's ID Number</label>
                                                    <input type="text" class="form-control @error('mother_id_number') is-invalid @enderror"
                                                           id="mother_id_number" name="mother_id_number" value="{{ old('mother_id_number', $player->mother_id_number) }}">
                                                    @error('mother_id_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="mother_phone" class="form-label">Mother's Phone</label>
                                                    <input type="tel" class="form-control @error('mother_phone') is-invalid @enderror"
                                                           id="mother_phone" name="mother_phone" value="{{ old('mother_phone', $player->mother_phone) }}">
                                                    @error('mother_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="mother_occupation" class="form-label">Mother's Occupation</label>
                                                    <input type="text" class="form-control @error('mother_occupation') is-invalid @enderror"
                                                           id="mother_occupation" name="mother_occupation" value="{{ old('mother_occupation', $player->mother_occupation) }}">
                                                    @error('mother_occupation')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="text-info mb-3"><i class="fas fa-user-shield me-1"></i>Legal Guardian (if different from parents)</h6>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="guardian_name" class="form-label">Guardian Name</label>
                                                    <input type="text" class="form-control @error('guardian_name') is-invalid @enderror"
                                                           id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $player->guardian_name) }}">
                                                    @error('guardian_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="guardian_phone" class="form-label">Guardian Phone</label>
                                                    <input type="tel" class="form-control @error('guardian_phone') is-invalid @enderror"
                                                           id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone', $player->guardian_phone) }}">
                                                    @error('guardian_phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="guardian_relationship" class="form-label">Relationship to Player</label>
                                                    <input type="text" class="form-control @error('guardian_relationship') is-invalid @enderror"
                                                           id="guardian_relationship" name="guardian_relationship" value="{{ old('guardian_relationship', $player->guardian_relationship) }}">
                                                    @error('guardian_relationship')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical Information Tab -->
                            <div class="tab-pane fade" id="medical" role="tabpanel">
                                <div class="card border-danger">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Medical Information (FIFA Required)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>FIFA Compliance:</strong> Medical information is mandatory for player registration and insurance purposes.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="blood_type" class="form-label">Blood Type</label>
                                                    <select class="form-select @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type">
                                                        <option value="">Select Blood Type</option>
                                                        <option value="A+" {{ old('blood_type', $player->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                                        <option value="A-" {{ old('blood_type', $player->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                                        <option value="B+" {{ old('blood_type', $player->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                                        <option value="B-" {{ old('blood_type', $player->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                                        <option value="AB+" {{ old('blood_type', $player->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                        <option value="AB-" {{ old('blood_type', $player->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                        <option value="O+" {{ old('blood_type', $player->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                                        <option value="O-" {{ old('blood_type', $player->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                                                    </select>
                                                    @error('blood_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="last_medical_checkup" class="form-label">Last Medical Checkup</label>
                                                    <input type="date" class="form-control @error('last_medical_checkup') is-invalid @enderror"
                                                           id="last_medical_checkup" name="last_medical_checkup" value="{{ old('last_medical_checkup', $player->last_medical_checkup) }}">
                                                    @error('last_medical_checkup')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="medical_conditions" class="form-label">Medical Conditions</label>
                                            <textarea class="form-control @error('medical_conditions') is-invalid @enderror"
                                                      id="medical_conditions" name="medical_conditions" rows="3"
                                                      placeholder="List any medical conditions, injuries, or health issues">{{ old('medical_conditions', $player->medical_conditions) }}</textarea>
                                            @error('medical_conditions')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="allergies" class="form-label">Allergies</label>
                                            <textarea class="form-control @error('allergies') is-invalid @enderror"
                                                      id="allergies" name="allergies" rows="2"
                                                      placeholder="List any allergies (food, medication, environmental)">{{ old('allergies', $player->allergies) }}</textarea>
                                            @error('allergies')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="medications" class="form-label">Current Medications</label>
                                            <textarea class="form-control @error('medications') is-invalid @enderror"
                                                      id="medications" name="medications" rows="2"
                                                      placeholder="List any medications currently being taken">{{ old('medications', $player->medications) }}</textarea>
                                            @error('medications')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <hr>
                                        <h6 class="text-danger mb-3"><i class="fas fa-shield-alt me-1"></i>Medical Insurance Information</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="medical_insurance_provider" class="form-label">Insurance Provider</label>
                                                    <input type="text" class="form-control @error('medical_insurance_provider') is-invalid @enderror"
                                                           id="medical_insurance_provider" name="medical_insurance_provider" value="{{ old('medical_insurance_provider', $player->medical_insurance_provider) }}">
                                                    @error('medical_insurance_provider')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="medical_insurance_number" class="form-label">Insurance Policy Number</label>
                                                    <input type="text" class="form-control @error('medical_insurance_number') is-invalid @enderror"
                                                           id="medical_insurance_number" name="medical_insurance_number" value="{{ old('medical_insurance_number', $player->medical_insurance_number) }}">
                                                    @error('medical_insurance_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Football Registration Tab -->
                            <div class="tab-pane fade" id="football" role="tabpanel">
                                <div class="card border-warning">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-futbol me-2"></i>Football Registration & Physical Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="height_cm" class="form-label">Height (cm)</label>
                                                    <input type="number" class="form-control @error('height_cm') is-invalid @enderror"
                                                           id="height_cm" name="height_cm" value="{{ old('height_cm', $player->height_cm) }}" min="50" max="250" step="0.1">
                                                    @error('height_cm')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="weight_kg" class="form-label">Weight (kg)</label>
                                                    <input type="number" class="form-control @error('weight_kg') is-invalid @enderror"
                                                           id="weight_kg" name="weight_kg" value="{{ old('weight_kg', $player->weight_kg) }}" min="20" max="200" step="0.1">
                                                    @error('weight_kg')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="dominant_foot" class="form-label">Dominant Foot</label>
                                                    <select class="form-select @error('dominant_foot') is-invalid @enderror" id="dominant_foot" name="dominant_foot">
                                                        <option value="">Select Foot</option>
                                                        <option value="Left" {{ old('dominant_foot', $player->dominant_foot) == 'Left' ? 'selected' : '' }}>Left</option>
                                                        <option value="Right" {{ old('dominant_foot', $player->dominant_foot) == 'Right' ? 'selected' : '' }}>Right</option>
                                                        <option value="Both" {{ old('dominant_foot', $player->dominant_foot) == 'Both' ? 'selected' : '' }}>Both</option>
                                                    </select>
                                                    @error('dominant_foot')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="text-warning mb-3"><i class="fas fa-id-badge me-1"></i>FIFA Registration Information</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="fifa_registration_number" class="form-label">FIFA Registration Number</label>
                                                    <input type="text" class="form-control @error('fifa_registration_number') is-invalid @enderror"
                                                           id="fifa_registration_number" name="fifa_registration_number" value="{{ old('fifa_registration_number', $player->fifa_registration_number) }}">
                                                    @error('fifa_registration_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="license_type" class="form-label">License Type</label>
                                                    <select class="form-select @error('license_type') is-invalid @enderror" id="license_type" name="license_type">
                                                        <option value="">Select License Type</option>
                                                        <option value="Amateur" {{ old('license_type', $player->license_type) == 'Amateur' ? 'selected' : '' }}>Amateur</option>
                                                        <option value="Semi-Professional" {{ old('license_type', $player->license_type) == 'Semi-Professional' ? 'selected' : '' }}>Semi-Professional</option>
                                                        <option value="Professional" {{ old('license_type', $player->license_type) == 'Professional' ? 'selected' : '' }}>Professional</option>
                                                        <option value="Youth" {{ old('license_type', $player->license_type) == 'Youth' ? 'selected' : '' }}>Youth</option>
                                                    </select>
                                                    @error('license_type')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="registration_date" class="form-label">Registration Date</label>
                                                    <input type="date" class="form-control @error('registration_date') is-invalid @enderror"
                                                           id="registration_date" name="registration_date" value="{{ old('registration_date', $player->registration_date) }}">
                                                    @error('registration_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="transfer_status" class="form-label">Transfer Status</label>
                                                    <select class="form-select @error('transfer_status') is-invalid @enderror" id="transfer_status" name="transfer_status">
                                                        <option value="">Select Status</option>
                                                        <option value="Free Agent" {{ old('transfer_status', $player->transfer_status) == 'Free Agent' ? 'selected' : '' }}>Free Agent</option>
                                                        <option value="Under Contract" {{ old('transfer_status', $player->transfer_status) == 'Under Contract' ? 'selected' : '' }}>Under Contract</option>
                                                        <option value="Transfer Listed" {{ old('transfer_status', $player->transfer_status) == 'Transfer Listed' ? 'selected' : '' }}>Transfer Listed</option>
                                                        <option value="Loan" {{ old('transfer_status', $player->transfer_status) == 'Loan' ? 'selected' : '' }}>Loan</option>
                                                    </select>
                                                    @error('transfer_status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="previous_clubs" class="form-label">Previous Clubs</label>
                                            <textarea class="form-control @error('previous_clubs') is-invalid @enderror"
                                                      id="previous_clubs" name="previous_clubs" rows="2"
                                                      placeholder="List previous clubs and years played">{{ old('previous_clubs', $player->previous_clubs) }}</textarea>
                                            @error('previous_clubs')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="contract_status" class="form-label">Contract Status</label>
                                            <select class="form-select @error('contract_status') is-invalid @enderror" id="contract_status" name="contract_status">
                                                <option value="">Select Contract Status</option>
                                                <option value="Academy Contract" {{ old('contract_status', $player->contract_status) == 'Academy Contract' ? 'selected' : '' }}>Academy Contract</option>
                                                <option value="Youth Contract" {{ old('contract_status', $player->contract_status) == 'Youth Contract' ? 'selected' : '' }}>Youth Contract</option>
                                                <option value="Professional Contract" {{ old('contract_status', $player->contract_status) == 'Professional Contract' ? 'selected' : '' }}>Professional Contract</option>
                                                <option value="Trial Period" {{ old('contract_status', $player->contract_status) == 'Trial Period' ? 'selected' : '' }}>Trial Period</option>
                                            </select>
                                            @error('contract_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <hr>
                                        <h6 class="text-info mb-3"><i class="fas fa-graduation-cap me-1"></i>Academic Information</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="school_name" class="form-label">School Name</label>
                                                    <input type="text" class="form-control @error('school_name') is-invalid @enderror"
                                                           id="school_name" name="school_name" value="{{ old('school_name', $player->school_name) }}">
                                                    @error('school_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="school_grade" class="form-label">Current Grade/Year</label>
                                                    <input type="text" class="form-control @error('school_grade') is-invalid @enderror"
                                                           id="school_grade" name="school_grade" value="{{ old('school_grade', $player->school_grade) }}" placeholder="e.g., Grade 8, Year 10">
                                                    @error('school_grade')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="academic_performance" class="form-label">Academic Performance</label>
                                            <select class="form-select @error('academic_performance') is-invalid @enderror" id="academic_performance" name="academic_performance">
                                                <option value="">Select Performance Level</option>
                                                <option value="Excellent" {{ old('academic_performance', $player->academic_performance) == 'Excellent' ? 'selected' : '' }}>Excellent (A/A+)</option>
                                                <option value="Good" {{ old('academic_performance', $player->academic_performance) == 'Good' ? 'selected' : '' }}>Good (B/B+)</option>
                                                <option value="Average" {{ old('academic_performance', $player->academic_performance) == 'Average' ? 'selected' : '' }}>Average (C/C+)</option>
                                                <option value="Needs Improvement" {{ old('academic_performance', $player->academic_performance) == 'Needs Improvement' ? 'selected' : '' }}>Needs Improvement (D/F)</option>
                                            </select>
                                            @error('academic_performance')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Academy Compliance Tab -->
                            <div class="tab-pane fade" id="academy" role="tabpanel">
                                <div class="card border-info">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Academy Compliance & Additional Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-primary">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Academy Standards:</strong> Additional information required for complete academy compliance and player development tracking.
                                        </div>

                                        <h6 class="text-info mb-3"><i class="fas fa-home me-1"></i>Accommodation Information</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="accommodation_provided" class="form-label">Accommodation Provided by Academy *</label>
                                                    <select class="form-select @error('accommodation_provided') is-invalid @enderror" id="accommodation_provided" name="accommodation_provided" required>
                                                        <option value="">Select Option</option>
                                                        <option value="1" {{ old('accommodation_provided', $player->accommodation_provided) == '1' ? 'selected' : '' }}>Yes</option>
                                                        <option value="0" {{ old('accommodation_provided', $player->accommodation_provided) == '0' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                    @error('accommodation_provided')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="accommodation_details" class="form-label">Accommodation Details</label>
                                                    <textarea class="form-control @error('accommodation_details') is-invalid @enderror"
                                                              id="accommodation_details" name="accommodation_details" rows="2"
                                                              placeholder="Describe accommodation arrangements if provided">{{ old('accommodation_details', $player->accommodation_details) }}</textarea>
                                                    @error('accommodation_details')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="text-success mb-3"><i class="fas fa-users me-1"></i>Training & Competition Information</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="age_group" class="form-label">Age Group</label>
                                                    <select class="form-select @error('age_group') is-invalid @enderror" id="age_group" name="age_group">
                                                        <option value="">Select Age Group</option>
                                                        <option value="U-8" {{ old('age_group', $player->age_group) == 'U-8' ? 'selected' : '' }}>U-8 (Under 8)</option>
                                                        <option value="U-9" {{ old('age_group', $player->age_group) == 'U-9' ? 'selected' : '' }}>U-9 (Under 9)</option>
                                                        <option value="U-10" {{ old('age_group', $player->age_group) == 'U-10' ? 'selected' : '' }}>U-10 (Under 10)</option>
                                                        <option value="U-11" {{ old('age_group', $player->age_group) == 'U-11' ? 'selected' : '' }}>U-11 (Under 11)</option>
                                                        <option value="U-12" {{ old('age_group', $player->age_group) == 'U-12' ? 'selected' : '' }}>U-12 (Under 12)</option>
                                                        <option value="U-13" {{ old('age_group', $player->age_group) == 'U-13' ? 'selected' : '' }}>U-13 (Under 13)</option>
                                                        <option value="U-14" {{ old('age_group', $player->age_group) == 'U-14' ? 'selected' : '' }}>U-14 (Under 14)</option>
                                                        <option value="U-15" {{ old('age_group', $player->age_group) == 'U-15' ? 'selected' : '' }}>U-15 (Under 15)</option>
                                                        <option value="U-16" {{ old('age_group', $player->age_group) == 'U-16' ? 'selected' : '' }}>U-16 (Under 16)</option>
                                                        <option value="U-17" {{ old('age_group', $player->age_group) == 'U-17' ? 'selected' : '' }}>U-17 (Under 17)</option>
                                                        <option value="U-18" {{ old('age_group', $player->age_group) == 'U-18' ? 'selected' : '' }}>U-18 (Under 18)</option>
                                                        <option value="Senior" {{ old('age_group', $player->age_group) == 'Senior' ? 'selected' : '' }}>Senior</option>
                                                    </select>
                                                    @error('age_group')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="training_schedule" class="form-label">Training Schedule</label>
                                                    <textarea class="form-control @error('training_schedule') is-invalid @enderror"
                                                              id="training_schedule" name="training_schedule" rows="3"
                                                              placeholder="e.g., Monday & Wednesday: 4-6 PM, Saturday: 9-11 AM">{{ old('training_schedule', $player->training_schedule) }}</textarea>
                                                    @error('training_schedule')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="competition_plan" class="form-label">Competition Plan</label>
                                            <textarea class="form-control @error('competition_plan') is-invalid @enderror"
                                                      id="competition_plan" name="competition_plan" rows="3"
                                                      placeholder="Describe planned competitions, tournaments, and match schedule">{{ old('competition_plan', $player->competition_plan) }}</textarea>
                                            @error('competition_plan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <hr>
                                        <h6 class="text-warning mb-3"><i class="fas fa-file-contract me-1"></i>Legal Documents & Consent</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="guardian_consent_form" class="form-label">Guardian Consent Form</label>
                                                    <input type="file" class="form-control @error('guardian_consent_form') is-invalid @enderror"
                                                           id="guardian_consent_form" name="guardian_consent_form" accept=".pdf,image/*">
                                                    @error('guardian_consent_form')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Signed parental/guardian consent form</div>
                                                    @if($player->guardian_consent_form)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current guardian consent form uploaded</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="participation_agreement" class="form-label">Participation Agreement</label>
                                                    <input type="file" class="form-control @error('participation_agreement') is-invalid @enderror"
                                                           id="participation_agreement" name="participation_agreement" accept=".pdf,image/*">
                                                    @error('participation_agreement')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Player participation and code of conduct agreement</div>
                                                    @if($player->participation_agreement)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current participation agreement uploaded</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="data_consent_form" class="form-label">Data Consent Form</label>
                                                    <input type="file" class="form-control @error('data_consent_form') is-invalid @enderror"
                                                           id="data_consent_form" name="data_consent_form" accept=".pdf,image/*">
                                                    @error('data_consent_form')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">GDPR/data protection consent form</div>
                                                    @if($player->data_consent_form)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current data consent form uploaded</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="form-check mt-4">
                                                        <input class="form-check-input @error('safeguarding_policy_acknowledged') is-invalid @enderror"
                                                               type="checkbox" id="safeguarding_policy_acknowledged" name="safeguarding_policy_acknowledged" value="1" {{ old('safeguarding_policy_acknowledged', $player->safeguarding_policy_acknowledged) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="safeguarding_policy_acknowledged">
                                                            Safeguarding Policy Acknowledged *
                                                        </label>
                                                        @error('safeguarding_policy_acknowledged')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <small class="form-text text-muted">Player/guardian acknowledges understanding of academy safeguarding policies</small>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="text-secondary mb-3"><i class="fas fa-id-card me-1"></i>Additional Identity Documents</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="guardian_id_document" class="form-label">Guardian ID Document</label>
                                                    <input type="file" class="form-control @error('guardian_id_document') is-invalid @enderror"
                                                           id="guardian_id_document" name="guardian_id_document" accept=".pdf,image/*">
                                                    @error('guardian_id_document')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Parent/guardian identification document</div>
                                                    @if($player->guardian_id_document)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current guardian ID uploaded</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="player_id_document" class="form-label">Player ID Document</label>
                                                    <input type="file" class="form-control @error('player_id_document') is-invalid @enderror"
                                                           id="player_id_document" name="player_id_document" accept=".pdf,image/*">
                                                    @error('player_id_document')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Player identification document (if applicable)</div>
                                                    @if($player->player_id_document)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current player ID uploaded</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <h6 class="text-info mb-3"><i class="fas fa-map-marker-alt me-1"></i>Previous Domicile (for relocated players)</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="previous_domicile" class="form-label">Previous Domicile</label>
                                                    <input type="text" class="form-control @error('previous_domicile') is-invalid @enderror"
                                                           id="previous_domicile" name="previous_domicile" value="{{ old('previous_domicile', $player->previous_domicile) }}"
                                                           placeholder="Previous city/country of residence">
                                                    @error('previous_domicile')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="relocation_reason" class="form-label">Reason for Relocation</label>
                                                    <textarea class="form-control @error('relocation_reason') is-invalid @enderror"
                                                              id="relocation_reason" name="relocation_reason" rows="2"
                                                              placeholder="Reason for moving to current location">{{ old('relocation_reason', $player->relocation_reason) }}</textarea>
                                                    @error('relocation_reason')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-success mt-4">
                                            <h6 class="alert-heading mb-2"><i class="fas fa-check-double me-2"></i>Complete Academy Compliance</h6>
                                            <p class="mb-0 small">This section ensures your academy meets all professional standards for player registration, safeguarding, and legal compliance.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Tab -->
                            <div class="tab-pane fade" id="documents" role="tabpanel">
                                <div class="card border-secondary">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Documents & Final Registration</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Document Requirements:</strong> Upload required documents for FIFA compliance. All documents should be in PDF or image format.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="photo" class="form-label">Player Photo *</label>
                                                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                                           id="photo" name="photo" accept="image/*" required>
                                                    @error('photo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Passport-style photo, max 2MB</div>
                                                    @if($player->photo)
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $player->photo) }}" alt="Current Photo" class="img-thumbnail" style="max-width: 100px;">
                                                            <small class="text-muted d-block">Current photo</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="passport_photo" class="form-label">Passport Photo</label>
                                                    <input type="file" class="form-control @error('passport_photo') is-invalid @enderror"
                                                           id="passport_photo" name="passport_photo" accept="image/*">
                                                    @error('passport_photo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Scanned passport photo page</div>
                                                    @if($player->passport_photo)
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $player->passport_photo) }}" alt="Current Passport" class="img-thumbnail" style="max-width: 100px;">
                                                            <small class="text-muted d-block">Current passport photo</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="birth_certificate" class="form-label">Birth Certificate *</label>
                                                    <input type="file" class="form-control @error('birth_certificate') is-invalid @enderror"
                                                           id="birth_certificate" name="birth_certificate" accept=".pdf,image/*" required>
                                                    @error('birth_certificate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Official birth certificate, max 5MB</div>
                                                    @if($player->birth_certificate)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current birth certificate uploaded</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="medical_certificate" class="form-label">Medical Certificate *</label>
                                                    <input type="file" class="form-control @error('medical_certificate') is-invalid @enderror"
                                                           id="medical_certificate" name="medical_certificate" accept=".pdf,image/*" required>
                                                    @error('medical_certificate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">Doctor's medical clearance certificate</div>
                                                    @if($player->medical_certificate)
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current medical certificate uploaded</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="school_certificate" class="form-label">School Certificate</label>
                                            <input type="file" class="form-control @error('school_certificate') is-invalid @enderror"
                                                   id="school_certificate" name="school_certificate" accept=".pdf,image/*">
                                            @error('school_certificate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Academic records or school certificate</div>
                                            @if($player->school_certificate)
                                                <div class="mt-2">
                                                    <small class="text-muted">Current school certificate uploaded</small>
                                                </div>
                                            @endif
                                        </div>

                                        <hr>
                                        <h6 class="text-success mb-3"><i class="fas fa-check-circle me-1"></i>Registration Status & Contract</h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="registration_status" class="form-label">Registration Status *</label>
                                                    <select class="form-select @error('registration_status') is-invalid @enderror" id="registration_status" name="registration_status" required>
                                                        <option value="Pending" {{ old('registration_status', $player->registration_status) == 'Pending' ? 'selected' : '' }}>Pending Review</option>
                                                        <option value="Approved" {{ old('registration_status', $player->registration_status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="Rejected" {{ old('registration_status', $player->registration_status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                        <option value="Active" {{ old('registration_status', $player->registration_status) == 'Active' ? 'selected' : '' }}>Active</option>
                                                        <option value="Inactive" {{ old('registration_status', $player->registration_status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                    @error('registration_status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="contract_start_date" class="form-label">Contract Start Date</label>
                                                    <input type="date" class="form-control @error('contract_start_date') is-invalid @enderror"
                                                           id="contract_start_date" name="contract_start_date" value="{{ old('contract_start_date', $player->contract_start_date) }}">
                                                    @error('contract_start_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="contract_end_date" class="form-label">Contract End Date</label>
                                                    <input type="date" class="form-control @error('contract_end_date') is-invalid @enderror"
                                                           id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date', $player->contract_end_date) }}">
                                                    @error('contract_end_date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="admin_notes" class="form-label">Admin Notes</label>
                                                    <textarea class="form-control @error('admin_notes') is-invalid @enderror"
                                                              id="admin_notes" name="admin_notes" rows="2"
                                                              placeholder="Internal notes for administrators">{{ old('admin_notes', $player->admin_notes) }}</textarea>
                                                    @error('admin_notes')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-success mt-4">
                                            <h6 class="alert-heading mb-2"><i class="fas fa-shield-alt me-2"></i>FIFA Compliance Achieved</h6>
                                            <p class="mb-0 small">This registration form includes all FIFA-required fields for academy player registration, ensuring compliance with international football standards.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" id="confirmAccuracy" required>
                                    <label class="form-check-label small" for="confirmAccuracy">
                                        I confirm that all information provided is accurate and complete
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-1"></i>Reset Changes
                                </button>
                                <a href="{{ route('admin.players.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Player
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Progress bar functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('#playerTabs .nav-link');
    const progressBar = document.getElementById('registrationProgress');

    function updateProgress() {
        const activeTab = document.querySelector('#playerTabs .nav-link.active');
        const tabIndex = Array.from(tabs).indexOf(activeTab);
        const progress = ((tabIndex + 1) / tabs.length) * 100;
        progressBar.style.width = progress + '%';
    }

    tabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', updateProgress);
    });

    // Auto-calculate age from date of birth
    document.getElementById('date_of_birth').addEventListener('change', function() {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const monthDiff = today.getMonth() - dob.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
            age--;
        }

        // Age will be calculated automatically in the controller
        console.log('Age calculated:', age);
    });

    // Form validation enhancement
    const form = document.getElementById('playerForm');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields marked with *');
        }
    });
});

function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will reload the original player data.')) {
        window.location.reload();
    }
}

// Real-time validation feedback
document.addEventListener('input', function(e) {
    if (e.target.hasAttribute('required') && e.target.value.trim()) {
        e.target.classList.remove('is-invalid');
        e.target.classList.add('is-valid');
    }
});
</script>

<style>
.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6c757d;
    font-weight: 600;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link.active {
    background-color: transparent;
    border-bottom-color: #ffc107;
    color: #ffc107;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #ffca2c;
    color: #ffca2c;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.progress {
    background-color: #e9ecef;
    border-radius: 10px;
}

.btn-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    border: none;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: linear-gradient(45deg, #e0a800, #e8590c);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.is-valid {
    border-color: #198754 !important;
}

.alert-success {
    background: linear-gradient(45deg, #d1ecf1, #d4edda);
    border: 1px solid #a3cfbb;
    color: #0f5132;
}
</style>
@endsection
