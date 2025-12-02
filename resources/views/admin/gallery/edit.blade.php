@extends('layouts.admin')

@section('title', 'Edit Gallery Item')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Gallery Item
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-0">Modify the details of this gallery item.</p>
                </div>
            </div>

            <!-- Form -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $gallery->title) }}"
                                   required
                                   placeholder="Enter a descriptive title for this gallery item">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Media Type -->
                        <div class="mb-4">
                            <label for="media_type" class="form-label fw-bold">
                                Media Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('media_type') is-invalid @enderror"
                                    id="media_type"
                                    name="media_type"
                                    required>
                                <option value="image" {{ old('media_type', $gallery->media_type) === 'image' ? 'selected' : '' }}>
                                    🖼️ Image (JPG, PNG, GIF)
                                </option>
                                <option value="video" {{ old('media_type', $gallery->media_type) === 'video' ? 'selected' : '' }}>
                                    🎥 Video (MP4, WebM)
                                </option>
                            </select>
                            @error('media_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Media URL -->
                        <div class="mb-4">
                            <label for="media_url" class="form-label fw-bold">
                                Media URL <span class="text-danger">*</span>
                            </label>
                            <input type="url"
                                   class="form-control @error('media_url') is-invalid @enderror"
                                   id="media_url"
                                   name="media_url"
                                   value="{{ old('media_url', $gallery->media_url) }}"
                                   required>
                            @error('media_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Media Preview -->
                        @if($gallery->media_type === 'image')
                            <div class="mb-4">
                                <label class="form-label fw-bold">Current Image</label>
                                <div class="border rounded p-3 bg-light">
                                    <img src="{{ $gallery->media_url }}" class="img-fluid rounded" style="max-height: 200px;" alt="Current Image">
                                </div>
                            </div>
                        @elseif($gallery->media_type === 'video')
                            <div class="mb-4">
                                <label class="form-label fw-bold">Current Video</label>
                                <div class="border rounded p-3 bg-light">
                                    <video controls class="img-fluid rounded" style="max-height: 200px;">
                                        <source src="{{ $gallery->media_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div class="form-text">
                                <span class="text-danger">*</span> Required fields
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Update Gallery Item
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
.form-control:focus, .form-select:focus {
    border-color: #ea1c4d;
    box-shadow: 0 0 0 0.2rem rgba(234, 28, 77, 0.25);
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #e0a800;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush
@endsection
