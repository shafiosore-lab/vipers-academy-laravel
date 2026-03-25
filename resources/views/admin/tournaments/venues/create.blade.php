@extends('layouts.admin')

@section('title', 'Add Venue - ' . $tournament->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Add New Venue</h1>
                    <p class="text-muted">{{ $tournament->name }}</p>
                </div>
                <div>
                    <a href="{{ route('tournaments.venues.index', $tournament) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Venues
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('tournaments.venues.store', $tournament) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Venue Name *</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text"
                                       class="form-control @error('city') is-invalid @enderror"
                                       id="city"
                                       name="city"
                                       value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text"
                                   class="form-control @error('address') is-invalid @enderror"
                                   id="address"
                                   name="address"
                                   value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Capacity</label>
                                <input type="number"
                                       class="form-control @error('capacity') is-invalid @enderror"
                                       id="capacity"
                                       name="capacity"
                                       value="{{ old('capacity') }}"
                                       min="0">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="surface_type" class="form-label">Surface Type</label>
                                <select class="form-select @error('surface_type') is-invalid @enderror"
                                        id="surface_type"
                                        name="surface_type">
                                    <option value="">Select Surface</option>
                                    <option value="Grass" {{ old('surface_type') == 'Grass' ? 'selected' : '' }}>Natural Grass</option>
                                    <option value="Artificial Turf" {{ old('surface_type') == 'Artificial Turf' ? 'selected' : '' }}>Artificial Turf</option>
                                    <option value="Hybrid" {{ old('surface_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    <option value="Indoor" {{ old('surface_type') == 'Indoor' ? 'selected' : '' }}>Indoor</option>
                                    <option value="Hard Court" {{ old('surface_type') == 'Hard Court' ? 'selected' : '' }}>Hard Court</option>
                                </select>
                                @error('surface_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (available for scheduling)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tournaments.venues.index', $tournament) }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Venue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
