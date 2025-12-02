@extends('layouts.admin')

@section('title', 'Add Gallery Item')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Add Gallery Item
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-0">Add a new image or video to the gallery. You can add media from a URL or upload to your media hosting.</p>
                </div>
            </div>

            <!-- Form -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                Title <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required
                                   placeholder="Enter a descriptive title for this gallery item">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Give this media item a clear, descriptive title that explains what it shows.
                            </div>
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
                                <option value="" disabled {{ old('media_type', '') === '' ? 'selected' : '' }}>
                                    Choose media type...
                                </option>
                                <option value="image" {{ old('media_type') === 'image' ? 'selected' : '' }}>
                                    🖼️ Image (JPG, PNG, GIF)
                                </option>
                                <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>
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
                                   value="{{ old('media_url') }}"
                                   required
                                   placeholder="https://example.com/image.jpg or https://example.com/video.mp4">
                            @error('media_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Enter the full URL of the image or video file. This can be:
                            </div>
                            <ul class="form-text small mt-2 mb-0">
                                <li>• A direct link to an image file (JPG, PNG, GIF)</li>
                                <li>• A direct link to a video file (MP4, WebM)</li>
                                <li>• A CDN URL or hosted media URL</li>
                            </ul>
                        </div>

                        <!-- Preview Section -->
                        <div class="mb-4" id="preview-section" style="display: none;">
                            <label class="form-label fw-bold">Preview</label>
                            <div class="border rounded p-3 bg-light">
                                <div id="image-preview" class="text-center" style="display: none;">
                                    <img id="preview-image" src="" class="img-fluid rounded" style="max-height: 300px;" alt="Preview">
                                    <p class="mt-2 text-success fw-bold">✓ Image Preview</p>
                                </div>
                                <div id="video-preview" class="text-center" style="display: none;">
                                    <video id="preview-video" controls class="img-fluid rounded" style="max-height: 300px;">
                                        Your browser does not support the video tag.
                                    </video>
                                    <p class="mt-2 text-success fw-bold">✓ Video Preview</p>
                                </div>
                                <div id="loading-preview" class="text-center py-4" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading preview...</p>
                                </div>
                                <div id="error-preview" class="text-center py-4" style="display: none;">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                    <p class="mt-2 text-warning fw-bold">Unable to load preview</p>
                                    <p class="text-muted small">The URL may be invalid or the file may not be publicly accessible.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div class="form-text">
                                <span class="text-danger">*</span> Required fields
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Gallery Item
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

.btn-primary {
    background-color: #ea1c4d;
    border-color: #ea1c4d;
}

.btn-primary:hover {
    background-color: #c0173f;
    border-color: #c0173f;
}

#preview-section {
    transition: all 0.3s ease;
}

#preview-image, #preview-video {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mediaTypeSelect = document.getElementById('media_type');
    const mediaUrlInput = document.getElementById('media_url');
    const previewSection = document.getElementById('preview-section');
    const imagePreview = document.getElementById('image-preview');
    const videoPreview = document.getElementById('video-preview');
    const loadingPreview = document.getElementById('loading-preview');
    const errorPreview = document.getElementById('error-preview');
    const previewImage = document.getElementById('preview-image');
    const previewVideo = document.getElementById('preview-video');

    let previewTimeout;

    // Show/hide preview and update form placeholder
    function updatePreview() {
        const mediaType = mediaTypeSelect.value;
        const mediaUrl = mediaUrlInput.value.trim();

        if (mediaType && mediaUrl) {
            previewSection.style.display = 'block';
            loadPreview(mediaType, mediaUrl);
        } else {
            previewSection.style.display = 'none';
        }
    }

    // Load media preview
    function loadPreview(mediaType, mediaUrl) {
        // Hide all preview elements
        imagePreview.style.display = 'none';
        videoPreview.style.display = 'none';
        loadingPreview.style.display = 'none';
        errorPreview.style.display = 'none';

        // Show loading
        loadingPreview.style.display = 'block';

        // Clear previous timeout
        if (previewTimeout) {
            clearTimeout(previewTimeout);
        }

        // Load preview after a short delay to avoid too many requests
        previewTimeout = setTimeout(() => {
            if (mediaType === 'image') {
                // Load image preview
                previewImage.onload = function() {
                    loadingPreview.style.display = 'none';
                    imagePreview.style.display = 'block';
                    errorPreview.style.display = 'none';
                };
                previewImage.onerror = function() {
                    loadingPreview.style.display = 'none';
                    imagePreview.style.display = 'none';
                    errorPreview.style.display = 'block';
                };
                previewImage.src = mediaUrl;
            } else if (mediaType === 'video') {
                // Load video preview
                previewVideo.onload = function() {
                    loadingPreview.style.display = 'none';
                    videoPreview.style.display = 'block';
                    errorPreview.style.display = 'none';
                };
                previewVideo.onerror = function() {
                    loadingPreview.style.display = 'none';
                    videoPreview.style.display = 'none';
                    errorPreview.style.display = 'block';
                };
                previewVideo.src = mediaUrl;
            }
        }, 500);
    }

    // Event listeners
    mediaTypeSelect.addEventListener('change', updatePreview);
    mediaUrlInput.addEventListener('input', updatePreview);

    // Update placeholder based on media type
    mediaTypeSelect.addEventListener('change', function() {
        if (this.value === 'image') {
            mediaUrlInput.placeholder = 'https://example.com/image.jpg or https://example.com/image.png';
        } else if (this.value === 'video') {
            mediaUrlInput.placeholder = 'https://example.com/video.mp4 or https://example.com/video.webm';
        } else {
            mediaUrlInput.placeholder = 'https://example.com/media-file';
        }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const mediaType = mediaTypeSelect.value;
        const mediaUrl = mediaUrlInput.value.trim();

        if (!mediaType || !mediaUrl) {
            e.preventDefault();
            alert('Please select a media type and provide a valid URL.');
            return false;
        }

        // Basic URL validation
        try {
            new URL(mediaUrl);
        } catch (_) {
            e.preventDefault();
            alert('Please provide a valid URL.');
            return false;
        }
    });

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
