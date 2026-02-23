@extends('layouts.admin')

@section('title', __('Dashboard - Vipers Academy Admin'))

@section('breadcrumb')
<nav aria-label="{{ __('Breadcrumb navigation') }}">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-th-large me-1" aria-hidden="true"></i>{{ __('Dashboard') }}
        </li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Welcome Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                        <p class="mb-0 opacity-75">Here's what's happening with your academy today</p>
                    </div>
                    <div class="text-end">
                        <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                        <p class="mb-0">{{ now()->format('g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Compact Analytics Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            @include('components.compact-analytics')
        </div>
    </div>



<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">⚡ Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.players.create') }}" class="btn btn-outline-primary w-100">
                            <div class="mb-1">👤</div>
                            <small>Add Player</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.programs.create') }}" class="btn btn-outline-success w-100">
                            <div class="mb-1">🎯</div>
                            <small>Add Program</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-warning w-100">
                            <div class="mb-1">📄</div>
                            <small>Documents</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.blog.create') }}" class="btn btn-outline-info w-100">
                            <div class="mb-1">📰</div>
                            <small>Add Blog</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.website-players.create') }}" class="btn btn-outline-secondary w-100">
                            <div class="mb-1">🖼️</div>
                            <small>Website Players</small>
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.gallery.create') }}" class="btn btn-outline-dark w-100">
                            <div class="mb-1">📸</div>
                            <small>Add Gallery</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status Row -->
<div class="row g-4">
    <!-- FIFA Compliance -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">⚽ FIFA Compliance Status</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="p-3 border rounded">
                            <div class="h2 mb-0 text-success">✅</div>
                            <div class="fw-bold">{{ \App\Models\Player::whereNotNull('fifa_registration_number')->count() }}</div>
                            <small class="text-muted">FIFA Registered</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded">
                            <div class="h2 mb-0 text-warning">🛡️</div>
                            <div class="fw-bold">{{ \App\Models\Player::where('safeguarding_policy_acknowledged', true)->count() }}</div>
                            <small class="text-muted">Safeguarding</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Health -->
    <div class="col-md-6">
        <div class="card h-100 bg-success text-white">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">💚 System Health</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-3">
                        <div class="p-2">
                            <div class="h4 mb-0">🟢</div>
                            <small>Database</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2">
                            <div class="h4 mb-0">🟢</div>
                            <small>Storage</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2">
                            <div class="h4 mb-0">🟢</div>
                            <small>Performance</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2">
                            <div class="h4 mb-0">🟢</div>
                            <small>API</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
