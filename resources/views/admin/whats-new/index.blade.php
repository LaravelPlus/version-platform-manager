@extends('layouts.admin')

@section('title', 'What\'s New Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">What's New Management</h1>
        <a href="{{ route('version-manager.whats-new.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Entry
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="version-filter" class="block text-sm font-medium text-gray-700 mb-1">Version</label>
                    <select id="version-filter" v-model="filters.version" @change="filterContent" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Versions</option>
                        <option v-for="version in versions" :key="version.id" :value="version.id">@{{ version.version }} - @{{ version.title }}</option>
                    </select>
                </div>
                <div>
                    <label for="type-filter" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="type-filter" v-model="filters.type" @change="filterContent" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="feature">Feature</option>
                        <option value="improvement">Improvement</option>
                        <option value="bugfix">Bug Fix</option>
                        <option value="security">Security</option>
                        <option value="deprecation">Deprecation</option>
                    </select>
                </div>
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status-filter" v-model="filters.status" @change="filterContent" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button @click="clearFilters" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div v-if="loading" class="flex justify-center items-center py-8">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            <div v-else-if="filteredContent.length === 0" class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No content found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new "What's New" entry.</p>
                <div class="mt-6">
                    <a href="{{ route('version-manager.whats-new.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Entry
                    </a>
                </div>
            </div>
            
            <div v-else class="space-y-4">
                <div v-for="item in filteredContent" :key="item.id" class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span :class="getTypeBadgeClass(item.type)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    @{{ getTypeIcon(item.type) }} @{{ getTypeLabel(item.type) }}
                                </span>
                                <span v-if="item.is_active" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                                <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">@{{ item.title }}</h3>
                            <p class="text-sm text-gray-600 mb-2">@{{ item.content }}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span>Version: @{{ getVersionLabel(item.platform_version_id) }}</span>
                                <span>Order: @{{ item.sort_order }}</span>
                                <span>Created: @{{ formatDate(item.created_at) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <a :href="`/admin/version-manager/whats-new/${item.id}/edit`" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                Edit
                            </a>
                            <button @click="deleteItem(item.id)" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            content: @json($whatsNew ?? []),
            versions: @json($versions ?? []),
            loading: false,
            filters: {
                version: '',
                type: '',
                status: ''
            }
        }
    },
    computed: {
        filteredContent() {
            let filtered = this.content;
            
            if (this.filters.version) {
                filtered = filtered.filter(item => item.platform_version_id == this.filters.version);
            }
            
            if (this.filters.type) {
                filtered = filtered.filter(item => item.type === this.filters.type);
            }
            
            if (this.filters.status !== '') {
                filtered = filtered.filter(item => item.is_active == this.filters.status);
            }
            
            return filtered.sort((a, b) => a.sort_order - b.sort_order);
        }
    },
    methods: {
        filterContent() {
            // Filtering is handled by computed property
        },
        clearFilters() {
            this.filters = {
                version: '',
                type: '',
                status: ''
            };
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
        getVersionLabel(versionId) {
            const version = this.versions.find(v => v.id == versionId);
            return version ? `${version.version} - ${version.title}` : 'Unknown';
        },
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString();
        },
        async deleteItem(id) {
            if (!confirm('Are you sure you want to delete this entry?')) {
                return;
            }
            
            try {
                const response = await fetch(`/admin/version-manager/whats-new/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    this.content = this.content.filter(item => item.id !== id);
                } else {
                    alert('Error deleting item');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting item');
            }
        }
    }
}).mount('#whats-new-app');
</script>
@endsection 