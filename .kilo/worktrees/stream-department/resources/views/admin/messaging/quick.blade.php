@extends('layouts.admin')

@section('title', 'Enhanced Quick Messaging - Vipers Academy')

@push('styles')
<style>
    .quick-messaging {
        background: #fff;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Channel Tabs */
    .channel-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid #eee;
        padding-bottom: 0.5rem;
    }

    .channel-tab {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        background: #f8f9fa;
        border: none;
        color: #6c757d;
        transition: all 0.2s;
    }

    .channel-tab:hover {
        background: #e9ecef;
    }

    .channel-tab.active {
        background: linear-gradient(45deg, #ea1c4d, #c0173f);
        color: white;
        box-shadow: 0 2px 8px rgba(234, 28, 77, 0.25);
    }

    /* Cards */
    .msg-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .msg-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .msg-card-title {
        font-size: 12px;
        font-weight: 600;
        color: #1a1a1a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Gateway Selection */
    .gateway-grid {
        display: flex;
        gap: 0.75rem;
    }

    .gateway-option {
        flex: 1;
        position: relative;
    }

    .gateway-option input {
        position: absolute;
        opacity: 0;
    }

    .gateway-card {
        padding: 0.75rem;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .gateway-option input:checked + .gateway-card {
        border-color: #ea1c4d;
        background: rgba(234, 28, 77, 0.05);
    }

    .gateway-card:hover {
        border-color: #ea1c4d;
    }

    .gateway-icon {
        font-size: 20px;
        margin-bottom: 0.25rem;
        display: block;
    }

    .gateway-name {
        font-weight: 600;
        font-size: 12px;
    }

    .gateway-desc {
        font-size: 10px;
        color: #6c757d;
    }

    /* Dropdown */
    .dropdown-container {
        position: relative;
    }

    .dropdown-toggle {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: white;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .dropdown-toggle:hover {
        border-color: #ea1c4d;
    }

    .dropdown-toggle span {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .dropdown-toggle i {
        font-size: 10px;
        color: #6c757d;
        flex-shrink: 0;
    }

    .dropdown-arrow {
        flex-shrink: 0;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        margin-top: 4px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 100;
        display: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-item {
        padding: 8px 12px;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dropdown-item:hover {
        background: #f8f9fa;
    }

    .dropdown-checkbox {
        width: 14px;
        height: 14px;
        accent-color: #ea1c4d;
    }

    /* Form Controls */
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 12px;
    }

    .form-control:focus {
        outline: none;
        border-color: #ea1c4d;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    /* Character Counter */
    .char-counter {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    .char-counter.warning { color: #f59e0b; }
    .char-counter.danger { color: #dc2626; }

    /* Delivery Options */
    .delivery-options {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        font-size: 12px;
    }

    .radio-label input {
        accent-color: #ea1c4d;
    }

    .schedule-datetime {
        margin-top: 0.75rem;
        display: none;
    }

    .schedule-datetime.show { display: block; }

    .schedule-datetime input {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 12px;
    }

    /* Summary Table - Compact Analytics Style */
    .summary-table {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
    }

    .summary-table table {
        margin-bottom: 0;
        font-size: 12px;
    }

    .summary-table th {
        background: #f8f9fa;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
    }

    .summary-table td {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }

    .summary-table tr:last-child td {
        border-bottom: none;
    }

    .summary-table .fw-bold {
        font-weight: 600;
    }

    .summary-table .text-primary {
        color: #ea1c4d;
    }

    /* Send Button */
    .btn-send {
        background: linear-gradient(45deg, #ea1c4d, #c0173f);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-send:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(234, 28, 77, 0.25);
    }

    .btn-send:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Channel Content */
    .channel-content { display: none; }
    .channel-content.active { display: block; }

    /* Badge */
    .selected-count {
        background: #ea1c4d;
        color: white;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        margin-left: 6px;
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
        margin-bottom: 1rem;
    }

    .page-header h3 {
        margin: 0;
        font-size: 18px;
    }

    /* Info Text */
    .info-text {
        font-size: 11px;
        color: #6c757d;
        margin-top: 0.25rem;
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
        <span>Enhanced Quick Messaging</span>
    </nav>

    {{-- Page Header --}}
    <div class="page-header">
        <h3><i class="fas fa-paper-plane me-2" style="color: #ea1c4d;"></i>Enhanced Quick Messaging</h3>
    </div>

    {{-- Channel Tabs --}}
    <div class="channel-tabs">
        <button class="channel-tab active" data-channel="sms">
            <i class="fas fa-sms me-1"></i>SMS
        </button>
        <button class="channel-tab" data-channel="whatsapp">
            <i class="fab fa-whatsapp me-1"></i>WhatsApp
        </button>
    </div>

    {{-- SMS Channel --}}
    <div class="channel-content active" id="sms-content">
        {{-- Gateway Selection Card --}}
        <div class="msg-card">
            <div class="msg-card-header">
                <span class="msg-card-title"><i class="fas fa-satellite-dish me-1"></i>SMS Gateway Selection</span>
            </div>
            <div class="gateway-grid">
                @php
                    $smsGateways = isset($gateways) ? $gateways->where('gateway_type', 'sms')->where('status', 'active') : collect([]);
                @endphp
                @forelse($smsGateways as $index => $gateway)
                <div class="gateway-option">
                    <input type="radio" name="gateway" id="gateway_{{ $gateway->id }}" value="{{ $gateway->id }}" {{ $index === 0 ? 'checked' : '' }}>
                    <label class="gateway-card" for="gateway_{{ $gateway->id }}">
                        <span class="gateway-icon">📱</span>
                        <div class="gateway-name">{{ $gateway->gateway_name }}</div>
                        <div class="gateway-desc">{{ $gateway->description ?? 'SMS Provider' }}</div>
                    </label>
                </div>
                @empty
                <div class="gateway-option">
                    <input type="radio" name="gateway" id="gateway_default" value="default" checked>
                    <label class="gateway-card" for="gateway_default">
                        <span class="gateway-icon">📱</span>
                        <div class="gateway-name">Default SMS</div>
                        <div class="gateway-desc">No active providers configured</div>
                    </label>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recipients Card --}}
        <div class="msg-card">
            <div class="msg-card-header">
                <span class="msg-card-title"><i class="fas fa-users me-1"></i>Select Recipients</span>
            </div>

            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label" style="font-size: 11px; font-weight: 600;">SMS Groups</label>
                    <div class="dropdown-container">
                        <div class="dropdown-toggle" id="groupDropdownToggle">
                            <span>Select groups... <span class="selected-count" id="groupCount">0</span></span>
                            <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="dropdown-menu" id="groupDropdown">
                            <div class="dropdown-item"><input type="checkbox" class="dropdown-checkbox" checked> All Players</div>
                            <div class="dropdown-item"><input type="checkbox" class="dropdown-checkbox"> All Parents/Guardians</div>
                            <div class="dropdown-item"><input type="checkbox" class="dropdown-checkbox"> All Staff</div>
                            <div class="dropdown-item"><input type="checkbox" class="dropdown-checkbox"> Active Players</div>
                            <div class="dropdown-item"><input type="checkbox" class="dropdown-checkbox"> Inactive Players</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" style="font-size: 11px; font-weight: 600;">Individual Players</label>
                    <div class="dropdown-container">
                        <div class="dropdown-toggle" id="playerDropdownToggle">
                            <span>Search players... <span class="selected-count" id="playerCount">0</span></span>
                            <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="dropdown-menu" id="playerDropdown" style="padding: 8px;">
                            <input type="text" class="form-control mb-2" id="playerSearch" placeholder="Search players..." style="font-size: 11px; padding: 6px 8px;">
                            <div style="max-height: 150px; overflow-y: auto;" id="playerList">
                                @if(isset($players) && $players->count() > 0)
                                    @foreach($players as $player)
                                    <div class="dropdown-item">
                                        <input type="checkbox" class="dropdown-checkbox" value="{{ $player->id }}">
                                        {{ $player->first_name }} {{ $player->last_name }}
                                    </div>
                                    @endforeach
                                @else
                                    <div class="dropdown-item" style="color: #6c757d;">No players found</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" style="font-size: 11px; font-weight: 600;">Manual Numbers</label>
                    <textarea class="form-control" id="manualPhones" rows="2" placeholder="+2547xxxxxxxx, 0723..."></textarea>
                </div>
            </div>
        </div>

        {{-- Message Card --}}
        <div class="msg-card">
            <div class="msg-card-header">
                <span class="msg-card-title"><i class="fas fa-pen me-1"></i>Compose Message</span>
            </div>
            <textarea class="form-control" id="messageText" placeholder="Type your message here..."></textarea>
            <div class="char-counter" id="charCounter">
                <span>0/160 characters</span>
                <span>1 SMS</span>
            </div>
            <div class="info-text">💡 Tip: Messages over 160 characters will be split into multiple SMS</div>
        </div>

        {{-- Delivery Options Card --}}
        <div class="msg-card">
            <div class="msg-card-header">
                <span class="msg-card-title"><i class="fas fa-clock me-1"></i>Delivery Options</span>
            </div>
            <div class="delivery-options">
                <label class="radio-label">
                    <input type="radio" name="delivery" value="immediate" checked>
                    Send Immediately
                </label>
                <label class="radio-label">
                    <input type="radio" name="delivery" value="schedule">
                    Schedule for Later
                </label>
            </div>
            <div class="schedule-datetime" id="scheduleDateTime">
                <input type="datetime-local" id="scheduledTime" min="{{ date('Y-m-d\TH:i') }}">
            </div>
        </div>

        {{-- Summary Table - Compact Analytics Style --}}
        <div class="summary-table">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th><i class="fas fa-chart-bar me-1"></i>Metric</th>
                        <th class="text-end">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-phone text-muted me-1"></i>Valid Phone Numbers</td>
                        <td class="text-end fw-bold" id="validPhoneCount">0</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-envelope text-muted me-1"></i>Estimated SMS Count</td>
                        <td class="text-end fw-bold" id="smsCount">0</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-dollar-sign text-muted me-1"></i>Estimated Cost</td>
                        <td class="text-end fw-bold text-primary" id="estimatedCost">$0.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Send Button --}}
        <div style="margin-top: 1rem; text-align: right;">
            <button class="btn-send" id="sendBtn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
                Send Message
            </button>
        </div>
    </div>

    {{-- WhatsApp Channel --}}
    <div class="channel-content" id="whatsapp-content">
        <div class="msg-card">
            <div class="msg-card-header">
                <span class="msg-card-title"><i class="fab fa-whatsapp me-1"></i>WhatsApp Gateway</span>
            </div>
            <div class="gateway-grid">
                @php
                    $whatsappGateways = isset($gateways) ? $gateways->where('gateway_type', 'whatsapp') : collect([]);
                @endphp
                @forelse($whatsappGateways as $index => $gateway)
                <div class="gateway-option">
                    <input type="radio" name="whatsapp_gateway" id="whatsapp_{{ $gateway->id }}" value="{{ $gateway->id }}" {{ $index === 0 ? 'checked' : '' }}>
                    <label class="gateway-card" for="whatsapp_{{ $gateway->id }}">
                        <span class="gateway-icon">✅</span>
                        <div class="gateway-name">{{ $gateway->gateway_name }}</div>
                        <div class="gateway-desc">{{ $gateway->description ?? 'WhatsApp Provider' }}</div>
                    </label>
                </div>
                @empty
                <div class="gateway-option">
                    <input type="radio" name="whatsapp_gateway" id="whatsapp_default" value="default" checked>
                    <label class="gateway-card" for="whatsapp_default">
                        <span class="gateway-icon">✅</span>
                        <div class="gateway-name">WhatsApp Business API</div>
                        <div class="gateway-desc">No active providers configured</div>
                    </label>
                </div>
                @endforelse
            </div>
        </div>

        <div class="msg-card">
            <div class="msg-card-header">
                <span class="msg-card-title"><i class="fas fa-users me-1"></i>Select Recipients</span>
            </div>
            <div class="row g-2">
                <div class="col-12">
                    <div class="dropdown-container">
                        <div class="dropdown-toggle" id="whatsappGroupToggle">
                            <span>Select groups... <span class="selected-count" id="whatsappGroupCount">0</span></span>
                            <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div class="dropdown-menu" id="whatsappGroupDropdown">
                            <div class="dropdown-item"><input type="checkbox" class="dropdown-checkbox" checked> All Players</div>
                            <div class="dropdown-item"><input type="checkbox" class="dropdown-checkbox"> All Parents/Guardians</div>
                            <div class="dropdown-item"><input type="checkbox" class="dropdown-checkbox"> All Staff</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="msg-card">
            <div class="msg-card-header">
                <span class="msg-card-title"><i class="fas fa-pen me-1"></i>Compose WhatsApp Message</span>
            </div>
            <textarea class="form-control" id="whatsappMessage" placeholder="Type your WhatsApp message here..."></textarea>
            <div class="char-counter" id="whatsappCharCounter">
                <span>0 characters</span>
            </div>
        </div>

        {{-- WhatsApp Delivery Options Card --}}
        <div class="msg-card">
            <div class="msg-card-header">
                <span class="msg-card-title"><i class="fas fa-clock me-1"></i>Delivery Options</span>
            </div>
            <div class="delivery-options">
                <label class="radio-label">
                    <input type="radio" name="whatsapp_delivery" value="immediate" checked>
                    Send Immediately
                </label>
                <label class="radio-label">
                    <input type="radio" name="whatsapp_delivery" value="schedule">
                    Schedule for Later
                </label>
            </div>
            <div class="schedule-datetime" id="whatsappScheduleDateTime">
                <input type="datetime-local" id="whatsappScheduledTime" min="{{ date('Y-m-d\TH:i') }}">
            </div>
        </div>

        <div style="margin-top: 1rem; text-align: right;">
            <button class="btn-send" id="whatsappSendBtn" onclick="sendWhatsAppMessage()">
                <i class="fab fa-whatsapp"></i>
                Send via WhatsApp
            </button>
        </div>
    </div>
</div>

{{-- Toast Container --}}
<div id="toastContainer"></div>

@endsection

@push('scripts')
<script>
    const SMS_COST_PER_UNIT = 0.05;
    const CHAR_LIMIT = 160;

    // Channel tabs
    document.querySelectorAll('.channel-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const channel = this.dataset.channel;
            document.querySelectorAll('.channel-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.channel-content').forEach(c => c.classList.remove('active'));
            document.getElementById(channel + '-content').classList.add('active');
        });
    });

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (!menu.contains(e.target) && !menu.previousElementSibling?.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    });

    // Dropdowns
    const dropdowns = [
        { toggle: 'groupDropdownToggle', menu: 'groupDropdown' },
        { toggle: 'playerDropdownToggle', menu: 'playerDropdown' },
        { toggle: 'whatsappGroupToggle', menu: 'whatsappGroupDropdown' }
    ];

    dropdowns.forEach(({ toggle, menu }) => {
        const toggleEl = document.getElementById(toggle);
        const menuEl = document.getElementById(menu);
        if (toggleEl && menuEl) {
            toggleEl.addEventListener('click', function(e) {
                e.stopPropagation();
                menuEl.classList.toggle('show');
            });
        }
    });

    // Update counts - groups
    const groupMenu = document.getElementById('groupDropdown');
    if (groupMenu) {
        groupMenu.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', function() {
                const count = groupMenu.querySelectorAll('input[type="checkbox"]:checked').length;
                document.getElementById('groupCount').textContent = count;
                updateSummary();
            });
        });
    }

    // Player search functionality
    const playerSearch = document.getElementById('playerSearch');
    if (playerSearch) {
        playerSearch.addEventListener('input', function(e) {
            e.stopPropagation();
            const search = this.value.toLowerCase();
            const playerList = document.getElementById('playerList');
            if (playerList) {
                playerList.querySelectorAll('.dropdown-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(search) ? 'flex' : 'none';
                });
            }
        });
    }

    // Player checkbox changes
    const playerMenu = document.getElementById('playerDropdown');
    if (playerMenu) {
        playerMenu.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', function() {
                const count = playerMenu.querySelectorAll('input[type="checkbox"]:checked').length;
                document.getElementById('playerCount').textContent = count;
                updateSummary();
            });
        });
    }

    // Character counter
    const messageText = document.getElementById('messageText');
    if (messageText) {
        messageText.addEventListener('input', function() {
            const chars = this.value.length;
            const smsCount = Math.ceil(chars / CHAR_LIMIT) || 1;
            const counter = document.getElementById('charCounter');
            counter.innerHTML = `<span>${chars}/160 characters</span><span>${smsCount} SMS</span>`;
            counter.className = 'char-counter' + (chars > 320 ? ' danger' : chars > 160 ? ' warning' : '');
            updateSummary();
        });
    }

    const whatsappMessage = document.getElementById('whatsappMessage');
    if (whatsappMessage) {
        whatsappMessage.addEventListener('input', function() {
            document.getElementById('whatsappCharCounter').querySelector('span').textContent = this.value.length + ' characters';
        });
    }

    // Delivery options - SMS
    document.querySelectorAll('input[name="delivery"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('scheduleDateTime').classList.toggle('show', this.value === 'schedule');
        });
    });

    // Delivery options - WhatsApp
    document.querySelectorAll('input[name="whatsapp_delivery"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('whatsappScheduleDateTime').classList.toggle('show', this.value === 'schedule');
        });
    });

    // Manual phones
    document.getElementById('manualPhones')?.addEventListener('input', updateSummary);

    function updateSummary() {
        const manualPhones = document.getElementById('manualPhones')?.value || '';
        const phoneList = manualPhones.split(',').map(p => p.trim()).filter(p => p.length > 5);
        const validPhones = phoneList.length;
        const messageLength = messageText?.value.length || 0;
        const smsCount = Math.ceil(messageLength / CHAR_LIMIT) * validPhones || 0;
        const cost = smsCount * SMS_COST_PER_UNIT;

        document.getElementById('validPhoneCount').textContent = validPhones;
        document.getElementById('smsCount').textContent = smsCount;
        document.getElementById('estimatedCost').textContent = '$' + cost.toFixed(2);
    }

    function sendMessage() {
        const message = messageText?.value;
        if (!message?.trim()) {
            showToast('Please enter a message', 'error');
            return;
        }

        const sendBtn = document.getElementById('sendBtn');
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

        fetch('/admin/messaging/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                gateway: document.querySelector('input[name="gateway"]:checked')?.value,
                message: message,
                delivery: document.querySelector('input[name="delivery"]:checked')?.value,
                scheduled_time: document.getElementById('scheduledTime')?.value,
                manual_phones: document.getElementById('manualPhones')?.value.split(',').map(p => p.trim()).filter(p => p.length > 5) || []
            })
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
            if (data.success) { messageText.value = ''; updateSummary(); }
        })
        .catch(error => showToast('Failed to send message', 'error'))
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
        });
    }

    function sendWhatsAppMessage() {
        const message = document.getElementById('whatsappMessage')?.value;
        if (!message?.trim()) {
            showToast('Please enter a WhatsApp message', 'error');
            return;
        }
        showToast('WhatsApp feature coming soon!', 'success');
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert-toast ${type}`;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    updateSummary();
</script>
@endpush
