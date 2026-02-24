@extends('layouts.admin')

@section('title', 'Edit ' . ucfirst($section) . ' Section - ' . ucfirst($page))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-capitalize">{{ str_replace('_', ' ', $section) }} Section</h1>
            <p class="text-muted">Edit content for the {{ $page }} page</p>
        </div>
        <a href="{{ route('admin.page-content.show', $page) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Sections
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Journey Section Edit Form -->
    @if($section === 'journey')
        <form action="{{ route('admin.page-content.update', ['page' => $page, 'section' => $section]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-route text-primary me-2"></i>Timeline Entries</h5>
                </div>
                <div class="card-body">
                    <!-- Section Title -->
                    @php
                        $titleContent = $contents->firstWhere('key', 'title');
                    @endphp
                    @if($titleContent)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Section Title</label>
                            <input type="text" name="contents[{{ $titleContent->id }}][value]" value="{{ $titleContent->value }}" class="form-control">
                            <input type="hidden" name="contents[{{ $titleContent->id }}][id]" value="{{ $titleContent->id }}">
                        </div>
                    @endif

                    <!-- Timeline Entries -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Timeline Entries</label>
                        <p class="text-muted small">Edit the year and description for each milestone</p>
                    </div>

                    @foreach($contents->filter(fn($c) => str_starts_with($c->key, 'year_')) as $index => $entry)
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Year</label>
                                        <input type="text" class="form-control" value="{{ str_replace('year_', '', $entry->key) }}" readonly>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Description</label>
                                        <textarea name="contents[{{ $entry->id }}][value]" class="form-control" rows="2">{{ $entry->value }}</textarea>
                                        <input type="hidden" name="contents[{{ $entry->id }}][id]" value="{{ $entry->id }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <a href="{{ route('admin.page-content.journey.delete', $entry->id) }}"
                                           class="btn btn-outline-danger w-100"
                                           onclick="return confirm('Are you sure you want to delete this entry?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer bg-white">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </form>

        <!-- Add New Entry Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-plus text-success me-2"></i>Add New Timeline Entry</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.page-content.journey.add') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Year</label>
                                <input type="text" name="year" class="form-control" placeholder="e.g., 2025" required>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="1" placeholder="Describe this milestone..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3 d-grid">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Add Entry
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Values Section Edit Form -->
    @if($section === 'values')
        <form action="{{ route('admin.page-content.update', ['page' => $page, 'section' => $section]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-heart text-primary me-2"></i>Core Values</h5>
                </div>
                <div class="card-body">
                    <!-- Section Title -->
                    @php
                        $titleContent = $contents->firstWhere('key', 'title');
                    @endphp
                    @if($titleContent)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Section Title</label>
                            <input type="text" name="contents[{{ $titleContent->id }}][value]" value="{{ $titleContent->value }}" class="form-control">
                            <input type="hidden" name="contents[{{ $titleContent->id }}][id]" value="{{ $titleContent->id }}">
                        </div>
                    @endif

                    <!-- Values Entries -->
                    @foreach($contents->filter(fn($c) => !str_starts_with($c->key, 'title')) as $entry)
                        @php
                            $valueData = is_array(json_decode($entry->value, true)) ? json_decode($entry->value, true) : ['title' => $entry->key, 'description' => $entry->value, 'icon' => '⭐'];
                        @endphp
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Icon</label>
                                        <input type="text" name="contents[{{ $entry->id }}][icon]" value="{{ $valueData['icon'] ?? '⭐' }}" class="form-control" style="font-size: 1.5rem; text-align: center;">
                                        <input type="hidden" name="contents[{{ $entry->id }}][id]" value="{{ $entry->id }}">
                                        <input type="hidden" name="contents[{{ $entry->id }}][original_value]" value="{{ $entry->value }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="contents[{{ $entry->id }}][title]" value="{{ $valueData['title'] ?? '' }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Description</label>
                                        <textarea name="contents[{{ $entry->id }}][description]" class="form-control" rows="2">{{ $valueData['description'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <a href="{{ route('admin.page-content.values.delete', $entry->id) }}"
                                           class="btn btn-outline-danger w-100"
                                           onclick="return confirm('Are you sure you want to delete this value?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer bg-white">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </form>

        <!-- Add New Value Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-plus text-success me-2"></i>Add New Value</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.page-content.values.add') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Icon</label>
                                <input type="text" name="icon" class="form-control" placeholder="🎯" value="⭐" required>
                                <small class="text-muted">Emoji or icon</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g., Teamwork" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="1" placeholder="Describe this value..." required></textarea>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3 d-grid">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Add Value
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Generic Section Edit -->
    @if($section !== 'journey' && $section !== 'values')
        <form action="{{ route('admin.page-content.update', ['page' => $page, 'section' => $section]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Edit Content</h5>
                </div>
                <div class="card-body">
                    @foreach($contents as $content)
                        <div class="mb-3">
                            <label class="form-label text-capitalize">{{ str_replace('_', ' ', $content->key) }}</label>
                            @if($content->type === 'textarea')
                                <textarea name="contents[{{ $content->id }}][value]" class="form-control" rows="3">{{ $content->value }}</textarea>
                            @else
                                <input type="text" name="contents[{{ $content->id }}][value]" value="{{ $content->value }}" class="form-control">
                            @endif
                            <input type="hidden" name="contents[{{ $content->id }}][id]" value="{{ $content->id }}">
                        </div>
                    @endforeach
                </div>
                <div class="card-footer bg-white">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
