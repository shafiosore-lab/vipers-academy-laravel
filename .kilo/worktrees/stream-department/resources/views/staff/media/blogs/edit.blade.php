@extends('layouts.staff')

@section('title', 'Edit Blog - Media Officer - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Edit Blog Post</h2>
                            <p class="mb-0">Update your blog article</p>
                        </div>
                        <a href="{{ route('media.blogs') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Back to Blogs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('media.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $blog->title) }}"
                                           placeholder="Enter blog title" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
                                        <option value="">Select a category</option>
                                        <option value="Academy News" {{ old('category', $blog->category) == 'Academy News' ? 'selected' : '' }}>Academy News</option>
                                        <option value="Match Reports" {{ old('category', $blog->category) == 'Match Reports' ? 'selected' : '' }}>Match Reports</option>
                                        <option value="Announcements" {{ old('category', $blog->category) == 'Announcements' ? 'selected' : '' }}>Announcements</option>
                                        <option value="Transfer News" {{ old('category', $blog->category) == 'Transfer News' ? 'selected' : '' }}>Transfer News</option>
                                        <option value="General" {{ old('category', $blog->category) == 'General' ? 'selected' : '' }}>General</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="15"
                                              placeholder="Write your blog content here..." required>{{ old('content', $blog->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Current Image -->
                                @if($blog->image)
                                <div class="mb-3">
                                    <label class="form-label">Current Image</label>
                                    <div class="border rounded p-2">
                                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>
                                @endif

                                <!-- Image -->
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ $blog->image ? 'Replace Image' : 'Featured Image' }}</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Max size: 2MB. Formats: jpeg, png, jpg, gif</small>

                                    <div class="mt-2" id="image-preview-container" style="display: none;">
                                        <img id="image-preview" src="#" alt="Image preview" class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                </div>

                                <!-- Publish Options -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="mb-0">Publishing</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="publish" name="publish" value="1"
                                                   {{ $blog->published_at ? 'checked' : '' }}>
                                            <label class="form-check-label" for="publish">
                                                Publish immediately
                                            </label>
                                        </div>
                                        @if($blog->published_at)
                                        <small class="text-muted d-block mt-2">
                                            Originally published: {{ $blog->published_at->format('M j, Y g:i A') }}
                                        </small>
                                        @endif
                                        <small class="text-muted d-block mt-2">
                                            If unchecked, the post will be saved as a draft.
                                        </small>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save"></i> Update Blog Post
                                    </button>
                                    <a href="{{ route('media.blogs') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>
@endsection
