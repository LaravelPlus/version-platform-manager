@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Create Platform Version')

@section('title', 'Create Platform Version')

@section('content')
<div id="create-version-app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Platform Version</h1>
                <p class="mt-2 text-sm text-gray-600">Create a new version and add features later</p>
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
        <div class="px-6 py-8">
            <form @submit.prevent="submitForm" method="POST" action="{{ route('version-manager.versions.store') }}" onsubmit="return window.submitFormHandler ? window.submitFormHandler(event) : true;">
                @csrf
                <input type="hidden" name="version_type" v-model="versionType" value="patch">
                <!-- Fallback for when JavaScript is disabled -->
                <noscript>
                    <input type="hidden" name="version_type" value="patch">
                </noscript>
                
                <div class="space-y-6">
                    <!-- Version Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-400 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                                                    <div>
                                        <h3 class="text-sm font-medium text-blue-800">Version Increment</h3>
                                        <p class="text-sm text-blue-700">
                                            @if($latestVersion)
                                                Current: <span class="font-mono">{{ $latestVersion->version }}</span> → 
                                                Next: <span class="font-mono font-bold">@{{ getNextVersion() }}</span>
                                            @else
                                                This will create version <span class="font-mono font-bold">{{ $nextVersion }}</span>
                                            @endif
                                        </p>
                                    </div>
                            </div>
                            @if($latestVersion && !empty($nextVersions))
                                <div class="flex space-x-2">
                                    <button type="button" @click="selectVersionType('patch')" :class="['px-3 py-1 text-xs rounded-full border', versionType === 'patch' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50']">
                                        Patch (@{{ nextVersions.patch }})
                                    </button>
                                    <button type="button" @click="selectVersionType('minor')" :class="['px-3 py-1 text-xs rounded-full border', versionType === 'minor' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50']">
                                        Minor (@{{ nextVersions.minor }})
                                    </button>
                                    <button type="button" @click="selectVersionType('major')" :class="['px-3 py-1 text-xs rounded-full border', versionType === 'major' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50']">
                                        Major (@{{ nextVersions.major }})
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-3">Title <span class="text-red-500">*</span></label>
                        <input type="text" 
                               v-model="form.title" 
                               @blur="validateTitle"
                               :class="['block w-full px-4 py-3 rounded-lg border-2 shadow-sm text-sm transition-all duration-200', 
                                        titleError ? 'border-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-red-50' : 'border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50']"
                               placeholder="Enter version title (e.g., Major Update, Bug Fix Release)" 
                               required>
                        <p v-if="titleError" class="mt-2 text-sm text-red-600">@{{ titleError }}</p>
                        <p class="mt-1 text-sm text-gray-500">A descriptive title for this version</p>
                    </div>

                    <div>
                        <label for="released_at" class="block text-sm font-semibold text-gray-900 mb-3">Release Date</label>
                        <input type="datetime-local" 
                               v-model="form.released_at" 
                               class="block w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all duration-200 bg-white hover:bg-gray-50">
                        <p class="mt-1 text-sm text-gray-500">When this version was or will be released (optional)</p>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button type="submit" 
                                :disabled="isSubmitting || hasErrors"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            @{{ isSubmitting ? 'Creating...' : 'Create Version' }}
                        </button>
                        <a href="{{ route('version-manager.versions.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Global fallback submit handler
window.submitFormHandler = function(event) {
    // If Vue.js is not loaded, allow normal form submission
    if (typeof Vue === 'undefined') {
        return true;
    }
    return false; // Let Vue handle it
};

const { createApp } = Vue;

createApp({
    data() {
        return {
            form: {
                title: '',
                released_at: '',
                is_active: false // Draft by default
            },
            titleError: '',
            isSubmitting: false,
            versionType: 'patch',
            nextVersions: @json($nextVersions ?? []),
            latestVersion: @json($latestVersion ? $latestVersion->version : null)
        }
    },
    computed: {
        hasErrors() {
            return this.titleError || !this.form.title;
        }
    },
    methods: {
        getNextVersion() {
            if (!this.latestVersion || !this.nextVersions[this.versionType]) {
                return '1.0.0';
            }
            return this.nextVersions[this.versionType];
        },
        
        validateTitle() {
            if (!this.form.title) {
                this.titleError = 'Title is required';
                return;
            }
            
            if (this.form.title.length < 3) {
                this.titleError = 'Title must be at least 3 characters';
                return;
            }
            
            this.titleError = '';
        },
        
        selectVersionType(type) {
            this.versionType = type;
        },
        
        async submitForm() {
            this.validateTitle();
            
            if (this.hasErrors) {
                return;
            }
            
            this.isSubmitting = true;
            
            // Update the hidden input with current version type
            document.querySelector('input[name="version_type"]').value = this.versionType;
            
            try {
                const formData = new FormData();
                formData.append('title', this.form.title);
                formData.append('released_at', this.form.released_at);
                formData.append('is_active', this.form.is_active ? '1' : '0');
                formData.append('version_type', this.versionType);
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                
                const response = await fetch('{{ route("version-manager.versions.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Success:', data);
                    // Redirect to the version show page to add features
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        console.error('No redirect URL in response');
                    }
                } else {
                    const data = await response.json();
                    console.error('Error response:', data);
                    if (data.errors) {
                        if (data.errors.title) {
                            this.titleError = data.errors.title[0];
                        }
                        if (data.errors.version_type) {
                            console.error('Version type error:', data.errors.version_type[0]);
                        }
                    }
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                // Fallback: try to redirect to versions index
                window.location.href = '{{ route("version-manager.versions.index") }}';
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}).mount('#create-version-app');
</script>
@endsection 