@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Create Feature')

@section('title', 'Create Feature')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Feature</h1>
                <p class="mt-2 text-sm text-gray-600">
                    @if($version)
                        Add a new feature to version {{ $version->version }} - {{ $version->title }}
                    @else
                        Add a new feature
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-3">
                @if($version)
                    <a href="{{ route('version-manager.versions.show', $version) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Version
                    </a>
                @else
                    <a href="{{ route('version-manager.whats-new.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Features
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ route('version-manager.whats-new.store') }}">
                @csrf
                
                @if($version)
                    <input type="hidden" name="platform_version_id" value="{{ $version->id }}">
                @else
                    <div class="mb-6">
                        <label for="platform_version_id" class="block text-sm font-semibold text-gray-900 mb-2">Version <span class="text-red-500">*</span></label>
                        <select name="platform_version_id" id="platform_version_id" required class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Select a version...</option>
                            @foreach($versions as $ver)
                                <option value="{{ $ver->id }}" {{ old('platform_version_id') == $ver->id ? 'selected' : '' }}>
                                    {{ $ver->version }} - {{ $ver->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('platform_version_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="title" 
                           id="title"
                           value="{{ old('title') }}"
                           class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                           placeholder="Feature title" 
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="content" class="block text-sm font-semibold text-gray-900 mb-2">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" 
                              id="content"
                              rows="6" 
                              class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none" 
                              placeholder="Describe the feature...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>



                <div class="mb-6">
                    <label for="type" class="block text-sm font-semibold text-gray-900 mb-2">Type</label>
                    <select name="type" 
                            id="type" 
                            class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="feature" {{ old('type', 'feature') === 'feature' ? 'selected' : '' }}>Feature</option>
                        <option value="improvement" {{ old('type') === 'improvement' ? 'selected' : '' }}>Improvement</option>
                        <option value="bugfix" {{ old('type') === 'bugfix' ? 'selected' : '' }}>Bug Fix</option>
                        <option value="security" {{ old('type') === 'security' ? 'selected' : '' }}>Security</option>
                        <option value="deprecation" {{ old('type') === 'deprecation' ? 'selected' : '' }}>Deprecation</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Feature
                        </button>
                        @if($version)
                            <a href="{{ route('version-manager.versions.show', $version) }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                        @else
                            <a href="{{ route('version-manager.whats-new.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 