@extends('layouts.admin')

@section('title', 'Create Job - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-briefcase fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Create Job Posting</h4>
                            <small class="opacity-75">Add a new job opportunity to the career page</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jobs.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Job Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location *</label>
                                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                                   id="location" name="location" value="{{ old('location') }}" required>
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Job Type *</label>
                                            <select class="form-control @error('type') is-invalid @enderror"
                                                    id="type" name="type" required>
                                                <option value="">Select Type</option>
                                                <option value="full-time" {{ old('type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                                <option value="part-time" {{ old('type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                                <option value="contract" {{ old('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="department" class="form-label">Department</label>
                                            <input type="text" class="form-control @error('department') is-invalid @enderror"
                                                   id="department" name="department" value="{{ old('department') }}">
                                            @error('department')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="salary" class="form-label">Salary</label>
                                            <input type="text" class="form-control @error('salary') is-invalid @enderror"
                                                   id="salary" name="salary" value="{{ old('salary') }}" placeholder="e.g., KES 50,000 - 70,000">
                                            @error('salary')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="application_deadline" class="form-label">Application Deadline</label>
                                    <input type="date" class="form-control @error('application_deadline') is-invalid @enderror"
                                           id="application_deadline" name="application_deadline" value="{{ old('application_deadline') }}">
                                    @error('application_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Job Description *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="requirements" class="form-label">Requirements *</label>
                                    <textarea class="form-control @error('requirements') is-invalid @enderror"
                                              id="requirements" name="requirements" rows="6" required>{{ old('requirements') }}</textarea>
                                    @error('requirements')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Job Posting Tips</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-primary">
                                            <small>
                                                <i class="fas fa-lightbulb me-1"></i>
                                                <strong>Tips for effective job postings:</strong><br>
                                                • Be specific about requirements<br>
                                                • Include salary range when possible<br>
                                                • Highlight what makes Vipers Academy special<br>
                                                • Set realistic deadlines
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="alert alert-info py-2 px-3 mb-0 me-3">
                                    <small class="mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        All required fields marked with *
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>Create Job
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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

.btn-success {
    background: linear-gradient(45deg, #65c16e, #4a8c52);
    border: none;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: linear-gradient(45deg, #4a8c52, #3a6b3f);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(101, 193, 110, 0.3);
}

.form-control:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}
</style>
@endsection
