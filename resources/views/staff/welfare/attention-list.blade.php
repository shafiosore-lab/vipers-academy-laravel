@extends('layouts.staff')

@section('title', 'Attention List - Welfare Officer - Vipers Academy')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0">Players Requiring Attention</h2>
                            <p class="mb-0">Players flagged for welfare concerns or overdue follow-ups</p>
                        </div>
                        <a href="{{ route('welfare.dashboard') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select class="form-select" id="filterType">
                                <option value="">All Players</option>
                                <option value="needs_attention">Needs Attention</option>
                                <option value="overdue">Overdue Follow-ups</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterAgeGroup">
                                <option value="">All Age Groups</option>
                                <option value="U8">U8</option>
                                <option value="U10">U10</option>
                                <option value="U12">U12</option>
                                <option value="U14">U14</option>
                                <option value="U16">U16</option>
                                <option value="U18">U18</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Search by name..." id="searchPlayer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Players Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($players) && $players->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Player Name</th>
                                        <th>Age Group</th>
                                        <th>Status</th>
                                        <th>Last Follow-up</th>
                                        <th>Days Overdue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($players as $player)
                                        <tr>
                                            <td>
                                                <strong>{{ $player->first_name }} {{ $player->last_name }}</strong>
                                            </td>
                                            <td>{{ $player->age_group ?? 'N/A' }}</td>
                                            <td>
                                                @if($player->needs_attention)
                                                    <span class="badge bg-danger">Needs Attention</span>
                                                @else
                                                    <span class="badge bg-success">Normal</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $player->last_follow_up ? $player->last_follow_up->format('M j, Y') : 'Never' }}
                                            </td>
                                            <td>
                                                @if($player->last_follow_up)
                                                    {{ now()->diffInDays($player->last_follow_up) > 30 ? now()->diffInDays($player->last_follow_up) - 30 : 0 }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('players.show', $player->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <button class="btn btn-sm btn-success" onclick="markFollowUp({{ $player->id }})">
                                                    <i class="fas fa-check"></i> Follow-up
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $players->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success display-1"></i>
                            <h3 class="mt-3">No Players Requiring Attention</h3>
                            <p class="text-muted">All players are in good standing.</p>
                            <a href="{{ route('welfare.dashboard') }}" class="btn btn-primary mt-2">
                                Return to Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
function markFollowUp(playerId) {
    if(confirm('Mark follow-up as completed for this player?')) {
        fetch('/welfare/player/' + playerId + '/follow-up', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endsection
