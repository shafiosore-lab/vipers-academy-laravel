@extends('layouts.admin')

@section('title', 'Website Content Management - Super Admin')

@section('content')
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Website Content Management</h4>
            <p class="text-muted mb-0">Manage all website pages and their content</p>
        </div>
        <div>
            <a href="{{ route('super-admin.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Website Pages Cards -->
    <div class="row g-3 mb-4">
        <!-- Home Page -->
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('super-admin.page-content.show', 'home') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-opacity-10 rounded p-3">
                                    <i class="fas fa-home fa-lg text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">Home Page</h6>
                                <p class="small text-muted mb-0">Hero, Features, Stats</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- About Page -->
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('super-admin.page-content.show', 'about') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info bg-opacity-10 rounded p-3">
                                    <i class="fas fa-info-circle fa-lg text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">About Us</h6>
                                <p class="small text-muted mb-0">Story, Mission, Values</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Programs Page -->
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('super-admin.page-content.show', 'programs') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success bg-opacity-10 rounded p-3">
                                    <i class="fas fa-clipboard-list fa-lg text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">Programs</h6>
                                <p class="small text-muted mb-0">Training programs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Staff Page -->
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('super-admin.page-content.show', 'staff') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning bg-opacity-10 rounded p-3">
                                    <i class="fas fa-user-tie fa-lg text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">Staff</h6>
                                <p class="small text-muted mb-0">Coaching team</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Announcements Page -->
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('super-admin.page-content.show', 'announcements') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-danger bg-opacity-10 rounded p-3">
                                    <i class="fas fa-bullhorn fa-lg text-danger"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">Announcements</h6>
                                <p class="small text-muted mb-0">News & updates</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Careers Page -->
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('super-admin.page-content.show', 'careers') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-teal bg-opacity-10 rounded p-3" style="background-color: rgba(0, 206, 201, 0.1);">
                                    <i class="fas fa-briefcase fa-lg" style="color: #00cec9;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">Careers</h6>
                                <p class="small text-muted mb-0">Job openings</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Leaders Page -->
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('super-admin.page-content.show', 'leaders') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-gold bg-opacity-10 rounded p-3" style="background-color: rgba(253, 203, 110, 0.1);">
                                    <i class="fas fa-star fa-lg" style="color: #fdcb6e;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">Leaders</h6>
                                <p class="small text-muted mb-0">Leadership team</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Existing Database Pages (if any) -->
    @if($pages->count() > 0)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Additional Pages from Database</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Page Name</th>
                            <th class="px-3">Sections</th>
                            <th class="px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                        <tr>
                            <td class="px-3">
                                <strong class="text-capitalize">{{ $page }}</strong>
                            </td>
                            <td class="px-3">
                                <span class="badge bg-secondary">
                                    {{ \App\Models\PageContent::where('page', $page)->count() }} items
                                </span>
                            </td>
                            <td class="px-3">
                                <a href="{{ route('super-admin.page-content.show', $page) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Manage
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="row g-3 mt-4">
        <div class="col-12">
            <h5 class="mb-3">Quick Actions</h5>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.blog.index') }}" class="card text-center text-decoration-none border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <i class="fas fa-newspaper fa-lg text-primary mb-2"></i>
                    <h6 class="small mb-0">Manage Blog/News</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.jobs.index') }}" class="card text-center text-decoration-none border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <i class="fas fa-briefcase fa-lg text-success mb-2"></i>
                    <h6 class="small mb-0">Manage Jobs</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.leaders.index') }}" class="card text-center text-decoration-none border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <i class="fas fa-star fa-lg text-warning mb-2"></i>
                    <h6 class="small mb-0">Manage Leaders</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.partners.index') }}" class="card text-center text-decoration-none border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <i class="fas fa-handshake fa-lg text-purple mb-2"></i>
                    <h6 class="small mb-0">Manage Partners</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.website-players.index') }}" class="card text-center text-decoration-none border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <i class="fas fa-futbol fa-lg text-danger mb-2"></i>
                    <h6 class="small mb-0">Website Players</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.programs.index') }}" class="card text-center text-decoration-none border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <i class="fas fa-graduation-cap fa-lg text-info mb-2"></i>
                    <h6 class="small mb-0">Programs Management</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.staff.index') }}" class="card text-center text-decoration-none border-0 shadow-sm h-100">
                <div class="card-body py-3">
                    <i class="fas fa-users-cog fa-lg text-secondary mb-2"></i>
                    <h6 class="small mb-0">Staff Management</h6>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
