@extends('layouts.staff')

@section('title', 'Sponsor Compliance - Team Manager')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-contract me-2"></i>Sponsor Compliance Report</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateReportModal">
            <i class="fas fa-file-export me-2"></i>Generate Report
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Sponsored Items</h6>
                            <h2 class="mb-0">{{ $totalSponsored }}</h2>
                        </div>
                        <i class="fas fa-handshake fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Compliant</h6>
                            <h2 class="mb-0">{{ $compliant }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Non-Compliant</h6>
                            <h2 class="mb-0">{{ $nonCompliant }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsors Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Sponsors Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($sponsors as $sponsor)
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3 text-center">
                                <i class="fas fa-handshake fa-2x text-primary mb-2"></i>
                                <h6>{{ $sponsor }}</h6>
                                <span class="badge bg-primary">
                                    {{ \App\Models\Equipment::where('sponsor', $sponsor)->count() }} items
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-3">
                            <p class="text-muted">No sponsors registered</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsored Equipment Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Sponsored Equipment List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Equipment Name</th>
                            <th>Category</th>
                            <th>Sponsor</th>
                            <th>Quantity</th>
                            <th>Purchase Date</th>
                            <th>Purchase Price</th>
                            <th>Compliance Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsoredItems as $item)
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $item->sponsor }}</span>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                @if($item->purchase_date)
                                {{ $item->purchase_date->format('M d, Y') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->purchase_price)
                                ${{ number_format($item->purchase_price, 2) }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->sponsor_compliant)
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Compliant
                                </span>
                                @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>Non-Compliant
                                </span>
                                @endif
                            </td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No sponsored equipment found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $sponsoredItems->links() }}
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div class="modal fade" id="generateReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Compliance Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('manager.equipment.compliance.report') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Filter by Sponsor</label>
                        <select name="sponsor" class="form-select">
                            <option value="">All Sponsors</option>
                            @foreach($sponsors as $sponsor)
                            <option value="{{ $sponsor }}">{{ $sponsor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        The report will show all sponsored equipment with compliance status based on the selected filters.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-export me-2"></i>Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
