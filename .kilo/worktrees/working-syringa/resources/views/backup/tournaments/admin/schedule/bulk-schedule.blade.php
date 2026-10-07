@extends('layouts.admin')

@section('title', 'Manual Schedule - ' . $tournament->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-pencil-square"></i> Manual Schedule
            </h1>
            <p class="text-muted mb-0">{{ $tournament->name }}</p>
        </div>
        <div>
            <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Schedule
            </a>
        </div>
    </div>

    <!-- Instructions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="bi bi-info-circle"></i> Instructions</h5>
                <p class="mb-0">Select matches below and assign their date, time, and venue. You can also use the "Auto-fill Available Slots" button to automatically assign the first available slot to each unscheduled match.</p>
            </div>
        </div>
    </div>

    <!-- Match List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Matches to Schedule ({{ $unscheduledMatches->count() }} unscheduled)</h5>
                    <button type="button" class="btn btn-success" id="autoFillBtn">
                        <i class="bi bi-magic"></i> Auto-fill Available Slots
                    </button>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tournaments.schedule.bulk-schedule.save', $tournament->id) }}" method="POST" id="scheduleForm">
                        @csrf

                        @if($unscheduledMatches->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover" id="matchesTable">
                                    <thead>
                                        <tr>
                                            <th>Match #</th>
                                            <th>Home Team</th>
                                            <th>Away Team</th>
                                            <th>Pool</th>
                                            <th>Round</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Venue</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($unscheduledMatches as $index => $match)
                                        <tr data-match-id="{{ $match->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <span class="team-home">{{ $match->homeTeam->name ?? 'TBD' }}</span>
                                                <input type="hidden" name="matches[{{ $index }}][match_id]" value="{{ $match->id }}">
                                            </td>
                                            <td>
                                                <span class="team-away">{{ $match->awayTeam->name ?? 'TBD' }}</span>
                                            </td>
                                            <td>{{ $match->pool ? $match->pool->name : '-' }}</td>
                                            <td>{{ $match->round ?? 'League' }}</td>
                                            <td>
                                                <input type="date" class="form-control form-control-sm match-date"
                                                       name="matches[{{ $index }}][match_date]"
                                                       value="{{ $match->match_date ? \Carbon\Carbon::parse($match->match_date)->format('Y-m-d') : '' }}"
                                                       min="{{ $tournament->start_date ? \Carbon\Carbon::parse($tournament->start_date)->format('Y-m-d') : '' }}"
                                                       max="{{ $tournament->end_date ? \Carbon\Carbon::parse($tournament->end_date)->format('Y-m-d') : '' }}">
                                            </td>
                                            <td>
                                                <input type="time" class="form-control form-control-sm match-time"
                                                       name="matches[{{ $index }}][start_time]"
                                                       value="{{ $match->start_time ? \Carbon\Carbon::parse($match->start_time)->format('H:i') : '' }}">
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm match-venue"
                                                        name="matches[{{ $index }}][venue_id]">
                                                    <option value="">Select Venue</option>
                                                    @foreach($tournament->venues as $venue)
                                                        <option value="{{ $venue->id }}" {{ $match->venue_id == $venue->id ? 'selected' : '' }}>
                                                            {{ $venue->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @if($match->match_date && $match->start_time && $match->venue_id)
                                                    <span class="badge bg-success">Scheduled</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Schedule
                                </button>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle display-1 text-success"></i>
                                <h4 class="mt-3">All Matches Scheduled!</h4>
                                <p class="text-muted">All matches have been scheduled. You can edit them individually from the matches list.</p>
                                <a href="{{ route('admin.tournaments.schedule.index', $tournament->id) }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-arrow-left"></i> Back to Schedule
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Slots Reference -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> Available Time Slots Reference</h5>
                </div>
                <div class="card-body">
                    @if(count($availableSlots) > 0)
                        <div class="table-responsive" style="max-height: 300px;">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Venue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableSlots as $slot)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($slot['date'])->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($slot['date'])->format('l') }}</td>
                                        <td>{{ $slot['time'] }}</td>
                                        <td>{{ $slot['venue'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No available time slots. Please check your tournament configuration.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const autoFillBtn = document.getElementById('autoFillBtn');
    const availableSlots = @json($availableSlots);

    autoFillBtn.addEventListener('click', function() {
        const rows = document.querySelectorAll('#matchesTable tbody tr');
        let slotIndex = 0;

        rows.forEach(function(row) {
            const dateInput = row.querySelector('.match-date');
            const timeInput = row.querySelector('.match-time');
            const venueSelect = row.querySelector('.match-venue');
            const statusBadge = row.querySelector('.badge');

            // Skip if already fully scheduled
            if (dateInput.value && timeInput.value && venueSelect.value) {
                return;
            }

            // Find next available slot
            while (slotIndex < availableSlots.length) {
                const slot = availableSlots[slotIndex];
                slotIndex++;

                // Check if venue exists in select
                const venueOption = Array.from(venueSelect.options).find(opt => opt.text === slot.venue);

                if (venueOption) {
                    dateInput.value = slot.date;
                    timeInput.value = slot.time;
                    venueSelect.value = venueOption.value;
                    statusBadge.className = 'badge bg-success';
                    statusBadge.textContent = 'Scheduled';
                    break;
                }
            }
        });

        if (slotIndex >= availableSlots.length && document.querySelectorAll('.badge.bg-warning').length > 0) {
            alert('No more available slots. Some matches could not be scheduled.');
        }
    });
});
</script>
@endpush
@endsection
