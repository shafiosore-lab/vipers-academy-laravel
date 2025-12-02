@extends('layouts.admin')

@section('title', 'News Details - ' . $news->title . ' - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-newspaper fa-lg me-3"></i>
                        <div>
                            <h4 class="card-title mb-0">News Details</h4>
                            <small class="opacity-75">View complete news article</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to News
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- News Header -->
                    <div class="row mb-4">
                        <div class="col-lg-8">
                            <h1 class="display-5 fw-bold text-primary mb-3">{{ $news->title }}</h1>
                            <div class="d-flex align-items-center text-muted mb-4">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span>Published on {{ $news->created_at->format('F j, Y \a\t g:i A') }}</span>
                                <span class="mx-3">•</span>
                                <i class="fas fa-clock me-2"></i>
                                <span>Last updated {{ $news->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 text-end">
                            @if($news->image)
                                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}"
                                     class="img-fluid rounded shadow" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="height: 200px; width: 100%;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- News Content -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-4">
                                    <div class="news-content">
                                        {!! nl2br(e($news->content)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Edit News
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this news article?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-2"></i>Delete News
                                        </button>
                                    </form>
                                </div>
                                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to All News
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .news-content {
        line-height: 1.8;
        font-size: 1.1rem;
        color: #333;
    }

    .news-content p {
        margin-bottom: 1.5rem;
    }

    .card-header .btn {
        border-radius: 20px;
    }

    .display-5 {
        line-height: 1.2;
    }
</style>
