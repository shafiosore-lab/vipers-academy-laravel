@extends('layouts.staff')

@section('title', 'My Profile')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">My Profile</h2>
        <p class="text-muted mb-0">Manage your account and preferences</p>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Information -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('parent.profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        <small class="text-muted">Contact admin to change your name</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        <small class="text-muted">Contact admin to change your email</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ Auth::user()->phone ?? '' }}"
                               placeholder="Enter phone number">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Linked Players -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">My Players</h5>
            </div>
            <div class="card-body">
                @if($linkedPlayers && $linkedPlayers->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($linkedPlayers as $player)
                    <div class="list-group-item px-0 py-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 flex-shrink-0">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $player->full_name }}</h6>
                                <p class="text-muted mb-0 small">
                                    <i class="fas fa-id-card-alt me-1"></i>{{ $player->player_id ?? 'N/A' }}
                                    @if($player->date_of_birth)
                                    <span class="ms-2"><i class="fas fa-birthday-cake me-1"></i>{{ \Carbon\Carbon::parse($player->date_of_birth)->age }} years old</span>
                                    @endif
                                </p>
                            </div>
                            <span class="badge bg-success">Linked</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-users fs-1 mb-2 d-block opacity-25"></i>
                    <p class="mb-0">No players linked to your account.</p>
                    <small>Contact the club admin to link a player.</small>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Password Change -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('parent.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-lock me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success mt-4">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger mt-4">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@endsection
