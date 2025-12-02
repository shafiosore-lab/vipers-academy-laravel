@extends('layouts.admin')

@section('title', 'Edit Program - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Edit Program</h4>
                            <small class="opacity-75">Update program information: {{ $program->title }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.programs.update', $program) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Program Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $program->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Enter a descriptive title for the program</div>
                                </div>

                                <div class="mb-3">
                                    <label for="age_group" class="form-label">Age Group *</label>
                                    <select class="form-control @error('age_group') is-invalid @enderror" id="age_group" name="age_group" required>
                                        <option value="">Select Age Group</option>
                                        <option value="U-8" {{ old('age_group', $program->age_group) == 'U-8' ? 'selected' : '' }}>U-8 (Under 8)</option>
                                        <option value="U-9" {{ old('age_group', $program->age_group) == 'U-9' ? 'selected' : '' }}>U-9 (Under 9)</option>
                                        <option value="U-10" {{ old('age_group', $program->age_group) == 'U-10' ? 'selected' : '' }}>U-10 (Under 10)</option>
                                        <option value="U-11" {{ old('age_group', $program->age_group) == 'U-11' ? 'selected' : '' }}>U-11 (Under 11)</option>
                                        <option value="U-12" {{ old('age_group', $program->age_group) == 'U-12' ? 'selected' : '' }}>U-12 (Under 12)</option>
                                        <option value="U-13" {{ old('age_group', $program->age_group) == 'U-13' ? 'selected' : '' }}>U-13 (Under 13)</option>
                                        <option value="U-14" {{ old('age_group', $program->age_group) == 'U-14' ? 'selected' : '' }}>U-14 (Under 14)</option>
                                        <option value="U-15" {{ old('age_group', $program->age_group) == 'U-15' ? 'selected' : '' }}>U-15 (Under 15)</option>
                                        <option value="U-16" {{ old('age_group', $program->age_group) == 'U-16' ? 'selected' : '' }}>U-16 (Under 16)</option>
                                        <option value="U-17" {{ old('age_group', $program->age_group) == 'U-17' ? 'selected' : '' }}>U-17 (Under 17)</option>
                                        <option value="U-18" {{ old('age_group', $program->age_group) == 'U-18' ? 'selected' : '' }}>U-18 (Under 18)</option>
                                        <option value="Senior" {{ old('age_group', $program->age_group) == 'Senior' ? 'selected' : '' }}>Senior</option>
                                    </select>
                                    @error('age_group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select the appropriate age group for this program</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="6" required>{{ old('description', $program->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Provide detailed information about the program, including objectives and benefits</div>
                                </div>

                                <div class="mb-3">
                                    <label for="schedule" class="form-label">Schedule *</label>
                                    <textarea class="form-control @error('schedule') is-invalid @enderror"
                                              id="schedule" name="schedule" rows="4" required
                                              placeholder="e.g., Monday & Wednesday: 4-6 PM, Saturday: 9-11 AM">{{ old('schedule', $program->schedule) }}</textarea>
                                    @error('schedule')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Specify the training schedule and timings</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duration" class="form-label">Duration</label>
                                            <input type="text" class="form-control @error('duration') is-invalid @enderror"
                                                   id="duration" name="duration" value="{{ old('duration', $program->duration) }}"
                                                   placeholder="e.g., 3 months, 1 year">
                                            @error('duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Program duration (optional)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mumias_discount_percentage" class="form-label">Mumias Discount (%)</label>
                                            <input type="number" class="form-control @error('mumias_discount_percentage') is-invalid @enderror"
                                                   id="mumias_discount_percentage" name="mumias_discount_percentage"
                                                   value="{{ old('mumias_discount_percentage', $program->mumias_discount_percentage ?? 50) }}" min="0" max="100">
                                            @error('mumias_discount_percentage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Discount percentage for Mumias community (default 50%)</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="regular_fee" class="form-label">Regular Fee (KSH)</label>
                                            <input type="number" class="form-control @error('regular_fee') is-invalid @enderror"
                                                   id="regular_fee" name="regular_fee" value="{{ old('regular_fee', $program->regular_fee) }}" min="0" step="0.01">
                                            @error('regular_fee')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Monthly fee for regular students (in Kenyan Shillings)</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mumias_fee" class="form-label">Mumias Fee (KSH)</label>
                                            <input type="number" class="form-control @error('mumias_fee') is-invalid @enderror"
                                                   id="mumias_fee" name="mumias_fee" value="{{ old('mumias_fee', $program->mumias_fee) }}" min="0" step="0.01">
                                            @error('mumias_fee')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Monthly fee for Mumias community (subsidized)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-info">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-image me-2"></i>Program Image</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload New Image</label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                   id="image" name="image" accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Optional: Upload a new program image (JPG, PNG, GIF, max 2MB)</div>
                                        </div>

                                        @if($program->image)
                                            <div class="mb-3">
                                                <label class="form-label">Current Image</label>
                                                <div class="border rounded p-2">
                                                    <img src="{{ asset('storage/' . $program->image) }}" alt="Current Image"
                                                         class="img-fluid rounded" style="max-height: 200px;">
                                                </div>
                                                <small class="text-muted">Leave empty to keep current image</small>
                                            </div>
                                        @endif

                                        <div class="alert alert-info">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                <strong>Image Guidelines:</strong><br>
                                                • Recommended size: 800x600px<br>
                                                • File formats: JPG, PNG, GIF<br>
                                                • Maximum file size: 2MB
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-warning mt-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Update Checklist</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="confirmDetails" required>
                                            <label class="form-check-label small" for="confirmDetails">
                                                I confirm that all updated program details are accurate and complete
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="alert alert-warning py-2 px-3 mb-0 me-3">
                                    <small class="mb-0">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        All required fields marked with *
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Program
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

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.alert-warning {
    background: linear-gradient(45deg, #fff3cd, #ffeaa7);
    border: 1px solid #ffecb5;
    color: #664d03;
}
</style>

<script>
// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');

    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields marked with *');
        }
    });

    // Real-time validation feedback
    document.addEventListener('input', function(e) {
        if (e.target.hasAttribute('required') && e.target.value.trim()) {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        }
    });

    // Format fee input
    document.getElementById('fee').addEventListener('blur', function() {
        const value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.value = value.toFixed(2);
        }
    });
});
</script>
@endsection
