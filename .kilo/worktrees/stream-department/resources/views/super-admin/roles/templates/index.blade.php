@extends('layouts.admin')

@section('title', __('Role Templates'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Role Templates') }}</h1>
                    <p class="text-muted">{{ __('Pre-configured role configurations for quick deployment') }}</p>
                </div>
                <div>
                    <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Roles') }}
                    </a>
                    <a href="{{ route('super-admin.roles.templates.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>{{ __('Create Template') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
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
                                    <button class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">{{ __('No role templates found.') }}</p>
                                <a href="{{ route('super-admin.roles.templates.create') }}" class="btn btn-link">
                                    {{ __('Create your first template') }}
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $templates->links() }}
        </div>
    </div>
</div>
@endsection
