@extends('layouts.admin')

@section('title', __('Bulk SMS'))

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('Bulk SMS') }}</h1>
        <a href="{{ route('admin.sms.history') }}" class="btn btn-secondary">
            <i class="fas fa-history"></i> {{ __('View History') }}
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Send SMS') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sms.send') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('Recipient Type') }}</label>
                            <select name="recipient_type" id="recipient_type" class="form-select" required>
                                <option value="players">{{ __('Players (Parents)') }}</option>
                                <option value="staff">{{ __('Staff') }}</option>
                                <option value="custom">{{ __('Custom Numbers') }}</option>
                            </select>
                        </div>

                        <div class="mb-3" id="recipients_field">
                            <label class="form-label">{{ __('Select Recipients') }}</label>
                            <select name="recipients[]" id="recipients" class="form-select" multiple>
                                @foreach($players as $player)
                                    <option value="{{ $player->parent_phone }}">{{ $player->full_name }} (Parent: {{ $player->parent_phone }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Message') }}</label>
                            <textarea name="message" id="message" class="form-control" rows="4" maxlength="1600" required placeholder="{{ __('Enter your message here...') }}"></textarea>
                            <small class="text-muted">Maximum 1600 characters</small>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> {{ __('Send SMS') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Quick Actions') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sms.sendAllPlayers') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ __('Send to All Players') }}</label>
                            <textarea name="message" class="form-control" rows="4" maxlength="1600" required placeholder="{{ __('Enter your message...') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-users"></i> {{ __('Send to All Players') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>{{ __('Statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Total Players') }}</span>
                        <strong>{{ $players->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Total Staff') }}</span>
                        <strong>{{ $staff->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('recipient_type').addEventListener('change', function() {
    const recipientsField = document.getElementById('recipients');
    const options = recipientsField.options;

    if (this.value === 'players') {
        @foreach($players as $player)
        options[{{ $loop->index }}] = new Option('{{ $player->full_name }} ({{ $player->parent_phone }})', '{{ $player->parent_phone }}');
        @endforeach
    } else if (this.value === 'staff') {
        @foreach($staff as $s)
        options[{{ $loop->index }}] = new Option('{{ $s->name }} ({{ $s->phone }})', '{{ $s->phone }}');
        @endforeach
    }
});
</script>
@endpush
@endsection
