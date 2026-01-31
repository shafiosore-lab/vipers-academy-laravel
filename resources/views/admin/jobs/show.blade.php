@extends('layouts.admin')

@section('title', 'Job Details - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-briefcase fa-lg me-3"></i>
                            <div>
                                <h4 class="card-title mb-0">{{ $job->title }}</h4>
                                <small class="opacity-75">{{ $job->location }} • {{ ucfirst($job->type) }}</small>
                            </div>
                        </div>
                        <div>
                            <span class="badge bg-{{ $job->status === 'open' ? 'success' : ($job->status === 'closed' ? 'danger' : 'secondary') }} fs-6 px-3 py-2">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Job Details -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Job Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Location:</strong> {{ $job->location }}</p>
                                            <p><strong>Type:</strong> {{ ucfirst($job->type) }}</p>
                                            @if($job->department)
                                                <p><strong>Department:</strong> {{ $job->department }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($job->salary)
                                                <p><strong>Salary:</strong> {{ $job->salary }}</p>
                                            @endif
                                            @if($job->application_deadline)
                                                <p><strong>Application Deadline:</strong> {{ $job->application_deadline->format('M d, Y') }}</p>
                                            @endif
                                            <p><strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Description -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Job Description</h5>
                                </div>
                                <div class="card-body">
                                    <div class="job-description">
                                        {!! nl2br(e($job->description)) !!}
                                    </div>
                                </div>
                            </div>

                            <!-- Requirements -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Requirements</h5>
                                </div>
                                <div class="card-body">
                                    <div class="job-requirements">
                                        {!! nl2br(e($job->requirements)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Statistics -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Application Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-12 mb-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-primary mb-1">{{ $job->applications->count() }}</h3>
                                                <small class="text-muted">Total Applications</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-2">
                                                <h5 class="text-warning mb-1">{{ $job->applications->where('status', 'pending')->count() }}</h5>
                                                <small class="text-muted">Pending</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-2">
                                                <h5 class="text-info mb-1">{{ $job->applications->where('status', 'reviewed')->count() }}</h5>
                                                <small class="text-muted">Reviewed</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mt-2">
                                            <div class="border rounded p-2">
                                                <h5 class="text-success mb-1">{{ $job->applications->where('status', 'accepted')->count() }}</h5>
                                                <small class="text-muted">Accepted</small>
                                            </div>
                                        </div>
                                        <div class="col-6 mt-2">
                                            <div class="border rounded p-2">
                                                <h5 class="text-danger mb-1">{{ $job->applications->where('status', 'rejected')->count() }}</h5>
                                                <small class="text-muted">Rejected</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>Edit Job
                                        </a>
                                        <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Jobs
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Applications Section -->
                    @if($job->applications->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Applications ({{ $job->applications->count() }})</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Applicant</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Applied</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($job->applications as $application)
                                                <tr>
                                                    <td>{{ $application->applicant_name }}</td>
                                                    <td>{{ $application->email }}</td>
                                                    <td>{{ $application->phone }}</td>
                                                    <td>{{ $application->applied_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'reviewed' ? 'info' : ($application->status === 'accepted' ? 'success' : 'danger')) }}">
                                                            {{ ucfirst($application->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="viewApplication({{ $application->id }})">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Applications Yet</h5>
                                    <p class="text-muted">Applications will appear here once candidates apply for this position.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Modal -->
<div class="modal fade" id="applicationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="applicationContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.job-description, .job-requirements {
    line-height: 1.6;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}
</style>

<script>
function viewApplication(applicationId) {
    // This would typically make an AJAX call to get application details
    // For now, we'll show a placeholder
    const content = `
        <div class="text-center">
            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
            <h5>Application Details</h5>
            <p class="text-muted">Detailed application view will be implemented with AJAX.</p>
            <p><strong>Application ID:</strong> ${applicationId}</p>
        </div>
    `;

    document.getElementById('applicationContent').innerHTML = content;
    new bootstrap.Modal(document.getElementById('applicationModal')).show();
}
</script>
@endsection
