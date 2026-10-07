@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Subscription Plans</h1>
        <a href="{{ route('super-admin.plans.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Plan
        </a>
    </div>

    <!-- Plans Grid -->
    <div class="row">
        @forelse($plans as $plan)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow h-100 {{ $plan->is_popular ? 'border-primary' : '' }}">
                @if($plan->is_popular)
                <div class="card-header bg-primary text-white text-center py-2">
                    <small>Most Popular</small>
                </div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h4 class="card-title">{{ $plan->name }}</h4>
                        <span class="badge bg-{{ $plan->is_active ? 'success' : 'secondary' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="text-muted">{{ $plan->description }}</p>

                    <div class="mb-3">
                        <span class="h2">KES {{ number_format($plan->price) }}</span>
                        <span class="text-muted">/ {{ $plan->billing_cycle }}</span>
                    </div>

                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-users text-success me-2"></i>
                            {{ $plan->max_users == -1 ? 'Unlimited' : $plan->max_users }} Users
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-running text-success me-2"></i>
                            {{ $plan->max_players == -1 ? 'Unlimited' : $plan->max_players }} Players
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-user-tie text-success me-2"></i>
                            {{ $plan->max_staff == -1 ? 'Unlimited' : $plan->max_staff }} Staff
                        </li>
                    </ul>

                    <!-- Permissions Summary -->
                    @php
                    $planPermissions = $plan->getPermissions();
                    @endphp
                    @if(count($planPermissions) > 0)
                    <div class="mt-3 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt"></i>
                            {{ count($planPermissions) }} permissions assigned
                        </small>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('super-admin.plans.edit', $plan) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($plan->subscriptions()->count() === 0)
                        <form action="{{ route('super-admin.plans.destroy', $plan) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this plan?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                        @else
                        <span class="text-muted small align-self-center">
                            <i class="fas fa-info-circle"></i> {{ $plan->subscriptions()->count() }} subscription(s)
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No subscription plans found.
                <a href="{{ route('super-admin.plans.create') }}">Create your first plan</a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
