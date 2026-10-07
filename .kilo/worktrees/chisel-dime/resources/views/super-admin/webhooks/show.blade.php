@extends('layouts.admin')

@section('title', 'Webhook Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-0 text-gray-900">Webhook Details</h1>
            <p class="mb-0 text-muted">View webhook configuration and delivery logs</p>
        </div>
    </div>

    <!-- Webhook Info -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Webhook Configuration</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Name:</label>
                                <p class="form-control-plaintext">{{ $webhook->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status:</label>
                                <span class="badge {{ $webhook->enabled ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $webhook->enabled ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Organization:</label>
                        <p class="form-control-plaintext">
                            <a href="{{ route('super-admin.organizations.show', $webhook->organization) }}">
                                {{ $webhook->organization->name }}
                            </a>
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">URL:</label>
                        <p class="form-control-plaintext">
                            <code>{{ $webhook->url }}</code>
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Events:</label>
                        <div>
                            @foreach($webhook->events as $event)
                                <span class="badge badge-info mr-2">{{ $event }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Secret:</label>
                                <p class="form-control-plaintext">
                                    <code>{{ $webhook->secret }}</code>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Timeout:</label>
                                <p class="form-control-plaintext">{{ $webhook->timeout }} seconds</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Retry Attempts:</label>
                                <p class="form-control-plaintext">{{ $webhook->retry_attempts }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Created:</label>
                                <p class="form-control-plaintext">{{ $webhook->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($webhook->headers)
                        <div class="form-group">
                            <label class="font-weight-bold">Custom Headers:</label>
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($webhook->headers, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('super-admin.webhooks.edit', $webhook) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('super-admin.webhooks.toggle', $webhook) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn {{ $webhook->enabled ? 'btn-secondary' : 'btn-success' }}">
                                    <i class="fas fa-power-off"></i> {{ $webhook->enabled ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            <button type="button" class="btn btn-primary test-webhook" data-webhook-id="{{ $webhook->id }}">
                                <i class="fas fa-play"></i> Test Webhook
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('super-admin.webhooks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Webhook Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Delivery Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $logs->total() }}</h5>
                                    <p class="card-text">Total Deliveries</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $logs->where('success', true)->count() }}</h5>
                                    <p class="card-text">Successful</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 text-center">
                        <div class="col-6">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $logs->where('success', false)->count() }}</h5>
                                    <p class="card-text">Failed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $logs->avg('response_time') ? number_format($logs->avg('response_time'), 2) : '0.00' }}</h5>
                                    <p class="card-text">Avg Response Time (s)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('super-admin.webhooks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create New Webhook
                        </a>
                        <a href="{{ route('super-admin.webhooks.index') }}" class="btn btn-info">
                            <i class="fas fa-list"></i> View All Webhooks
                        </a>
                        <form action="{{ route('super-admin.webhooks.destroy', $webhook) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this webhook?')">
                                <i class="fas fa-trash"></i> Delete Webhook
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Logs -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Delivery Logs</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Attempt</th>
                                    <th>Status</th>
                                    <th>Response Time</th>
                                    <th>Response Status</th>
                                    <th>Error</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr>
                                    <td>
                                        <span class="badge badge-info">{{ $log->event }}</span>
                                    </td>
                                    <td>{{ $log->attempt }}</td>
                                    <td>
                                        @if($log->success)
                                            <span class="badge badge-success">Success</span>
                                        @else
                                            <span class="badge badge-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->response_time ? $log->response_time . 's' : 'N/A' }}</td>
                                    <td>{{ $log->response_status ?: 'N/A' }}</td>
                                    <td>
                                        @if($log->error_message)
                                            <span class="text-danger">{{ Str::limit($log->error_message, 50) }}</span>
                                        @else
                                            <span class="text-success">None</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Test webhook functionality
    const testButton = document.querySelector('.test-webhook');
    if (testButton) {
        testButton.addEventListener('click', function() {
            const webhookId = this.getAttribute('data-webhook-id');
            const button = this;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
            button.disabled = true;

            fetch(`/super-admin/webhooks/${webhookId}/test`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Webhook test sent successfully!');
                } else {
                    showToast('error', 'Failed to send webhook test: ' + data.message);
                }
            })
            .catch(error => {
                showToast('error', 'An error occurred while testing the webhook.');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    }

    function showToast(type, message) {
        // Simple toast implementation
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
});
</script>
@endpush
@endsection
