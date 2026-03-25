@extends('layouts.admin')

@section('title', 'WhatsApp History')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0"><i class="fab fa-whatsapp"></i> WhatsApp Message History</h5>
            <small class="text-muted">View previously sent WhatsApp messages</small>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(count($history) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Recipient</th>
                            <th>Message</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                        <tr>
                            <td>{{ $item['date'] }}</td>
                            <td>{{ $item['recipient'] }}</td>
                            <td><small>{{ $item['message'] }}</small></td>
                            <td>
                                <span class="badge bg-{{ $item['status'] === 'sent' ? 'success' : 'warning' }}">
                                    {{ ucfirst($item['status']) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fab fa-whatsapp fa-3x text-muted mb-3"></i>
                <h5>No Message History</h5>
                <p class="text-muted">WhatsApp messages you send will appear here.</p>
                <a href="{{ route('admin.whatsapp.index') }}" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send WhatsApp Message
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
