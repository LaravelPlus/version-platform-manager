@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Version Manager Dashboard')

@section('title', 'Version Manager Dashboard')

@section('content')
<div id="dashboard-app" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Version Manager Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600">Overview of your platform version management system</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    System Active
                </span>
                <button @click="refreshData" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Versions</dt>
                            <dd class="text-lg font-medium text-gray-900">@{{ statistics.total_versions }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Versions</dt>
                            <dd class="text-lg font-medium text-gray-900">@{{ statistics.active_versions }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

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
                            <dd class="text-lg font-medium text-gray-900">@{{ statistics.total_users }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">This Month</dt>
                            <dd class="text-lg font-medium text-gray-900">@{{ statistics.versions_this_month }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quick Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('version-manager.versions.create') }}" class="group bg-white p-4 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 hover:shadow-md flex items-start space-x-4">
                            <span class="rounded-lg inline-flex p-3 bg-blue-50 text-blue-700 ring-4 ring-white flex-shrink-0">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-lg font-medium text-gray-900">Create New Version</span>
                                <span class="block mt-1 text-sm text-gray-500">Add a new platform version with release notes</span>
                            </span>
                        </a>
                        <a href="{{ route('version-manager.versions.index') }}" class="group bg-white p-4 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 hover:shadow-md flex items-start space-x-4">
                            <span class="rounded-lg inline-flex p-3 bg-green-50 text-green-700 ring-4 ring-white flex-shrink-0">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-lg font-medium text-gray-900">Manage Versions</span>
                                <span class="block mt-1 text-sm text-gray-500">View and edit all platform versions</span>
                            </span>
                        </a>
                        <a href="{{ route('version-manager.users.index') }}" class="group bg-white p-4 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 hover:shadow-md flex items-start space-x-4">
                            <span class="rounded-lg inline-flex p-3 bg-purple-50 text-purple-700 ring-4 ring-white flex-shrink-0">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-lg font-medium text-gray-900">User Management</span>
                                <span class="block mt-1 text-sm text-gray-500">Manage users and their permissions</span>
                            </span>
                        </a>
                        <a href="{{ route('version-manager.analytics.index') }}" class="group bg-white p-4 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 hover:shadow-md flex items-start space-x-4">
                            <span class="rounded-lg inline-flex p-3 bg-orange-50 text-orange-700 ring-4 ring-white flex-shrink-0">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-lg font-medium text-gray-900">Analytics</span>
                                <span class="block mt-1 text-sm text-gray-500">View detailed analytics and reports</span>
                            </span>
                        </a>
                        <a href="{{ route('version-manager.whats-new.index') }}" class="group bg-white p-4 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 hover:shadow-md flex items-start space-x-4">
                            <span class="rounded-lg inline-flex p-3 bg-indigo-50 text-indigo-700 ring-4 ring-white flex-shrink-0">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-lg font-medium text-gray-900">What's New</span>
                                <span class="block mt-1 text-sm text-gray-500">Manage release notes and updates</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Latest Versions -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Latest Versions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Latest Versions</h3>
                        <a href="{{ route('version-manager.versions.index') }}" class="text-sm text-blue-600 hover:text-blue-500 font-medium">
                            View all →
                        </a>
                    </div>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li v-for="version in recentVersions" :key="version.id">
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Version <span class="font-medium text-gray-900">@{{ version.version }}</span> 
                                                    <span class="font-medium text-gray-900">@{{ version.title }}</span>
                                                </p>
                                                <p class="text-sm text-gray-500">@{{ version.description }}</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time :datetime="version.released_at">@{{ formatDate(version.released_at) }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">System Status</h3>
                        <button @click="runHealthCheck" :disabled="isHealthChecking" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg v-if="isHealthChecking" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="-ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            @{{ isHealthChecking ? 'Checking...' : 'Check Health' }}
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg v-if="healthStatus.overall_status === 'healthy'" class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <svg v-else class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">System Health</p>
                                    <p class="text-sm text-gray-500">@{{ healthStatus.overall_status === 'healthy' ? 'All systems operational' : 'System issues detected' }}</p>
                                </div>
                            </div>
                            <span :class="healthStatus.overall_status === 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                                @{{ healthStatus.overall_status === 'healthy' ? 'Healthy' : 'Unhealthy' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                    <p class="text-sm text-gray-500">@{{ healthStatus.timestamp ? new Date(healthStatus.timestamp).toLocaleString() : lastUpdated }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Health Check Results -->
                    <div v-if="Object.keys(healthStatus.checks).length > 0" class="mt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Detailed Status</h4>
                        <div class="space-y-2">
                            <div v-for="(check, key) in healthStatus.checks" :key="key" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <svg v-if="check.status === 'healthy'" class="h-4 w-4 text-green-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg v-else class="h-4 w-4 text-red-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">@{{ key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }}</span>
                                </div>
                                <div class="text-right">
                                    <span :class="check.status === 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium">
                                        @{{ check.status }}
                                    </span>
                                </div>
                            </div>
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
            statistics: {
                total_versions: {{ $statistics['total_versions'] ?? 0 }},
                active_versions: {{ $statistics['active_versions'] ?? 0 }},
                total_users: {{ $statistics['total_users'] ?? 0 }},
                versions_this_month: {{ $statistics['versions_this_month'] ?? 0 }}
            },
            recentVersions: @json($recentVersions ?? []),
            lastUpdated: new Date().toLocaleString(),
            isLoading: false,
            isHealthChecking: false,
            healthStatus: {
                overall_status: 'healthy',
                timestamp: null,
                checks: {}
            }
        }
    },
    methods: {
        async refreshData() {
            this.isLoading = true;
            try {
                // Simulate API call to refresh data
                await new Promise(resolve => setTimeout(resolve, 1000));
                this.lastUpdated = new Date().toLocaleString();
                // In a real app, you would fetch fresh data from the server
            } catch (error) {
                console.error('Error refreshing data:', error);
            } finally {
                this.isLoading = false;
            }
        },
        async runHealthCheck() {
            this.isHealthChecking = true;
            try {
                const response = await fetch('{{ route("version-manager.health") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                if (response.ok) {
                    this.healthStatus = await response.json();
                } else {
                    console.error('Health check failed:', response.statusText);
                    this.healthStatus = {
                        overall_status: 'unhealthy',
                        timestamp: new Date().toISOString(),
                        checks: {
                            api_endpoint: {
                                status: 'unhealthy',
                                message: 'Health check endpoint not accessible'
                            }
                        }
                    };
                }
            } catch (error) {
                console.error('Error running health check:', error);
                this.healthStatus = {
                    overall_status: 'unhealthy',
                    timestamp: new Date().toISOString(),
                    checks: {
                        network: {
                            status: 'unhealthy',
                            message: 'Network error during health check'
                        }
                    }
                };
            } finally {
                this.isHealthChecking = false;
            }
        },
        formatDate(dateString) {
            if (!dateString) return 'Not released';
            const date = new Date(dateString);
            return date.toLocaleDateString();
        }
    },
    mounted() {
        // Auto-refresh data every 30 seconds
        setInterval(() => {
            this.refreshData();
        }, 30000);
        
        // Run initial health check
        this.runHealthCheck();
    }
}).mount('#dashboard-app');
</script>
@endsection 