@extends('layouts.staff')

@section('title', 'Welfare Officer Dashboard - Vipers Academy')

@section('content')
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Welfare Officer Dashboard</h2>
                            <p class="mb-0">Welcome back, {{ auth()->user()->name }}!</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0">{{ now()->format('l, F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-primary">{{ $totalPlayers ?? 0 }}</div>
                    <p class="text-muted mb-0">Total Players</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-danger">{{ $playersNeedingAttention ?? 0 }}</div>
                    <p class="text-muted mb-0">Need Attention</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-warning">{{ $overdueFollowUps ?? 0 }}</div>
                    <p class="text-muted mb-0">Overdue Follow-ups</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="display-4 text-info">{{ $expiringDocuments ?? 0 }}</div>
                    <p class="text-muted mb-0">Expiring Documents</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Players by Academic -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Players by Academic Performance</h5>
                </div>
                <div class="card-body">
                    @if(isset($playersByAcademic) && $playersByAcademic->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($playersByAcademic as $academic)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ ucfirst($academic->academic_performance ?? 'N/A') }}</span>
                                    <span class="badge bg-primary">{{ $academic->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No academic data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Compliance Status -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Document Compliance</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <div class="display-4 text-success">{{ $totalPlayers - $pendingDocuments }}</div>
                        <p class="text-muted">Compliant Players</p>
                        <hr>
                        <div class="display-4 text-warning">{{ $pendingDocuments }}</div>
                        <p class="text-muted">Pending Documents</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('welfare.attention.list') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-clipboard-list mb-2 d-block"></i>
                                Attention List
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('welfare.compliance') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-medical mb-2 d-block"></i>
                                Review Documents
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('welfare.attention.list') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-user-check mb-2 d-block"></i>
                                Follow-ups
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-info w-100">
                                <i class="fas fa-shield-alt mb-2 d-block"></i>
                                Safeguarding
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
