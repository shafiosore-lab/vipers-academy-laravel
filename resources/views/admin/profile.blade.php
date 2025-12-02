@extends('layouts.admin')

@section('title', 'My Profile - Vipers Academy Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-cog fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">My Profile</h4>
                            <small class="opacity-75">Manage your account settings and preferences</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Profile Header -->
                    <div class="row mb-4">
                        <div class="col-lg-4 text-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-3x text-muted"></i>
                            </div>
                            <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                            <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                            <span class="badge bg-primary">Administrator</span>
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Account Information</h6>
                                            <div class="row g-2">
                                                <div class="col-12"><strong>Account Created:</strong> {{ Auth::user()->created_at->format('M j, Y') }}</div>
                                                <div class="col-12"><strong>Last Updated:</strong> {{ Auth::user()->updated_at->diffForHumans() }}</div>
                                                <div class="col-12"><strong>Email Verified:</strong>
                                                    @if(Auth::user()->email_verified_at)
                                                        <span class="text-success"><i class="fas fa-check-circle me-1"></i>Verified</span>
                                                    @else
                                                        <span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Not Verified</span>
                                                    @endif
                                                </div>
                                                <div class="col-12"><strong>Role:</strong> Administrator</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-success mb-3"><i class="fas fa-shield-alt me-2"></i>Security Status</h6>
                                            <div class="row g-2">
                                                <div class="col-12"><strong>Password:</strong> <span class="text-success">Set</span></div>
                                                <div class="col-12"><strong>Two-Factor Auth:</strong> <span class="text-warning">Not Enabled</span></div>
                                                <div class="col-12"><strong>Last Login:</strong> {{ now()->format('M j, Y H:i') }}</div>
                                                <div class="col-12"><strong>Login Method:</strong> Email & Password</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information Form -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-user-edit me-2"></i>Update Profile Information</h6>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="{{ route('profile.update') }}" class="row g-3">
                                        @csrf
                                        @method('patch')

                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Profile
                                            </button>

                                            @if (session('status') === 'profile-updated')
                                                <span class="text-success ms-3">
                                                    <i class="fas fa-check-circle me-1"></i>Profile updated successfully!
                                                </span>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Change Form -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-key me-2"></i>Change Password</h6>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="{{ route('password.update') }}" class="row g-3">
                                        @csrf
                                        @method('put')

                                        <div class="col-md-6">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                                   id="current_password" name="current_password" required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   id="password" name="password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                   name="password_confirmation" required>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-key me-2"></i>Update Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Activity -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Account Activity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="activity-timeline">
                                        <div class="activity-item">
                                            <div class="activity-icon bg-primary">
                                                <i class="fas fa-sign-in-alt"></i>
                                            </div>
                                            <div class="activity-content">
                                                <h6 class="mb-1">Logged into admin panel</h6>
                                                <small class="text-muted">{{ now()->format('M j, Y \a\t g:i A') }}</small>
                                            </div>
                                        </div>
                                        <div class="activity-item">
                                            <div class="activity-icon bg-info">
                                                <i class="fas fa-user-edit"></i>
                                            </div>
                                            <div class="activity-content">
                                                <h6 class="mb-1">Profile information updated</h6>
                                                <small class="text-muted">{{ Auth::user()->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <div class="activity-item">
                                            <div class="activity-icon bg-success">
                                                <i class="fas fa-user-plus"></i>
                                            </div>
                                            <div class="activity-content">
                                                <h6 class="mb-1">Account created</h6>
                                                <small class="text-muted">{{ Auth::user()->created_at->format('M j, Y \a\t g:i A') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="text-danger">Delete Account</h6>
                                            <p class="text-muted mb-0">
                                                Once you delete your account, there is no going back. Please be certain.
                                                This action will permanently delete your account and remove your data from our servers.
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                                <i class="fas fa-trash me-2"></i>Delete Account
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone. All your data will be permanently removed from our servers.</p>

                <div class="alert alert-warning">
                    <strong>Warning:</strong> This will also log you out and you will lose access to the admin panel.
                </div>

                <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('delete')

                    <div class="mb-3">
                        <label for="password" class="form-label">Enter your password to confirm:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Yes, Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .activity-timeline {
        position: relative;
        padding-left: 40px;
    }

    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .activity-item {
        position: relative;
        margin-bottom: 30px;
        display: flex;
        align-items: flex-start;
    }

    .activity-item:last-child {
        margin-bottom: 0;
    }

    .activity-icon {
        position: absolute;
        left: -30px;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 1;
    }

    .activity-content h6 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .activity-content small {
        color: #6c757d;
    }

    .card-header .btn {
        border-radius: 20px;
    }

    .badge {
        font-size: 0.75rem;
    }
</style>
