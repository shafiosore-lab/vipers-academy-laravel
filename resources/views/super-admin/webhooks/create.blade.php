@extends('layouts.admin')

@section('title', 'Create Webhook')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-0 text-gray-900">Create Webhook</h1>
            <p class="mb-0 text-muted">Configure external integrations for your organization</p>
        </div>
    </div>

    <!-- Webhook Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Webhook Configuration</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('super-admin.webhooks.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Webhook Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="organization_id">Organization</label>
                                    <select class="form-control @error('organization_id') is-invalid @enderror" id="organization_id" name="organization_id" required>
                                        <option value="">Select Organization</option>
                                        @foreach($organizations as $organization)
                                            <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>
                                                {{ $organization->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('organization_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="url">Webhook URL</label>
                            <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}" required>
                            <small class="form-text text-muted">The URL where webhook payloads will be sent</small>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="events">Events to Subscribe To</label>
                            <div class="row">
                                @foreach($events as $eventKey => $eventName)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="events[]" value="{{ $eventKey }}" id="event_{{ $eventKey }}" {{ in_array($eventKey, old('events', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="event_{{ $eventKey }}">
                                                {{ $eventName }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('events')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="secret">Secret Key</label>
                                    <input type="text" class="form-control @error('secret') is-invalid @enderror" id="secret" name="secret" value="{{ old('secret') }}" placeholder="Leave empty to generate automatically">
                                    <small class="form-text text-muted">Optional secret for payload verification</small>
                                    @error('secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="timeout">Timeout (seconds)</label>
                                    <input type="number" class="form-control @error('timeout') is-invalid @enderror" id="timeout" name="timeout" value="{{ old('timeout', 10) }}" min="5" max="60">
                                    @error('timeout')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="retry_attempts">Retry Attempts</label>
                                    <input type="number" class="form-control @error('retry_attempts') is-invalid @enderror" id="retry_attempts" name="retry_attempts" value="{{ old('retry_attempts', 3) }}" min="0" max="5">
                                    <small class="form-text text-muted">Number of retry attempts on failure</small>
                                    @error('retry_attempts')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="enabled">Status</label>
                                    <select class="form-control @error('enabled') is-invalid @enderror" id="enabled" name="enabled">
                                        <option value="1" {{ old('enabled', true) ? 'selected' : '' }}>Enabled</option>
                                        <option value="0" {{ old('enabled') ? '' : 'selected' }}>Disabled</option>
                                    </select>
                                    @error('enabled')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="headers">Custom Headers (JSON)</label>
                            <textarea class="form-control @error('headers') is-invalid @enderror" id="headers" name="headers" rows="3" placeholder='{"Authorization": "Bearer token"}'>{{ old('headers') }}</textarea>
                            <small class="form-text text-muted">Optional custom headers to send with webhook requests</small>
                            @error('headers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('super-admin.webhooks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Webhook
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Help Sidebar -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Webhook Events</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Select the events you want to receive webhooks for:</p>
                    <ul class="list-unstyled">
                        @foreach($events as $eventKey => $eventName)
                            <li class="mb-2">
                                <code>{{ $eventKey }}</code>
                                <small class="text-muted"> - {{ $eventName }}</small>
                            </li>
                        @endforeach
                    </ul>

                    <hr>

                    <h6 class="text-primary">Webhook Format</h6>
                    <pre class="bg-light p-2 rounded"><code>{
  "event": "organization.created",
  "timestamp": "2023-01-01T00:00:00Z",
  "data": {
    "organization_id": 123,
    "name": "Example Org",
    "status": "active"
  }
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
