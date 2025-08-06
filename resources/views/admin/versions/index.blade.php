@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Platform Versions')

@section('title', 'Platform Versions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Platform Versions</h1>
                <p class="mt-2 text-sm text-gray-600">Manage platform versions and what's new content</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Import/Export Dropdown -->
                <div class="relative inline-block text-left">
                    <div>
                        <button type="button" onclick="toggleImportExport()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            Import/Export
                            <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                    
                    <div id="importExportDropdown" class="hidden absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1" role="menu">
                            <!-- Import Section -->
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Import Features</h3>
                                <form method="POST" enctype="multipart/form-data" id="importForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Select Version</label>
                                        <select name="platform_version_id" required class="w-full px-2 py-1 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" id="importVersionSelect">
                                            <option value="">Choose a version...</option>
                                            @foreach($versions as $version)
                                                <option value="{{ $version->id }}">{{ $version->version }} - {{ $version->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                <form method="GET" id="exportForm">
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Select Version</label>
                                        <select name="platform_version_id" required class="w-full px-2 py-1 text-xs border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" id="exportVersionSelect">
                                            <option value="">Choose a version...</option>
                                            @foreach($versions as $version)
                                                <option value="{{ $version->id }}">{{ $version->version }} - {{ $version->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full px-3 py-1 text-xs font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Export Features
                                    </button>
                                </form>
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
**Status:** draft

- Reduced page load times by 40%
- Optimized database queries
- Improved caching mechanisms

## Bug Fixes
**Type:** bugfix
**Status:** published

Fixed several critical issues in the authentication system.</pre>
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('version-manager.versions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Version
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
                                <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['total_users'] }}</dd>
                                            <dd class="text-xs text-gray-500">
                                                @if($statistics['users_with_versions'] > 0)
                                                    {{ $statistics['users_with_versions'] }} with version data
                                                @else
                                                    No version tracking yet
                                                @endif
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

        <!-- Users on Latest -->
                                <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Users on Latest</dt>
                                            <dd class="text-lg font-medium text-green-600">{{ $statistics['users_on_latest'] }}</dd>
                                            <dd class="text-xs text-gray-500">
                                                @if($statistics['users_with_versions'] > 0)
                                                    {{ $statistics['adoption_rate'] }}% of tracked users
                                                @else
                                                    No version data
                                                @endif
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

        <!-- Users Needing Update -->
                                <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Need Update</dt>
                                            <dd class="text-lg font-medium text-yellow-600">{{ $statistics['users_needing_update'] }}</dd>
                                            <dd class="text-xs text-gray-500">
                                                @if($statistics['active_users'] > 0)
                                                    {{ $statistics['active_users'] }} active (30d)
                                                @else
                                                    No recent activity
                                                @endif
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

        <!-- Adoption Rate -->
                                <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Adoption Rate</dt>
                                            <dd class="text-lg font-medium text-purple-600">{{ $statistics['adoption_rate'] }}%</dd>
                                            <dd class="text-xs text-gray-500">
                                                @if($statistics['users_with_versions'] > 0)
                                                    of tracked users
                                                @else
                                                    No version data
                                                @endif
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>

    <!-- Versions Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Platform Versions</h3>
            <p class="mt-1 text-sm text-gray-500">Manage and track platform versions</p>
        </div>
        
        @if($versions->count() === 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No versions</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new version.</p>
                <div class="mt-6">
                    <a href="{{ route('version-manager.versions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Version
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Released</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($versions as $version)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $version->version }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $version->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($version->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $version->released_at?->format('M d, Y') ?? 'Not set' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $version->whatsNew->count() }} features
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('version-manager.versions.show', $version) }}" class="text-green-600 hover:text-green-900">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('version-manager.versions.edit', $version) }}" class="text-blue-600 hover:text-blue-900">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('version-manager.versions.destroy', $version) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this version?')">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($versions->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $versions->links() }}
                </div>
            @endif
        @endif
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

// Update form actions when version is selected
document.addEventListener('DOMContentLoaded', function() {
    const importForm = document.getElementById('importForm');
    const exportForm = document.getElementById('exportForm');
    
    // Import form
    importForm.addEventListener('submit', function(e) {
        const versionSelect = importForm.querySelector('select[name="platform_version_id"]');
        if (!versionSelect.value) {
            e.preventDefault();
            alert('Please select a version for import.');
            return;
        }
        
        // Set the form action with the selected version
        importForm.action = '{{ route("version-manager.whats-new.import-markdown", ["platformVersion" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', versionSelect.value);
    });
    
    // Export form
    exportForm.addEventListener('submit', function(e) {
        const versionSelect = exportForm.querySelector('select[name="platform_version_id"]');
        if (!versionSelect.value) {
            e.preventDefault();
            alert('Please select a version for export.');
            return;
        }
        
        // Set the form action with the selected version
        exportForm.action = '{{ route("version-manager.whats-new.export-markdown", ["platformVersion" => "PLACEHOLDER"]) }}'.replace('PLACEHOLDER', versionSelect.value);
    });
});
</script>
@endsection 