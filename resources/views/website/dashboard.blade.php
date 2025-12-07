@extends('layouts.app')

@section('title', 'Dashboard - Vipers Academy')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome to Your Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Quick Actions -->
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800 mb-2">Programs</h3>
                    <p class="text-blue-600 text-sm mb-3">View available training programs</p>
                    <a href="{{ route('programs') }}" class="text-blue-600 hover:text-blue-800 font-medium">Browse Programs →</a>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-green-800 mb-2">Products</h3>
                    <p class="text-green-600 text-sm mb-3">Shop for academy merchandise</p>
                    <a href="{{ route('products.index') }}" class="text-green-600 hover:text-green-800 font-medium">Shop Now →</a>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-purple-800 mb-2">Profile</h3>
                    <p class="text-purple-600 text-sm mb-3">Manage your account settings</p>
                    <a href="{{ route('profile.edit') }}" class="text-purple-600 hover:text-purple-800 font-medium">Edit Profile →</a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Activity</h2>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">Welcome to Vipers Academy! Explore our programs and services.</p>
                            <p class="text-xs text-gray-400">{{ now()->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
