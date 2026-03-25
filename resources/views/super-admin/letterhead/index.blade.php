@extends('layouts.admin')

@section('title', 'Letterhead Management - Super Admin')

@section('content')
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0"><i class="fas fa-file-signature"></i> Letterhead Management</h5>
            <small class="text-muted">Manage letterhead templates across all organizations</small>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- No Organizations State -->
    @if($organizations->count() === 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-building fa-3x text-muted mb-3"></i>
            <h5>No Organizations Found</h5>
            <p class="text-muted mb-3">You need to create an organization before you can manage letterheads.</p>
            <a href="{{ route('super-admin.organizations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Organization
            </a>
        </div>
    </div>
    @else
    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-building text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Organizations</div>
                            <div class="fs-4 fw-bold">{{ $organizations->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-file-signature text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Letterheads</div>
                            <div class="fs-4 fw-bold">{{ $organizations->sum(function($org) { return $org->letterheads->count(); }) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-check-circle text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">With Default</div>
                            <div class="fs-4 fw-bold">{{ $organizations->filter(function($org) { return $org->letterheads->where('is_default', true)->count() > 0; })->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-exclamation-circle text-danger fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Without Letterhead</div>
                            <div class="fs-4 fw-bold">{{ $organizations->filter(function($org) { return $org->letterheads->count() == 0; })->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organization Filter -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('super-admin.letterhead.index') }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Select Organization</label>
                </div>
                <div class="col-md-6">
                    <select name="organization_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Organizations --</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(request('organization_id'))
                <div class="col-md-2">
                    <a href="{{ route('super-admin.letterhead.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Organizations Overview -->
    <div class="row">
        @foreach($organizations as $organization)
        @if(!request('organization_id') || request('organization_id') == $organization->id)
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            @if($organization->logo)
                            <img src="{{ asset('storage/' . $organization->logo) }}" alt="Logo" style="width: 40px; height: 40px; object-fit: contain; border-radius: 4px;" class="me-3">
                            @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; border-radius: 4px;">
                                <i class="fas fa-building"></i>
                            </div>
                            @endif
                            <div>
                                <h6 class="mb-0">{{ $organization->name }}</h6>
                                <small class="text-muted">{{ $organization->email }}</small>
                            </div>
                        </div>
                        <div>
                            @if($organization->letterheads->where('is_default', true)->count() > 0)
                            <span class="badge bg-success"><i class="fas fa-check"></i> Configured</span>
                            @else
                            <span class="badge bg-warning"><i class="fas fa-exclamation"></i> Needs Setup</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($organization->letterheads->count() > 0)
                    <div class="mb-3">
                        <small class="text-muted fw-semibold">Letterhead Templates ({{ $organization->letterheads->count() }})</small>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($organization->letterheads->take(3) as $lh)
                        <div class="border rounded px-2 py-1 d-flex align-items-center" style="font-size: 12px;">
                            <span style="width: 12px; height: 12px; background-color: {{ $lh->primary_color }}; border-radius: 2px; margin-right: 6px;"></span>
                            {{ $lh->name }}
                            @if($lh->is_default)
                            <span class="badge bg-primary ms-1" style="font-size: 10px;">Default</span>
                            @endif
                        </div>
                        @endforeach
                        @if($organization->letterheads->count() > 3)
                        <span class="text-muted small align-self-center">+{{ $organization->letterheads->count() - 3 }} more</span>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('super-admin.letterhead.index', ['organization_id' => $organization->id]) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> View/Manage
                        </a>
                        <a href="{{ route('super-admin.letterhead.create', ['organization_id' => $organization->id]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                        <p class="mb-2 small">No letterhead templates</p>
                        <a href="{{ route('super-admin.letterhead.create', ['organization_id' => $organization->id]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Create First Letterhead
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <!-- Help Section -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-light py-3">
            <h6 class="mb-0"><i class="fas fa-question-circle"></i> Getting Started with Letterheads</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6><i class="fas fa-step-forward text-primary"></i> 1. Select Organization</h6>
                    <p class="text-muted small">Choose an organization from the dropdown above or click "View/Manage" on any organization card.</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-plus-circle text-success"></i> 2. Create Letterhead</h6>
                    <p class="text-muted small">Click "New Letterhead" to create a template. Choose from classic, modern, or minimal styles.</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-star text-warning"></i> 3. Set Default</h6>
                    <p class="text-muted small">Mark one letterhead as "Default" to use it automatically when generating documents.</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-top">
                <a href="{{ route('super-admin.organizations.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-building"></i> Manage Organizations
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
