@extends('layouts.admin')

@section('title', __('Module Permissions'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Module Permissions') }}</h1>
                    <p class="text-muted">{{ __('Manage permission modules and their actions') }}</p>
                </div>
                <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Roles') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Create Module Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>{{ __('Add New Module') }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('super-admin.roles.modules.store') }}" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label for="module" class="form-label">{{ __('Module Name') }}</label>
                    <input type="text" class="form-control" id="module" name="module" placeholder="e.g., players" required>
                </div>
                <div class="col-md-3">
                    <label for="name" class="form-label">{{ __('Display Name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="e.g., Players Management" required>
                </div>
                <div class="col-md-4">
                    <label for="actions" class="form-label">{{ __('Actions (comma separated)') }}</label>
                    <input type="text" class="form-control" id="actions" name="actions" placeholder="e.g., view,create,edit,delete" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>{{ __('Add') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modules Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Module') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Actions') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modules as $module)
                        <tr>
                            <td><code>{{ $module->module }}</code></td>
                            <td>{{ $module->name }}</td>
                            <td>{{ $module->description ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ is_array($module->actions) ? count($module->actions) : 0 }}</span>
                                {{ __('actions') }}
                            </td>
                            <td>
                                @if($module->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted mb-0">{{ __('No module permissions found.') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
