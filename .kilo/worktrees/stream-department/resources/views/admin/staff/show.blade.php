@extends('layouts.admin')

@section('title', 'Staff Details - ' . $staff->first_name . ' ' . $staff->last_name . ' - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Staff Details</h4>
                            <small class="opacity-75">Complete staff profile and role information</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Staff
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Staff Header -->
                    <div class="row mb-4">
                        <div class="col-lg-3 text-center">
                            @if($staff->photo)
                                <img src="{{ asset('storage/' . $staff->photo) }}" alt="{{ $staff->first_name }} {{ $staff->last_name }}"
                                     class="img-fluid rounded-circle shadow mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            @endif
                            <h5 class="mb-1">{{ $staff->first_name }} {{ $staff->last_name }}</h5>
                            <p class="text-muted mb-2">{{ $staff->roles->first()->name ?? 'No Role Assigned' }}</p>
                            <span class="badge bg-{{ $staff->approval_status == 'approved' ? 'success' : 'warning' }}">
                                {{ ucfirst($staff->approval_status) }}
                            </span>
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                                            <div class="row g-2">
                                                <div class="col-12"><strong>Full Name:</strong> {{ $staff->name }}</div>
                                                <div class="col-12"><strong>Email:</strong> {{ $staff->email }}</div>
                                                <div class="col-12"><strong>Phone:</strong> {{ $staff->phone }}</div>
                                                <div class="col-12"><strong>User Type:</strong> {{ ucfirst($staff->user_type) }}</div>
                                                <div class="col-12"><strong>Status:</strong> {{ ucfirst($staff->approval_status) }}</div>
                                                <div class="col-12"><strong>Member Since:</strong> {{ $staff->created_at->format('M j, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="text-success mb-3"><i class="fas fa-users-cog me-2"></i>Role Information</h6>
                                            <div class="row g-2">
                                                @if($staff->roles->count() > 0)
                                                    @foreach($staff->roles as $role)
                                                        <div class="col-12"><strong>Role:</strong> {{ $role->name }}</div>
                                                        <div class="col-12"><strong>Role Type:</strong> {{ ucfirst($role->type ?? 'N/A') }}</div>
                                                        <div class="col-12"><strong>Permissions:</strong>
                                                            @if($role->permissions->count() > 0)
                                                                <span class="badge bg-secondary">{{ $role->permissions->count() }} permissions</span>
                                                            @else
                                                                <span class="text-muted">No permissions assigned</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-12"><span class="text-muted">No role assigned</span></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Partner Information (if applicable) -->
                    @if($staff->partner)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Partner Association</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Partner Details</h6>
                                            <p><strong>Partner Name:</strong> {{ $staff->partner->name }}</p>
                                            <p><strong>Partner Email:</strong> {{ $staff->partner->email }}</p>
                                            <p><strong>Partner Phone:</strong> {{ $staff->partner->phone }}</p>
                                            <p><strong>Status:</strong> <span class="badge bg-{{ $staff->partner->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($staff->partner->status ?? 'inactive') }}</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Company Information</h6>
                                            <p><strong>Company:</strong> {{ $staff->partner->company_name ?? 'N/A' }}</p>
                                            <p><strong>Industry:</strong> {{ $staff->partner->industry ?? 'N/A' }}</p>
                                            <p><strong>Website:</strong> {{ $staff->partner->company_website ? '<a href="' . $staff->partner->company_website . '" target="_blank">' . $staff->partner->company_website . '</a>' : 'N/A' }}</p>
                                            <p><strong>Description:</strong> {{ $staff->partner->company_description ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Login Activity (if available) -->
                    @if($staff->loginActivities && $staff->loginActivities->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Login Activity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Login Time</th>
                                                    <th>IP Address</th>
                                                    <th>User Agent</th>
                                                    <th>Location</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($staff->loginActivities->take(5) as $activity)
                                                <tr>
                                                    <td>{{ $activity->created_at->format('M j, Y H:i') }}</td>
                                                    <td>{{ $activity->ip_address }}</td>
                                                    <td><small>{{ Str::limit($activity->user_agent, 50) }}</small></td>
                                                    <td>{{ $activity->location ?? 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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
                                    @if($staff->approval_status === 'approved')
                                        <form action="{{ route('admin.staff.deactivate', $staff) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to deactivate this staff member?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-pause me-2"></i>Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.staff.activate', $staff) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to activate this staff member?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-play me-2"></i>Activate
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this staff member? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-2"></i>Delete Staff
                                        </button>
                                    </form>
                                </div>
                                <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to All Staff
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
