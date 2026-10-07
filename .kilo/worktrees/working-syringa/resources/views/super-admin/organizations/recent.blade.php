@extends('layouts.admin')

@section('title', 'Recent Organizations')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-0 text-gray-800">Recent Organizations</h1>
                    <p class="text-muted mb-0">Recently created organizations</p>
                </div>
                <div>
                    <a href="{{ route('super-admin.organizations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Organization
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="organizationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="dashboard-tab" href="{{ route('super-admin.organizations.dashboard') }}" role="tab" aria-selected="false">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="management-tab" href="{{ route('super-admin.organizations.index') }}" role="tab" aria-selected="false">
                        <i class="fas fa-cog"></i> Management
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="recent-tab" href="{{ route('super-admin.organizations.recent') }}" role="tab" aria-selected="true">
                        <i class="fas fa-clock"></i> Recent
                    </a>
                </li>
            </ul>

            <div class="card shadow mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Plan</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrganizations as $organization)
                                <tr>
                                    <td>
                                        <a href="{{ route('super-admin.organizations.show', $organization) }}" class="text-decoration-none">
                                            {{ $organization->name }}
                                        </a>
                                    </td>
                                    <td>{{ $organization->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $organization->status === 'active' ? 'success' : ($organization->status === 'trial' ? 'info' : 'warning') }}">
                                            {{ ucfirst($organization->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $organization->subscription && $organization->subscription->plan ? $organization->subscription->plan->name : 'No Plan' }}
                                    </td>
                                    <td>{{ $organization->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('super-admin.organizations.show', $organization) }}" class="btn btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('super-admin.organizations.edit', $organization) }}" class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No recent organizations found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
