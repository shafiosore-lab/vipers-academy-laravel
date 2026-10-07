@extends(auth()->user()->hasRole('super-admin') ? 'layouts.admin' : (auth()->user()->hasRole('org-admin') ? 'layouts.admin' : 'layouts.app'))

@section('title', 'No Team Assigned')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-4"></i>
                    <h3>No Team Assigned</h3>
                    <p class="text-muted">
                        You haven't been assigned to any team yet.
                        Please contact your organization administrator to get access to a team.
                    </p>

                    @if(isset($message))
                        <div class="alert alert-info">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
