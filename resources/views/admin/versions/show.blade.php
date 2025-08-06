@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Version Details')

@section('title', 'Version Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $version->title }}</h1>
                <p class="mt-2 text-sm text-gray-600">Version {{ $version->version }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $version->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $version->is_active ? 'Published' : 'Draft' }}
                </span>
                @if(!$version->is_active)
                    <form action="{{ route('version-manager.versions.update', $version) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="title" value="{{ $version->title }}">
                        <input type="hidden" name="released_at" value="{{ $version->released_at }}">
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Publish Version
                        </button>
                    </form>
                @endif
                <a href="{{ route('version-manager.versions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Versions
                </a>
            </div>
        </div>
    </div>

    <!-- Version Info -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Version</h3>
                    <p class="text-sm text-gray-600">{{ $version->version }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Release Date</h3>
                    <p class="text-sm text-gray-600">{{ $version->released_at ? $version->released_at->format('M d, Y H:i') : 'Not set' }}</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Status</h3>
                    <p class="text-sm text-gray-600">{{ $version->is_active ? 'Published' : 'Draft' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Features</h3>
                    <p class="text-sm text-gray-600">Manage features for this version</p>
                </div>
                <a href="{{ route('version-manager.whats-new.create', ['version_id' => $version->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Feature
                </a>
            </div>

            <!-- Features List -->
            @if($features->count() === 0)
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No features</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new feature.</p>
                    <div class="mt-6">
                        <a href="{{ route('version-manager.whats-new.create', ['version_id' => $version->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Feature
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($features as $feature)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($feature->status === 'draft') bg-gray-100 text-gray-800
                                        @elseif($feature->status === 'private') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($feature->status) }}
                                    </span>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $feature->title }}</h4>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('version-manager.whats-new.edit', $feature) }}" class="text-blue-600 hover:text-blue-900">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('version-manager.whats-new.destroy', $feature) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="platform_version_id" value="{{ $version->id }}">
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this feature?')">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                {!! nl2br(e($feature->content)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 