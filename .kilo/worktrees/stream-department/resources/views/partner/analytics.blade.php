@extends('layouts.staff')

@section('title', 'Analytics - Partner Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Analytics</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('partner.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="text-white">Total Players</h5>
                    <h2 class="mb-0">{{ $analytics['total_players'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="text-white">Active Players</h5>
                    <h2 class="mb-0">{{ $analytics['active_players'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="text-white">Pending Approvals</h5>
                    <h2 class="mb-0">{{ $analytics['pending_approvals'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="text-white">Approved Players</h5>
                    <h2 class="mb-0">{{ $analytics['approved_players'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Players by Program</h5>
                </div>
                <div class="card-body">
                    @if($analytics['players_by_program']->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Program ID</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['players_by_program'] as $programId => $count)
                                <tr>
                                    <td>{{ $programId ?? 'Unassigned' }}</td>
                                    <td><span class="badge bg-primary">{{ $count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted text-center">No program data available</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Players by Age Group</h5>
                </div>
                <div class="card-body">
                    @if($analytics['players_by_age_group']->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Age Group</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['players_by_age_group'] as $ageGroup => $count)
                                <tr>
                                    <td>{{ $ageGroup ?? 'Unassigned' }}</td>
                                    <td><span class="badge bg-info">{{ $count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted text-center">No age group data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Registrations</h5>
                </div>
                <div class="card-body">
                    @if($analytics['recent_registrations']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Registration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['recent_registrations'] as $player)
                                    <tr>
                                        <td>{{ $player->full_name ?? 'N/A' }}</td>
                                        <td>
                                            @if($player->registration_status === 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($player->registration_status === 'Pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $player->registration_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $player->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent registrations</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
