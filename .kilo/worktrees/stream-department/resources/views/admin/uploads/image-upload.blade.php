@extends('layouts.admin')

@section('title', __('Image Upload - Vipers Academy Admin'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header">
            <div>
                <h1 class="page-title">{{ __('Image Upload Manager') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Image Upload') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Upload Form -->
    <div class="col-lg-4 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <h5 class="content-card-title">{{ __('Upload Images') }}</h5>
            </div>
            <div class="content-card-body">
                <form action="{{ route('admin.image-upload.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <div class="mb-3">
                        <label for="folder" class="form-label">{{ __('Select Folder') }}</label>
                        <select class="form-select" id="folder" name="folder" required>
                            <option value="">{{ __('Choose folder...') }}</option>
                            @foreach($folders as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">{{ __('Select Images') }}</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                        <div class="form-text">{{ __('You can select multiple images. Max 2MB each.') }}</div>
                    </div>

                    <div class="mb-3">
                        <div id="imagePreview" class="row g-2" style="display: none;"></div>
                    </div>

                    <button type="submit" class="btn btn-alibaba-primary w-100" id="uploadBtn">
                        <i class="fas fa-upload me-2"></i>{{ __('Upload Images') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Gallery -->
    <div class="col-lg-8 mb-4">
        <div class="content-card">
            <div class="content-card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="content-card-title">{{ __('Image Gallery') }}</h5>
                    <select class="form-select form-select-sm" id="galleryFolder" style="width: auto;">
                        <option value="">{{ __('All Folders') }}</option>
                        @foreach($folders as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="content-card-body">
                <div id="imageGallery" class="row g-3">
                    <!-- Images will be loaded here -->
                </div>

                <div id="loadingSpinner" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                </div>

                <div id="noImages" class="text-center py-4 text-muted" style="display: none;">
                    <i class="fas fa-images fa-3x mb-3 opacity-50"></i>
                    <p>{{ __('No images found in this folder.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Delete Image') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this image? This action cannot be undone.') }}</p>
                <div class="text-center">
                    <img id="deleteImagePreview" src="" alt="" class="img-fluid rounded" style="max-height: 200px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    const imagesInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');
    const uploadBtn = document.getElementById('uploadBtn');
    const galleryFolder = document.getElementById('galleryFolder');
    const imageGallery = document.getElementById('imageGallery');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const noImages = document.getElementById('noImages');

    let deleteImageData = null;

    // Image preview functionality
    imagesInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        imagePreview.innerHTML = '';

        if (files.length > 0) {
            imagePreview.style.display = 'block';
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-6 col-md-4';
                        col.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-fluid rounded" style="height: 80px; width: 100%; object-fit: cover;">
                                <small class="text-muted d-block mt-1">${file.name}</small>
                            </div>
                        `;
                        imagePreview.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Form submission
    uploadForm.addEventListener('submit', function(e) {
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("Uploading...") }}';
    });

    // Load images for selected folder
    function loadImages(folder = '') {
        loadingSpinner.style.display = 'block';
        imageGallery.innerHTML = '';

        fetch(`{{ route("admin.images") }}?folder=${folder}`)
            .then(response => response.json())
            .then(data => {
                loadingSpinner.style.display = 'none';

                if (data.images && data.images.length > 0) {
                    noImages.style.display = 'none';
                    data.images.forEach(image => {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-6';
                        col.innerHTML = `
                            <div class="card h-100">
                                <div class="position-relative">
                                    <img src="${image.url}" class="card-img-top" alt="${image.name}" style="height: 150px; object-fit: cover;">
                                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-btn"
                                            data-folder="${data.folder}"
                                            data-filename="${image.name}"
                                            data-url="${image.url}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="card-body p-2">
                                    <p class="card-text small mb-1 text-truncate" title="${image.name}">${image.name}</p>
                                    <small class="text-muted">${formatFileSize(image.size)}</small>
                                </div>
                            </div>
                        `;
                        imageGallery.appendChild(col);
                    });
                } else {
                    noImages.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading images:', error);
                loadingSpinner.style.display = 'none';
                noImages.style.display = 'block';
            });
    }

    // Gallery folder change
    galleryFolder.addEventListener('change', function() {
        loadImages(this.value);
    });

    // Delete image functionality
    imageGallery.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
            const btn = e.target.classList.contains('delete-btn') ? e.target : e.target.closest('.delete-btn');
            deleteImageData = {
                folder: btn.dataset.folder,
                filename: btn.dataset.filename,
                url: btn.dataset.url
            };

            document.getElementById('deleteImagePreview').src = deleteImageData.url;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    });

    // Confirm delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (deleteImageData) {
            fetch('{{ route("admin.images.delete") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    folder: deleteImageData.folder,
                    filename: deleteImageData.filename
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    loadImages(galleryFolder.value);
                    showAlert('{{ __("Image deleted successfully!") }}', 'success');
                } else {
                    showAlert('{{ __("Failed to delete image.") }}', 'error');
                }
            })
            .catch(error => {
                console.error('Error deleting image:', error);
                showAlert('{{ __("An error occurred while deleting the image.") }}', 'error');
            });
        }
    });

    // Utility functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-alibaba success' : 'alert-alibaba error';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';

        const alert = document.createElement('div');
        alert.className = alertClass;
        alert.innerHTML = `
            <i class="fas fa-${icon}"></i>
            <div>${message}</div>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
        `;

        document.querySelector('.admin-main').insertBefore(alert, document.querySelector('.admin-main').firstChild);

        setTimeout(() => {
            if (alert.parentElement) {
                alert.remove();
            }
        }, 5000);
    }

    // Load all images initially
    loadImages();
});
</script>
@endpush

<style>
    .card-img-top {
        border-radius: 0.375rem 0.375rem 0 0;
    }

    .delete-btn {
        opacity: 0;
        transition: opacity 0.2s;
    }

    .card:hover .delete-btn {
        opacity: 1;
    }

    #imagePreview img {
        border: 2px solid #e9ecef;
    }
</style>
