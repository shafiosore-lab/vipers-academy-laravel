@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Organization</h1>
        <a href="{{ route('super-admin.organizations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Organizations
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('super-admin.organizations.store') }}">
                @csrf

                <!-- Organization Details -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Organization Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Organization Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Organization Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                       id="website" name="website" value="{{ old('website') }}" placeholder="https://">
                                @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="2">{{ old('address') }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Subscription Plan -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Subscription Plan</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="plan_id" class="form-label">Select Plan</label>
                            <select class="form-select @error('plan_id') is-invalid @enderror"
                                    id="plan_id" name="plan_id">
                                <option value="">No Plan (Free)</option>
                                @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - KES {{ number_format($plan->price) }}/{{ $plan->billing_cycle }}
                                    ({{ $plan->max_users == -1 ? 'Unlimited' : $plan->max_users }} users,
                                    {{ $plan->max_players == -1 ? 'Unlimited' : $plan->max_players }} players)
                                </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_users" class="form-label">Max Users (if no plan)</label>
                                <input type="number" class="form-control @error('max_users') is-invalid @enderror"
                                       id="max_users" name="max_users" value="{{ old('max_users', 10) }}" min="1">
                                @error('max_users')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_players" class="form-label">Max Players (if no plan)</label>
                                <input type="number" class="form-control @error('max_players') is-invalid @enderror"
                                       id="max_players" name="max_players" value="{{ old('max_players', 100) }}" min="1">
                                @error('max_players')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organization Admin -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Organization Admin Account</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Create the main administrator account for this organization.</p>

                        <div class="mb-3">
                            <label for="admin_name" class="form-label">Admin Name *</label>
                            <input type="text" class="form-control @error('admin_name') is-invalid @enderror"
                                   id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required>
                            @error('admin_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Admin Email *</label>
                            <input type="email" class="form-control @error('admin_email') is-invalid @enderror"
                                   id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                            @error('admin_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_password" class="form-label">Admin Password *</label>
                            <input type="password" class="form-control @error('admin_password') is-invalid @enderror"
                                   id="admin_password" name="admin_password" required minlength="8">
                            @error('admin_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Create Organization
                    </button>
                    <a href="{{ route('super-admin.organizations.index') }}" class="btn btn-secondary btn-lg ms-2">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Help</h6>
                </div>
                <div class="card-body">
                    <h6>Creating an Organization</h6>
                    <p class="text-muted">When you create an organization:</p>
                    <ul class="text-muted">
                        <li>A new organization record is created</li>
                        <li>An admin user account is automatically created with <strong>org-admin</strong> role</li>
                        <li>If a subscription plan is selected, a subscription is created</li>
                    </ul>

                    <h6 class="mt-3">Organization Admin</h6>
                    <p class="text-muted">The organization admin will be able to:</p>
                    <ul class="text-muted">
                        <li>Access the organization dashboard</li>
                        <li>Manage their organization's users and players</li>
                        <li>View organization-specific reports</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
