@extends('layouts.admin')

@section('title', 'Message Settings - Vipers Academy')

@push('styles')
<style>
    .messaging-settings {
        background: #fff;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Gateway Cards */
    .gateway-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .gateway-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .gateway-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        background: #fff;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    .gateway-header:hover {
        background: #fafafa;
    }

    .gateway-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .gateway-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .gateway-icon.sms { background: #e3f2fd; color: #1976d2; }
    .gateway-icon.whatsapp { background: #e8f5e9; color: #4caf50; }
    .gateway-icon.talksasa { background: #fff3e0; color: #ff9800; }
    .gateway-icon.africaastalking { background: #fce4ec; color: #e91e63; }

    .gateway-title h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
    }

    .gateway-title small {
        color: #6c757d;
        font-size: 11px;
    }

    .gateway-status {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .status-badge {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.active {
        background: rgba(101, 193, 110, 0.15);
        color: #28a745;
    }

    .status-badge.inactive {
        background: #f3f4f6;
        color: #9ca3af;
    }

    .status-badge.primary {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
    }

    .gateway-body {
        padding: 1rem;
    }

    .gateway-description {
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    /* Compact Form */
    .compact-form {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    @media (max-width: 992px) {
        .compact-form {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .compact-form {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        font-size: 10px;
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 12px;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #ea1c4d;
    }

    /* Toggle Switch */
    .toggle-switch {
        position: relative;
        width: 36px;
        height: 20px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.3s;
        border-radius: 20px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    .toggle-switch input:checked + .toggle-slider {
        background-color: #ea1c4d;
    }

    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(16px);
    }

    /* Actions Row */
    .actions-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .btn-primary {
        background: linear-gradient(45deg, #ea1c4d, #c0173f);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.25);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid #dee2e6;
        color: #6c757d;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-outline:hover {
        border-color: #ea1c4d;
        color: #ea1c4d;
    }

    /* Test Message Section */
    .test-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .test-section h6 {
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #1a1a1a;
    }

    .test-form {
        display: flex;
        gap: 0.5rem;
    }

    .test-form input {
        flex: 1;
        padding: 6px 10px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 12px;
    }

    /* Accordion */
    .accordion-icon {
        transition: transform 0.3s ease;
        font-size: 12px;
        color: #6c757d;
    }

    .gateway-card.open .accordion-icon {
        transform: rotate(180deg);
    }

    .gateway-card:not(.open) .gateway-body,
    .gateway-card:not(.open) .test-section {
        display: none;
    }

    /* Toast */
    .alert-toast {
        position: fixed;
        top: 80px;
        right: 20px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

    .alert-toast.success {
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .alert-toast.error {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* Breadcrumb */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .breadcrumb a {
        color: #ea1c4d;
        text-decoration: none;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .page-header h3 {
        margin: 0;
        font-size: 18px;
    }

    .primary-badge {
        background: rgba(245, 158, 11, 0.15);
        color: #d97706;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 9px;
        font-weight: 600;
        text-transform: uppercase;
        margin-left: 6px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="#">Communication</a>
        <i class="fas fa-chevron-right"></i>
        <span>Message Settings</span>
    </nav>

    {{-- Page Header --}}
    <div class="page-header">
        <h3><i class="fas fa-cog me-2" style="color: #ea1c4d;"></i>Message Gateway Settings</h3>
        <button class="btn-primary" onclick="showAddProviderModal()">
            <i class="fas fa-plus me-1"></i>Add Provider
        </button>
    </div>

    {{-- Add Provider Modal --}}
    <div class="modal fade" id="addProviderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add New Provider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Provider Name</label>
                        <input type="text" class="form-control" id="newProviderName" placeholder="e.g., Nexmo, Twilio">
                    </div>
                    <div class="form-group mb-3">
                        <label>Gateway Type</label>
                        <select class="form-control" id="newProviderType">
                            <option value="sms">SMS</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="talksasa">TalkSasa</option>
                            <option value="africaastalking">Africa's Talking</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Description</label>
                        <textarea class="form-control" id="newProviderDesc" rows="2" placeholder="Provider description"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>API Key</label>
                        <input type="text" class="form-control" id="newProviderApiKey" placeholder="Enter API Key">
                    </div>
                    <div class="form-group mb-3">
                        <label>API Secret</label>
                        <input type="password" class="form-control" id="newProviderApiSecret" placeholder="Enter API Secret">
                    </div>
                    <div class="form-group">
                        <label>Sender ID</label>
                        <input type="text" class="form-control" id="newProviderSenderId" placeholder="e.g., VIPERS">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-primary" onclick="addNewProvider()">Add Provider</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Gateway Cards --}}
    <div class="messaging-settings">
        @foreach($gateways->groupBy('gateway_type') as $type => $typeGateways)
            @foreach($typeGateways as $gateway)
            <div class="gateway-card {{ $loop->first && $loop->parent->first ? 'open' : '' }}" data-gateway-id="{{ $gateway->id }}">
                <div class="gateway-header" onclick="toggleGateway({{ $gateway->id }})">
                    <div class="gateway-title">
                        <div class="gateway-icon {{ $gateway->gateway_type }}">
                            @if($gateway->gateway_type === 'sms')
                                <i class="fas fa-sms"></i>
                            @elseif($gateway->gateway_type === 'whatsapp')
                                <i class="fab fa-whatsapp"></i>
                            @elseif($gateway->gateway_type === 'talksasa')
                                <i class="fas fa-comments"></i>
                            @else
                                <i class="fas fa-globe"></i>
                            @endif
                        </div>
                        <div>
                            <h5>{{ $gateway->gateway_name }}
                                @if($gateway->is_primary)<span class="primary-badge">Primary</span>@endif
                            </h5>
                            <small>{{ $gateway->gateway_type === 'sms' ? 'SMS Gateway' : ucfirst($gateway->gateway_type) }}</small>
                        </div>
                    </div>
                    <div class="gateway-status">
                        <span class="status-badge {{ $gateway->status }}">{{ $gateway->status }}</span>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </div>
                </div>
                <div class="gateway-body">
                    <p class="gateway-description">{{ $gateway->description }}</p>

                    <div class="compact-form">
                        <div class="form-group">
                            <label>API Key</label>
                            <input type="text" class="form-control" name="api_key" value="{{ $gateway->api_key }}" placeholder="Enter API Key">
                        </div>
                        <div class="form-group">
                            <label>API Secret</label>
                            <input type="password" class="form-control" name="api_secret" value="{{ $gateway->api_secret }}" placeholder="Enter API Secret">
                        </div>
                        <div class="form-group">
                            <label>Sender ID</label>
                            <input type="text" class="form-control" name="sender_id" value="{{ $gateway->sender_id }}" placeholder="e.g., VIPERS">
                        </div>
                        <div class="form-group">
                            <label>Account ID</label>
                            <input type="text" class="form-control" name="account_id" value="{{ $gateway->account_id }}" placeholder="Account ID">
                        </div>
                    </div>

                    <div class="actions-row">
                        <label class="toggle-switch">
                            <input type="checkbox" {{ $gateway->status === 'active' ? 'checked' : '' }} onchange="toggleStatus({{ $gateway->id }})">
                            <span class="toggle-slider"></span>
                        </label>
                        <span style="font-size: 11px; color: #6c757d;">{{ $gateway->status === 'active' ? 'Active' : 'Inactive' }}</span>

                        <button class="btn-outline btn-sm" onclick="setAsPrimary({{ $gateway->id }})" {{ $gateway->is_primary ? 'disabled' : '' }}>
                            <i class="fas fa-star me-1"></i>Set Primary
                        </button>

                        <button class="btn-outline btn-sm text-danger" onclick="deleteGateway({{ $gateway->id }})" style="margin-left: auto;">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>

                    {{-- Test Message Section --}}
                    <div class="test-section">
                        <h6><i class="fas fa-paper-plane me-1"></i>Test Message</h6>
                        <div class="test-form">
                            <input type="text" id="test_phone_{{ $gateway->id }}" placeholder="+254700000000">
                            <input type="text" id="test_message_{{ $gateway->id }}" placeholder="Test message">
                            <button class="btn-primary" onclick="sendTestMessage({{ $gateway->id }})" {{ $gateway->status !== 'active' ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane me-1"></i>Test
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
</div>

{{-- Toast Container --}}
<div id="toastContainer"></div>

@endsection

@push('scripts')
<script>
function showAddProviderModal() {
    const modal = new bootstrap.Modal(document.getElementById('addProviderModal'));
    modal.show();
}

function addNewProvider() {
    const name = document.getElementById('newProviderName').value;
    const type = document.getElementById('newProviderType').value;
    const desc = document.getElementById('newProviderDesc').value;
    const apiKey = document.getElementById('newProviderApiKey').value;
    const apiSecret = document.getElementById('newProviderApiSecret').value;
    const senderId = document.getElementById('newProviderSenderId').value;

    if (!name) {
        showToast('Please enter provider name', 'error');
        return;
    }

    fetch('/admin/messaging/gateway', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            gateway_name: name,
            gateway_type: type,
            description: desc,
            api_key: apiKey,
            api_secret: apiSecret,
            sender_id: senderId,
            status: 'inactive'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('addProviderModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => showToast('Failed to add provider', 'error'));
}

function toggleGateway(gatewayId) {
    const card = document.querySelector('[data-gateway-id="' + gatewayId + '"]');
    card.classList.toggle('open');
}

function toggleStatus(gatewayId) {
    fetch(`/admin/messaging/gateway/${gatewayId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => showToast('An error occurred', 'error'));
}

function deleteGateway(gatewayId) {
    if (!confirm('Are you sure you want to delete this provider?')) {
        return;
    }

    fetch(`/admin/messaging/gateway/${gatewayId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => showToast('An error occurred', 'error'));
}

function setAsPrimary(gatewayId) {
    fetch(`/admin/messaging/gateway/${gatewayId}/set-primary`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => showToast('An error occurred', 'error'));
}

function sendTestMessage(gatewayId) {
    const phone = document.getElementById(`test_phone_${gatewayId}`).value;
    const message = document.getElementById(`test_message_${gatewayId}`).value;

    if (!phone || !message) {
        showToast('Please enter phone number and message', 'error');
        return;
    }

    fetch('/admin/messaging/gateway/test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            gateway_id: gatewayId,
            phone_number: phone,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        showToast(data.message, data.success ? 'success' : 'error');
    })
    .catch(error => showToast('Failed to send test message', 'error'));
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert-toast ${type}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

document.addEventListener('DOMContentLoaded', function() {
    const firstCard = document.querySelector('.gateway-card');
    if (firstCard) firstCard.classList.add('open');
});
</script>
@endpush
