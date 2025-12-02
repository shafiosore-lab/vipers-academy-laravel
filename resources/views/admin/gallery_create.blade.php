@extends('layouts.admin')

@section('title', 'Create Gallery - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-images fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Create Photo Gallery</h4>
                            <small class="opacity-75">Add a new image gallery to showcase academy activities</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Gallery Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Enter a descriptive title for the gallery</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional: Provide a description for the gallery</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-dark">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-camera me-2"></i>Gallery Images</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Upload Images *</label>
                                            <input type="file"
                                                class="form-control @error('images') is-invalid @enderror" id="images"
                                                name="images[]" accept="image/*" multiple required>
                                            @error('images')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Select multiple images (JPG, PNG, GIF, max 2MB each)
                                            </div>
                                        </div>

                                        <div id="imagePreview" class="mt-3" style="display: none;">
                                            <h6>Selected Images:</h6>
                                            <div id="previewContainer" class="d-flex flex-wrap gap-2"></div>
                                        </div>

                                        <div class="alert alert-dark">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                <strong>Image Guidelines:</strong><br>
                                                • Minimum 1 image required<br>
                                                • File formats: JPG, PNG, GIF<br>
                                                • Maximum file size: 2MB per image<br>
                                                • Recommended size: 800x600px
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-success mt-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Gallery Checklist
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="confirmDetails"
                                                required>
                                            <label class="form-check-label small" for="confirmDetails">
                                                I confirm that the gallery contains appropriate content and is ready for
                                                publication
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                <button type="submit" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-save me-2"></i>Create Gallery
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

.btn-secondary {
    background: linear-gradient(45deg, #6c757d, #8e9297);
    border: none;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: linear-gradient(45deg, #5a6268, #7e8286);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.form-control:focus {
    border-color: #6c757d;
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
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
                if (!field.files || field.files.length === 0) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
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
