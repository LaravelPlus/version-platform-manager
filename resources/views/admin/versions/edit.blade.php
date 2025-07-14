@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Edit Platform Version')

@section('title', 'Edit Platform Version')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Platform Version</h1>
                <p class="mt-2 text-sm text-gray-600">Update version details</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('version-manager.versions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Versions
                </a>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('version-manager.versions.update', $version) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="version" class="block text-sm font-medium text-gray-700">Version <span class="text-red-500">*</span></label>
                        <input type="text" name="version" id="version" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('version') border-red-300 @enderror" 
                               placeholder="1.0.0" value="{{ old('version', $version->version) }}" required>
                        @error('version')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Use semantic versioning (e.g., 1.0.0, 1.1.0, 2.0.0)</p>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('title') border-red-300 @enderror" 
                               placeholder="Major Update" value="{{ old('title', $version->title) }}" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-300 @enderror" 
                              placeholder="Describe what this version includes...">{{ old('description', $version->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                    <div>
                        <label for="released_at" class="block text-sm font-medium text-gray-700">Release Date</label>
                        <input type="datetime-local" name="released_at" id="released_at" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('released_at') border-red-300 @enderror" 
                               value="{{ old('released_at', $version->released_at ? $version->released_at->format('Y-m-d\TH:i') : '') }}">
                        @error('released_at')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input id="is_active" name="is_active" type="checkbox" value="1" 
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded @error('is_active') border-red-300 @enderror"
                                   {{ old('is_active', $version->is_active) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700">Active</label>
                            <p class="text-gray-500">Active versions will be shown to users</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end space-x-3">
                    <a href="{{ route('version-manager.versions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Version
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 