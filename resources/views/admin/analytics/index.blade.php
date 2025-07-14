@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Analytics')

@section('title', 'Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="analytics-app">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600">Track user adoption and version statistics</p>
            </div>
            <div class="flex items-center space-x-3">
                <select v-model="selectedPeriod" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="365">Last year</option>
                </select>
                <button @click="exportAnalytics" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                            <dd class="text-lg font-medium text-gray-900">@{{ metrics.totalUsers }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                            <dd class="text-lg font-medium text-green-600">@{{ metrics.activeUsers }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Adoption Rate</dt>
                            <dd class="text-lg font-medium text-purple-600">@{{ metrics.adoptionRate }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg Update Time</dt>
                            <dd class="text-lg font-medium text-yellow-600">@{{ metrics.avgUpdateTime }} days</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Version Adoption Chart -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Version Adoption</h3>
                <div class="space-y-4">
                    <div v-for="version in versionAdoption" :key="version.version" class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-900">@{{ version.version }}</span>
                            <span class="ml-2 text-sm text-gray-500">(@{{ version.users }} users)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-blue-600 h-2 rounded-full" :style="{ width: version.percentage + '%' }"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">@{{ version.percentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Activity Chart -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">User Activity</h3>
                <div class="space-y-4">
                    <div v-for="activity in userActivity" :key="activity.period" class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">@{{ activity.period }}</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-green-600 h-2 rounded-full" :style="{ width: activity.percentage + '%' }"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">@{{ activity.users }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Update Notifications -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Notifications</h3>
                <div class="space-y-3">
                    <div v-for="notification in updateNotifications" :key="notification.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">@{{ notification.version }}</p>
                            <p class="text-xs text-gray-500">@{{ notification.date }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">@{{ notification.sent }}</p>
                            <p class="text-xs text-gray-500">sent</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Users -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Top Users</h3>
                <div class="space-y-3">
                    <div v-for="user in topUsers" :key="user.id" class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                <span class="text-xs font-medium text-gray-700">@{{ user.name.charAt(0).toUpperCase() }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">@{{ user.name }}</p>
                                <p class="text-xs text-gray-500">@{{ user.version }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">@{{ user.loginCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div v-for="activity in recentActivity" :key="activity.id" class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-6 w-6 rounded-full flex items-center justify-center" :class="getActivityColor(activity.type)">
                                <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">@{{ activity.title }}</p>
                            <p class="text-xs text-gray-500">@{{ activity.time }}</p>
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
            selectedPeriod: '30',
            metrics: {
                totalUsers: 1250,
                activeUsers: 892,
                adoptionRate: 71.4,
                avgUpdateTime: 3.2
            },
            versionAdoption: [
                { version: '2.1.0', users: 456, percentage: 36.5 },
                { version: '2.0.0', users: 389, percentage: 31.1 },
                { version: '1.9.0', users: 234, percentage: 18.7 },
                { version: '1.8.0', users: 171, percentage: 13.7 }
            ],
            userActivity: [
                { period: 'Today', users: 156, percentage: 12.5 },
                { period: 'This Week', users: 892, percentage: 71.4 },
                { period: 'This Month', users: 1245, percentage: 99.6 }
            ],
            updateNotifications: [
                { id: 1, version: '2.1.0', date: '2024-01-15', sent: 234 },
                { id: 2, version: '2.0.0', date: '2024-01-10', sent: 456 },
                { id: 3, version: '1.9.0', date: '2024-01-05', sent: 189 }
            ],
            topUsers: [
                { id: 1, name: 'John Doe', version: '2.1.0', loginCount: 45 },
                { id: 2, name: 'Jane Smith', version: '2.1.0', loginCount: 38 },
                { id: 3, name: 'Bob Johnson', version: '2.0.0', loginCount: 32 },
                { id: 4, name: 'Alice Brown', version: '2.1.0', loginCount: 29 },
                { id: 5, name: 'Charlie Wilson', version: '2.0.0', loginCount: 26 }
            ],
            recentActivity: [
                { id: 1, type: 'update', title: 'Version 2.1.0 released', time: '2 hours ago' },
                { id: 2, type: 'user', title: 'New user registered', time: '4 hours ago' },
                { id: 3, type: 'notification', title: 'Update notification sent', time: '6 hours ago' },
                { id: 4, type: 'update', title: 'Version 2.0.0 activated', time: '1 day ago' },
                { id: 5, type: 'user', title: 'User updated to 2.1.0', time: '1 day ago' }
            ]
        }
    },
    methods: {
        getActivityColor(type) {
            const colors = {
                'update': 'bg-blue-500',
                'user': 'bg-green-500',
                'notification': 'bg-yellow-500'
            };
            return colors[type] || 'bg-gray-500';
        },
        exportAnalytics() {
            alert('Exporting analytics data...');
        }
    },
    watch: {
        selectedPeriod() {
            // In a real app, this would fetch new data based on the selected period
            console.log('Period changed to:', this.selectedPeriod);
        }
    }
}).mount('#analytics-app');
</script>
@endsection 