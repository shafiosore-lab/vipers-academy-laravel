@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Subscription Plan</h1>
        <a href="{{ route('super-admin.plans.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Plans
        </a>
    </div>

    <form method="POST" action="{{ route('super-admin.plans.update', $plan) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Plan Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Plan Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $plan->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug *</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="{{ $plan->slug }}" readonly>
                            <small class="text-muted">Slug cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $plan->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price (KES) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price', $plan->price) }}" min="0" step="0.01" required>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_cycle" class="form-label">Billing Cycle *</label>
                                <select class="form-select @error('billing_cycle') is-invalid @enderror"
                                        id="billing_cycle" name="billing_cycle" required>
                                    <option value="monthly" {{ $plan->billing_cycle === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ $plan->billing_cycle === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                                @error('billing_cycle')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Limits</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="max_users" class="form-label">Max Users</label>
                                <input type="number" class="form-control" id="max_users" name="max_users"
                                       value="{{ old('max_users', $plan->max_users) }}">
                                <small class="text-muted">Use -1 for unlimited</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="max_players" class="form-label">Max Players</label>
                                <input type="number" class="form-control" id="max_players" name="max_players"
                                       value="{{ old('max_players', $plan->max_players) }}">
                                <small class="text-muted">Use -1 for unlimited</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="max_staff" class="form-label">Max Staff</label>
                                <input type="number" class="form-control" id="max_staff" name="max_staff"
                                       value="{{ old('max_staff', $plan->max_staff) }}">
                                <small class="text-muted">Use -1 for unlimited</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Function Permissions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-shield-alt"></i> Function Permissions
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <p class="text-muted mb-3 px-3 pt-3">Select which functions this plan can access. Check the module to grant full access, or select individual permissions below.</p>

                        @php
                        $planPermissions = $plan->getPermissions();
                        @endphp

                        @if($permissions && count($permissions) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0" style="font-size: 0.85rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 180px;">Module</th>
                                            <th>Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $module => $modulePermissions)
                                        @php
                                        // Check if all permissions in this module are selected
                                        $modulePermIds = $modulePermissions->pluck('id')->map(function($id) { return (string) $id; })->toArray();
                                        $selectedInModule = array_intersect($modulePermIds, $planPermissions);
                                        $allSelected = count($modulePermIds) > 0 && count($selectedInModule) === count($modulePermIds);
                                        @endphp
                                        <tr>
                                            <td class="align-middle">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                           class="form-check-input module-check"
                                                           data-module="{{ $module }}"
                                                           id="module_{{ $loop->index }}"
                                                           {{ $allSelected ? 'checked' : '' }}>
                                                    <label for="module_{{ $loop->index }}" class="form-check-label fw-bold">{{ ucfirst($module) }}</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($modulePermissions as $permission)
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox"
                                                               class="form-check-input permission-check"
                                                               id="perm_{{ $permission->id }}"
                                                               name="permissions[{{ $permission->id }}]"
                                                               data-module="{{ $module }}"
                                                               {{ in_array((string)$permission->id, $planPermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning m-3">
                                <i class="fas fa-exclamation-triangle"></i> No permissions found. Please run the roles audit script to create permissions.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Settings --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                   value="1" {{ $plan->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_popular" name="is_popular"
                                   value="1" {{ $plan->is_popular ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_popular">
                                Mark as Popular
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Update Plan
                    </button>
                    <a href="{{ route('super-admin.plans.index') }}" class="btn btn-secondary btn-lg ms-2">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize module checkboxes state based on current selections
    document.querySelectorAll('.module-check').forEach(function(moduleCheckbox) {
        const module = moduleCheckbox.dataset.module;
        const allPermsInModule = document.querySelectorAll(`.permission-check[data-module="${module}"]`);
        const checkedPerms = document.querySelectorAll(`.permission-check[data-module="${module}"]:checked`);

        if (allPermsInModule.length === checkedPerms.length && checkedPerms.length > 0) {
            moduleCheckbox.checked = true;
            moduleCheckbox.indeterminate = false;
        } else if (checkedPerms.length > 0) {
            moduleCheckbox.checked = false;
            moduleCheckbox.indeterminate = true;
        }

        // Handle module checkbox change
        moduleCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;

            document.querySelectorAll(`.permission-check[data-module="${module}"]`).forEach(function(permCheck) {
                permCheck.checked = isChecked;
            });
            this.indeterminate = false;
        });
    });

    // Handle individual permission checkbox - update module checkbox state
    document.querySelectorAll('.permission-check').forEach(function(permCheck) {
        permCheck.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckbox = document.querySelector(`.module-check[data-module="${module}"]`);
            const allPermsInModule = document.querySelectorAll(`.permission-check[data-module="${module}"]`);
            const checkedPerms = document.querySelectorAll(`.permission-check[data-module="${module}"]:checked`);

            if (allPermsInModule.length === checkedPerms.length) {
                moduleCheckbox.checked = true;
                moduleCheckbox.indeterminate = false;
            } else if (checkedPerms.length > 0) {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = true;
            } else {
                moduleCheckbox.checked = false;
                moduleCheckbox.indeterminate = false;
            }
        });
    });
});
</script>
@endpush
@endsection
