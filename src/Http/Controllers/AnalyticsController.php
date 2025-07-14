<?php

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index(): View
    {
        // In a real application, you would fetch analytics data from the database
        // For now, we'll return the view with mock data
        return view('version-platform-manager::admin.analytics.index');
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request): Response
    {
        // In a real application, you would generate and return analytics data
        // For now, we'll return a simple response
        $data = [
            'total_users' => 1250,
            'active_users' => 892,
            'adoption_rate' => 71.4,
            'avg_update_time' => 3.2,
            'version_adoption' => [
                ['version' => '2.1.0', 'users' => 456, 'percentage' => 36.5],
                ['version' => '2.0.0', 'users' => 389, 'percentage' => 31.1],
                ['version' => '1.9.0', 'users' => 234, 'percentage' => 18.7],
                ['version' => '1.8.0', 'users' => 171, 'percentage' => 13.7],
            ],
            'user_activity' => [
                ['period' => 'Today', 'users' => 156, 'percentage' => 12.5],
                ['period' => 'This Week', 'users' => 892, 'percentage' => 71.4],
                ['period' => 'This Month', 'users' => 1245, 'percentage' => 99.6],
            ],
            'update_notifications' => [
                ['version' => '2.1.0', 'date' => '2024-01-15', 'sent' => 234],
                ['version' => '2.0.0', 'date' => '2024-01-10', 'sent' => 456],
                ['version' => '1.9.0', 'date' => '2024-01-05', 'sent' => 189],
            ],
            'top_users' => [
                ['name' => 'John Doe', 'version' => '2.1.0', 'login_count' => 45],
                ['name' => 'Jane Smith', 'version' => '2.1.0', 'login_count' => 38],
                ['name' => 'Bob Johnson', 'version' => '2.0.0', 'login_count' => 32],
                ['name' => 'Alice Brown', 'version' => '2.1.0', 'login_count' => 29],
                ['name' => 'Charlie Wilson', 'version' => '2.0.0', 'login_count' => 26],
            ],
            'recent_activity' => [
                ['type' => 'update', 'title' => 'Version 2.1.0 released', 'time' => '2 hours ago'],
                ['type' => 'user', 'title' => 'New user registered', 'time' => '4 hours ago'],
                ['type' => 'notification', 'title' => 'Update notification sent', 'time' => '6 hours ago'],
                ['type' => 'update', 'title' => 'Version 2.0.0 activated', 'time' => '1 day ago'],
                ['type' => 'user', 'title' => 'User updated to 2.1.0', 'time' => '1 day ago'],
            ],
        ];

        $filename = 'analytics_' . date('Y-m-d_H-i-s') . '.json';
        
        return response($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
} 