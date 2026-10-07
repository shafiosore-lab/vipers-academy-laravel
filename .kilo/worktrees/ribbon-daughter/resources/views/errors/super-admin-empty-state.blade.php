@extends('layouts.admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8 text-center">
        <div class="mb-6">
            <i class="{{ $icon ?? 'fas fa-cog fa-spin' }} text-6xl text-blue-500"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $title ?? 'System Synchronization in Progress' }}</h1>
        <p class="text-gray-600 mb-6">{{ $message ?? 'Access granted, but this resource is currently synchronizing across the system.' }}</p>
        <div class="space-y-2">
            <p class="text-sm text-gray-500">As a Super Administrator, you have full access to all system features.</p>
            <p class="text-sm text-gray-500">This empty state indicates the data is being processed or is temporarily unavailable.</p>
        </div>
        <div class="mt-6">
            <a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Return to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection