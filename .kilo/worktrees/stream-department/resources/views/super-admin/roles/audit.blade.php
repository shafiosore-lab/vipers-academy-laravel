@extends('layouts.admin')

@section('title', __('Role Audit Logs'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Role Audit Logs') }}</h1>
                    <p class="text-muted">{{ __('Track all role-related activities') }}</p>
                </div>
                <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Roles') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="action" class="form-label">{{ __('Action Type') }}</label>
                    <select class="form-select" id="action" name="action">
                        <option value="">{{ __('All Actions') }}</option>
                        @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ __(ucwords(str_replace('_', ' ', $action))) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="user_id" class="form-label">{{ __('User') }}</label>
                    <input type="text" class="form-control" id="user_id" name="user_id" value="{{ request('user_id') }}" placeholder="{{ __('User ID') }}">
                </div>
                <div class="col-md-3">
                    <label for="role_id" class="form-label">{{ __('Role') }}</label>
                    <input type="text" class="form-control" id="role_id" name="role_id" value="{{ request('role_id') }}" placeholder="{{ __('Role ID') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>{{ __('Filter') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Action') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($log->user)
                                <a href="#">{{ $log->user->name }}</a>
                                @else
                                <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                @switch($log->action)
                                    @case('role_created')
                                    <span class="badge bg-success">{{ __('Created') }}</span>
                                    @break
                                    @case('role_updated')
                                    <span class="badge bg-info">{{ __('Updated') }}</span>
                                    @break
                                    @case('role_deleted')
                                    <span class="badge bg-danger">{{ __('Deleted') }}</span>
                                    @break
                                    @case('permission_changed')
                                    <span class="badge bg-warning">{{ __('Permissions Changed') }}</span>
                                    @break
                                    @case('role_assigned')
                                    <span class="badge bg-primary">{{ __('Role Assigned') }}</span>
                                    @break
                                    @case('role_removed')
                                    <span class="badge bg-secondary">{{ __('Role Removed') }}</span>
                                    @break
                                    @default
                                    <span class="badge bg-light text-dark">{{ $log->action }}</span>
                                @endswitch
                            </td>
                            <td>
                                @if($log->role)
                                <a href="{{ route('super-admin.roles.show', $log->role) }}">{{ $log->role->name }}</a>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#log-details-{{ $log->id }}">
                                    <i class="fas fa-eye"></i> {{ __('View') }}
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="p-0">
                                <div class="collapse" id="log-details-{{ $log->id }}">
                                    <div class="card card-body bg-light">
                                        <pre class="mb-0">{{ json_encode($log->old_values ?? [], JSON_PRETTY_PRINT) }}</pre>
                                        <hr>
                                        <pre class="mb-0">{{ json_encode($log->new_values ?? [], JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted mb-0">{{ __('No audit logs found.') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
