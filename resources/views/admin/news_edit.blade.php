@extends('layouts.admin')

@section('title', 'Edit News - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">Edit News Article</h4>
                            <small class="opacity-75">Update news article: {{ $news->title }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">News Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $news->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Enter an engaging title for the news article</div>
                                </div>

                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-control @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
                                        <option value="">Select a category</option>
                                        <option value="Academy News" {{ old('category', $news->category) == 'Academy News' ? 'selected' : '' }}>Academy News</option>
                                        <option value="Match Reports" {{ old('category', $news->category) == 'Match Reports' ? 'selected' : '' }}>Match Reports</option>
                                        <option value="Player Updates" {{ old('category', $news->category) == 'Player Updates' ? 'selected' : '' }}>Player Updates</option>
                                        <option value="Training Updates" {{ old('category', $news->category) == 'Training Updates' ? 'selected' : '' }}>Training Updates</option>
                                        <option value="Events" {{ old('category', $news->category) == 'Events' ? 'selected' : '' }}>Events</option>
                                        <option value="Announcements" {{ old('category', $news->category) == 'Announcements' ? 'selected' : '' }}>Announcements</option>
                                        <option value="Transfer News" {{ old('category', $news->category) == 'Transfer News' ? 'selected' : '' }}>Transfer News</option>
                                        <option value="Other" {{ old('category', $news->category) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select the appropriate category for this news article</div>
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Content *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="12" required>{{ old('content', $news->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Write the full news article content</div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-primary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-image me-2"></i>Featured Image</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload New Image</label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                   id="image" name="image" accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Optional: Upload a new featured image (JPG, PNG, GIF, max 2MB)</div>
                                        </div>

                                        @if($news->image)
                                            <div class="mb-3">
                                                <label class="form-label">Current Image</label>
                                                <div class="border rounded p-2">
                                                    <img src="{{ asset('storage/' . $news->image) }}" alt="Current Image"
                                                         class="img-fluid rounded" style="max-height: 200px;">
                                                </div>
                                                <small class="text-muted">Leave empty to keep current image</small>
                                            </div>
                                        @endif

                                        <div class="alert alert-primary">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                <strong>Image Guidelines:</strong><br>
                                                • Recommended size: 800x400px<br>
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
                                                I confirm that the updated news article is accurate and ready for publication
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="alert alert-primary py-2 px-3 mb-0 me-3">
                                    <small class="mb-0">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        All required fields marked with *
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Update News
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

.btn-primary {
    background: linear-gradient(45deg, #0d6efd, #4c63d2);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0b5ed7, #4158d0);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.alert-primary {
    background: linear-gradient(45deg, #cfe2ff, #b3d4fc);
    border: 1px solid #9ec5f8;
    color: #084298;
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

    // Character counter for content
    const contentTextarea = document.getElementById('content');
    const titleInput = document.getElementById('title');

    function updateCharCount() {
        const contentLength = contentTextarea.value.length;
        const titleLength = titleInput.value.length;

        // Update content counter
        let contentCounter = contentTextarea.parentNode.querySelector('.char-counter');
        if (!contentCounter) {
            contentCounter = document.createElement('small');
            contentCounter.className = 'char-counter form-text text-muted';
            contentTextarea.parentNode.appendChild(contentCounter);
        }
        contentCounter.textContent = `${contentLength} characters`;

        // Update title counter
        let titleCounter = titleInput.parentNode.querySelector('.char-counter');
        if (!titleCounter) {
            titleCounter = document.createElement('small');
            titleCounter.className = 'char-counter form-text text-muted';
            titleInput.parentNode.appendChild(titleCounter);
        }
        titleCounter.textContent = `${titleLength}/255 characters`;
    }

    contentTextarea.addEventListener('input', updateCharCount);
    titleInput.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
});
</script>
@endsection
