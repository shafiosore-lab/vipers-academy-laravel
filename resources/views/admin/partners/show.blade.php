@extends('layouts.admin')

@section('title', __('Partner Details - Vipers Academy Admin'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">{{ __('Partner Details') }}</h1>
                    <p class="text-muted">{{ __('View and manage partner information') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>{{ __('Edit Partner') }}
                    </a>
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Partners') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Partner Status Banner -->
    @if($partner->status === 'pending')
        <div class="alert alert-warning mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-clock fa-lg me-3"></i>
                <div>
                    <h5 class="mb-1">{{ __('Pending Approval') }}</h5>
                    <p class="mb-0">{{ __('This partner account is waiting for administrator approval.') }}</p>
                </div>
                <div class="ms-auto">
                    <form method="POST" action="{{ route('admin.partners.approve', $partner) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-check me-1"></i>{{ __('Approve') }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.partners.reject', $partner) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i>{{ __('Reject') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @elseif($partner->status === 'active')
        <div class="alert alert-success mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-lg me-3"></i>
                <div>
                    <h5 class="mb-1">{{ __('Active Partner') }}</h5>
                    <p class="mb-0">{{ __('This partner has full access to the system.') }}</p>
                </div>
            </div>
        </div>
    @elseif($partner->status === 'rejected')
        <div class="alert alert-danger mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-times-circle fa-lg me-3"></i>
                <div>
                    <h5 class="mb-1">{{ __('Rejected Partner') }}</h5>
                    <p class="mb-0">{{ __('This partner application has been rejected.') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Partner Information -->
    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Organization Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-building me-2 text-primary"></i>{{ __('Organization Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Organization Name') }}</label>
                            <p class="mb-0">{{ $partner->organization_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Organization Type') }}</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $partner->organization_type)) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Contact Person') }}</label>
                            <p class="mb-0">{{ $partner->contact_person }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Phone Number') }}</label>
                            <p class="mb-0">{{ $partner->phone ?: __('Not provided') }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">{{ __('Address') }}</label>
                            <p class="mb-0">{{ $partner->address ?: __('Not provided') }}</p>
                        </div>
                        @if($partner->website)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Website') }}</label>
                            <p class="mb-0">
                                <a href="{{ $partner->website }}" target="_blank" class="text-decoration-none">
                                    {{ $partner->website }} <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </p>
                        </div>
                        @endif
                        @if($partner->partnership_type)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Partnership Type') }}</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $partner->partnership_type)) }}</span>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2 text-success"></i>{{ __('Account Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Full Name') }}</label>
                            <p class="mb-0">{{ $partner->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Email Address') }}</label>
                            <p class="mb-0">{{ $partner->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Account Status') }}</label>
                            <p class="mb-0">
                                @if($partner->status === 'active')
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @elseif($partner->status === 'pending')
                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                @elseif($partner->status === 'rejected')
                                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Member Since') }}</label>
                            <p class="mb-0">{{ $partner->created_at->format('M j, Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('Last Updated') }}</label>
                            <p class="mb-0">{{ $partner->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($partner->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note me-2 text-info"></i>{{ __('Additional Notes') }}</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $partner->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2 text-warning"></i>{{ __('Recent Activity') }}</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ __('Account Created') }}</h6>
                                <p class="mb-0 text-muted small">{{ $partner->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($partner->status === 'active')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ __('Account Approved') }}</h6>
                                <p class="mb-0 text-muted small">{{ __('Partner granted full system access') }}</p>
                            </div>
                        </div>
                        @elseif($partner->status === 'rejected')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ __('Account Rejected') }}</h6>
                                <p class="mb-0 text-muted small">{{ __('Partner application was not approved') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>{{ __('Quick Actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>{{ __('Edit Partner') }}
                        </a>

                        @if($partner->status === 'pending')
                        <form method="POST" action="{{ route('admin.partners.approve', $partner) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>{{ __('Approve Partner') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.partners.reject', $partner) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times me-2"></i>{{ __('Reject Partner') }}
                            </button>
                        </form>
                        @endif

                        <button class="btn btn-info" onclick="sendEmail()">
                            <i class="fas fa-envelope me-2"></i>{{ __('Send Email') }}
                        </button>

                        <button class="btn btn-secondary" onclick="printDetails()">
                            <i class="fas fa-print me-2"></i>{{ __('Print Details') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-info"></i>{{ __('Partner Statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h4 class="mb-1">{{ $partner->players_count ?? 0 }}</h4>
                                <small class="text-muted">{{ __('Players') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3">
                                <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                <h4 class="mb-1">{{ $partner->active_programs_count ?? 0 }}</h4>
                                <small class="text-muted">{{ __('Programs') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-address-card me-2 text-success"></i>{{ __('Contact Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>{{ __('Email') }}:</strong><br>
                        <a href="mailto:{{ $partner->email }}" class="text-decoration-none">{{ $partner->email }}</a>
                    </div>

                    @if($partner->phone)
                    <div class="mb-2">
                        <strong>{{ __('Phone') }}:</strong><br>
                        <a href="tel:{{ $partner->phone }}" class="text-decoration-none">{{ $partner->phone }}</a>
                    </div>
                    @endif

                    @if($partner->address)
                    <div class="mb-0">
                        <strong>{{ __('Address') }}:</strong><br>
                        <span>{{ $partner->address }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }

    .timeline-content h6 {
        margin-bottom: 5px;
        color: #495057;
    }
</style>

<script>
function sendEmail() {
    const email = '{{ $partner->email }}';
    const subject = 'Vipers Academy Partnership Update';
    const body = 'Dear {{ $partner->name }},\n\nWe hope this email finds you well.\n\nBest regards,\nVipers Academy Team';

    window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
}

function printDetails() {
    window.print();
}
</script>
@endsection
