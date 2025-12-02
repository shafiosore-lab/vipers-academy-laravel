@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Football Matches</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Match
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Opponent</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Match Date</th>
                                    <th>Venue</th>
                                    <th>Score</th>
                                    <th>Images</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($matches as $match)
                                <tr>
                                    <td>
                                        <strong>{{ $match->opponent }}</strong>
                                        @if($match->tournament_name)
                                        <br><small class="text-muted">{{ $match->tournament_name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $match->type === 'friendly' ? 'success' : 'info' }}">
                                            {{ ucfirst($match->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $match->status === 'completed' ? 'success' : ($match->status === 'upcoming' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($match->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $match->match_date->format('M d, Y H:i') }}</td>
                                    <td>{{ $match->venue }}</td>
                                    <td>
                                        @if($match->status === 'completed')
                                            <strong>{{ $match->vipers_score ?? 0 }} - {{ $match->opponent_score ?? 0 }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($match->images && count($match->images) > 0)
                                            <span class="badge bg-success">{{ count($match->images) }} image(s)</span>
                                        @else
                                            <span class="badge bg-secondary">No images</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.matches.show', $match) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.matches.edit', $match) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this match?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-futbol fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No matches found</h5>
                                        <p class="text-muted">Start by adding your first football match.</p>
                                        <a href="{{ route('admin.matches.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add First Match
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($matches->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $matches->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
