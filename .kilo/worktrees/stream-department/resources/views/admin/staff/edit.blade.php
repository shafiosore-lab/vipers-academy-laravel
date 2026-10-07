@extends('layouts.admin')

@section('title', 'Edit Staff - ' . $staff->first_name . ' ' . $staff->last_name . ' - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-edit fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Edit Staff Member</h4>
                            <small class="opacity-75">Update staff information and role assignment</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $staff->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $staff->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role *</label>
                            <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                                <option value="">Select a Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $staff->roles->first()->id ?? null) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }} ({{ ucfirst($role->type ?? 'general') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select the appropriate role for this staff member. This determines their permissions and access level.</div>
                        </div>

                        <hr>
                        <h6 class="text-info mb-3"><i class="fas fa-info-circle me-1"></i>Current Status Information</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Current Status</label>
                                    <div class="form-control-plaintext">
                                        <span class="badge bg-{{ $staff->approval_status == 'approved' ? 'success' : 'warning' }} fs-6">
                                            {{ ucfirst($staff->approval_status) }}
                                        </span>
                                    </div>
                                    <small class="form-text text-muted">
                                        To change the approval status, use the activate/deactivate buttons on the staff list or detail page.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Member Since</label>
                                    <div class="form-control-plaintext">
                                        {{ $staff->created_at->format('F j, Y \a\t g:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div class="d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" id="confirmChanges" required>
                                    <label class="form-check-label small" for="confirmChanges">
                                        I confirm that the information provided is accurate and complete
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.staff.show', $staff) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                                <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Update Staff Member
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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

    .badge {
        font-size: 0.85rem;
    }
</style>
