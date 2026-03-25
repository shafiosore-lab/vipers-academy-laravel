@extends('layouts.admin')

@section('title', 'Sections - Website Content')

@section('content')
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-capitalize">{{ $page }}</h4>
            <p class="text-muted mb-0">Manage sections for this page</p>
        </div>
        <div>
            <a href="{{ route('super-admin.page-content.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Organization Filter -->
    <div class="mb-3">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="organization_id" class="form-select form-select-sm">
                    <option value="">All Organizations</option>
                    @foreach(\App\Models\Organization::active()->orderBy('name')->pluck('name', 'id') as $id => $name)
                        <option value="{{ $id }}" {{ request('organization_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            </div>
        </form>
    </div>

    <!-- Sections List -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Sections</h5>
        </div>
        <div class="card-body">
            @forelse($sections as $sectionName => $items)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <strong class="text-capitalize">{{ $sectionName }}</strong>
                        <span class="badge bg-secondary ms-2">{{ $items->count() }} items</span>
                    </div>
                    <a href="{{ route('super-admin.page-content.edit', ['page' => $page, 'section' => $sectionName]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                    <h5>No Sections Found</h5>
                    <p class="text-muted">Sections will appear here once content is added.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
