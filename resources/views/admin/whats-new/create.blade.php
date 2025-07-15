@extends('layouts.admin')

@section('title', 'Add New What\'s New Entry')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Add New What's New Entry</h1>
            <a href="{{ route('version-manager.whats-new.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form @submit.prevent="submitForm" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Platform Version -->
                    <div>
                        <label for="platform_version_id" class="block text-sm font-medium text-gray-700 mb-2">Platform Version *</label>
                        <select id="platform_version_id" v-model="form.platform_version_id" @change="validateForm" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.platform_version_id }">
                            <option value="">Select a version</option>
                            <option v-for="version in versions" :key="version.id" :value="version.id">@{{ version.version }} - @{{ version.title }}</option>
                        </select>
                        <p v-if="errors.platform_version_id" class="mt-1 text-sm text-red-600">@{{ errors.platform_version_id }}</p>
                    </div>

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" v-model="form.title" @input="validateForm" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.title }" placeholder="Enter a descriptive title">
                        <p v-if="errors.title" class="mt-1 text-sm text-red-600">@{{ errors.title }}</p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select id="type" v-model="form.type" @change="validateForm" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.type }">
                            <option value="">Select a type</option>
                            <option value="feature">🎉 Feature</option>
                            <option value="improvement">⚡ Improvement</option>
                            <option value="bugfix">🐛 Bug Fix</option>
                            <option value="security">🔒 Security</option>
                            <option value="deprecation">⚠️ Deprecation</option>
                        </select>
                        <p v-if="errors.type" class="mt-1 text-sm text-red-600">@{{ errors.type }}</p>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                        <textarea id="content" v-model="form.content" @input="validateForm" rows="6" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.content }" placeholder="Describe the new feature, improvement, or change..."></textarea>
                        <p v-if="errors.content" class="mt-1 text-sm text-red-600">@{{ errors.content }}</p>
                        <p class="mt-1 text-sm text-gray-500">You can use Markdown formatting for rich content.</p>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" id="sort_order" v-model="form.sort_order" @input="validateForm" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" :class="{ 'border-red-500': errors.sort_order }" placeholder="0" min="0">
                        <p v-if="errors.sort_order" class="mt-1 text-sm text-red-600">@{{ errors.sort_order }}</p>
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first. Leave empty for auto-ordering.</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" v-model="form.is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Inactive entries won't be shown to users.</p>
                    </div>
                </div>

                <!-- Preview -->
                <div v-if="form.title && form.content" class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-3">
                            <span :class="getTypeBadgeClass(form.type)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                @{{ getTypeIcon(form.type) }} @{{ getTypeLabel(form.type) }}
                            </span>
                            <span v-if="form.is_active" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">@{{ form.title }}</h4>
                        <p class="text-gray-600 whitespace-pre-wrap">@{{ form.content }}</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('version-manager.whats-new.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" :disabled="!isFormValid || submitting" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg v-if="submitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        @{{ submitting ? 'Creating...' : 'Create Entry' }}
                    </button>
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
            versions: @json($versions ?? []),
            form: {
                platform_version_id: '',
                title: '',
                content: '',
                type: '',
                sort_order: '',
                is_active: true
            },
            errors: {},
            submitting: false
        }
    },
    computed: {
        isFormValid() {
            return this.form.platform_version_id && 
                   this.form.title && 
                   this.form.content && 
                   this.form.type &&
                   Object.keys(this.errors).length === 0;
        }
    },
    methods: {
        validateForm() {
            this.errors = {};
            
            if (!this.form.platform_version_id) {
                this.errors.platform_version_id = 'Please select a platform version';
            }
            
            if (!this.form.title) {
                this.errors.title = 'Title is required';
            } else if (this.form.title.length < 3) {
                this.errors.title = 'Title must be at least 3 characters';
            }
            
            if (!this.form.content) {
                this.errors.content = 'Content is required';
            } else if (this.form.content.length < 10) {
                this.errors.content = 'Content must be at least 10 characters';
            }
            
            if (!this.form.type) {
                this.errors.type = 'Please select a type';
            }
            
            if (this.form.sort_order && (isNaN(this.form.sort_order) || this.form.sort_order < 0)) {
                this.errors.sort_order = 'Sort order must be a positive number';
            }
        },
        getTypeBadgeClass(type) {
            const classes = {
                'feature': 'bg-blue-100 text-blue-800',
                'improvement': 'bg-green-100 text-green-800',
                'bugfix': 'bg-yellow-100 text-yellow-800',
                'security': 'bg-red-100 text-red-800',
                'deprecation': 'bg-orange-100 text-orange-800'
            };
            return classes[type] || 'bg-gray-100 text-gray-800';
        },
        getTypeIcon(type) {
            const icons = {
                'feature': '🎉',
                'improvement': '⚡',
                'bugfix': '🐛',
                'security': '🔒',
                'deprecation': '⚠️'
            };
            return icons[type] || '📝';
        },
        getTypeLabel(type) {
            return type.charAt(0).toUpperCase() + type.slice(1);
        },
        async submitForm() {
            this.validateForm();
            
            if (!this.isFormValid) {
                return;
            }
            
            this.submitting = true;
            
            try {
                const response = await fetch('{{ route("version-manager.whats-new.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });
                
                if (response.ok) {
                    window.location.href = '{{ route("version-manager.whats-new.index") }}';
                } else {
                    const data = await response.json();
                    if (data.errors) {
                        this.errors = data.errors;
                    } else {
                        alert('Error creating entry');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error creating entry');
            } finally {
                this.submitting = false;
            }
        }
    },
    mounted() {
        this.validateForm();
    }
}).mount('#whats-new-create-app');
</script>
@endsection 