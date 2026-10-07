@extends('layouts.admin')

@section('title', __('Role Requests'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Role Requests') }}</h1>
                    <p class="text-muted">{{ __('Manage role access requests from users') }}</p>
                </div>
                <a href="{{ route('super-admin.roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Roles') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Requested Role') }}</th>
                            <th>{{ __('Organization') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Requested At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        <tr>
                            <td>
                                @if($request->user)
                                <a href="#">{{ $request->user->name }}</a>
                                <small class="d-block text-muted">{{ $request->user->email }}</small>
                                @else
                                <span class="text-muted">Unknown User</span>
                                @endif
                            </td>
                            <td>{{ $request->requestedRole->name ?? 'Unknown Role' }}</td>
                            <td>
                                @if($request->organization)
                                {{ $request->organization->name }}
                                @else
                                <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                @switch($request->status)
                                    @case('pending')
                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                    @break
                                    @case('approved')
                                    <span class="badge bg-success">{{ __('Approved') }}</span>
                                    @break
                                    @case('rejected')
                                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                    @break
                                    @default
                                    <span class="badge bg-secondary">{{ $request->status }}</span>
                                @endswitch
                            </td>
                            <td>{{ $request->requested_at ? $request->requested_at->format('M d, Y H:i') : '-' }}</td>
                            <td>
                                @if($request->status === 'pending')
                                <div class="btn-group btn-group-sm">
                                    <form method="POST" action="{{ route('super-admin.roles.requests.approve', $request) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('super-admin.roles.requests.reject', $request) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">{{ __('No role requests found.') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
