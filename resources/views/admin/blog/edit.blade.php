@extends('layouts.admin')
@section('title', 'Edit Blog Post - Admin Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit fa-lg me-3 text-warning"></i>
                    <div>
                        <h4 class="card-title mb-0">Edit Blog Post</h4>
                        <small class="opacity-75">Update blog post: {{ $blog->title }}</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Blog Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter an engaging title for the blog post</div>
                            </div>

                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                          id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">A short summary of the blog post (optional)</div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Blog Content *</label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content" name="content" rows="15" required>{{ old('content', $blog->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Write the full blog post content</div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                        id="category" name="category" required>
                                    <option value="">Select a category</option>
                                    <option value="Academy News" {{ old('category', $blog->category) == 'Academy News' ? 'selected' : '' }}>Academy News</option>
                                    <option value="Match Reports" {{ old('category', $blog->category) == 'Match Reports' ? 'selected' : '' }}>Match Reports</option>
                                    <option value="Player Updates" {{ old('category', $blog->category) == 'Player Updates' ? 'selected' : '' }}>Player Updates</option>
                                    <option value="Training Updates" {{ old('category', $blog->category) == 'Training Updates' ? 'selected' : '' }}>Training Updates</option>
                                    <option value="Events" {{ old('category', $blog->category) == 'Events' ? 'selected' : '' }}>Events</option>
                                    <option value="Announcements" {{ old('category', $blog->category) == 'Announcements' ? 'selected' : '' }}>Announcements</option>
                                    <option value="Transfer News" {{ old('category', $blog->category) == 'Transfer News' ? 'selected' : '' }}>Transfer News</option>
                                    <option value="General" {{ old('category', $blog->category) == 'General' ? 'selected' : '' }}>General</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Select the appropriate category for this blog post</div>
                            </div>

                            <div class="mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror"
                                       id="author" name="author" value="{{ old('author', $blog->author) }}">
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload a featured image (max 2MB)</div>

                                @if($blog->image)
                                    <div class="mt-2">
                                        <div class="border rounded p-2">
                                            <img src="{{ asset('storage/' . $blog->image) }}" alt="Current Image"
                                                 class="img-fluid rounded" style="max-height: 200px;">
                                            <div class="mt-2">
                                                <span class="badge bg-success">Current Image</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control @error('tags') is-invalid @enderror"
                                       id="tags" name="tags" value="{{ old('tags', is_array($blog->tags) ? implode(', ', $blog->tags) : $blog->tags) }}"
                                       placeholder="tag1, tag2, tag3">
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Comma-separated list of tags</div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="publish" name="publish" value="1" {{ $blog->published_at ? 'checked' : '' }}>
                                    <label class="form-check-label" for="publish">
                                        Publish this post
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="featured" name="is_featured" value="1" {{ $blog->is_featured ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured">
                                        Feature this post
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Update Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
