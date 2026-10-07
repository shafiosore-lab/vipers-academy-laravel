@extends('layouts.admin')

@section('title', __('Role Templates'))

@section('content')
<div class="container-fluid py-2">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">{{ __('Role Templates') }}</h5>
            <small class="text-muted">{{ __('Pre-configured role configurations') }}</small>
        </div>
        <div>
            <a href="{{ route('super-admin.roles.index') }}" class="btn btn-sm btn-outline-secondary me-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('super-admin.roles.templates.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Create
            </a>
        </div>
    </div>

    <!-- Templates Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Slug') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Organizations') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td><code>{{ $template->slug }}</code></td>
                            <td>{{ $template->description }}</td>
                            <td>{{ $template->organization_id ? 1 : __('Public') }}</td>
                            <td>
                                @if($template->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td>{{ $template->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3">
                                <p class="text-muted mb-1">{{ __('No role templates found.') }}</p>
                                <a href="{{ route('super-admin.roles.templates.create') }}" class="btn btn-sm btn-link">
                                    {{ __('Create your first template') }}
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($templates->hasPages())
            <div class="card-footer py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $templates->firstItem() ?? 0 }} to {{ $templates->lastItem() ?? 0 }} of {{ $templates->total() }} templates
                    </div>
                    {{ $templates->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

