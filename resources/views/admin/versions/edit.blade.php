@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Edit Platform Version')

@section('title', 'Edit Platform Version')

@section('content')
<div id="edit-version-app" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
            <form @submit.prevent="submitForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="version" class="block text-sm font-semibold text-gray-900 mb-2">Version <span class="text-red-500">*</span></label>
                        <input type="text" 
                               v-model="form.version" 
                               @blur="validateVersion"
                               :class="['block w-full px-4 py-3 rounded-lg border shadow-sm text-sm transition-all duration-200', 
                                        versionError ? 'border-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50']"
                               placeholder="1.0.0" 
                               required>
                        <p v-if="versionError" class="mt-2 text-sm text-red-600">@{{ versionError }}</p>
                        <p class="mt-2 text-xs text-gray-500">Use semantic versioning (e.g., 1.0.0, 1.1.0, 2.0.0)</p>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" 
                               v-model="form.title" 
                               @blur="validateTitle"
                               :class="['block w-full px-4 py-3 rounded-lg border shadow-sm text-sm transition-all duration-200', 
                                        titleError ? 'border-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50']"
                               placeholder="Major Update" 
                               required>
                        <p v-if="titleError" class="mt-2 text-sm text-red-600">@{{ titleError }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                    <textarea v-model="form.description" 
                              @input="updateDescriptionCount"
                              rows="4" 
                              class="block w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 bg-white hover:bg-gray-50 resize-none" 
                              placeholder="Describe what this version includes..."></textarea>
                    <p class="mt-2 text-xs text-gray-500">@{{ descriptionCount }}/500 characters</p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                    <div>
                        <label for="released_at" class="block text-sm font-semibold text-gray-900 mb-2">Release Date</label>
                        <input type="datetime-local" 
                               v-model="form.released_at" 
                               class="block w-full px-4 py-3 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 bg-white hover:bg-gray-50">
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input id="is_active" 
                                   v-model="form.is_active" 
                                   type="checkbox" 
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
                            <button type="button" @click="exportMarkdown" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Export
                            </button>
                            <button type="button" @click="importMarkdown" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Import
                            </button>
                            <input type="file" ref="markdownFile" @change="handleFileImport" accept=".md,.txt" class="hidden">
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
                                    v-model="whatsNewMarkdown" 
                                    @input="updatePreview"
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

The old API endpoint will be removed in version 2.0.">
                                </textarea>
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
                                    <div v-if="!whatsNewMarkdown.trim()" class="text-gray-400 text-center py-12">
                                        <svg class="mx-auto h-16 w-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <p class="text-sm font-medium">Start typing to see preview</p>
                                        <p class="text-xs mt-1">Your content will appear here as you type</p>
                                    </div>
                                    <div v-else v-html="markdownPreview" class="prose prose-sm max-w-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div v-if="showPreview" class="mt-8 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Version Preview</h3>
                    <div class="bg-white p-4 rounded border">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                @{{ form.version }}
                            </span>
                            <span v-if="form.is_active" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Inactive
                            </span>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900">@{{ form.title }}</h4>
                        <p v-if="form.description" class="text-gray-600 mt-2">@{{ form.description }}</p>
                        <p v-if="form.released_at" class="text-sm text-gray-500 mt-2">Released: @{{ formatDate(form.released_at) }}</p>
                        

                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button type="button" @click="togglePreview" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            @{{ showPreview ? 'Hide' : 'Show' }} Preview
                        </button>
                        <button type="button" @click="resetForm" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset to Original
                        </button>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('version-manager.versions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                :disabled="!isFormValid || isSubmitting"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            @{{ isSubmitting ? 'Updating...' : 'Update Version' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            originalData: {
                version: '{{ $version->version }}',
                title: '{{ $version->title }}',
                description: '{{ $version->description }}',
                released_at: '{{ $version->released_at ? $version->released_at->format("Y-m-d\TH:i") : "" }}',
                is_active: {{ $version->is_active ? 'true' : 'false' }}
            },
            form: {
                version: '{{ $version->version }}',
                title: '{{ $version->title }}',
                description: '{{ $version->description }}',
                released_at: '{{ $version->released_at ? $version->released_at->format("Y-m-d\TH:i") : "" }}',
                is_active: {{ $version->is_active ? 'true' : 'false' }}
            },
            whatsNewMarkdown: @json($version->whatsNewMarkdown ?? ''),
            markdownPreview: '',
            versionError: '',
            titleError: '',
            descriptionCount: {{ strlen($version->description) }},
            showPreview: false,
            isSubmitting: false
        }
    },
    computed: {
        isFormValid() {
            return this.form.version && 
                   this.form.title && 
                   !this.versionError && 
                   !this.titleError &&
                   this.form.description.length <= 500;
        },
        hasChanges() {
            return JSON.stringify(this.form) !== JSON.stringify(this.originalData);
        }
    },
    methods: {
        validateVersion() {
            const versionRegex = /^\d+\.\d+\.\d+$/;
            if (!this.form.version) {
                this.versionError = 'Version is required';
            } else if (!versionRegex.test(this.form.version)) {
                this.versionError = 'Version must be in semantic format (e.g., 1.0.0)';
            } else {
                this.versionError = '';
            }
        },
        validateTitle() {
            if (!this.form.title) {
                this.titleError = 'Title is required';
            } else if (this.form.title.length < 3) {
                this.titleError = 'Title must be at least 3 characters';
            } else if (this.form.title.length > 255) {
                this.titleError = 'Title must be less than 255 characters';
            } else {
                this.titleError = '';
            }
        },
        updateDescriptionCount() {
            this.descriptionCount = this.form.description.length;
        },
        exportMarkdown() {
            if (this.whatsNewMarkdown.trim()) {
                const blob = new Blob([this.whatsNewMarkdown], { type: 'text/markdown' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `whats-new-v${this.form.version}.md`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        },
        importMarkdown() {
            this.$refs.markdownFile.click();
        },
        handleFileImport(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.whatsNewMarkdown = e.target.result;
                    this.updatePreview();
                };
                reader.readAsText(file);
            }
        },
        updatePreview() {
            // Simple markdown to HTML conversion
            let html = this.whatsNewMarkdown
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
            
            this.markdownPreview = html;
        },
        togglePreview() {
            this.showPreview = !this.showPreview;
        },
        resetForm() {
            this.form = { ...this.originalData };
            this.whatsNewMarkdown = @json($version->whatsNewMarkdown ?? '');
            this.markdownPreview = '';
            this.updatePreview();
            this.versionError = '';
            this.titleError = '';
            this.descriptionCount = this.form.description.length;
            this.showPreview = false;
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        },
        async submitForm() {
            if (!this.isFormValid) return;
            
            this.isSubmitting = true;
            
            try {
                const formData = new FormData();
                formData.append('version', this.form.version);
                formData.append('title', this.form.title);
                formData.append('description', this.form.description);
                formData.append('released_at', this.form.released_at);
                formData.append('is_active', this.form.is_active ? '1' : '0');
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('_method', 'PUT');
                
                // Add What's New markdown content
                if (this.whatsNewMarkdown.trim()) {
                    formData.append('whats_new_markdown', this.whatsNewMarkdown);
                }
                
                const response = await fetch('{{ route("version-manager.versions.update", $version) }}', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    window.location.href = '{{ route("version-manager.versions.index") }}';
                } else {
                    const data = await response.json();
                    if (data.errors) {
                        if (data.errors.version) this.versionError = data.errors.version[0];
                        if (data.errors.title) this.titleError = data.errors.title[0];
                    }
                }
            } catch (error) {
                console.error('Error submitting form:', error);
            } finally {
                this.isSubmitting = false;
            }
        }
    },
    mounted() {
        this.updateDescriptionCount();
    }
}).mount('#edit-version-app');
</script>
@endsection 