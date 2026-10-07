@extends('layouts.staff')

@section('title', 'Distribution Records - Team Manager')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list me-2"></i>Distribution Records</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#distributeEquipmentModal">
            <i class="fas fa-plus me-2"></i>Distribute Equipment
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Distributions</h6>
                            <h2 class="mb-0">{{ $totalDistributed }}</h2>
                        </div>
                        <i class="fas fa-clipboard-list fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Active Loans</h6>
                            <h2 class="mb-0">{{ $activeDistributions }}</h2>
                        </div>
                        <i class="fas fa-hand-holding fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Returned</h6>
                            <h2 class="mb-0">{{ $distributions->where('status', 'returned')->count() }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Equipment</th>
                            <th>Assigned To</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Assigned Date</th>
                            <th>Returned Date</th>
                            <th>Condition (Out)</th>
                            <th>Condition (In)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distributions as $distribution)
                        <tr>
                            <td>
                                <strong>{{ $distribution->equipment->name ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">{{ $distribution->equipment->category->name ?? '' }}</small>
                            </td>
                            <td>{{ $distribution->assigned_to_name }}</td>
                            <td>
                                <span class="badge bg-info">{{ $distribution->assigned_to_type }}</span>
                            </td>
                            <td>{{ $distribution->quantity }}</td>
                            <td>{{ $distribution->assigned_date->format('M d, Y') }}</td>
                            <td>
                                @if($distribution->returned_date)
                                {{ $distribution->returned_date->format('M d, Y') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $distribution->equipment ? $distribution->equipment->getConditionBadgeClass() : 'secondary' }}">
                                    {{ ucfirst($distribution->condition_when_assigned) }}
                                </span>
                            </td>
                            <td>
                                @if($distribution->condition_when_returned)
                                <span class="badge bg-{{ $distribution->equipment ? $distribution->equipment->getConditionBadgeClass() : 'secondary' }}">
                                    {{ ucfirst($distribution->condition_when_returned) }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $distribution->getStatusBadgeClass() }}">
                                    {{ ucfirst($distribution->status) }}
                                </span>
                            </td>
                            <td>
                                @if($distribution->status == 'active')
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#returnEquipmentModal{{ $distribution->id }}">
                                    <i class="fas fa-undo"></i> Return
                                </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Return Equipment Modal -->
                        <div class="modal fade" id="returnEquipmentModal{{ $distribution->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Return Equipment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('manager.equipment.distribution.return', $distribution) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Returning <strong>{{ $distribution->quantity }}x {{ $distribution->equipment->name }}</strong>
                                                to {{ $distribution->assigned_to_name }}
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Condition When Returned <span class="text-danger">*</span></label>
                                                <select name="condition_when_returned" class="form-select" required>
                                                    @foreach(\App\Models\Equipment::getConditionOptions() as $value => $label)
                                                    <option value="{{ $value }}" {{ $distribution->condition_when_assigned == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notes</label>
                                                <textarea name="notes" class="form-control" rows="3" placeholder="Any notes about the condition..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Confirm Return</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No distribution records found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $distributions->links() }}
        </div>
    </div>
</div>

<!-- Distribute Equipment Modal -->
<div class="modal fade" id="distributeEquipmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Distribute Equipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('manager.equipment.distribution.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Equipment <span class="text-danger">*</span></label>
                                <select name="equipment_id" class="form-select" required>
                                    <option value="">Select Equipment</option>
                                    @foreach(\App\Models\Equipment::available()->get() as $equipment)
                                    <option value="{{ $equipment->id }}">
                                        {{ $equipment->name }} ({{ $equipment->getAvailableQuantity() }} available) - {{ $equipment->category->name ?? '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Assign to Player</label>
                                <select name="player_id" class="form-select">
                                    <option value="">Select Player (Optional)</option>
                                    @foreach(\App\Models\Player::where('registration_status', 'Approved')->get() as $player)
                                    <option value="{{ $player->id }}">{{ $player->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Assign to Staff</label>
                                <select name="staff_id" class="form-select">
                                    <option value="">Select Staff (Optional)</option>
                                    @foreach(\App\Models\Staff::all() as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Team Name</label>
                        <input type="text" name="team_name" class="form-control" placeholder="e.g., U-15 Team A">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Assigned Date <span class="text-danger">*</span></label>
                                <input type="date" name="assigned_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Condition When Assigned <span class="text-danger">*</span></label>
                                <select name="condition" class="form-select" required>
                                    @foreach(\App\Models\Equipment::getConditionOptions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any special instructions..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Distribute Equipment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
