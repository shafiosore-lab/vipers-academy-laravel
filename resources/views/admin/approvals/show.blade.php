@extends('layouts.admin')

@section('title', 'Review User: ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-600">{{ $user->email }}</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $user->user_type === 'player' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ ucfirst($user->user_type) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 ml-2">
                        Pending Approval
                    </span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.approvals.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Approvals
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">User Information</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->user_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Registration Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Partner Details (if applicable) -->
            @if($user->isPartner() && $user->partner_details)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Organization Details</h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($user->partner_details as $key => $value)
                            @if($value)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $key)) }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $value }}</dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                </div>
            @endif

            <!-- Player Details (if applicable) -->
            @if($user->isPlayer() && $user->player)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Player Information</h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->player->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Age</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->player->age }} years</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Position</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->player->formatted_position }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->player->formatted_category }}</dd>
                        </div>
                    </dl>
                </div>
            @endif
        </div>

        <!-- Approval Actions -->
        <div class="space-y-6">
            <!-- Document Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Document Status</h3>
                @php
                    $totalDocs = $uploadedDocuments->count();
                    $approvedDocs = $uploadedDocuments->where('status', 'approved')->count();
                    $pendingDocs = $uploadedDocuments->where('status', 'pending_review')->count();
                    $rejectedDocs = $uploadedDocuments->where('status', 'rejected')->count();
                @endphp

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Documents</span>
                        <span class="text-sm font-medium">{{ $totalDocs }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-green-600">Approved</span>
                        <span class="text-sm font-medium text-green-600">{{ $approvedDocs }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-yellow-600">Pending Review</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $pendingDocs }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-red-600">Rejected</span>
                        <span class="text-sm font-medium text-red-600">{{ $rejectedDocs }}</span>
                    </div>
                </div>

                @if($totalDocs > 0)
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalDocs > 0 ? ($approvedDocs / $totalDocs) * 100 : 0 }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-center">
                            {{ round(($approvedDocs / $totalDocs) * 100) }}% Complete
                        </p>
                    </div>
                @endif
            </div>

            <!-- Approval Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Actions</h3>

                <form action="{{ route('admin.approvals.approve', $user->id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-4">
                        <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">Approval Notes (Optional)</label>
                        <textarea name="notes" id="approve_notes" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                  placeholder="Any notes about this approval..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-check mr-2"></i>Approve User
                    </button>
                </form>

                <form action="{{ route('admin.approvals.reject', $user->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                  placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                        <textarea name="notes" id="reject_notes" rows="2"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                  placeholder="Any additional notes..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fas fa-times mr-2"></i>Reject User
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Uploaded Documents -->
    @if($uploadedDocuments->count() > 0)
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Uploaded Documents</h2>
        </div>

        <div class="divide-y divide-gray-200">
            @foreach($uploadedDocuments as $document)
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-{{ $document->mime_type === 'application/pdf' ? 'pdf' : 'image' }} text-gray-400 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ ucwords(str_replace('_', ' ', $document->document_type)) }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Uploaded {{ $document->uploaded_at->format('M d, Y H:i') }} •
                                    {{ number_format($document->file_size / 1024, 1) }} KB
                                </p>
                                @if($document->review_notes)
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Review Notes:</strong> {{ $document->review_notes }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($document->status === 'approved') bg-green-100 text-green-800
                                @elseif($document->status === 'pending_review') bg-yellow-100 text-yellow-800
                                @elseif($document->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($document->status === 'approved') <i class="fas fa-check mr-1"></i> @endif
                                @if($document->status === 'pending_review') <i class="fas fa-clock mr-1"></i> @endif
                                @if($document->status === 'rejected') <i class="fas fa-times mr-1"></i> @endif
                                {{ ucwords(str_replace('_', ' ', $document->status)) }}
                            </span>

                            <a href="{{ route('admin.approvals.documents.download', $document->id) }}"
                               class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
