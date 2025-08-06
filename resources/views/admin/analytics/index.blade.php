@extends('version-platform-manager::layouts.admin')

@section('page-title', 'Analytics')

@section('title', 'Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                <p class="mt-2 text-sm text-gray-600">Track user adoption and version statistics</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('version-manager.analytics.export') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                </a>
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
                            <dd class="text-lg font-medium text-gray-900">{{ $analyticsData['metrics']['totalUsers'] ?? 0 }}</dd>
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
                            <dd class="text-lg font-medium text-green-600">{{ $analyticsData['metrics']['activeUsers'] ?? 0 }}</dd>
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
                            <dd class="text-lg font-medium text-purple-600">{{ $analyticsData['metrics']['adoptionRate'] ?? 0 }}%</dd>
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
                            <dd class="text-lg font-medium text-yellow-600">{{ $analyticsData['metrics']['avgUpdateTime'] ?? 0 }} days</dd>
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
                @if(!empty($analyticsData['versionAdoption']))
                    <div class="space-y-4">
                        @foreach($analyticsData['versionAdoption'] as $version)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $version['version'] }}</span>
                                    <span class="ml-2 text-sm text-gray-500">({{ $version['users'] }} users)</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $version['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $version['percentage'] }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No version adoption data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Activity Chart -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">User Activity</h3>
                @if(!empty($analyticsData['userActivity']))
                    <div class="space-y-4">
                        @foreach($analyticsData['userActivity'] as $activity)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">{{ $activity['period'] }}</span>
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $activity['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $activity['users'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No user activity data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Update Notifications -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Notifications</h3>
                @if(!empty($analyticsData['updateNotifications']))
                    <div class="space-y-3">
                        @foreach($analyticsData['updateNotifications'] as $notification)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $notification['version'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $notification['date'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification['sent'] }}</p>
                                    <p class="text-xs text-gray-500">sent</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No update notifications available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Users -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Top Users</h3>
                @if(!empty($analyticsData['topUsers']))
                    <div class="space-y-3">
                        @foreach($analyticsData['topUsers'] as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                        <span class="text-xs font-medium text-gray-700">{{ strtoupper(substr($user['name'], 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $user['version'] }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $user['loginCount'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No user data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>
                @if(!empty($analyticsData['recentActivity']))
                    <div class="space-y-3">
                        @foreach($analyticsData['recentActivity'] as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @php
                                        $activityColors = [
                                            'update' => 'bg-blue-500',
                                            'user' => 'bg-green-500',
                                            'notification' => 'bg-yellow-500'
                                        ];
                                        $color = $activityColors[$activity['type']] ?? 'bg-gray-500';
                                    @endphp
                                    <div class="h-6 w-6 rounded-full flex items-center justify-center {{ $color }}">
                                        <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No recent activity available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 