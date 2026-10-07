@extends('layouts.app')

@section('title', 'Document Upload')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Document Upload</h1>
            <p class="text-gray-600">Upload required documents for your account approval</p>
        </div>

        <!-- Required Documents Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Required Documents</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($requiredDocuments as $type => $info)
                    @php
                        $uploaded = $userDocuments->where('document_type', $type)->first();
                        $status = $uploaded ? $uploaded->status : 'not_uploaded';
                    @endphp

                    <div class="border rounded-lg p-4 {{ $status === 'approved' ? 'border-green-200 bg-green-50' : ($status === 'pending_review' ? 'border-yellow-200 bg-yellow-50' : 'border-gray-200') }}">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-medium text-gray-900">{{ $info['name'] }}</h3>
                            @if($status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Approved
                                </span>
                            @elseif($status === 'pending_review')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending Review
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-upload mr-1"></i>Not Uploaded
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-gray-600 mb-3">{{ $info['description'] }}</p>

                        @if($uploaded)
                            <div class="text-xs text-gray-500 mb-3">
                                <p>Uploaded: {{ $uploaded->uploaded_at->format('M d, Y') }}</p>
                                <p>Size: {{ number_format($uploaded->file_size / 1024, 1) }} KB</p>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('documents.upload.download', $uploaded->id) }}"
                                   class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>

                                @if($status !== 'approved')
                                    <form action="{{ route('documents.upload.destroy', $uploaded->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('documents.upload.create', $type) }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-upload mr-2"></i>Upload Document
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Uploaded Documents History -->
        @if($userDocuments->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload History</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($userDocuments as $document)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ ucwords(str_replace('_', ' ', $document->document_type)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($document->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Approved
                                        </span>
                                    @elseif($document->status === 'pending_review')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending Review
                                        </span>
                                    @elseif($document->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-question mr-1"></i>Unknown
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $document->uploaded_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('documents.upload.download', $document->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    @if($document->status !== 'approved')
                                        <form action="{{ route('documents.upload.destroy', $document->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
