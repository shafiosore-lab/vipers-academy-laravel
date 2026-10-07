@extends('layouts.admin')

@section('title', 'Edit Gallery - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Edit Photo Gallery</h4>
                            <small class="opacity-75">Update gallery: {{ $gallery->title }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Gallery Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Enter a descriptive title for the gallery</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4">{{ old('description', $gallery->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional: Provide a description for the gallery</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-dark">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-camera me-2"></i>Add More Images</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Upload Additional Images</label>
                                            <input type="file" class="form-control @error('images') is-invalid @enderror"
                                                   id="images" name="images[]" accept="image/*" multiple>
                                            @error('images')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Optional: Select additional images (JPG, PNG, GIF, max 2MB each)</div>
                                        </div>

                                        <div id="imagePreview" class="mt-3" style="display: none;">
                                            <h6>New Images to Add:</h6>
                                            <div id="previewContainer" class="d-flex flex-wrap gap-2"></div>
                                        </div>

                                        <div class="alert alert-dark">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                <strong>Image Guidelines:</strong><br>
                                                • File formats: JPG, PNG, GIF<br>
                                                • Maximum file size: 2MB per image<br>
                                                • Recommended size: 800x600px
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
                                                I confirm that the updated gallery contains appropriate content and is ready for publication
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Images Section -->
                        @if($gallery->images && json_decode($gallery->images, true))
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border-info">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0"><i class="fas fa-images me-2"></i>Current Gallery Images</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Current images in this gallery. New images will be added to the existing ones.
                                            </div>
                                            <div class="row" id="currentImages">
                                                @foreach(json_decode($gallery->images, true) as $image)
                                                    <div class="col-md-3 col-sm-6 mb-3">
                                                        <div class="card h-100">
                                                            <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="Gallery Image" style="height: 200px; object-fit: cover;">
                                                            <div class="card-body p-2">
                                                                <small class="text-muted">{{ basename($image) }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="alert alert-dark py-2 px-3 mb-0 me-3">
                                    <small class="mb-0">
                                        <i class="fas fa-asterisk me-1"></i>
                                        All required fields marked with *
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-dark btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Gallery
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

.btn-dark {
    background: linear-gradient(45deg, #343a40, #5a6268);
    border: none;
    transition: all 0.3s ease;
}

.btn-dark:hover {
    background: linear-gradient(45deg, #23272b, #495057);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(52, 58, 64, 0.3);
}

.form-control:focus {
    border-color: #343a40;
    box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.25);
}

.alert-dark {
    background: linear-gradient(45deg, #e9ecef, #d6d8db);
    border: 1px solid #c4c8cb;
    color: #383d41;
}

.preview-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    border: 2px solid #dee2e6;
}

.card-img-top {
    border-radius: calc(0.375rem - 1px) calc(0.375rem - 1px) 0 0;
}
</style>

<script>
// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');

    // Image preview functionality
    imageInput.addEventListener('change', function(e) {
        previewContainer.innerHTML = '';
        const files = Array.from(e.target.files);

        if (files.length > 0) {
            imagePreview.style.display = 'block';

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-image';
                        img.title = file.name;
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            imagePreview.style.display = 'none';
        }
    });

    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (field.type === 'file') {
                // File inputs are optional for edit
            } else if (!field.value.trim()) {
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

    // Character counter for description
    const descriptionTextarea = document.getElementById('description');
    const titleInput = document.getElementById('title');

    function updateCharCount() {
        const descriptionLength = descriptionTextarea.value.length;
        const titleLength = titleInput.value.length;

        // Update description counter
        let descriptionCounter = descriptionTextarea.parentNode.querySelector('.char-counter');
        if (!descriptionCounter) {
            descriptionCounter = document.createElement('small');
            descriptionCounter.className = 'char-counter form-text text-muted';
            descriptionTextarea.parentNode.appendChild(descriptionCounter);
        }
        descriptionCounter.textContent = `${descriptionLength} characters`;

        // Update title counter
        let titleCounter = titleInput.parentNode.querySelector('.char-counter');
        if (!titleCounter) {
            titleCounter = document.createElement('small');
            titleCounter.className = 'char-counter form-text text-muted';
            titleInput.parentNode.appendChild(titleCounter);
        }
        titleCounter.textContent = `${titleLength}/255 characters`;
    }

    descriptionTextarea.addEventListener('input', updateCharCount);
    titleInput.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
});
</script>
@endsection
