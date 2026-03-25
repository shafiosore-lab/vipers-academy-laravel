@extends('layouts.admin')

@section('title', 'Webhook Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-0 text-gray-900">Webhook Management</h1>
            <p class="mb-0 text-muted">Manage external integrations and webhooks</p>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('super-admin.webhooks.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Webhook
                    </a>
                </div>
                <div class="text-muted">
                    Total webhooks: {{ $webhooks->total() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Webhooks Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Webhooks</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Organization</th>
                                    <th>URL</th>
                                    <th>Events</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($webhooks as $webhook)
                                <tr>
                                    <td>
                                        <strong>{{ $webhook->name }}</strong>
                                        @if($webhook->enabled)
                                            <span class="badge badge-success ml-2">Active</span>
                                        @else
                                            <span class="badge badge-secondary ml-2">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('super-admin.organizations.show', $webhook->organization) }}">
                                            {{ $webhook->organization->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <code>{{ Str::limit($webhook->url, 50) }}</code>
                                    </td>
                                    <td>
                                        @foreach($webhook->events as $event)
                                            <span class="badge badge-info">{{ $event }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="badge {{ $webhook->enabled ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $webhook->enabled ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td>{{ $webhook->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('super-admin.webhooks.show', $webhook) }}" class="btn btn-sm btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('super-admin.webhooks.edit', $webhook) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('super-admin.webhooks.toggle', $webhook) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $webhook->enabled ? 'btn-secondary' : 'btn-success' }}" title="{{ $webhook->enabled ? 'Disable' : 'Enable' }}">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-primary test-webhook" data-webhook-id="{{ $webhook->id }}" title="Test Webhook">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <form action="{{ route('super-admin.webhooks.destroy', $webhook) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this webhook?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $webhooks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Test webhook functionality
    document.querySelectorAll('.test-webhook').forEach(button => {
        button.addEventListener('click', function() {
            const webhookId = this.getAttribute('data-webhook-id');
            const button = this;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
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
    });

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
