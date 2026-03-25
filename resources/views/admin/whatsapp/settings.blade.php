@extends('layouts.admin')

@section('title', 'WhatsApp Configuration')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h3 class="page-title">
            <i class="fab fa-whatsapp mr-2"></i>
            WhatsApp Configuration
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">WhatsApp API Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.whatsapp.settings.update') }}">
                        @csrf

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="whatsapp_enabled" name="whatsapp_enabled"
                                    {{ ($settings->whatsapp_enabled ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="whatsapp_enabled">
                                    <strong>Enable WhatsApp Notifications</strong>
                                </label>
                            </div>
                            <small class="text-muted">When enabled, WhatsApp will be used as the primary notification channel (if players opt-in).</small>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="whatsapp_phone_number_id" class="form-label">WhatsApp Phone Number ID</label>
                            <input type="text" class="form-control" id="whatsapp_phone_number_id" name="whatsapp_phone_number_id"
                                value="{{ $settings->whatsapp_phone_number_id ?? '' }}"
                                placeholder="Enter your WhatsApp Phone Number ID">
                            <small class="text-muted">Found in Meta Developer Portal → WhatsApp → API Setup</small>
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp_business_account_id" class="form-label">WhatsApp Business Account ID</label>
                            <input type="text" class="form-control" id="whatsapp_business_account_id" name="whatsapp_business_account_id"
                                value="{{ $settings->whatsapp_business_account_id ?? '' }}"
                                placeholder="Enter your Business Account ID">
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp_access_token" class="form-label">Meta Access Token</label>
                            <input type="password" class="form-control" id="whatsapp_access_token" name="whatsapp_access_token"
                                value="{{ $settings->whatsapp_access_token ?? '' }}"
                                placeholder="Enter your Meta Access Token">
                            <small class="text-muted">Get from Meta Developer Portal → WhatsApp → Configuration</small>
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp_default_template" class="form-label">Default Template Name</label>
                            <input type="text" class="form-control" id="whatsapp_default_template" name="whatsapp_default_template"
                                value="{{ $settings->whatsapp_default_template ?? 'training_reminder' }}"
                                placeholder="training_reminder">
                            <small class="text-muted">The default WhatsApp template to use for notifications</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="fallback_to_sms" checked disabled>
                                <label class="form-check-label" for="fallback_to_sms">
                                    Automatic SMS fallback enabled
                                </label>
                            </div>
                            <small class="text-muted">If WhatsApp fails or limit is exceeded, SMS will be sent automatically.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Settings
                            </button>

                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#testConnectionModal">
                                <i class="fas fa-paper-plane mr-1"></i> Test Connection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Usage Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Status:</span>
                            <span class="badge {{ $whatsappStatus['enabled'] ? 'bg-success' : 'bg-secondary' }}">
                                {{ $whatsappStatus['enabled'] ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Configured:</span>
                            <span class="badge {{ $whatsappStatus['configured'] ? 'bg-success' : 'bg-warning' }}">
                                {{ $whatsappStatus['configured'] ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <small class="text-muted">Monthly Limit: {{ $whatsappStatus['monthly_limit'] }}</small>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Used This Month: {{ $whatsappStatus['used_this_month'] }}</small>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Remaining: {{ $whatsappStatus['remaining'] }}</small>
                    </div>

                    @if($whatsappStatus['used_this_month'] > 0)
                    <div class="mt-3">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar {{ $whatsappStatus['remaining'] < 100 ? 'bg-warning' : 'bg-info' }}"
                                role="progressbar"
                                style="width: {{ ($whatsappStatus['used_this_month'] / $whatsappStatus['monthly_limit']) * 100 }}%">
                                {{ round(($whatsappStatus['used_this_month'] / $whatsappStatus['monthly_limit']) * 100) }}%
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Setup Guide
                    </h5>
                </div>
                <div class="card-body">
                    <ol class="ps-3">
                        <li class="mb-2">Go to <a href="https://developers.facebook.com/" target="_blank">Meta Developer Portal</a></li>
                        <li class="mb-2">Create a new WhatsApp app</li>
                        <li class="mb-2">Get your Phone Number ID</li>
                        <li class="mb-2">Get your Business Account ID</li>
                        <li class="mb-2">Generate a temporary access token</li>
                        <li class="mb-2">Create message templates in Meta portal</li>
                        <li>Enter credentials here and save</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Connection Modal -->
<div class="modal fade" id="testConnectionModal" tabindex="-1" aria-labelledby="testConnectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testConnectionModalLabel">Test WhatsApp Connection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.whatsapp.test') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="test_phone" class="form-label">Test Phone Number</label>
                        <input type="text" class="form-control" id="test_phone" name="test_phone"
                            placeholder="2547XXXXXXXX" required>
                        <small class="text-muted">Enter phone number in format: 2547XXXXXXXX</small>
                    </div>
                    <p class="text-muted">A test message will be sent to this number.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Test</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
