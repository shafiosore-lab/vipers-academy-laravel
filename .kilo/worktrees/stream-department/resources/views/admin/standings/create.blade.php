@extends('layouts.admin')

@section('title', 'Add New Standings')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Add New {{ ucfirst($type) }} Entry
                    </h4>
                </div>
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" class="text-white">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.standings.index', ['type' => $type]) }}" class="text-white">Standings</a>
                            </li>
                            <li class="breadcrumb-item active text-light" aria-current="page">Add New</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($type === 'league')
                        @include('admin.standings.forms.league_form', ['action' => 'create'])
                    @elseif($type === 'scorers')
                        @include('admin.standings.forms.scorer_form', ['action' => 'create'])
                    @elseif($type === 'cleansheets')
                        @include('admin.standings.forms.cleansheet_form', ['action' => 'create'])
                    @elseif($type === 'goalkeepers')
                        @include('admin.standings.forms.goalkeeper_form', ['action' => 'create'])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-dismiss alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
@endpush
@endsection
