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
            <form method="POST" action="{{ route('version-manager.versions.update', $version) }}">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="version" class="block text-sm font-semibold text-gray-900 mb-2">Version <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="version"
                               value="{{ old('version', $version->version) }}"
                               class="block w-full px-4 py-3 rounded-lg border-2 shadow-sm text-sm transition-all duration-200 {{ $errors->has('version') ? 'border-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50' }}"
                               placeholder="1.0.0" 
                               required>
                        @error('version')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Use semantic versioning (e.g., 1.0.0, 1.1.0, 2.0.0)</p>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="title"
                               value="{{ old('title', $version->title) }}"
                               class="block w-full px-4 py-3 rounded-lg border-2 shadow-sm text-sm transition-all duration-200 {{ $errors->has('title') ? 'border-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50' }}"
                               placeholder="Major Update" 
                               required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                    <textarea name="description"
                              rows="4" 
                              class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 bg-white hover:bg-gray-50 resize-none" 
                              placeholder="Describe what this version includes...">{{ old('description', $version->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                    <div>
                        <label for="released_at" class="block text-sm font-semibold text-gray-900 mb-2">Release Date</label>
                        <input type="datetime-local" 
                               name="released_at"
                               value="{{ old('released_at', $version->released_at ? $version->released_at->format('Y-m-d\TH:i') : '') }}"
                               class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 bg-white hover:bg-gray-50">
                        @error('released_at')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input id="is_active" 
                                   name="is_active"
                                   type="checkbox" 
                                   value="1"
                                   {{ old('is_active', $version->is_active) ? 'checked' : '' }}
                                   class="focus:ring-2 focus:ring-blue-500 h-5 w-5 text-blue-600 border-gray-300 rounded transition-all duration-200">
                        </div>
                        <div class="ml-3">
                            <label for="is_active" class="text-sm font-semibold text-gray-900">Active</label>
                            <p class="text-xs text-gray-500">Active versions will be shown to users</p>
                        </div>
                    </div>
                </div>

                <!-- What's New Section -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">What's New Content</h3>
                            <p class="text-sm text-gray-600 mt-1">Write your version updates in Markdown format</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('version-manager.whats-new.export-markdown', $version) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export
                            </a>
                            <button type="button" onclick="document.getElementById('markdownFile').click()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import
                            </button>
                            <input type="file" id="markdownFile" accept=".md,.txt" class="hidden" onchange="handleFileImport(event)">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Editor -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <label class="block text-sm font-semibold text-gray-900">Markdown Editor</label>
                                <p class="text-xs text-gray-500 mt-1">Use emojis to categorize: 🎉 Feature, ⚡ Improvement, 🐛 Bug Fix, 🔒 Security, ⚠️ Deprecation</p>
                            </div>
                            <div class="p-6">
                                <textarea 
                                    name="whats_new_markdown"
                                    rows="24" 
                                    class="block w-full border-0 bg-gray-50 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono resize-none transition-all duration-200" 
                                    placeholder="🎉 New Feature

This is a great new feature that does amazing things!

⚡ Performance Improvement

Made the app 50% faster by optimizing database queries.

🐛 Bug Fix

Fixed the login issue that was affecting some users.

🔒 Security Update

Enhanced password requirements and added 2FA support.

⚠️ Deprecation Notice

The old API endpoint will be removed in version 2.0.">{{ old('whats_new_markdown', $version->whatsNewMarkdown ?? '') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Preview -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <label class="block text-sm font-semibold text-gray-900">Live Preview</label>
                                <p class="text-xs text-gray-500 mt-1">See how your content will appear to users</p>
                            </div>
                            <div class="p-6">
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 min-h-[400px] overflow-y-auto">
                                    <div id="preview-content" class="prose prose-sm max-w-none">
                                        @if($version->whatsNewMarkdown)
                                            {!! \Illuminate\Support\Str::markdown($version->whatsNewMarkdown) !!}
                                        @else
                                            <div class="text-gray-400 text-center py-12">
                                                <svg class="mx-auto h-16 w-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <p class="text-sm font-medium">Start typing to see preview</p>
                                                <p class="text-xs mt-1">Your content will appear here as you type</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="resetForm()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset to Original
                        </button>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('version-manager.versions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Version
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function handleFileImport(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const textarea = document.querySelector('textarea[name="whats_new_markdown"]');
            textarea.value = e.target.result;
            updatePreview();
        };
        reader.readAsText(file);
    }
}

function updatePreview() {
    const textarea = document.querySelector('textarea[name="whats_new_markdown"]');
    const preview = document.getElementById('preview-content');
    
    if (textarea.value.trim()) {
        // Simple markdown to HTML conversion
        let html = textarea.value
            .replace(/^### (.*$)/gim, '<h3>$1</h3>')
            .replace(/^## (.*$)/gim, '<h2>$1</h2>')
            .replace(/^# (.*$)/gim, '<h1>$1</h1>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/`(.*?)`/g, '<code>$1</code>')
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>');
        
        if (html) {
            html = '<p>' + html + '</p>';
        }
        
        preview.innerHTML = html;
    } else {
        preview.innerHTML = `
            <div class="text-gray-400 text-center py-12">
                <svg class="mx-auto h-16 w-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <p class="text-sm font-medium">Start typing to see preview</p>
                <p class="text-xs mt-1">Your content will appear here as you type</p>
            </div>
        `;
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        window.location.reload();
    }
}

// Update preview when textarea changes
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="whats_new_markdown"]');
    if (textarea) {
        textarea.addEventListener('input', updatePreview);
    }
});
</script>
@endsection 