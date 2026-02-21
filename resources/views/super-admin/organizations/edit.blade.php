@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Organization</h1>
        <a href="{{ route('super-admin.organizations.show', $organization) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> View Details
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('super-admin.organizations.update', $organization) }}">
                @csrf
                @method('PUT')

                <!-- Organization Details -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Organization Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Organization Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $organization->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Organization Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $organization->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $organization->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                    <option value="active" {{ $organization->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $organization->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="trial" {{ $organization->status === 'trial' ? 'selected' : '' }}>Trial</option>
                                    <option value="suspended" {{ $organization->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="pending" {{ $organization->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="2">{{ old('address', $organization->address) }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $organization->description) }}</textarea>
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
                                <option value="{{ $plan->id }}" {{ $organization->subscription_plan_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - KES {{ number_format($plan->price) }}/{{ $plan->billing_cycle }}
                                </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_users" class="form-label">Max Users</label>
                                <input type="number" class="form-control @error('max_users') is-invalid @enderror"
                                       id="max_users" name="max_users" value="{{ old('max_users', $organization->max_users) }}" min="1">
                                @error('max_users')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="max_players" class="form-label">Max Players</label>
                                <input type="number" class="form-control @error('max_players') is-invalid @enderror"
                                       id="max_players" name="max_players" value="{{ old('max_players', $organization->max_players) }}" min="1">
                                @error('max_players')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Update Organization
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
                    <h6 class="m-0 font-weight-bold text-primary">Organization Info</h6>
                </div>
                <div class="card-body">
                    <p><strong>Slug:</strong> {{ $organization->slug }}</p>
                    <p><strong>Created:</strong> {{ $organization->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong> {{ $organization->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>Created By:</strong> {{ $organization->createdBy->name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Danger Zone</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('super-admin.organizations.toggle-status', $organization) }}">
                        @csrf
                        @if($organization->status === 'active')
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-ban"></i> Suspend Organization
                        </button>
                        @else
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check"></i> Activate Organization
                        </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
