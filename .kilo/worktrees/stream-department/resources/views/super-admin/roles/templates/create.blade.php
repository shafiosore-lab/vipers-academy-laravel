@extends('layouts.admin')

@section('title', __('Create Role Template'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Create Role Template') }}</h1>
                    <p class="text-muted">{{ __('Create a reusable role configuration template') }}</p>
                </div>
                <a href="{{ route('super-admin.roles.templates.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Templates') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>{{ __('Template Details') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('super-admin.roles.templates.store') }}">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('Template Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="form-label">{{ __('Slug') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                       id="slug" name="slug" value="{{ old('slug') }}" required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    {{ __('Public Template') }}
                                </label>
                                <div class="form-text">{{ __('Public templates can be used by all organizations') }}</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('super-admin.roles.templates.index') }}" class="btn btn-outline-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Create Template') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-info"></i>{{ __('Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>{{ __('Role Templates') }}</h6>
                        <p class="mb-0 small">{{ __('Templates allow you to pre-configure role settings that can be quickly deployed across organizations.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
