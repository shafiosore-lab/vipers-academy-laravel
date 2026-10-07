@extends('layouts.admin')

@section('title', 'Player Details - ' . $player->first_name . ' ' . $player->last_name . ' - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Player Details</h4>
                            <small class="opacity-75">Complete player profile and compliance information</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.players.edit', $player->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.players.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Players
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Player Header -->
                    <div class="row mb-4">
                        <div class="col-lg-3 text-center">
                            @if($player->photo)
                                <img src="{{ asset('storage/' . $player->photo) }}" alt="{{ $player->first_name }} {{ $player->last_name }}"
                                     class="img-fluid rounded-circle shadow mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            @endif
                            <h5 class="mb-1">{{ $player->first_name }} {{ $player->last_name }}</h5>
                            <p class="text-muted mb-2">{{ $player->position }}</p>
                            <span class="badge bg-{{ $player->registration_status == 'Active' ? 'success' : ($player->registration_status == 'Pending' ? 'warning' : 'secondary') }}">
                                {{ $player->registration_status }}
                            </span>
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                                            <div class="row g-2">
                                                <div class="col-6"><strong>Age:</strong> {{ $player->age ?? 'N/A' }} years</div>
                                                <div class="col-6"><strong>Gender:</strong> {{ $player->gender }}</div>
                                                <div class="col-6"><strong>Nationality:</strong> {{ $player->nationality }}</div>
                                                <div class="col-6"><strong>Birth Place:</strong> {{ $player->place_of_birth }}</div>
                                                <div class="col-6"><strong>Height:</strong> {{ $player->height_cm ? $player->height_cm . ' cm' : 'N/A' }}</div>
                                                <div class="col-6"><strong>Weight:</strong> {{ $player->weight_kg ? $player->weight_kg . ' kg' : 'N/A' }}</div>
                                                <div class="col-6"><strong>Dominant Foot:</strong> {{ $player->dominant_foot ?? 'N/A' }}</div>
                                                <div class="col-6"><strong>Blood Type:</strong> {{ $player->blood_type ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-success mb-3"><i class="fas fa-futbol me-2"></i>Football Information</h6>
                                            <div class="row g-2">
                                                <div class="col-12"><strong>FIFA Registration:</strong> {{ $player->fifa_registration_number ?? 'Not Registered' }}</div>
                                                <div class="col-12"><strong>License Type:</strong> {{ $player->license_type ?? 'N/A' }}</div>
                                                <div class="col-12"><strong>Registration Date:</strong> {{ $player->registration_date ? \Carbon\Carbon::parse($player->registration_date)->format('M j, Y') : 'N/A' }}</div>
                                                <div class="col-12"><strong>Age Group:</strong> {{ $player->age_group ?? 'N/A' }}</div>
                                                <div class="col-12"><strong>Previous Clubs:</strong> {{ $player->previous_clubs ?? 'None' }}</div>
                                                <div class="col-12"><strong>Transfer Status:</strong> {{ $player->transfer_status ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-address-book me-2"></i>Contact Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Primary Contact</h6>
                                            <p><strong>Phone:</strong> {{ $player->phone }}</p>
                                            <p><strong>Email:</strong> {{ $player->email }}</p>
                                            <p><strong>Address:</strong> {{ $player->address }}, {{ $player->city }}, {{ $player->country }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-warning">Emergency Contact</h6>
                                            <p><strong>Name:</strong> {{ $player->emergency_contact_name }}</p>
                                            <p><strong>Phone:</strong> {{ $player->emergency_contact_phone }}</p>
                                            <p><strong>Relationship:</strong> {{ $player->emergency_contact_relationship }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guardian Information -->
                    @if($player->father_name || $player->mother_name || $player->guardian_name)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Guardian Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($player->father_name)
                                        <div class="col-md-4">
                                            <h6 class="text-primary">Father</h6>
                                            <p><strong>Name:</strong> {{ $player->father_name }}</p>
                                            <p><strong>ID Number:</strong> {{ $player->father_id_number ?? 'N/A' }}</p>
                                            <p><strong>Phone:</strong> {{ $player->father_phone ?? 'N/A' }}</p>
                                        </div>
                                        @endif
                                        @if($player->mother_name)
                                        <div class="col-md-4">
                                            <h6 class="text-primary">Mother</h6>
                                            <p><strong>Name:</strong> {{ $player->mother_name }}</p>
                                            <p><strong>ID Number:</strong> {{ $player->mother_id_number ?? 'N/A' }}</p>
                                            <p><strong>Phone:</strong> {{ $player->mother_phone ?? 'N/A' }}</p>
                                        </div>
                                        @endif
                                        @if($player->guardian_name)
                                        <div class="col-md-4">
                                            <h6 class="text-primary">Legal Guardian</h6>
                                            <p><strong>Name:</strong> {{ $player->guardian_name }}</p>
                                            <p><strong>Phone:</strong> {{ $player->guardian_phone ?? 'N/A' }}</p>
                                            <p><strong>Relationship:</strong> {{ $player->guardian_relationship ?? 'N/A' }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Medical Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Medical Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-danger">Health Details</h6>
                                            <p><strong>Medical Conditions:</strong> {{ $player->medical_conditions ?? 'None reported' }}</p>
                                            <p><strong>Allergies:</strong> {{ $player->allergies ?? 'None reported' }}</p>
                                            <p><strong>Medications:</strong> {{ $player->medications ?? 'None' }}</p>
                                            <p><strong>Last Checkup:</strong> {{ $player->last_medical_checkup ? \Carbon\Carbon::parse($player->last_medical_checkup)->format('M j, Y') : 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-danger">Insurance</h6>
                                            <p><strong>Provider:</strong> {{ $player->medical_insurance_provider ?? 'N/A' }}</p>
                                            <p><strong>Policy Number:</strong> {{ $player->medical_insurance_number ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>School:</strong> {{ $player->school_name ?? 'N/A' }}</p>
                                            <p><strong>Grade:</strong> {{ $player->school_grade ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Academic Performance:</strong> {{ $player->academic_performance ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FIFA Compliance Status -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-shield-alt me-2"></i>FIFA Compliance Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                                <h6>FIFA Registration</h6>
                                                <small class="text-muted">{{ $player->fifa_registration_number ? 'Registered' : 'Not Registered' }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-file-contract fa-2x {{ $player->safeguarding_policy_acknowledged ? 'text-success' : 'text-warning' }} mb-2"></i>
                                                <h6>Safeguarding Policy</h6>
                                                <small class="text-muted">{{ $player->safeguarding_policy_acknowledged ? 'Acknowledged' : 'Not Acknowledged' }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-home fa-2x {{ $player->accommodation_provided ? 'text-success' : 'text-warning' }} mb-2"></i>
                                                <h6>Accommodation</h6>
                                                <small class="text-muted">{{ $player->accommodation_provided ? 'Provided' : 'Not Provided' }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="p-3">
                                                <i class="fas fa-id-card fa-2x {{ $player->guardian_id_document ? 'text-success' : 'text-warning' }} mb-2"></i>
                                                <h6>ID Documents</h6>
                                                <small class="text-muted">{{ $player->guardian_id_document ? 'Uploaded' : 'Missing' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @if($player->accommodation_details)
                                    <div class="mt-3">
                                        <strong>Accommodation Details:</strong> {{ $player->accommodation_details }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contract Information -->
                    @if($player->contract_start_date || $player->contract_end_date)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Contract Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Start Date:</strong> {{ $player->contract_start_date ? \Carbon\Carbon::parse($player->contract_start_date)->format('M j, Y') : 'N/A' }}</p>
                                            <p><strong>End Date:</strong> {{ $player->contract_end_date ? \Carbon\Carbon::parse($player->contract_end_date)->format('M j, Y') : 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Contract Type:</strong> {{ $player->contract_type ?? 'N/A' }}</p>
                                            <p><strong>Contract Status:</strong> {{ $player->contract_status ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($player->admin_notes)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Admin Notes</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $player->admin_notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.players.edit', $player->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Edit Player
                                    </a>
                                    <form action="{{ route('admin.players.destroy', $player->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this player? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-2"></i>Delete Player
                                        </button>
                                    </form>
                                </div>
                                <a href="{{ route('admin.players.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to All Players
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .card-header .btn {
        border-radius: 20px;
    }

    .badge {
        font-size: 0.75rem;
    }

    .card-body p {
        margin-bottom: 0.5rem;
    }

    .card-body h6 {
        border-bottom: 2px solid currentColor;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
</style>
