@extends('version-platform-manager::layouts.app')

@section('title', 'Example Page')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to the Example Page</h1>
            <p class="text-gray-600 mb-6">
                This page demonstrates the version update modal functionality. 
                If you have a newer version available, you should see a modal popup.
            </p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h2 class="text-lg font-semibold text-blue-900 mb-2">How it works:</h2>
                <ul class="text-blue-800 space-y-1">
                    <li>• The middleware checks if you need to see version updates</li>
                    <li>• If you do, a modal will appear automatically</li>
                    <li>• You can mark as read or skip for now</li>
                    <li>• Cookies prevent showing the same version again for 1 day</li>
                </ul>
            </div>
            
            <div class="mt-6">
                <a href="{{ route('version-manager.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Go to Version Manager
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 