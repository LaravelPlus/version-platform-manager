@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Version Details')

@section('title', 'Version Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $version->title ?: 'Version ' . $version->version }}</h1>
                <p class="mt-2 text-sm text-gray-600">Version {{ $version->version }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Import/Export Dropdown -->
                <div class="relative">
                    <button type="button" onclick="toggleImportExport()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Import/Export
                        <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div id="importExportDropdown" class="hidden absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1" role="menu">
                            <!-- Import Section -->
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Import Features</h3>
                                <form method="POST" enctype="multipart/form-data" id="importForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Markdown File</label>
                                        <input type="file" name="markdown_file" accept=".md,.txt" required class="w-full text-xs">
                                    </div>
                                    <button type="submit" class="w-full px-3 py-1 text-xs font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Import Features
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Export Section -->
                            <div class="px-4 py-3">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Export Features</h3>
                                <a href="{{ route('version-manager.whats-new.export-markdown', $version) }}" class="w-full px-3 py-1 text-xs font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 inline-block text-center">
                                    Export Features
                                </a>
                            </div>
                            
                            <!-- Help Section -->
                            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Import Format</h3>
                                <details class="text-xs text-gray-600">
                                    <summary class="cursor-pointer hover:text-gray-800">Show sample format</summary>
                                    <div class="mt-2 p-2 bg-white rounded border text-xs font-mono">
<pre>## New User Dashboard
**Type:** feature
**Status:** published

This release introduces a completely redesigned user dashboard with improved navigation and better data visualization.

## Performance Improvements
**Type:** improvement
**Status:** published

- Reduced page load times by 40%
- Optimized database queries
- Improved caching mechanisms</pre>
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>
                </div>

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
            @if($version->description)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900">Description</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $version->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Features ({{ $features->count() }})</h3>
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
                        <div class="border border-gray-200 rounded-lg p-6 hover:border-gray-300 transition-colors duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Feature Title and Badges -->
                                    <div class="flex items-center space-x-2 mb-3">
                                        <h4 class="text-lg font-medium text-gray-900">
                                            {{ $feature->title ?: 'Untitled Feature' }}
                                        </h4>
                                        
                                        <!-- Type Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($feature->type === 'feature') bg-blue-100 text-blue-800
                                            @elseif($feature->type === 'improvement') bg-green-100 text-green-800
                                            @elseif($feature->type === 'bugfix') bg-red-100 text-red-800
                                            @elseif($feature->type === 'security') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($feature->type) }}
                                        </span>
                                        
                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($feature->status === 'draft') bg-gray-100 text-gray-800
                                            @elseif($feature->status === 'private') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($feature->status) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Feature Content with Markdown Rendering -->
                                    <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                                        {!! Str::markdown($feature->content) !!}
                                    </div>
                                    
                                    <!-- Feature Meta -->
                                    <div class="mt-3 flex items-center space-x-4 text-xs text-gray-500">
                                        <span>Created: {{ $feature->created_at->format('M j, Y') }}</span>
                                        @if($feature->updated_at != $feature->created_at)
                                            <span>Updated: {{ $feature->updated_at->format('M j, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('version-manager.whats-new.edit', $feature) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('version-manager.whats-new.destroy', $feature) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="platform_version_id" value="{{ $version->id }}">
                                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete this feature?')">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleImportExport() {
    const dropdown = document.getElementById('importExportDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('importExportDropdown');
    const button = event.target.closest('button');
    
    if (!dropdown.contains(event.target) && !button) {
        dropdown.classList.add('hidden');
    }
});

// Set import form action when form is submitted
document.addEventListener('DOMContentLoaded', function() {
    const importForm = document.getElementById('importForm');
    
    importForm.addEventListener('submit', function(e) {
        // Set the form action with the current version
        importForm.action = '{{ route("version-manager.whats-new.import-markdown", $version) }}';
    });
});
</script>
@endsection 