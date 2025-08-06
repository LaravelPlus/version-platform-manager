@extends('version-platform-manager::layouts.app')

@section('title', 'Changelog')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Changelog</h1>
                <p class="text-gray-600">Track all updates and improvements to our platform</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($versions->count() > 0)
            <!-- Current Version Status -->
            @if($userVersion && $latestVersion)
                <div class="mb-8">
                    @if($userVersion->isOlderThan($latestVersion->version))
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-blue-800">Update Available</h3>
                                    <p class="text-sm text-blue-700">You're running version {{ $userVersion->version }}. The latest version is {{ $latestVersion->version }}.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-green-800">Up to Date</h3>
                                    <p class="text-sm text-green-700">You're running the latest version ({{ $userVersion->version }}).</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Version Timeline -->
            <div class="space-y-8">
                @foreach($versions as $version)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        <!-- Version Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-2xl font-bold text-gray-900">{{ $version->version }}</span>
                                        @if($version->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Latest
                                            </span>
                                        @endif
                                    </div>
                                    @if($userVersion && $userVersion->isOlderThan($version->version))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            New
                                        </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if($version->released_at)
                                        <div class="text-sm text-gray-500">
                                            {{ $version->released_at->format('M j, Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($version->title)
                                <h3 class="text-lg font-semibold text-gray-900 mt-2">{{ $version->title }}</h3>
                            @endif
                            
                            @if($version->description)
                                <p class="text-gray-600 mt-1">{{ $version->description }}</p>
                            @endif
                        </div>

                        <!-- Features -->
                        @if($version->whatsNew->count() > 0)
                            <div class="px-6 py-4">
                                <div class="space-y-3">
                                    @foreach($version->whatsNew as $feature)
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <span class="text-lg">{{ $feature->type_icon }}</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                        @if($feature->type === 'feature') bg-blue-100 text-blue-800
                                                        @elseif($feature->type === 'improvement') bg-green-100 text-green-800
                                                        @elseif($feature->type === 'bugfix') bg-red-100 text-red-800
                                                        @elseif($feature->type === 'security') bg-yellow-100 text-yellow-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst($feature->type) }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600">
                                                    {{ $feature->content }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-500 italic">No detailed features available for this version.</p>
                            </div>
                        @endif

                        <!-- Mark as Read Button (only for versions newer than user's version) -->
                        @if($userVersion && $userVersion->isOlderThan($version->version))
                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                                <form action="{{ route('version-platform-manager.whats-new.mark-read') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="version_id" value="{{ $version->id }}">
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Mark as Read
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <div class="inline-block bg-white rounded-lg border border-gray-200 px-6 py-4">
                    <p class="text-sm text-gray-600">
                        {!! config('version-platform-manager.whats_new_signature', 'The Development Team') !!}
                    </p>
                </div>
            </div>

        @else
            <!-- No Versions Available -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8 text-center">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No versions available</h3>
                <p class="text-gray-600">There are no platform versions configured yet.</p>
            </div>
        @endif
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50" id="success-message">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const message = document.getElementById('success-message');
            if (message) {
                message.style.opacity = '0';
                message.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    message.style.display = 'none';
                }, 300);
            }
        }, 3000);
    </script>
@endif
@endsection 